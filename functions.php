<?php
/**
 * Tema implementado por Beatriz Gasparoto
 *
 * @package ATS
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/vendor/autoload.php';

if ( ! defined( 'ATS_PATH' ) ) {
	define( 'ATS_PATH', get_stylesheet_directory() );
}

if ( ! defined( 'ATS_DOMAIN' ) ) {
	define( 'ATS_DOMAIN', get_theme_file_uri() );
}

if ( ! defined( 'ATS_VERSION' ) ) {
	define( 'ATS_VERSION', '1.0.0' );
}

if ( class_exists( 'App\\Ats\\Config' ) ) {
	new App\Ats\Config();
}


add_action('wp_ajax_create_job', 'create_job');
add_action('wp_ajax_nopriv_create_job', 'create_job');

function create_job() {

    $required = ['title', 'salary', 'benefits', 'city', 'description'];

    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            wp_send_json_error("Todos os campos são obrigatórios.");
        }
    }

    $post_id = wp_insert_post([
        'post_type' => 'jobs',
        'post_title' => sanitize_text_field($_POST['title']),
        'post_status' => 'publish'
    ]);

    if ($post_id) {
        update_post_meta($post_id, '_salary', sanitize_text_field($_POST['salary']));
        update_post_meta($post_id, '_benefits', sanitize_text_field($_POST['benefits']));
        update_post_meta($post_id, '_city', sanitize_text_field($_POST['city']));
        update_post_meta($post_id, '_description', sanitize_text_field($_POST['city']));
        update_post_meta($post_id, '_state', 'aberta');

        wp_send_json_success('Vaga cadastrada com sucesso!');
    }

    wp_send_json_error('Erro ao cadastrar vaga.');
}

add_action('wp_ajax_create_candidate', 'create_candidate');
add_action('wp_ajax_nopriv_create_candidate', 'create_candidate');

function create_candidate() {

    if (empty($_POST['name']) || empty($_POST['phone']) || empty($_FILES['resume'])) {
        wp_send_json_error("Todos os campos são obrigatórios.");
    }

    $post_id = wp_insert_post([
        'post_type' => 'candidates',
        'post_title' => sanitize_text_field($_POST['name']),
        'post_status' => 'publish'
    ]);

    if ($post_id) {

        update_post_meta($post_id, '_phone', sanitize_text_field($_POST['phone']));
        update_post_meta($post_id, '_city', sanitize_text_field($_POST['city']));
        update_post_meta($post_id, '_state', 'triagem');
        
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';

		$attachment_id = media_handle_upload( 'resume', $post_id );

		if ( is_wp_error( $attachment_id ) ) {
			wp_send_json_error( 'Erro ao enviar o currículo.' );
		}

		update_post_meta( $post_id, '_resume', $attachment_id );
        wp_send_json_success('Candidato cadastrado!');
    }

    wp_send_json_error('Erro ao cadastrar candidato.');
}

add_action('wp_ajax_update_job_state', 'update_job_state');

function update_job_state() {

    if (empty($_POST['post_id']) || empty($_POST['state'])) {
        wp_send_json_error('Dados inválidos');
    }

    $post_id = intval($_POST['post_id']);
    $state = sanitize_text_field($_POST['state']);

    update_post_meta($post_id, '_state', $state);

    wp_send_json_success('Atualizado com sucesso');
}

add_action('wp_ajax_update_candidate_state', 'update_candidate_state');

function update_candidate_state() {

    if (empty($_POST['post_id']) || empty($_POST['state'])) {
        wp_send_json_error('Dados inválidos');
    }

    $post_id = intval($_POST['post_id']);
    $state = sanitize_text_field($_POST['state']);

    update_post_meta($post_id, '_state', $state);

    wp_send_json_success('Atualizado com sucesso');
}

add_action('wp_ajax_get_candidates_by_job', 'get_candidates_by_job');

function get_candidates_by_job() {

    if (empty($_POST['job_id'])) {
        wp_send_json_error('Job inválido');
    }

    $job_id = intval($_POST['job_id']);

    $query = new WP_Query([
        'post_type' => 'candidates',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'meta_query' => [
            [
                'key' => '_candidate_job',
                'value' => $job_id,
                'compare' => '='
            ]
        ]
    ]);

    $data = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            $data[] = [
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'state' => get_post_meta(get_the_ID(), '_state', true) ?: 'triagem'
            ];
        }
    }

    wp_reset_postdata();

    wp_send_json_success($data);
}

add_action('wp_ajax_get_job_dashboard', 'get_job_dashboard');

function get_job_dashboard() {

    if (empty($_POST['job_id'])) {
        wp_send_json_error('Job inválido');
    }

    global $wpdb;

    $job_id = intval($_POST['job_id']);

    // Total de candidaturas
    $total = $wpdb->get_var($wpdb->prepare("
        SELECT COUNT(*)
        FROM {$wpdb->postmeta} pm
        INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
        WHERE pm.meta_key = '_candidate_job'
        AND pm.meta_value = %d
        AND p.post_type = 'candidates'
        AND p.post_status = 'publish'
    ", $job_id));

    // Contagem por status (_state)
    $results = $wpdb->get_results($wpdb->prepare("
        SELECT pm_state.meta_value as state, COUNT(*) as total
        FROM {$wpdb->postmeta} pm_job
        INNER JOIN {$wpdb->postmeta} pm_state 
            ON pm_state.post_id = pm_job.post_id
        INNER JOIN {$wpdb->posts} p 
            ON p.ID = pm_job.post_id
        WHERE pm_job.meta_key = '_candidate_job'
        AND pm_job.meta_value = %d
        AND pm_state.meta_key = '_state'
        AND p.post_type = 'candidates'
        AND p.post_status = 'publish'
        GROUP BY pm_state.meta_value
    ", $job_id));

    $states = [];

    if ($results) {
        foreach ($results as $row) {
            $states[$row->state] = (int) $row->total;
        }
    }

    wp_send_json_success([
        'total' => (int) $total,
        'states' => $states
    ]);
}
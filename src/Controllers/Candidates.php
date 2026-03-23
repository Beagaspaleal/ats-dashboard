<?php
/**
 * Tema implementado por Beatriz Gasparoto
 *
 * @package ATS
 */

namespace App\Ats\Controllers;

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/**
 * Class Candidates.
 */
class Candidates {

	/**
	 * Método construtor
	 */
	public function __construct() {
		add_action( 'init', array( self::class, 'register_post_types' ) );
		add_action( 'carbon_fields_register_fields', array( self::class, 'register_fields' ) );
		add_action( 'add_meta_boxes', array( self::class, 'metabox' ) );
	}

	/**
	 * Registra os Custom Post Types
	 */
	public static function register_post_types(): void {
		// Candidates
		register_post_type(
			'candidates',
			array(
				'label'     => 'Candidatos',
				'public'    => false,
				'show_ui'   => true,
				'menu_icon' => 'dashicons-id',
				'supports'  => array( 'title' ),
			)
		);
	}

	/**
	 * Registra os campos do Carbon Fields
	 */
	public static function register_fields(): void {
		/*
		|--------------------------------------------------------------------------
		| Candidates
		|--------------------------------------------------------------------------
		*/
		Container::make( 'post_meta', 'Informações do Candidato' )
			->where( 'post_type', '=', 'candidates' )
			->add_fields(
				array(
					Field::make( 'text', 'phone', 'Telefone' )
						->set_required(),

					Field::make( 'select', 'state', 'Modelo' )
						->add_options(
							array(
								'triagem'           => 'Triagem',
								'entrevista-rh'     => 'Entrevista com RH',
								'entrevista-gestor' => 'Entrevista com Gestor',
								'aprovado'          => 'Aprovado',
								'reprovado'         => 'Reprovado',
								'declinou'          => 'Declinou',
								'admissao'          => 'Admissão',
							)
						)
						->set_required(),

					Field::make( 'text', 'city', 'Cidade' )
						->set_required(),

					Field::make('select', 'candidate_job', 'Vaga')
						->set_options( self::get_jobs_options() ),


					Field::make( 'file', 'resume', 'Currículo (PDF)' )
						->set_required()
						->set_value_type( 'id' ),
				)
			);
	}

	/**
	 * Registra o metabox
	 */
	public static function get_jobs_options() {
		$jobs = get_posts([
			'post_type' => 'jobs',
			'post_status' => 'any', // opcional
			'numberposts' => -1
		]);

		$options = [];

		if ($jobs) {
			foreach ($jobs as $job) {
				$options[$job->ID] = $job->post_title;
			}
		}

		return $options;
	}

	/**
	 * Registra o metabox
	 */
	public static function metabox(): void {
		add_meta_box(
			'download_curriculo',
			'Currículo do Candidato',
			array( self::class, 'render_metabox' ),
			'candidates',
			'side',
			'default'
		);
	}

	/**
	 * Exibe o metabox
	 */
	public static function render_metabox( $post ) {

		$curriculo_id = carbon_get_post_meta( $post->ID, 'resume' );

		if ( ! $curriculo_id ) {
			echo '<p>Nenhum currículo enviado.</p>';
			return;
		}

		$url = wp_get_attachment_url( $curriculo_id );

		echo '<p>';
		echo '<a href="' . esc_url( $url ) . '" target="_blank" class="button button-primary">';
		echo 'Baixar currículo';
		echo '</a>';
		echo '</p>';
	}
}

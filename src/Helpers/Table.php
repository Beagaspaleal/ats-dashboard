<?php
/**
 * Tema implementado por Gustavo Breternitz
 *
 * @package Total_Talent
 */

namespace App\Ats\Helpers;

/**
 * Class Table.
 */
class Table {

	/**
	 * Retorna um item com base no array
	 */
	public static function candidate_state( string $key = '' ): mixed {
		$array = array(
            'triagem'           => 'Triagem',
            'entrevista-rh'     => 'Entrevista com RH',
            'entrevista-gestor' => 'Entrevista com Gestor',
            'aprovado'          => 'Aprovado',
            'reprovado'         => 'Reprovado',
            'declinou'          => 'Declinou',
            'admissao'          => 'Admissão',
		);

		return (bool) $key ? $array[ $key ] : $array;
	}
	
	/**
	 * Retorna um item com base no array
	 */
	public static function jobs_state( string $key = '' ): mixed {
		$array = array(
			'aberta'  => 'Aberta',
			'process' => 'Em Processo',
			'on_hold' => 'Aguardando',
			'fechada' => 'Fechada',
		);

		return (bool) $key ? $array[ $key ] : $array;
	}

	public static function get_dashboard_data() {

		global $wpdb;

		/*
		|--------------------------------------------------------------------------
		| JOBS
		|--------------------------------------------------------------------------
		*/

		// IDs dos jobs
		$jobs_ids = get_posts([
			'post_type' => 'jobs',
			'post_status' => 'publish',
			'numberposts' => -1,
			'fields' => 'ids'
		]);

		// Contagem por _state
		$jobs_states_raw = $wpdb->get_results("
			SELECT pm.meta_value as state, COUNT(*) as total
			FROM {$wpdb->postmeta} pm
			INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
			WHERE pm.meta_key = '_state'
			AND p.post_type = 'jobs'
			AND p.post_status = 'publish'
			GROUP BY pm.meta_value
		");

		$jobs_states = [];

		foreach ($jobs_states_raw as $row) {
			$jobs_states[$row->state] = (int) $row->total;
		}

		/*
		|--------------------------------------------------------------------------
		| CANDIDATES
		|--------------------------------------------------------------------------
		*/

		// IDs dos candidates
		$candidates_ids = get_posts([
			'post_type' => 'candidates',
			'post_status' => 'publish',
			'numberposts' => -1,
			'fields' => 'ids'
		]);

		// Contagem por _state
		$candidates_states_raw = $wpdb->get_results("
			SELECT pm.meta_value as state, COUNT(*) as total
			FROM {$wpdb->postmeta} pm
			INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
			WHERE pm.meta_key = '_state'
			AND p.post_type = 'candidates'
			AND p.post_status = 'publish'
			GROUP BY pm.meta_value
		");

		$candidates_states = [];

		foreach ($candidates_states_raw as $row) {
			$candidates_states[$row->state] = (int) $row->total;
		}

		/*
		|--------------------------------------------------------------------------
		| RETURN
		|--------------------------------------------------------------------------
		*/

		return [
			'jobs' => [
				'ids' => $jobs_ids,
				'total' => count($jobs_ids),
				'states' => $jobs_states
			],
			'candidates' => [
				'ids' => $candidates_ids,
				'total' => count($candidates_ids),
				'states' => $candidates_states
			]
		];
	}

		public static function get_jobs_candidature()
	{

		global $wpdb;

		$jobs = get_posts([
			'post_type' => 'jobs',
			'post_status' => 'publish',
			'numberposts' => -1,
			'fields' => 'ids'
		]);

		if (empty($jobs)) {
			return [];
		}

		$jobs_ids = implode(',', array_map('intval', $jobs));

		$results = $wpdb->get_results("
        SELECT pm_job.meta_value as job_id, COUNT(*) as total
        FROM {$wpdb->postmeta} pm_job
        INNER JOIN {$wpdb->postmeta} pm_state 
            ON pm_state.post_id = pm_job.post_id
        INNER JOIN {$wpdb->posts} p 
            ON p.ID = pm_job.post_id
        WHERE pm_job.meta_key = '_candidate_job'
        AND pm_state.meta_key = '_state'
        AND p.post_type = 'candidates'
        AND p.post_status = 'publish'
        AND pm_job.meta_value IN ($jobs_ids)
        GROUP BY pm_job.meta_value
    ");

		$counts = [];

		foreach ($results as $row) {
			$counts[(int) $row->job_id] = (int) $row->total;
		}

		$data = [];

		foreach ($jobs as $job_id) {
			$data[] = [
				'id' => $job_id,
				'title' => get_the_title($job_id),
				'candidates_count' => $counts[$job_id] ?? 0
			];
		}

		usort($data, function($a, $b) {
			return $b['candidates_count'] <=> $a['candidates_count'];
		});

		return $data;
	}
}

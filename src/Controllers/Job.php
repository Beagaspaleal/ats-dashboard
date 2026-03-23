<?php
/**
 * Tema implementado por Beatriz Gasparoto
 *
 * @package ATS
 */

namespace App\Ats\Controllers;

use Carbon_Fields\Container;
use Carbon_Fields\Field;
use WP_Query;

/**
 * Class Job.
 */
class Job {

	/**
	 * Método construtor
	 */
	public function __construct() {
		add_action( 'init', array( self::class, 'register_post_types' ) );
		add_action( 'carbon_fields_register_fields', array( self::class, 'register_fields' ) );
	}

	/**
	 * Registra os Custom Post Types
	 */
	public static function register_post_types(): void {
		// Jobs
		register_post_type(
			'jobs',
			array(
				'labels'       => array(
					'name'          => 'Vagas',
					'singular_name' => 'Vaga',
				),
				'public'       => true,
				'has_archive'  => true,
				'menu_icon'    => 'dashicons-businessperson',
				'supports'     => array( 'title' ),
				'show_in_rest' => true,
			)
		);
	}

	/**
	 * Registra os campos do Carbon Fields
	 */
	public static function register_fields(): void {
		/*
		|--------------------------------------------------------------------------
		| Jobs
		|--------------------------------------------------------------------------
		*/

		Container::make( 'post_meta', 'Dados da Vaga' )
			->where( 'post_type', '=', 'jobs' )
			->add_fields(
				array(
					Field::make( 'text', 'salary', 'Salário' )
					->set_required(),

					Field::make( 'textarea', 'benefits', 'Befícios' )
						->set_required(),

					Field::make( 'text', 'city', 'Localização' )
						->set_required(),

					Field::make( 'textarea', 'description', 'Descrição' )
						->set_required(),

					Field::make( 'select', 'state', 'Status' )
						->add_options(
							array(
								'aberta'  => 'Aberta',
								'process' => 'Em Processo',
								'on_hold' => 'Aguardando',
								'fechada' => 'Triagem',
							)
						)
						->set_required(),
				)
			);
	}

	public static function get_jobs_count_by_status() {

		global $wpdb;

		$results = $wpdb->get_results("
			SELECT meta_value as state, COUNT(*) as total
			FROM {$wpdb->postmeta} pm
			INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
			WHERE pm.meta_key = '_state'
			AND p.post_type = 'jobs'
			AND p.post_status = 'publish'
			GROUP BY pm.meta_value
		");

		// Estados padrão
		$counts = [
			'aberta'  => 0,
			'process' => 0,
			'on_hold' => 0,
			'fechada' => 0,
		];

		if ($results) {
			foreach ($results as $row) {
				if (isset($counts[$row->state])) {
					$counts[$row->state] = (int) $row->total;
				}
			}
		}

		// Total geral
		$counts['total'] = array_sum($counts);

		return $counts;
	}
}

<?php
/**
 * Tema implementado por Beatriz Gasparoto
 *
 * @package ATS
 */

namespace App\Ats;

use App\Ats\Controllers\Candidates;
use App\Ats\Controllers\Job;
use App\Ats\Setup\Enqueue;

/**
 * Class Config.
 */
class Config {

	/**
	 * Método construtor
	 */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'init' ) );
		\Carbon_Fields\Carbon_Fields::boot();
	}

	/**
	 * Método para iniciar as controllers
	 */
	public function init() {
		new Enqueue;
		new Job();
		new Candidates();
	}
}

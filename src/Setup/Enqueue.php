<?php
/**
 * Tema implementado por Beatriz Gasparoto
 *
 * @package ATS
 */

namespace App\Ats\Setup;

use App\Ats\Helpers\Table;

/**
 * Class Enqueue.
 */
class Enqueue {

	/**
	 * Método construtor
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'basic' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin' ) );
	}

	/**
	 * Enfileira o CSS e JS principal compilado do tema (Parcel build).
	 *
	 * @return void
	 */
	public function basic(): void {
		wp_enqueue_style( 'parcel-build-theme-css', ATS_DOMAIN . '/dist/css/main.css', array(), ATS_VERSION );
		wp_enqueue_script( 'parcel-build-theme-js', ATS_DOMAIN . '/dist/js/index.js', array( 'jquery' ), ATS_VERSION );

		wp_localize_script('parcel-build-theme-js', 'ajaxData', [
			'url' => admin_url('admin-ajax.php'),
			'states' => Table::candidate_state()
		]);
	
		wp_enqueue_style(
			'remixicon',
			'https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css',
			array(),
			'4.6.0'
		);

		wp_enqueue_script(
			'jquery-mask',
			'https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js',
			array( 'jquery' ),
			'1.14.16',
			true
		);
	}

	/**
	 * Enfileira o CSS e JS principal compilado do tema (Parcel build).
	 *
	 * @return void
	 */
	public function admin(): void {
		wp_enqueue_style( 'parcel-build-theme-css-admin', ATS_DOMAIN . '/assets/scss/global/admin.css', array(), ATS_VERSION );
	}
}

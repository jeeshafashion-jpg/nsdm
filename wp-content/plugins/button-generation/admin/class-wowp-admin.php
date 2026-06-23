<?php

/**
 * Class WOWP_Admin
 *
 * The main admin class responsible for initializing the admin functionality of the plugin.
 *
 * @package    ButtonGenerator
 * @subpackage Admin
 * @author     Dmytro Lobov <dev@wow-company.com>, Wow-Company
 * @copyright  2024 Dmytro Lobov
 * @license    GPL-2.0+
 */

namespace ButtonGenerator;

use ButtonGenerator\Admin\AdminActions;
use ButtonGenerator\Admin\Dashboard;

defined( 'ABSPATH' ) || exit;

class WOWP_Admin {
	public function __construct() {
		Dashboard::init();
		AdminActions::init();
		$this->includes();

		add_action( WOWP_Plugin::PREFIX . '_admin_header_links', [ $this, 'plugin_links' ] );
		add_filter( WOWP_Plugin::PREFIX . '_save_settings', [ $this, 'save_settings' ] );
		add_action( WOWP_Plugin::PREFIX . '_admin_load_assets', [ $this, 'load_assets' ] );

		add_action( 'wp_ajax_reset_statistic', [ $this, 'reset_statistic' ] );
	}

	public function includes(): void {
		require_once plugin_dir_path( __FILE__ ) . 'class-settings-helper.php';
	}

	public function reset_statistic(): void {
		$id = !empty( $_POST['id'] ) ? absint( wp_unslash($_POST['id']) ) : 0;
		if ( ! current_user_can( 'manage_options' ) ) {
			exit();
		}

		if ( ! check_ajax_referer( WOWP_Plugin::PREFIX . '_nonce', WOWP_Plugin::PREFIX . '_reset_count' ) ) {
			exit();
		}

		$prefix = 'button_generator';

		$option_name_view   = '_' . $prefix . '_view_counter_' . $id;
		$option_name_action = '_' . $prefix . '_action_counter_' . $id;
		$delete_view        = delete_option( $option_name_view );
		$delete_action      = delete_option( $option_name_action );
		if ( $delete_view === true && $delete_action === true ) {
			$response = array(
				"result" => 'OK',
			);
			wp_send_json( $response );
		}
		exit();
	}

	public function plugin_links(): void {
		?>
        <div class="wpie-links">
            <a href="<?php echo esc_url( WOWP_Plugin::info( 'change' ) ); ?>" target="_blank">Check for Updates</a>
            <a href="<?php echo esc_url( WOWP_Plugin::info( 'rating' ) ); ?>" target="_blank" class="wpie-color-orange">Rate Us</a>
            <span class="wpie-links-divider">|</span>
            <a href="<?php echo esc_url( WOWP_Plugin::info( 'pro' ) ); ?>" target="_blank" class="wpie-color-danger">Upgrade to Pro</a>
        </div>
		<?php
	}

	public function save_settings( $request ) {

		$param = ! empty( $request ) ? map_deep( $request, 'sanitize_text_field' ) : [];

		if ( isset( $request['tooltip'] ) ) {
			$param['tooltip'] = map_deep( $request['tooltip'], array(
				$this,
				'sanitize_text'
			) );
		}

		if ( isset( $request['text'] ) ) {
			$param['text'] = map_deep( $request['text'], [
				$this,
				'sanitize_text'
			] );
		}

		return $param;

	}

	public function sanitize_text( $text ): string {
		return sanitize_text_field( wp_unslash( $text ) );
	}


	public function load_assets(): void {

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_editor();
		wp_enqueue_media();

		$handle     = WOWP_Plugin::SLUG;
		$version    = WOWP_Plugin::info( 'version' );
		$url_assets = plugin_dir_url( __FILE__ ) . 'assets/';

		$iconpicker_js = $url_assets . 'iconpicker/fontawesome-iconpicker.js';
		wp_enqueue_script( $handle . '-fonticonpicker', $iconpicker_js, array( 'jquery' ), $version, true );

		$fonticonpicker_css = $url_assets . 'iconpicker/fontawesome-iconpicker.css';
		wp_enqueue_style( $handle . '-fonticonpicker-darkgrey', $fonticonpicker_css, null, $version );


//		$fonticonpicker_js = $url_assets . 'fonticonpicker/fonticonpicker.min.js';
//		wp_enqueue_script( $handle . '-fonticonpicker', $fonticonpicker_js, array( 'jquery' ), $version, true );
//
//		$fonticonpicker_css = $url_assets . 'fonticonpicker/css/fonticonpicker.min.css';
//		wp_enqueue_style( $handle . '-fonticonpicker', $fonticonpicker_css, null, $version );
//
//		$fonticonpicker_dark_css = $url_assets . 'fonticonpicker/fonticonpicker.darkgrey.min.css';
//		wp_enqueue_style( $handle . '-fonticonpicker-darkgrey', $fonticonpicker_dark_css, null, $version );

		$url_fontawesome = WOWP_Plugin::url() . '/vendors/fontawesome/css/all.css';
		wp_enqueue_style( 'wowp-fontawesome', $url_fontawesome, null, '7.1' );

	}

}
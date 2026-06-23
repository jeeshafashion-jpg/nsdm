<?php

/**
 * Class WOWP_Public
 *
 * This class handles the public functionality of the Float Menu Pro plugin.
 *
 * @package    ButtonGenerator
 * @subpackage Public
 * @author     Dmytro Lobov <dev@wow-company.com>, Wow-Company
 * @copyright  2024 Dmytro Lobov
 * @license    GPL-2.0+
 */

namespace ButtonGenerator;

use ButtonGenerator\Admin\DBManager;
use ButtonGenerator\Maker\Button;
use ButtonGenerator\Maker\Style;
use ButtonGenerator\Publish\Conditions;
use ButtonGenerator\Publish\Display;
use ButtonGenerator\Publish\Singleton;

defined( 'ABSPATH' ) || exit;

class WOWP_Public {

	private string $pefix;

	public function __construct() {
		// prefix for plugin assets
		$this->pefix = '.min';

		add_action( 'wp_ajax_nopriv_button_action', [ $this, 'button_action' ] );
		add_action( 'wp_ajax_button_action', [ $this, 'button_action' ] );

		add_action( 'wp_enqueue_scripts', [ $this, 'assets' ] );
		add_filter( 'the_content', [ $this, 'filter_content' ] );
		add_shortcode( WOWP_Plugin::SHORTCODE, [ $this, 'shortcode' ] );
		add_action( 'wp_footer', [ $this, 'footer' ] );
	}


	public function button_action() {
		if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ) ),
				'btg_button_counter' ) ) {
			exit();
		}

		$id     = isset( $_POST['id'] ) ? absint( wp_unslash( $_POST['id'] ) ) : 0;
		$result = DBManager::get_data_by_id( $id );

		if ( empty( $result->param ) ) {
			return '';
		}

		$param       = maybe_unserialize( $result->param );
		$default     = $param['badge_content'] ?? 0;
		$prefix      = 'button_generator';
		$option_name = '_' . $prefix . '_action_counter_' . $id;
		$count       = get_option( $option_name, $default );
		$next_count  = absint( $count ) + 1;
		$updated     = update_option( $option_name, $next_count );
		if ( true === $updated ) {
			$response = [
				'msg'   => 'OK',
				'count' => $next_count
			];
			wp_send_json( $response );
		}
		exit();
	}

	public function assets(): void {
		$handle          = WOWP_Plugin::SLUG;
		$assets          = plugin_dir_url( __FILE__ ) . 'assets/';
		$version         = WOWP_Plugin::info( 'version' );
		$url_fontawesome = WOWP_Plugin::url() . 'vendors/fontawesome/css/all.css';

		$this->check_display();
		$this->check_shortcode();
		$singleton = Singleton::getInstance();
		$args      = $singleton->getValue();

		if ( ! empty( $args ) ) {
			wp_enqueue_style( $handle, $assets . 'css/button' . $this->pefix . '.css', [], $version, $media = 'all' );

			$custom_css = '';
			foreach ( $args as $id => $param ) {
				if ( empty( $param['fontawesome'] ) ) {
					wp_enqueue_style( $handle . '-fontawesome', $url_fontawesome, null, '7.1' );
				}
				if ( isset( $param['_set_style'] ) && $param['_set_style'] === true ) {
					$css        = new Style( $id, $param );
					$custom_css .= $css->init();
				}
			}
			wp_add_inline_style( $handle, $custom_css );
		}
	}

	public function filter_content( $content ) {
		$singleton = Singleton::getInstance();
		$args      = $singleton->getValue();

		if ( empty( $args ) ) {
			return $content;
		}

		$after  = '';
		$before = '';

		foreach ( $args as $id => $param ) {
			if ( $param['type'] === 'standard' ) {
				if ( $param['standard'] === 'after' ) {
					unset( $param['_in_footer'] );
					$after .= do_shortcode( '[' . esc_attr( WOWP_Plugin::SHORTCODE ) . ' id="' . absint( $id ) . '" ]' );
				}
				if ( $param['standard'] === 'before' ) {
					unset( $param['_in_footer'] );
					$before .= do_shortcode( '[' . esc_attr( WOWP_Plugin::SHORTCODE ) . ' id="' . absint( $id ) . '"]' );
				}
			}
			$singleton->setValue( $id, $param );
		}

		if ( ! empty( $after ) ) {
			$after = "<div class='btg-button__group btg-button-after'>{$after}</div>";
		}

		if ( ! empty( $before ) ) {
			$before = "<div class='btg-button__group btg-button-before'>{$before}</div>";
		}

		return $before . $content . $after;
	}


	public function shortcode( $atts ): string {
		$atts = shortcode_atts(
			[ 'id' => "" ],
			$atts,
			WOWP_Plugin::SHORTCODE
		);

		if ( empty( $atts['id'] ) ) {
			return '';
		}


		$singleton = Singleton::getInstance();

		if ( $singleton->hasKey( $atts['id'] ) ) {
			$param = $singleton->getValueByKey( $atts['id'] );
		} else {
			$result = DBManager::get_data_by_id( $atts['id'] );

			if ( empty( $result->param ) ) {
				return '';
			}

			$conditions = Conditions::init( $result );
			if ( $conditions === false ) {
				return '';
			}

			$param = maybe_unserialize( $result->param );
			$singleton->setValue( $atts['id'], $param );
		}


		$walker = new Button( $atts['id'], $param );
		$out    = $walker->init();

		$this->view_counter( $atts['id'] );

		return $out;
	}


	public function footer(): void {
		$handle          = WOWP_Plugin::SLUG;
		$assets          = plugin_dir_url( __FILE__ ) . 'assets/';
		$version         = WOWP_Plugin::info( 'version' );
		$url_fontawesome = WOWP_Plugin::url() . 'vendors/fontawesome/css/all.css';

		$singleton = Singleton::getInstance();
		$args      = $singleton->getValue();

		if ( empty( $args ) ) {
			return;
		}

		$shortcodes = '';
		$css_out    = '';
		foreach ( $args as $id => $param ) {
			if ( empty( $param['fontawesome'] ) ) {
				wp_enqueue_style( $handle . '-fontawesome', $url_fontawesome, null, '7.1' );
			}
			if ( ! empty( $param['_in_footer'] ) ) {
				$shortcodes .= '[' . WOWP_Plugin::SHORTCODE . ' id="' . absint( $id ) . '"]';
			}

			if ( ! isset( $param['_set_style'] ) ) {
				$css     = new Style( $id, $param );
				$css_out .= $css->init();
			}
		}
		wp_enqueue_style( $handle, $assets . 'css/button' . $this->pefix . '.css', [], $version, $media = 'all' );
		wp_enqueue_script( $handle, $assets . 'js/button' . $this->pefix . '.js', array( 'jquery' ), $version, true );
		wp_localize_script( $handle, 'btg_button', array(
			'url'      => admin_url( 'admin-ajax.php' ),
			'security' => wp_create_nonce( 'btg_button_counter' ),
		) );

		if ( ! empty( $css_out ) ) {
			wp_add_inline_style( $handle, $css_out );
		}

		echo do_shortcode( $shortcodes );
	}

	private function check_display(): void {
		$results = DBManager::get_all_data();
		if ( $results !== false ) {
			$singleton = Singleton::getInstance();
			foreach ( $results as $result ) {
				$param = maybe_unserialize( $result->param );
				if ( Display::init( $result->id, $param ) === true && Conditions::init( $result ) === true ) {
					$param['_in_footer'] = true;
					$param['_set_style'] = true;
					$singleton->setValue( $result->id, $param );
				}
			}
		}
	}

	private function check_shortcode(): void {
		global $post;
		$shortcode = WOWP_Plugin::SHORTCODE;
		$singleton = Singleton::getInstance();

		if ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, $shortcode ) ) {
			$pattern = get_shortcode_regex( [ $shortcode ] );
			if ( preg_match_all( '/' . $pattern . '/s', $post->post_content, $matches )
			     && array_key_exists( 2, $matches )
			     && in_array( $shortcode, $matches[2] )
			) {
				foreach ( $matches[3] as $attrs ) {
					$attrs = shortcode_parse_atts( $attrs );
					if ( $attrs && is_array( $attrs ) && array_key_exists( 'id', $attrs ) ) {
						$result = DBManager::get_data_by_id( $attrs['id'] );

						if ( ! empty( $result->param ) ) {
							$param = maybe_unserialize( $result->param );
							if ( Conditions::init( $result ) === true ) {
								$param['_set_style'] = true;
								$singleton->setValue( $attrs['id'], $param );
							}
						}
					}
				}
			}
		}
	}

	private function view_counter( $id ): void {
		$prefix       = 'button_generator';
		$should_count = true;
		if(empty($_SERVER['HTTP_USER_AGENT'])) {
			return;
		}
		$useragent    = sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) );
		$notbot       = "Mozilla|Opera";
		$bot          = "Bot/|robot|Slurp/|yahoo";
		if ( ! preg_match( "/$notbot/i", $useragent ) || preg_match( "!$bot!i", $useragent ) ) {
			$should_count = false;
		}
		if ( $should_count === true ) {
			$option_name = '_' . $prefix . '_view_counter_' . $id;
			$tool_view   = get_option( $option_name, '0' );
			update_option( $option_name, ( $tool_view + 1 ) );
		}
	}

}
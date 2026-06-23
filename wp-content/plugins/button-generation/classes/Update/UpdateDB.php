<?php

/**
 * Class UpdateDB
 *
 * Contains methods for updating the database structure and data
 *
 * @package    ButtonGenerator
 * @subpackage Update
 * @author     Dmytro Lobov <dev@wow-company.com>, Wow-Company
 * @copyright  2024 Dmytro Lobov
 * @license    GPL-2.0+
 *
 */

namespace ButtonGenerator\Update;

use ButtonGenerator\Admin\DBManager;
use ButtonGenerator\Settings_Helper;
use ButtonGenerator\WOWP_Plugin;

class UpdateDB {

	public static function init(): void {
		$current_db_version = get_site_option( WOWP_Plugin::PREFIX . '_db_version' );

		if ( $current_db_version && version_compare( $current_db_version, '3.0', '>=' ) ) {
			return;
		}

		self::start_update();

		update_site_option( WOWP_Plugin::PREFIX . '_db_version', '3.0' );
	}

	public static function start_update(): void {
		self::update_database();
		self::update_options();
		self::update_fields();
	}

	public static function update_database(): void {

		global $wpdb;
		$table           = $wpdb->prefix . WOWP_Plugin::PREFIX;
		$charset_collate = $wpdb->get_charset_collate();

		$columns = "
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			title VARCHAR(200) DEFAULT '' NOT NULL,
			param longtext DEFAULT '' NOT NULL,
			status boolean DEFAULT 0 NOT NULL,
			mode boolean DEFAULT 0 NOT NULL,
			tag text DEFAULT '' NOT NULL,
			PRIMARY KEY  (id)
			";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$sql = "CREATE TABLE $table ($columns) $charset_collate;";
		dbDelta( $sql );
	}

	public static function update_options(): void {

		$license = get_option( 'wow_license_key_fmp' );
		$status  = get_option( 'wow_license_status_fmp' );
		if ( $license !== false ) {
			update_option( 'wow_license_key_' . WOWP_Plugin::PREFIX, $license );
		}

		if ( $status !== false ) {
			update_option( 'wow_license_status_' . WOWP_Plugin::PREFIX, $status );
		}
	}

	public static function update_fields(): void {
		$results = DBManager::get_all_data();

		if ( empty( $results ) || ! is_array( $results ) ) {
			return;
		}
		foreach ( $results as $result ) {
			$param     = maybe_unserialize( $result->param );
			$status    = 0;
			$test_mode = 0;

			$param = self::update_param( $param );

			$data = [
				'param'  => maybe_serialize( $param ),
				'status' => absint( $status ),
				'mode'   => absint( $test_mode ),
				'tag'    => '',
			];

			$where = [ 'id' => $result->id ];

			$data_formats = [ '%s', '%d', '%d', '%s' ];

			DBManager::update( $data, $where, $data_formats );

		}
	}

	public static function update_param( $param ) {
		if ( isset( $param['text_location'] ) ) {
			if ( $param['text_location'] === 'before' ) {
				$param['text_location'] = 'row';
			}
			if ( $param['text_location'] === 'after' ) {
				$param['text_location'] = 'row-reverse';
			}
		}

		$param['hover_effects'] = 'none';

		if ( ! isset( $param['icon_size'] ) ) {
			$param['icon_size'] = $param['font_size'];
		}

		if ( ! isset( $param['icon_color'] ) ) {
			$param['icon_color'] = $param['color'];
		}

		if ( ! isset( $param['icon_hover_color'] ) ) {
			$param['icon_hover_color'] = $param['hover_color'];
		}

		// Responsive
		if ( ! isset( $param['mobile_on'] ) ) {
			$param['mobile_on'] = $param['include_mobile'] ?? 0;
		}
		if ( ! isset( $param['mobile'] ) ) {
			$param['mobile'] = $param['screen'] ?? 768;
		}
		if ( ! isset( $param['desktop_on'] ) ) {
			$param['desktop_on'] = $param['include_more_screen'] ?? 0;
		}
		if ( ! isset( $param['desktop'] ) ) {
			$param['desktop'] = $param['screen_more'] ?? 1024;
		}

		// Other options
		if ( ! isset( $param['fontawesome'] ) ) {
			$param['fontawesome'] = $param['disable_fontawesome'] ?? 0;
		}


		// Show
		if ( ! is_array( $param['show'] ) ) {
			$show_old = ! empty( $param['show'] ) ? $param['show'] : 'shortcode';

			$param['show']      = [];
			$param['operator']  = [];
			$param['page_type'] = [];
			$param['ids']       = [];

			$param['show'][0]      = 'shortcode';
			$param['operator'][0]  = '1';
			$param['page_type'][0] = 'is_front_page';
			$param['ids'][0]       = '';

			switch ( $show_old ) {
				case 'all':
					$param['show'][0] = 'everywhere';
					break;
				case 'onlypost':
					$param['show'][0] = 'post_all';
					break;
				case 'posts':
					$param['show'][0] = 'post_selected';
					$param['ids'][0]  = ! empty( $param['id_post'] ) ? $param['id_post'] : '';
					break;
				case 'postsincat':
					$param['show'][0] = 'post_category';
					$param['ids'][0]  = ! empty( $param['id_post'] ) ? $param['id_post'] : '';
					break;
				case 'expost':
					$param['show'][0]     = 'post_selected';
					$param['operator'][0] = 0;
					$param['ids'][0]      = ! empty( $param['id_post'] ) ? $param['id_post'] : '';
					break;
				case 'onlypage':
					$param['show'][0] = 'page_all';
					break;
				case 'pages':
					$param['show'][0] = 'page_selected';
					$param['ids'][0]  = ! empty( $param['id_post'] ) ? $param['id_post'] : '';
					break;
				case 'expage':
					$param['show'][0]     = 'page_selected';
					$param['operator'][0] = 0;
					$param['ids'][0]      = ! empty( $param['id_post'] ) ? $param['id_post'] : '';
					break;
				case 'homepage':
					$param['show'][0]      = 'page_type';
					$param['page_type'][0] = 'is_front_page';
					break;
				case 'searchpage':
					$param['show'][0]      = 'page_type';
					$param['page_type'][0] = 'is_search';
					break;
				case 'archivepage':
					$param['show'][0]      = 'page_type';
					$param['page_type'][0] = 'is_archive';
					break;
				case 'error_page':
					$param['show'][0]      = 'page_type';
					$param['page_type'][0] = 'is_404';
					break;
				case 'post_type':
					$custom_post      = $param['post_types'] ?? '';
					$param['show'][0] = 'custom_post_all_' . $custom_post;
					break;
				case 'taxonomy':
					$taxonomy         = $param['taxonomy'] ?? '';
					$param['show'][0] = 'custom_post_tax_|' . $taxonomy;
					break;
			}
		}

		return $param;
	}

}
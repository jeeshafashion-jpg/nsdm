<?php

/**
 * Class Display
 *
 * This class is responsible for displaying the item based on specific conditions.
 *
 * @package    WowPlugin
 * @subpackage Publish
 * @author     Dmytro Lobov <dev@wow-company.com>, Wow-Company
 * @copyright  2024 Dmytro Lobov
 * @license    GPL-2.0+
 *
 */

namespace ButtonGenerator\Publish;

defined( 'ABSPATH' ) || exit;

class Display {

	private const POST_PREFIX = 'custom_post_';

	public static function init( $id, $param ): bool {
		if ( self::can_abort_early( $id, $param ) ) {
			return false;
		}

		return self::check_shows( $param['show'], $param );
	}

	private static function can_abort_early( $id, $param ): bool {
		return empty( $param ) || ! is_array( $param ) || empty( absint( $id ) );
	}

	private static function check_shows( $showParams, $param ): bool {

		foreach ( $showParams as $i => $show ) {
			if ( str_contains( $show, self::POST_PREFIX ) && self::custom_post( $i, $param ) ) {
				return true;
			}

			if ( self::is_match( $show, $i, $param ) ) {
				return true;
			}
		}

		return false;
	}

	private static function is_match( $show, $i, $param ): bool {

		$cases = [
			'everywhere'    => 'check_everywhere',
			'post_all'      => 'is_post',
			'post_selected' => 'post_selected',
			'post_category' => 'post_category',
			'post_tag'      => 'post_tag',
			'page_all'      => 'is_page',
			'page_type'     => 'is_page_type',
			'page_selected' => 'page_selected',
			'is_archive'    => 'is_archive_page',
			'is_category'   => 'is_archive_page',
			'is_tag'        => 'is_archive_page',
			'is_author'     => 'is_archive_page',
			'is_date'       => 'is_archive_page',
			'_is_category'  => 'archive_page',
			'_is_tag'       => 'archive_page',
			'_is_author'    => 'archive_page',
		];

		if ( ! isset( $cases[ $show ] ) ) {
			return false;
		}

		$function = $cases[ $show ];

		return self::$function( $i, $param );

	}

	private static function check_everywhere(): bool {
		return true;
	}

	private static function is_post(): bool {
		return is_singular( 'post' );
	}

	private static function post_selected( $i, $param ): bool {
		$ids         = explode( ',', $param['ids'][ $i ] );
		$trimmed_ids = array_map( 'trim', $ids );

		return (bool) $param['operator'][ $i ] === is_single( $trimmed_ids );
	}

	private static function post_category( $i, $param ): bool {
		if ( ! is_single() ) {
			return false;
		}

		$ids         = explode( ',', $param['ids'][ $i ] );
		$trimmed_ids = array_map( 'trim', $ids );

		return (bool) $param['operator'][ $i ] === in_category( $trimmed_ids );
	}

	private static function post_tag( $i, $param ): bool {

		if ( ! is_single() ) {
			return false;
		}

		$ids         = explode( ',', $param['ids'][ $i ] );
		$trimmed_ids = array_map( 'trim', $ids );

		return (bool) $param['operator'][ $i ] === has_tag( $trimmed_ids );

	}

	private static function is_page(): bool {
		return is_singular( 'page' );
	}

	private static function is_page_type( $i, $param ): bool {

		return (bool) $param['operator'][ $i ] === call_user_func( $param['page_type'][ $i ] );
	}

	private static function page_selected( $i, $param ): bool {
		if ( ! is_page() ) {
			return false;
		}

		$ids         = explode( ',', $param['ids'][ $i ] );
		$trimmed_ids = array_map( 'trim', $ids );

		return (bool) $param['operator'][ $i ] === is_page( $trimmed_ids );
	}

	private static function is_archive_page( $i, $param ) {
		return call_user_func( $param['show'][ $i ] );
	}

	private static function archive_page( $i, $param ): bool {
		$ids         = explode( ',', $param['ids'][ $i ] );
		$trimmed_ids = array_map( 'trim', $ids );

		return (bool) $param['operator'][ $i ] === call_user_func( ltrim( $param['show'][ $i ], "_" ), $trimmed_ids );
	}

	private static function custom_post( $i, $param ): bool {
		$show        = $param['show'][ $i ];
		$ids         = explode( ',', $param['ids'][ $i ] );
		$trimmed_ids = array_map( 'trim', $ids );

		if ( str_contains( $show, 'custom_post_selected' ) ) {
			$post_type = str_replace( 'custom_post_selected_', '', $show );
			if ( is_singular( $post_type ) ) {
				return (bool) $param['operator'][ $i ] === is_single( $trimmed_ids );
			}
		}
		if ( str_contains( $show, 'custom_post_tax_' ) ) {

			$ids = preg_split( "/[,]+/", $param['ids'][ $i ] );

			if ( is_single() ) {
				$taxonomy = explode( '|', $show )[1];

				return (bool) $param['operator'][ $i ] === has_term( $ids, $taxonomy, get_the_ID() );
			}

			return false;

		}
		if ( str_contains( $show, 'custom_post_taxonomy' ) ) {
			if ( is_tax() ) {
				$post_type = str_replace( 'custom_post_taxonomy_', '', $show );
				$args      = [
					'object_type' => [ $post_type ]
				];

				$taxonomies = get_taxonomies( $args );
				$ids        = preg_split( "/[,]+/", $param['ids'][ $i ] );

				return (bool) $param['operator'][ $i ] === is_tax( $taxonomies, $ids );
			}

			return false;
		}

		if ( str_contains( $show, 'custom_post_all' ) ) {
			$post_type = str_replace( 'custom_post_all_', '', $show );

			return is_singular( $post_type );
		}

		if ( str_contains( $show, 'custom_post_archive' ) ) {
			$post_type = str_replace( 'custom_post_archive_', '', $show );

			return is_post_type_archive( $post_type );
		}

		return false;
	}


}
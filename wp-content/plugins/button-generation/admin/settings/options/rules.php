<?php


use ButtonGenerator\Settings_Helper;

defined( 'ABSPATH' ) || exit;

$show = [
	'general_start' => __( 'General', 'button-generation' ),
	'shortcode'     => __( 'Shortcode', 'button-generation' ),
	'everywhere'    => __( 'Everywhere', 'button-generation' ),
	'general_end'   => __( 'General', 'button-generation' ),
	'post_start'    => __( 'Posts', 'button-generation' ),
	'post_all'      => __( 'All posts', 'button-generation' ),
	'post_selected' => __( 'Selected posts', 'button-generation' ),
	'post_category' => __( 'Post has category', 'button-generation' ),
	'post_tag'      => __( 'Post has tag', 'button-generation' ),
	'post_end'      => __( 'Posts End', 'button-generation' ),
	'page_start'    => __( 'Pages', 'button-generation' ),
	'page_all'      => __( 'All pages', 'button-generation' ),
	'page_selected' => __( 'Selected pages', 'button-generation' ),
	'page_type'     => __( 'Page type', 'button-generation' ),
	'page_end'      => __( 'Pages End', 'button-generation' ),
	'archive_start' => __( 'Archives', 'button-generation' ),
	'is_archive'    => __( 'All Archives', 'button-generation' ),
	'is_category'   => __( 'All Categories', 'button-generation' ),
	'is_tag'        => __( 'All Tags', 'button-generation' ),
	'is_author'     => __( 'All Authors', 'button-generation' ),
	'is_date'       => __( 'All Dates', 'button-generation' ),
	'_is_category'  => __( 'Category', 'button-generation' ),
	'_is_tag'       => __( 'Tag', 'button-generation' ),
	'_is_author'    => __( 'Author', 'button-generation' ),
	'archive_end'   => __( 'Archives End', 'button-generation' ),

];

$post_types = get_post_types( [ 'public' => true, '_builtin' => false, ], 'objects' );

foreach ( $post_types as $key => $post_type ) {
	$taxonomies = get_object_taxonomies( $post_type->name, 'names' );

	$show[ $key . '_start' ]                = __( 'Custom Post:',
			'button-generation' ) . ' ' . $post_type->labels->singular_name;
	$show[ 'custom_post_all_' . $key ]      = __( 'All', 'button-generation' ) . ' ' . $post_type->labels->name;
	$show[ 'custom_post_selected_' . $key ] = __( 'Selected', 'button-generation' ) . ' ' . $post_type->labels->name;

	if ( ! empty( $taxonomies ) && is_array( $taxonomies ) ) {
		foreach ( $taxonomies as $taxonomy ) {
			$show[ 'custom_post_tax_' . $key . '|' . $taxonomy ] = $post_type->labels->singular_name . ' has ' . $taxonomy;
		}
	}

	$show[ 'custom_post_taxonomy_' . $key ] = $post_type->labels->name . ' ' . __( 'taxonomy', 'button-generation' );
	if ( $post_type->has_archive ) {
		$show[ 'custom_post_archive_' . $key ] = __( 'Archive', 'button-generation' ) . ' ' . $post_type->labels->archives;
	}
	$show[ $key . '_end' ] = __( 'Custom Post:', 'button-generation' ) . ' ' . $post_type->labels->singular_name;
}

$pages_type = [
	'is_front_page' => __( 'Home Page', 'button-generation' ),
	'is_home'       => __( 'Posts Page', 'button-generation' ),
	'is_search'     => __( 'Search Pages', 'button-generation' ),
	'is_404'        => __( '404 Pages', 'button-generation' ),
];

$operator = [
	'1' => 'is',
	'0' => 'is not',
];


$args = [

	'type' => [
		'type'  => 'select',
		'title' => __( 'Type', 'button-generation' ),
		'atts'  => [
			'standard' => __( 'Standard', 'button-generation' ),
			'floating' => __( 'Floating', 'button-generation' ),
		],
	],

	'standard' => [
		'type'  => 'select',
		'title' => __( 'Location', 'button-generation' ),
		'atts'  => [
			'shortcode' => __( 'Shortcode placement', 'button-generation' ),
		],
	],

	'location' => [
		'type'  => 'select',
		'title' => __( 'Location', 'button-generation' ),
		'atts'  => [
			'topLeft'      => __( 'Top Left', 'button-generation' ),
			'topCenter'    => __( 'Top Center', 'button-generation' ),
			'topRight'     => __( 'Top Right', 'button-generation' ),
			'bottomLeft'   => __( 'Bottom Left', 'button-generation' ),
			'bottomCenter' => __( 'Bottom Center', 'button-generation' ),
			'bottomRight'  => __( 'Bottom Right', 'button-generation' ),
			'left'         => __( 'Left', 'button-generation' ),
			'right'        => __( 'Right', 'button-generation' ),
		],
	],

	'location_top' => [
		'type'  => 'number',
		'val'   => 0,
		'addon' => 'px',
		'title' => __( 'Top', 'button-generation' ),
	],

	'location_bottom' => [
		'type'  => 'number',
		'val'   => 0,
		'addon' => 'px',
		'title' => __( 'Bottom', 'button-generation' ),
	],

	'location_left' => [
		'type'  => 'number',
		'val'   => 0,
		'addon' => 'px',
		'title' => __( 'Left', 'button-generation' ),
	],

	'location_right' => [
		'type'  => 'number',
		'val'   => 0,
		'addon' => 'px',
		'title' => __( 'Right', 'button-generation' ),
	],

	'show' => [
		'type'  => 'select',
		'title' => __( 'Display', 'button-generation' ),
		'val'   => 'shortcode',
		'atts'  => $show,
	],

	'operator' => [
		'type'  => 'select',
		'title' => __( 'Is or is not', 'button-generation' ),
		'atts'  => $operator,
		'val'   => '1',
		'class' => 'is-hidden',
	],

	'ids' => [
		'type'  => 'text',
		'title' => __( 'Enter ID\'s', 'button-generation' ),
		'atts'  => [
			'placeholder' => __( 'Enter IDs, separated by comma.', 'button-generation' )
		],
		'class' => 'is-hidden',
	],

	'page_type' => [
		'type'  => 'select',
		'title' => __( 'Specific page types', 'button-generation' ),
		'atts'  => $pages_type,
		'class' => 'is-hidden',
	],

	'fontawesome' => [
		'type'  => 'checkbox',
		'title' => __( 'Disable Font Awesome Icon', 'button-generation' ),
		'val'   => 0,
		'label' => __( 'Disable', 'button-generation' ),
	],


	'mobile' => [
		'type'  => 'number',
		'title' => [
			'label'  => __( 'Hide on smaller screens', 'button-generation' ),
			'name'   => 'mobile_on',
			'toggle' => true,
		],
		'val'   => 480,
		'addon' => 'px',
	],

	'desktop' => [
		'type'  => 'number',
		'title' => [
			'label'  => __( 'Hide on larger screens', 'button-generation' ),
			'name'   => 'desktop_on',
			'toggle' => true,
		],
		'val'   => 1024,
		'addon' => 'px'
	],

];



return $args;

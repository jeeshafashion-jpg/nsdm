<?php

use ButtonGenerator\Settings_Helper;

defined( 'ABSPATH' ) || exit;

return [

	'appearance' => [
		'type'  => 'select',
		'title' => __( 'Appearance', 'button-generation' ),
		'atts'  => [
			'text'      => __( 'Only Text', 'button-generation' ),
			'text_icon' => __( 'Text & Icon', 'button-generation' ),
			'icon'      => __( 'Icon', 'button-generation' ),
		],
	],

	'text' => [
		'type'  => 'text',
		'title' => __( 'Text', 'button-generation' ),
		'val'   => __( 'Text', 'button-generation' ),
	],

	'text_location' => [
		'type'  => 'select',
		'title' => __( 'Text location', 'button-generation' ),
		'atts'  => [
			'row'            => __( 'Before Icon', 'button-generation' ),
			'row-reverse'    => __( 'After Icon', 'button-generation' ),
			'column'         => __( 'Above the icon', 'button-generation' ),
			'column-reverse' => __( 'Under the icon', 'button-generation' ),
		],
	],

	'gap' => [
		'type'  => 'number',
		'title' => __( 'Gap', 'button-generation' ),
		'val'   => 8,
		'addon' => 'px',
	],


	'icon' => [
		'type'  => 'text',
		'title' => __( 'Icon', 'button-generation' ),
		'addon' => [
			'name' => 'icon_type',
			'type' => 'select',
			'val'  => 'icon',
			'atts' => [
				'icon'  => __( 'Icons', 'button-generation' ),
			]
		],
		'atts'  => [
			'class' => 'wpie-icon-picker',
		],
	],

	'rotate_icon' => [
		'type'  => 'select',
		'title' => __( 'Rotate icon', 'button-generation' ),
		'atts'  => [
			''   => __( 'None', 'button-generation' ),
			'fa-rotate-90'  => '90°',
			'fa-rotate-180' => '180°',
			'fa-rotate-270' => '270°',
			'custom' => __( 'Custom', 'button-generation' ),
		],
	],

	'rotate_icon_custom' => [
		'type'  => 'number',
		'title' => __( 'Degree', 'button-generation' ),
		'val'   => 0,
		'addon' => '&deg;',
	],

	'item_type' => [
		'type'  => 'select',
		'title' => __( 'Item type', 'button-generation' ),
		'atts'  => Settings_Helper::item_type(),
	],

	'item_link' => [
		'type'  => 'text',
		'title' => __( 'Link', 'button-generation' ),
		'class' => 'is-hidden',
	],

	'button_class' => [
		'type'  => 'text',
		'title' => __( 'Class', 'button-generation' ),
	],

	'button_id' => [
		'type'  => 'text',
		'title' => __( 'ID', 'button-generation' ),
	],

	'aria_label' => [
		'type'  => 'text',
		'title' => __( 'Aria label', 'button-generation' ),
	],

];
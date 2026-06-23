<?php

use ButtonGenerator\Settings_Helper;

defined( 'ABSPATH' ) || exit;

return [
	//region General
	'rotate_button' => [
		'type'  => 'select',
		'title' => __( 'Rotate button', 'button-generation' ),
		'atts'  => [
			'0deg'   => __( 'None', 'button-generation' ),
			'90deg'  => '90°',
			'180deg' => '180°',
			'270deg' => '270°',
			'custom' => __( 'Custom', 'button-generation' ),
		],
	],

	'rotate_btn_custom' => [
		'type'  => 'number',
		'title' => __( 'Degree', 'button-generation' ),
		'val' => 0,
		'addon' => '&deg;',

	],

	'zindex' => [
		'type'  => 'number',
		'val'   => 999,
		'title' => __( 'z-index', 'button-generation' ),
		'atts'  => [
			'min'         => '1',
			'max'         => '9999999',
			'step'        => '1',
			'placeholder' => '999',
		],
	],
	//endregion

	//region Sizes
	'width'         => [
		'type'  => 'text',
		'title' => __( 'Width', 'button-generation' ),
		'val'   => '100px',
		'atts'  => [
			'placeholder' => __( 'Set value with px or %', 'button-generation' )
		],
	],

	'height' => [
		'type'  => 'text',
		'title' => __( 'Height', 'button-generation' ),
		'val'   => '50px',
		'atts'  => [
			'placeholder' => __( 'Set value with px or %', 'button-generation' )
		],
	],
	//endregion

	//region Colors
	'icon_color'  => [
		'type'  => 'text',
		'val'   => '#ffffff',
		'atts'  => [
			'class'              => 'wpie-color',
			'data-alpha-enabled' => 'true',
		],
		'title' => __( 'Icon Color', 'button-generation' ),
	],

	'icon_hover_color'  => [
		'type'  => 'text',
		'val'   => '#ffffff',
		'atts'  => [
			'class'              => 'wpie-color',
			'data-alpha-enabled' => 'true',
		],
		'title' => __( 'Icon Hover Color', 'button-generation' ),
	],

	'color'  => [
		'type'  => 'text',
		'val'   => '#ffffff',
		'atts'  => [
			'class'              => 'wpie-color',
			'data-alpha-enabled' => 'true',
		],
		'title' => __( 'Color', 'button-generation' ),
	],

	'background' => [
		'type'  => 'text',
		'val'   => '#1f9ef8',
		'atts'  => [
			'class'              => 'wpie-color',
			'data-alpha-enabled' => 'true',
		],
		'title' => __( 'Background', 'button-generation' ),
	],

	'hover_color' => [
		'type'  => 'text',
		'val'   => '#ffffff',
		'atts'  => [
			'class'              => 'wpie-color',
			'data-alpha-enabled' => 'true',
		],
		'title' => __( 'Hover Color', 'button-generation' ),
	],

	'hover_background' => [
		'type'  => 'text',
		'val'   => '#0090f7',
		'atts'  => [
			'class'              => 'wpie-color',
			'data-alpha-enabled' => 'true',
		],
		'title' => __( 'Hover Background', 'button-generation' ),
	],

	//region Border
	'border_radius'    => [
		'type'  => 'text',
		'title' => __( 'Radius', 'button-generation' ),
		'val'   => '1px',
		'atts'  => [
			'placeholder' => __( 'Set value with px or %', 'button-generation' )
		],
	],

	'border_style' => [
		'type'  => 'select',
		'title' => __( 'Style', 'button-generation' ),
		'atts'  => [
			'none'   => __( 'None', 'button-generation' ),
			'solid'  => __( 'Solid', 'button-generation' ),
			'dotted' => __( 'Dotted', 'button-generation' ),
			'dashed' => __( 'Dashed', 'button-generation' ),
			'double' => __( 'Double', 'button-generation' ),
			'groove' => __( 'Groove', 'button-generation' ),
			'inset'  => __( 'Inset', 'button-generation' ),
			'outset' => __( 'Outset', 'button-generation' ),
			'ridge'  => __( 'Ridge', 'button-generation' ),
		],
	],

	'border_color' => [
		'type'  => 'text',
		'val'   => '#383838',
		'atts'  => [
			'class'              => 'wpie-color',
			'data-alpha-enabled' => 'true',
		],
		'title' => __( 'Color', 'button-generation' ),
	],

	'border_width' => [
		'type'  => 'number',
		'val'   => 1,
		'title' => __( 'Thickness', 'button-generation' ),
		'atts'  => [
			'min'         => '0',
			'max'         => '100',
			'step'        => '1',
			'placeholder' => '0',
		],
		'addon' => 'px',
	],

	//endregion

	//region Shadow
	'shadow'       => [
		'type'  => 'select',
		'title' => __( 'Shadow', 'button-generation' ),
		'atts'  => [
			'none'   => __( 'None', 'button-generation' ),
			'outset' => __( 'Outset', 'button-generation' ),
			'inset'  => __( 'Inset', 'button-generation' ),
		],
	],

	'shadow_h_offset' => [
		'type'  => 'number',
		'val'   => 0,
		'title' => __( 'Horizontal Position', 'button-generation' ),
		'addon' => 'px',
	],

	'shadow_v_offset' => [
		'type'  => 'number',
		'val'   => 0,
		'title' => __( 'Vertical Position', 'button-generation' ),
		'addon' => 'px',
	],

	'shadow_blur' => [
		'type'  => 'number',
		'val'   => 3,
		'title' => __( 'Blur', 'button-generation' ),
		'addon' => 'px',
	],

	'shadow_spread' => [
		'type'  => 'number',
		'val'   => 0,
		'title' => __( 'Spread', 'button-generation' ),
		'addon' => 'px',
	],

	'shadow_color' => [
		'type'  => 'text',
		'val'   => '#020202',
		'atts'  => [
			'class'              => 'wpie-color',
			'data-alpha-enabled' => 'true',
		],
		'title' => __( 'Color', 'button-generation' ),
	],
	//endregion

	//region Fonts
	'icon_size'    => [
		'type'  => 'number',
		'val'   => 16,
		'title' => __( 'Icon Size', 'button-generation' ),
		'addon' => 'px',
	],

	'font_size'    => [
		'type'  => 'number',
		'val'   => 16,
		'title' => __( 'Font Size', 'button-generation' ),
		'addon' => 'px',
	],

	'font_family' => [
		'type'  => 'select',
		'title' => __( 'Font Family', 'button-generation' ),
		'atts'  => [
			'inherit'         => 'Use Your Themes',
			'Tahoma'          => 'Tahoma',
			'Georgia'         => 'Georgia',
			'Comic Sans MS'   => 'Comic Sans MS',
			'Arial'           => 'Arial',
			'Lucida Grande'   => 'Lucida Grande',
			'Times New Roman' => 'Times New Roman',
		],
	],

	'font_weight' => [
		'type'  => 'select',
		'title' => __( 'Font Weight', 'button-generation' ),
		'atts'  => [
			'normal' => 'Normal',
			'100'    => '100',
			'200'    => '200',
			'300'    => '300',
			'400'    => '400',
			'500'    => '500',
			'600'    => '600',
			'700'    => '700',
			'800'    => '800',
			'900'    => '900',
		],
	],

	'font_style' => [
		'type'  => 'select',
		'title' => __( 'Font Style', 'button-generation' ),
		'atts'  => [
			'normal' => 'Normal',
			'italic' => 'Italic',
		],
	],
	//endregion


];
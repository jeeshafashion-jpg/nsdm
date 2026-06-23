<?php

use ButtonGenerator\Settings_Helper;

defined( 'ABSPATH' ) || exit;

return [

	// Transition
	'transition_duration' => [
		'type'  => 'number',
		'title' => __( 'Transition Duration', 'button-generation' ),
		'val'   => 0.2,
		'atts'  => [
			'step' => 0.1,
			'min'  => 0,
		],
		'addon' => 'sec',
	],

	'transition_function' => [
		'type'  => 'select',
		'title' => __( 'Transition Function', 'button-generation' ),
		'val'   => 'ease',
		'atts'  => [
			'ease'        => 'ease',
			'ease-in'     => 'ease-in',
			'ease-out'    => 'ease-out',
			'ease-in-out' => 'ease-in-out',
			'linear'      => 'linear',
		],
	],
	//endregion
];
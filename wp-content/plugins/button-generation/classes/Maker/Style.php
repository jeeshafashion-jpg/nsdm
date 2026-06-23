<?php

/**
 * Class Maker\Style
 * Creates the CSS style for Button.
 *
 * @package    ButtonGenerator
 * @subpackage Public
 * @author     Dmytro Lobov <dev@wow-company.com>, Wow-Company
 * @copyright  2024 Dmytro Lobov
 * @license    GPL-2.0+
 */

namespace ButtonGenerator\Maker;

defined( 'ABSPATH' ) || exit;

class Style {
	/**
	 * @var mixed
	 */
	private $id;
	/**
	 * @var mixed
	 */
	private $param;

	public function __construct( $id, $param ) {
		$this->id    = $id;
		$this->param = $param;
	}

	public function init(): string {

		$css = $this->main_style();
		$css .= $this->icon_style();
		$css .= $this->mobile_rules();

		return trim( preg_replace( '~\s+~', ' ', $css ) );
	}

	private function main_style(): string {
		$param      = $this->param;
		$properties = $this->btn_properties();
		$css        = '.btg-button-' . absint( $this->id ) . '{';

		if ( $param['type'] === 'floating' ) {
			$css .= $this->floating();
		}

		foreach ( $properties as $property => $variable ) {

			if ( ! isset( $param[ $property ] ) ) {
				continue;
			}

			if ( $param[ $property ] === $variable[1] ) {
				continue;
			}

			if ( $property === 'shadow' ) {
				$shadow = "{$param['shadow_h_offset']}px {$param['shadow_v_offset']}px {$param['shadow_blur']}px {$param['shadow_spread']}px {$param['shadow_color']}";
				$inset  = $param[ $property ] === 'inset' ? 'inset ' : '';
				$css    .= "{$variable[0]}: {$inset}{$shadow};";
				continue;
			}

			if ( isset( $variable[2] ) && is_string( $variable[2] ) ) {
				$css .= "{$variable[0]}: {$param[ $property ]}{$variable[2]};";
				continue;
			}

			$css .= "{$variable[0]}: {$param[ $property ]};";
		}

		$css .= '}';

		return $css;
	}

	private function floating(): string {
		$param = $this->param;
		$css   = '--position: fixed;';

		$location = $param['location'] ?? 'topLeft';
		$top      = $param['location_top'] ?? '0';
		$bottom   = $param['location_bottom'] ?? '0';
		$left     = $param['location_left'] ?? '0';
		$right    = $param['location_right'] ?? '0';

		switch ( $location ) {
			case 'topLeft':
				$css .= "top:{$top}px; left:{$left}px;";
				break;
			case 'topCenter':
				$css .= "top:{$top}px;";
				break;
			case 'topRight':
				$css .= "top:{$top}px; right:{$right}px;";
				break;
			case 'bottomLeft':
				$css .= "bottom:{$bottom}px; left:{$left}px;";
				break;
			case 'bottomCenter':
				$css .= "bottom:{$bottom}px;";
				break;
			case 'bottomRight':
				$css .= "bottom:{$bottom}px; right:{$right}px;";
				break;
			case 'left':
				$css .= "left:{$left}px;";
				break;
			case 'right':
				$css .= "right:{$right}px;";
				break;
		}

		return $css;
	}

	private function btn_properties(): array {

		$arg = [
			'text_location'       => [ '--direction', 'row' ],
			'gap'                 => [ '--gap', '8', 'px' ],
			'zindex'              => [ '--z-index', '999' ],
			'width'               => [ '--width', '100px' ],
			'height'              => [ '--height', '50px' ],
			'rotate_button'       => [ '--rotate', '0deg' ],
			'color'               => [ '--color', '#ffffff' ],
			'background'          => [ '--background', '#1f9ef8' ],
			'hover_color'         => [ '--hover-color', '#ffffff' ],
			'hover_background'    => [ '--hover-background', '#0090f7' ],
			'icon_hover_color'    => [ '--icon-hover-color', '#ffffff' ],
			'border_radius'       => [ '--radius', '1px' ],
			'border_style'        => [ '--border-style', 'none' ],
			'border_color'        => [ '--border-color', '#383838' ],
			'border_width'        => [ '--border-width', '1', 'px' ],
			'shadow'              => [ '--shadow', 'none' ],
			'font_size'           => [ '--font-size', '16', 'px' ],
			'font_family'         => [ '--font-family', 'inherit' ],
			'font_weight'         => [ '--font-weight', 'normal' ],
			'font_style'          => [ '--font-style', 'normal' ],
			'transition_duration' => [ '--transition-duration', ' 0.2', 's' ],
			'transition_function' => [ '--transition-function', ' ease-out' ],
		];

		return $arg;

	}

	private function icon_style(): string {
		$param = $this->param;
		$css   = '.btg-button-' . absint( $this->id ) . ' .btg-icon,  .btg-button-' . absint( $this->id ) . ' img.btg-icon{';

		if ( $param['rotate_icon'] === 'custom' ) {
			$css .= '--rotate: ' . esc_attr( $param['rotate_icon_custom'] ) . 'deg;';
		}
		$icon_size  = $param['icon_size'] ?? 16;
		$icon_color = $param['icon_color'] ?? '#ffffff';
		$css        .= '--font-size: ' . esc_attr( $icon_size ) . 'px;';
		$css        .= '--color: ' . esc_attr( $icon_color ) . ';';
		$css        .= '}';

		return $css;
	}
	private function mobile_rules(): string {
		$param = $this->param;
		$css   = '';

		if ( ! empty( $param['mobile_on'] ) ) {
			$mobile = $param['mobile'] ?? 480;
			$css    .= "@media only screen and (max-width: {$mobile}px){
				.btg-button-$this->id {
					display:none;
				}
			}";
		}

		if ( ! empty( $param['desktop_on'] ) ) {
			$desktop = $param['desktop'] ?? 1024;
			$css     .= "@media only screen and (min-width: {$desktop}px){
				.btg-button-$this->id {
					display:none;
				}
			}";
		}

		return $css;
	}
}
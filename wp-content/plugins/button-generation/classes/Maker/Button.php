<?php

/**
 * Class Maker\Button
 *
 * This class is responsible for generating the floating menu HTML using the provided parameters.
 *
 * @package    ButtonGenerator
 * @subpackage Public
 * @author     Dmytro Lobov <dev@wow-company.com>, Wow-Company
 * @copyright  2024 Dmytro Lobov
 * @license    GPL-2.0+
 */

namespace ButtonGenerator\Maker;

defined( 'ABSPATH' ) || exit;

class Button {
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
		$param = $this->param;

		$float = '';
		if ( $param['type'] === 'floating' ) {
			$float = ' btg-' . $param['location'];
		}

		$class = '';
		if ( ! empty( $param['button_class'] ) ) {
			$class = ' ' . $param['button_class'];
		}


		$hover_effect = '';
		if ( ! empty( $param['hover_effects'] ) && $param['hover_effects'] !== 'none' ) {
			$hover_effect = ' ' . str_replace( "hvr-", "_", $param['hover_effects'] );
		}

		$menu = '<button translate="no" class="btg-button btg-button-' . absint( $this->id );
		$menu .= esc_attr( $float );
		$menu .= esc_attr( $class );
		$menu .= '" data-btnid="' . absint( $this->id ) . '"';
		if ( ! empty( $param['button_id'] ) ) {
			$menu .= ' id="' . esc_attr( $param['button_id'] ) . '"';
		}
		if ( ! empty( $param['aria_label'] ) ) {
			$menu .= ' aria-label="' . esc_attr( $param['aria_label'] ) . '"';
		}
		$menu .= $this->type();
		$menu .= '>';
		$menu .= $this->text();
		$menu .= $this->icon();
		$menu .= '</button>';

		return $menu;
	}

	private function type(): string {
		$param  = $this->param;
		$link   = '';
		$target = '';
		$action = '';

		switch ( $param['item_type'] ) {
			case 'link':
				$action = 'link';
				$link   = $param['item_link'] ?? '#';
				$target = ! empty( $param['new_tab'] ) ? '_blank' : '_self';
				break;
			case 'login':
				$action = 'link';
				$link   = wp_login_url( $param['item_link'] );
				break;
			case 'logout':
				$action = 'link';
				$link   = wp_logout_url( $param['item_link'] );
				break;
			case 'register':
				$action = 'link';
				$link   = wp_registration_url();
				break;
			case 'lostpassword':
				$action = 'link';
				$link   = wp_lostpassword_url( $param['item_link'] );
				break;
		}

		$out = '';
		$out .= ( ! empty( $link ) ) ? ' data-url="' . esc_attr( $link ) . '"' : '';
		$out .= ! empty( $action ) ? ' data-action="' . esc_attr( $action ) . '"' : '';
		$out .= ! empty( $target ) ? ' data-target="' . esc_attr( $target ) . '"' : '';


		return $out;
	}

	private function text(): ?string {
		$param = $this->param;
		if ( $param['appearance'] === 'icon' ) {
			return '';
		}

		return str_replace( '\n', '<br/>', esc_html( $param['text'] ) );
	}

	private function icon(): string {
		$param = $this->param;
		if ( $param['appearance'] === 'text' ) {
			return '';
		}
		$out    = '';
		$icon   = $param['icon'] ?? '';
		$type   = $param['icon_type'] ?? 'icon';
		$rotate = '';
		if ( $param['rotate_icon'] !== 'none' && $param['rotate_icon'] !== 'custom' ) {
			$rotate = ' ' . $param['rotate_icon'];
		}

		return '<span class="' . esc_attr( $icon ) . ' btg-icon' . esc_attr( $rotate ) . '"></span>';
	}
}
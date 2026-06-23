<?php
/*
 * Page Name: Style
 */

use ButtonGenerator\Admin\CreateFields;

defined( 'ABSPATH' ) || exit;

$page_opt = include( 'options/style.php' );
$field    = new CreateFields( $options, $page_opt );
?>

    <div class="wpie-fieldset">
        <div class="wpie-fields">
			<?php $field->create( 'zindex' ); ?>
			<?php $field->create( 'rotate_button' ); ?>
			<?php $field->create( 'rotate_btn_custom' ); ?>
        </div>
    </div>

    <div class="wpie-fieldset">
        <div class="wpie-legend"><?php esc_html_e( 'Sizes', 'button-generation' ); ?></div>
        <div class="wpie-fields">
			<?php $field->create( 'width' ); ?>
			<?php $field->create( 'height' ); ?>
        </div>
    </div>

    <div class="wpie-fieldset">
        <div class="wpie-legend"><?php esc_html_e( 'Colors', 'button-generation' ); ?></div>
        <div class="wpie-fields">
			<?php $field->create( 'icon_color' ); ?>
			<?php $field->create( 'icon_hover_color' ); ?>
        </div>
        <div class="wpie-fields">
			<?php $field->create( 'color' ); ?>
			<?php $field->create( 'background' ); ?>
			<?php $field->create( 'hover_color' ); ?>
			<?php $field->create( 'hover_background' ); ?>
        </div>
    </div>

    <div class="wpie-fieldset">
        <div class="wpie-legend"><?php esc_html_e( 'Border', 'button-generation' ); ?></div>
        <div class="wpie-fields">
			<?php $field->create( 'border_radius' ); ?>
			<?php $field->create( 'border_style' ); ?>
			<?php $field->create( 'border_color' ); ?>
			<?php $field->create( 'border_width' ); ?>
			
        </div>
    </div>
    <div class="wpie-fieldset">
        <div class="wpie-legend"><?php esc_html_e( 'Drop Shadow', 'button-generation' ); ?></div>
        <div class="wpie-fields">
			<?php $field->create( 'shadow' ); ?>
			<?php $field->create( 'shadow_h_offset' ); ?>
			<?php $field->create( 'shadow_v_offset' ); ?>
			<?php $field->create( 'shadow_blur' ); ?>
			<?php $field->create( 'shadow_spread' ); ?>
			<?php $field->create( 'shadow_color' ); ?>
        </div>
    </div>
    <div class="wpie-fieldset">
        <div class="wpie-legend"><?php esc_html_e( 'Font', 'button-generation' ); ?></div>
        <div class="wpie-fields">
			<?php $field->create( 'icon_size' ); ?>
        </div>
        <div class="wpie-fields">
			<?php $field->create( 'font_size' ); ?>
			<?php $field->create( 'font_family' ); ?>
			<?php $field->create( 'font_weight' ); ?>
			<?php $field->create( 'font_style' ); ?>
        </div>
    </div>


<?php

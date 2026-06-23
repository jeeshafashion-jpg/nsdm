<?php
/*
 * Page Name: Settings
 */

use ButtonGenerator\Admin\CreateFields;

defined( 'ABSPATH' ) || exit;

$page_opt = include( 'options/settings.php' );
$field    = new CreateFields( $options, $page_opt );

$item_order = ! empty( $options['item_order']['content'] ) ? 1 : 0;
$open       = ! empty( $item_order ) ? ' open' : '';
?>
    <details class="wpie-item"<?php echo esc_attr( $open ); ?>>
        <input type="hidden" name="item_order[content]" class="wpie-item__toggle" value="<?php echo absint( $item_order ); ?>">
        <summary class="wpie-item_heading">
            <span class="wpie-item_heading_icon"><span class="wpie-icon wpie_icon-file-content"></span></span>
            <span class="wpie-item_heading_label"><?php
				esc_html_e( 'Content', 'button-generation' ); ?></span>
            <span class="wpie-item_heading_type"></span>
            <span class="wpie-item_heading_toogle">
                <span class="wpie-icon wpie_icon-chevron-down"></span>
                <span class="wpie-icon wpie_icon-chevron-up "></span>
            </span>
        </summary>
        <div class="wpie-item_content">
            <div class="wpie-fieldset">
                <div class="wpie-fields">
					<?php
					$field->create( 'appearance' ); ?>
                </div>
                <div class="wpie-fields">
					<?php
					$field->create( 'text' ); ?><?php
					$field->create( 'text_location' ); ?><?php
					$field->create( 'gap' ); ?>
                </div>
                <div class="wpie-fields">
					<?php
					$field->create( 'icon' ); ?><?php
					$field->create( 'rotate_icon' ); ?><?php
					$field->create( 'rotate_icon_custom' ); ?>
                </div>
            </div>
        </div>
    </details>

<?php
$item_order = ! empty( $options['item_order']['type'] ) ? 1 : 0;
$open       = ! empty( $item_order ) ? ' open' : '';
?>

    <details class="wpie-item"<?php
	echo esc_attr( $open ); ?>>
        <input type="hidden" name="item_order[type]" class="wpie-item__toggle" value="<?php
		echo absint( $item_order ); ?>">
        <summary class="wpie-item_heading">
            <span class="wpie-item_heading_icon"><span class="wpie-icon wpie_icon-buttons"></span></span>
            <span class="wpie-item_heading_label"><?php
				esc_html_e( 'Type', 'button-generation' ); ?></span>
            <span class="wpie-item_heading_type"></span>
            <span class="wpie-item_heading_toogle">
        <span class="wpie-icon wpie_icon-chevron-down"></span>
        <span class="wpie-icon wpie_icon-chevron-up "></span>
    </span>
        </summary>
        <div class="wpie-item_content">

            <div class="wpie-fieldset">
                <div class="wpie-fields">
					<?php
					$field->create( 'item_type' ); ?><?php
					$field->create( 'item_link' ); ?>
                </div>
            </div>

            <div class="wpie-fieldset">
                <div class="wpie-legend"><?php
					esc_html_e( 'Attributes', 'button-generation' ); ?></div>
                <div class="wpie-fields">
					<?php $field->create( 'button_id' ); ?>
                    <?php $field->create( 'button_class' ); ?>
                    <?php $field->create( 'aria_label' ); ?>
                </div>
            </div>

        </div>
    </details>

<?php

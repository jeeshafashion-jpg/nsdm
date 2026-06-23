<?php
/*
 * Page Name: Targeting & Rules
 */

use ButtonGenerator\Admin\CreateFields;
use ButtonGenerator\Settings_Helper;

defined( 'ABSPATH' ) || exit;

$page_opt = include( 'options/rules.php' );
$field    = new CreateFields( $options, $page_opt );

?>

<div class="wpie-fieldset">
    <div class="wpie-legend"><?php
		esc_html_e( 'Button Type', 'button-generation' ); ?></div>
    <div class="wpie-fields">
		<?php
		$field->create( 'type' ); ?><?php
		$field->create( 'standard' ); ?><?php
		$field->create( 'location' ); ?><?php
		$field->create( 'location_top' ); ?><?php
		$field->create( 'location_bottom' ); ?><?php
		$field->create( 'location_left' ); ?><?php
		$field->create( 'location_right' ); ?>
    </div>
</div>

<?php
$item_order = ! empty( $options['item_order']['rules'] ) ? 1 : 0;
$open       = ! empty( $item_order ) ? ' open' : '';
?>
<details class="has-mt wpie-item"<?php
echo esc_attr( $open ); ?>>
    <input type="hidden" name="item_order[rules]" class="wpie-item__toggle" value="<?php
	echo absint( $item_order ); ?>">
    <summary class="wpie-item_heading">
        <span class="wpie-item_heading_icon"><span class="wpie-icon wpie_icon-roadmap"></span></span>
        <span class="wpie-item_heading_label"><?php
			esc_html_e( 'Display Rules', 'button-generation' ); ?></span>
        <span class="wpie-item_heading_type"></span>
        <span class="wpie-item_heading_toogle">
        <span class="wpie-icon wpie_icon-chevron-down"></span>
        <span class="wpie-icon wpie_icon-chevron-up "></span>
    </span>
    </summary>
    <div class="wpie-item_content">
        <div class="wpie-fieldset wpie-rules">
            <div class="wpie-fields">
				<?php
				$field->create( 'show', 0 ); ?><?php
				$field->create( 'operator', 0 ); ?><?php
				$field->create( 'ids', 0 ); ?><?php
				$field->create( 'page_type', 0 ); ?>
            </div>

        </div>
    </div>
</details>

<?php
$item_order = ! empty( $options['item_order']['responsive'] ) ? 1 : 0;
$open       = ! empty( $item_order ) ? ' open' : '';
?>

<details class="has-mt wpie-item"<?php
echo esc_attr( $open ); ?>>
    <input type="hidden" name="item_order[responsive]" class="wpie-item__toggle" value="<?php
	echo absint( $item_order ); ?>">
    <summary class="wpie-item_heading">
        <span class="wpie-item_heading_icon"><span class="wpie-icon wpie_icon-laptop-mobile"></span></span>
        <span class="wpie-item_heading_label"><?php
			esc_html_e( 'Responsive Visibility', 'button-generation' ); ?></span>
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
				$field->create( 'mobile' ); ?><?php
				$field->create( 'desktop' ); ?>
            </div>
        </div>

    </div>
</details>

<?php
$item_order = ! empty( $options['item_order']['other'] ) ? 1 : 0;
$open       = ! empty( $item_order ) ? ' open' : '';
?>
<details class="has-mt wpie-item"<?php
echo esc_attr( $open ); ?>>
    <input type="hidden" name="item_order[other]" class="wpie-item__toggle" value="<?php
	echo absint( $item_order ); ?>">
    <summary class="wpie-item_heading">
        <span class="wpie-item_heading_icon"><span class="wpie-icon wpie_icon-gear"></span></span>
        <span class="wpie-item_heading_label"><?php
			esc_html_e( 'Other', 'button-generation' ); ?></span>
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
				$field->create( 'fontawesome' ); ?>
            </div>
        </div>
    </div>
</details>






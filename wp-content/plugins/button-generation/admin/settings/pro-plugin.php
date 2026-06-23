<?php

use FloatMenuLite\WOWP_Plugin;

defined( 'ABSPATH' ) || exit;

$default    = $options['item_order']['pro_feature'] ?? 1;
$item_order = ! empty( $default ) ? 1 : 0;
$open       = ! empty( $item_order ) ? ' open' : '';
?>

    <div class="wpie-sidebar wpie-sidebar-features">
        <div class="wpie-item_heading">
            <span class="wpie-item_heading_icon"><span
                        class="wpie-icon wpie_icon-rocket wpie-color-danger"></span></span>
            <span class="wpie-item_heading_label"><?php
				esc_html_e( 'PRO VERSION', 'button-generation' ); ?></span>
            <span class="wpie-item_heading_type"></span>
        </div>

        <div class="wpie-buttons">
            <a href="https://demo.wow-estore.com/button-generator-pro/" target="_blank" class="wpie-button is-demo">Demo</a>
            <a href="https://wow-estore.com/item/button-generator-pro/" target="_blank" class="wpie-button is-pro">GET PRO</a>
        </div>
    </div>

<?php

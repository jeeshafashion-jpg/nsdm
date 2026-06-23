<?php

use ButtonGenerator\Admin\Link;

defined( 'ABSPATH' ) || exit;

$prefix = 'button_generator';
// Counters
$option_name_view   = '_' . $prefix . '_view_counter_' . $id;
$option_name_action = '_' . $prefix . '_action_counter_' . $id;
$tool_view          = get_option( $option_name_view, '0' );
$tool_action        = get_option( $option_name_action, '0' );
if ( ! empty( $tool_view ) ) {
	$conversion = round( $tool_action / $tool_view * 100, 2 ) . '%';
} else {
	$conversion = '0%';
}


?>

    <div class="wpie-sidebar">

        <h2 class="wpie-title"><?php esc_html_e( 'Analytics', 'button-generation' ); ?></h2>
        <div class="wpie-fields__box">
            <div class="wpie-field">
                <div class="wpie-field__title"><?php esc_html_e( 'Views', 'button-generation' ); ?></div>
                <label class="wpie-field__label has-icon">
                    <span class="wpie-icon wpie_icon-eye-open"></span>
                    <input type="text" id="tool_view" value="<?php echo absint( $tool_view ); ?>" readonly>
                </label>
            </div>

            <div class="wpie-field">
                <div class="wpie-field__title"><?php esc_html_e( 'Actions', 'button-generation' ); ?></div>
                <label class="wpie-field__label has-icon">
                    <span class="wpie-icon wpie_icon-target"></span>
                    <input type="text" id="tool_action" value="<?php echo absint( $tool_action ); ?>" readonly>
                </label>
            </div>

            <div class="wpie-field">
                <div class="wpie-field__title"><?php esc_html_e( 'Conversion', 'button-generation' ); ?></div>
                <label class="wpie-field__label has-icon">
                    <span class="wpie-icon wpie_icon-filter"></span>
                    <input type="text" id="conversion" value="<?php echo esc_attr( $conversion ); ?>" readonly>
                </label>
            </div>
        </div>

        <div class="wpie-actions__box">
            <div class="wpie-action__link">
			    <?php if ( ! empty( $options['id'] ) ): ?>
                    <a class="wpie-link-reset-static" href="<?php echo esc_url( Link::reset_count( $options['id'] ) ); ?>">
					    <?php esc_html_e( 'Reset', 'button-generation' ); ?>
                    </a>
			    <?php endif; ?>
            </div>
        </div>
    </div>
<?php

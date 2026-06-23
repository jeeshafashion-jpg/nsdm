<?php
/*
 * Page Name: Effects
 */

use ButtonGenerator\Admin\CreateFields;

defined( 'ABSPATH' ) || exit;

$page_opt = include( 'options/effects.php' );
$field    = new CreateFields( $options, $page_opt );
?>

    <div class="wpie-fieldset">
        <div class="wpie-fields">
		    <?php $field->create( 'transition_duration' ); ?>
		    <?php $field->create( 'transition_function' ); ?>
        </div>
    </div>

<?php

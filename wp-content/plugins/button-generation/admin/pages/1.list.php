<?php
/*
 * Page Name: List
 */

use ButtonGenerator\Admin\ListTable;
use ButtonGenerator\WOWP_Plugin;

defined( 'ABSPATH' ) || exit;

$list_table = new ListTable();
$list_table->prepare_items();
$table_page = WOWP_Plugin::SLUG;

$wp_plugins = [
	[
		'free'    => 'https://wordpress.org/plugins/modal-window/',
		'title'   => 'Modal Windows',
		'content' => 'Designed to ease the process of creating and setting the modal windows on the WordPress site.'
	],
	[
		'free'    => 'https://wordpress.org/plugins/counter-box/',
		'title'   => 'Counter Box',
		'content' => 'Quickly and easily create countdowns, counters, and timers with a live preview.'
	],
	[
		'free'    => 'https://wordpress.org/plugins/calculator-builder/',
		'title'   => 'Calculator Builder',
		'content' => 'A simple way to create an online calculator.'
	],
	[
		'free'    => 'https://wordpress.org/plugins/mwp-herd-effect/',
		'title'   => 'Herd Effects',
		'content' => 'Designed to create a “sense of queue” or “herd effect”, motivating the visitors of the page to perform any actions.'
	],
	[
		'free'    => 'https://wordpress.org/plugins/flexi-menu/',
		'title'   => 'Flexi Menu',
		'content' => 'A powerful WordPress plugin for creating floating, dropdown, static, or context menus with ease.'
	],

];

?>

    <div class="wpie-notification -success">
        <strong>Works Great With:</strong>
		<?php foreach ($wp_plugins as $plugin) {
			echo '<a href="' .esc_url($plugin['free']).'" target="_blank" class="has-tooltip on-bottom" data-tooltip="' .esc_attr($plugin['content']).'">'.esc_html($plugin['title']).'</a> <span class="wpie-separator">|</span> ';
		}?>
    </div>

    <form method="post" class="wpie-list">
		<?php
		$list_table->search_box( esc_attr__( 'Search', 'button-generation' ), WOWP_Plugin::PREFIX );
		$list_table->display();
		?>
        <input type="hidden" name="page" value="<?php echo esc_attr( $table_page ); ?>"/>
		<?php wp_nonce_field( WOWP_Plugin::PREFIX . '_nonce', WOWP_Plugin::PREFIX . '_list_action' ); ?>
    </form>
<?php

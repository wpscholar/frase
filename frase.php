<?php
/**
 * Frase
 *
 * @package           Frase
 * @author            Micah Wood
 * @copyright         Copyright 2020 by Micah Wood - All rights reserved.
 * @license           GPL2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Frase
 * Plugin URI:
 * Description:
 * Version:           1.0
 * Requires PHP:      5.6
 * Requires at least: 5.0
 * Author:            Micah Wood
 * Author URI:        https://wpscholar.com
 * Text Domain:       frase
 * Domain Path:       /languages
 * License:           GPL V2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

add_action(
	'wp_enqueue_scripts',
	function () {
		if ( ! is_admin() && get_option( 'frase_data_hash' ) ) {

			if ( ! function_exists( 'get_plugin_data' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}

			wp_enqueue_script(
				'frase-script',
				'https://app.frase.io/js/libraries/bot.js',
				array( 'jquery' ),
				get_plugin_data( __FILE__ )['Version'],
				false
			);
		}
	}
);

add_filter(
	'script_loader_tag',
	function ( $tag, $handle ) {
		if ( 'frase-script' === $handle ) {
			$tag = str_replace( '></', ' data-hash="' . get_option( 'frase_data_hash', '' ) . '" async></', $tag );
			$tag = str_replace( $handle . '-js', $handle, $tag );
		}

		return $tag;
	},
	10,
	2
);

add_action(
	'admin_init',
	function () {

		register_setting( 'frase', 'frase_data_hash' );

		add_settings_section(
			'frase_settings',
			__( 'Settings', 'frase' ),
			'__return_false',
			'frase'
		);

		add_settings_field(
			'frase_data_hash',
			__( 'Data hash', 'frase' ),
			function () {
				?>
				<input
					id="frase_data_hash"
					name="frase_data_hash"
					class="regular-text"
					value="<?php echo esc_attr( get_option( 'frase_data_hash', '' ) ); ?>"
				/>
				<p class="description">
					<?php esc_html_e( 'Copy the data hash from your script tag as follows: ', 'frase' ); ?><br />
					<span
						style="display: block; white-space: nowrap; border: 1px solid darkgrey; border-radius: 3px; padding: .5em .25em;">
						&lt;script
						id="frase-script"
						data-hash="<span style="background-color: #1EA374; color: white;">xxxxxx</span>"
						src="https://app.frase.io/js/libraries/bot.js"&gt;&lt;/script&gt;
					</span>
				</p>
				<?php
			},
			'frase',
			'frase_settings'
		);
	}
);

add_action(
	'admin_menu',
	function () {
		add_menu_page(
			__( 'Frase', 'frase' ),
			__( 'Frase', 'frase' ),
			'manage_options',
			'frase',
			function () {
				if ( ! current_user_can( 'manage_options' ) ) {
					return;
				}
				if ( isset( $_GET['settings-updated'] ) ) {
					add_settings_error( 'frase_messages', 'frase_message', __( 'Settings Saved', 'frase' ), 'updated' );
				}
				settings_errors( 'frase_messages' );
				?>
				<div class="wrap">
					<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
					<form action="options.php" method="post">
						<?php
						settings_fields( 'frase' );
						do_settings_sections( 'frase' );
						submit_button( 'Save Settings' );
						?>
					</form>
				</div>
				<?php
			}
		);
	}
);

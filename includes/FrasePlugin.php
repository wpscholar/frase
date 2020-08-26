<?php

/**
 * Class FrasePlugin
 */
class FrasePlugin {

	/**
	 * WordPress capability required to manage settings.
	 *
	 * @var string
	 */
	const CAPABILITY = 'manage_options';

	/**
	 * The WordPress admin settings page slug.
	 */
	const PAGE = 'frase';

	/**
	 * Initialize plugin
	 */
	public static function init() {
		if ( is_admin() ) {
			add_action( 'admin_init', array( __CLASS__, 'registerSettings' ) );
			add_action( 'admin_menu', array( __CLASS__, 'addSettingsPage' ) );
		} else {
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueueScripts' ) );
			add_filter( 'script_loader_tag', array( __CLASS__, 'scriptLoader' ), 10, 2 );
		}
	}

	/**
	 * Get the plugin version.
	 *
	 * @return string
	 */
	public static function getPluginVersion() {
		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}
		$data = get_plugin_data( FRASE_PLUGIN_FILE );

		return (string) $data['Version'];
	}

	/**
	 * Check if data hash exists.
	 *
	 * @return bool
	 */
	public static function hasDataHash() {
		return ! empty( self::getDataHash() );
	}

	/**
	 * Get the data hash.
	 *
	 * @return string
	 */
	public static function getDataHash() {
		return get_option( 'frase_data_hash', '' );
	}

	/**
	 * Enqueue scripts.
	 */
	public static function enqueueScripts() {
		if ( self::hasDataHash() ) {
			wp_enqueue_script(
				'frase-script',
				'https://app.frase.io/js/libraries/bot.js',
				array( 'jquery' ),
				self::getPluginVersion(),
				true
			);
		}
	}

	/**
	 * Update our script to have the right markup.
	 *
	 * @param string $tag    The script tag markup.
	 * @param string $handle The script's WordPress handle.
	 *
	 * @return string
	 */
	public static function scriptLoader( $tag, $handle ) {
		if ( 'frase-script' === $handle ) {
			$tag = str_replace( '></', ' data-hash="' . self::getDataHash() . '" async></', $tag );
			// Check if an ID exists
			if ( false === strpos( $tag, 'id=' ) ) {
				// If not, add it
				$tag = str_replace( '<script', '<script id="frase-script"', $tag );
			} else {
				// If so, replace it
				$tag = str_replace( "{$handle}-js", $handle, $tag );
			}
		}

		return $tag;
	}

	/**
	 * Register settings.
	 */
	public static function registerSettings() {

		register_setting( self::PAGE, 'frase_data_hash' );

		add_settings_section(
			'frase_settings',
			__( 'Settings', 'frase' ),
			function () {
				self::renderView( 'settings-section.php' );
			},
			self::PAGE
		);

		add_settings_field(
			'frase_data_hash',
			__( 'Data Hash', 'frase' ),
			function ( $args ) {
				self::renderView( 'settings-field-input.php', $args );
			},
			self::PAGE,
			'frase_settings',
			array(
				'class' => 'regular-text',
				'label' => esc_html__( 'Data Hash', 'frase' ),
				'name'  => 'frase_data_hash',
				'value' => self::getDataHash(),
			)
		);
	}

	/**
	 * Add settings page.
	 */
	public static function addSettingsPage() {
		add_submenu_page(
			'options-general.php',
			__( 'Frase', 'frase' ),
			__( 'Frase', 'frase' ),
			self::CAPABILITY,
			self::PAGE,
			function () {
				wp_enqueue_style( 'frase-admin-styles', plugins_url( '/assets/css/admin.css', FRASE_PLUGIN_FILE ) );
				self::renderView( 'settings-page.php' );
			}
		);
	}

	/**
	 * Render a view.
	 *
	 * @param string $path The path relative to the `/views` directory.
	 * @param array  $args Optional. Additional arguments passed to the view.
	 */
	public static function renderView( $path, $args = array() ) {
		extract( $args, EXTR_SKIP );
		require wp_normalize_path( FRASE_PLUGIN_DIR . 'views/' . ltrim( $path, '/' ) );
	}

}

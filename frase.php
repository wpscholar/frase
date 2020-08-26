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
 * Plugin URI:        https://github.com/wpscholar/frase
 * Description:       A WordPress plugin for integrating with Frase.io Answer Assistants
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

define( 'FRASE_PLUGIN_FILE', __FILE__ );
define( 'FRASE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'FRASE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require __DIR__ . '/includes/FrasePlugin.php';

FrasePlugin::init();

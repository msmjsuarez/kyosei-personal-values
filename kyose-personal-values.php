<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://https://kyoseicreative.com/
 * @since             2.1.0
 * @package           Kyose_Personal_Values
 *
 * @wordpress-plugin
 * Plugin Name:       Kyosei Personal Values
 * Plugin URI:        https://https://kyoseicreative.com/
 * Description:       This plugin is used to calculate personal values. 
 * Version:           2.1.0
 * Author:            MJ of Kyosei
 * Author URI:        https://https://kyoseicreative.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       kyose-personal-values
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'KYOSE_PERSONAL_VALUES_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-kyose-personal-values-activator.php
 */
function activate_kyose_personal_values() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-kyose-personal-values-activator.php';
	Kyose_Personal_Values_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-kyose-personal-values-deactivator.php
 */
function deactivate_kyose_personal_values() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-kyose-personal-values-deactivator.php';
	Kyose_Personal_Values_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_kyose_personal_values' );
register_deactivation_hook( __FILE__, 'deactivate_kyose_personal_values' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-kyose-personal-values.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_kyose_personal_values() {

	$plugin = new Kyose_Personal_Values();
	$plugin->run();

}
run_kyose_personal_values();

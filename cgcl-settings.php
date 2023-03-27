<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://chinarajames.com
 * @since             1.0.0
 * @package           Cgcl_Settings
 *
 * @wordpress-plugin
 * Plugin Name:       CGCL Settings
 * Plugin URI:        https://chinarajames.com
 * Description:       Custom settings to add functionality to CGCL's website
 * Version:           1.0.0
 * Author:            Chinara James
 * Author URI:        https://chinarajames.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cgcl-settings
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
define( 'CGCL_SETTINGS_VERSION', '2.0.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cgcl-settings-activator.php
 */
function activate_cgcl_settings() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cgcl-settings-activator.php';
	Cgcl_Settings_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cgcl-settings-deactivator.php
 */
function deactivate_cgcl_settings() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cgcl-settings-deactivator.php';
	Cgcl_Settings_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_cgcl_settings' );
register_deactivation_hook( __FILE__, 'deactivate_cgcl_settings' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cgcl-settings.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cgcl_settings() {

	$plugin = new Cgcl_Settings();
	$plugin->run();

}
run_cgcl_settings();

<?php

/**
 *
 * @link              http://www.versionebeta.com
 * @since             1.0.0
 * @package           Simple_Tracker
 *
 * @wordpress-plugin
 * Plugin Name:       Simple Tracker
 * Plugin URI:        http://www.versionebeta.com/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Luca Ubiali
 * Author URI:        http://www.versionebeta.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       simple-tracker
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-simple-tracker-activator.php
 */
function activate_simple_tracker() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-simple-tracker-activator.php';
	Simple_Tracker_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-simple-tracker-deactivator.php
 */
function deactivate_simple_tracker() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-simple-tracker-deactivator.php';
	Simple_Tracker_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_simple_tracker' );
register_deactivation_hook( __FILE__, 'deactivate_simple_tracker' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-simple-tracker.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_simple_tracker() {

	$plugin = new Simple_Tracker();
	$plugin->run();

}
run_simple_tracker();

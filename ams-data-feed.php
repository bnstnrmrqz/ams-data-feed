<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/bnstnrmrqz
 * @since             1.0
 * @package           Ams_Data_Feed
 *
 * @wordpress-plugin
 * Plugin Name:       AMS Data Feed
 * Plugin URI:        https://github.com/bnstnrmrqz/ams-data-feed
 * Description:       Shortcode usage: [ams_data_feed type="tthm"] or [ams_data_feed type="mg"]
 * Version:           1.1
 * Author:            Ben Steiner Marquez
 * Author URI:        https://github.com/bnstnrmrqz/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ams-data-feed
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
define( 'AMS_DATA_FEED_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ams-data-feed-activator.php
 */
function activate_ams_data_feed() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ams-data-feed-activator.php';
	Ams_Data_Feed_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ams-data-feed-deactivator.php
 */
function deactivate_ams_data_feed() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ams-data-feed-deactivator.php';
	Ams_Data_Feed_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ams_data_feed' );
register_deactivation_hook( __FILE__, 'deactivate_ams_data_feed' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ams-data-feed.php';

/**
 * Include the shortcodes functionality file.
 */
include_once plugin_dir_path( __FILE__ ) . 'includes/ams-shortcode.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ams_data_feed() {

	$plugin = new Ams_Data_Feed();
	$plugin->run();

}
run_ams_data_feed();

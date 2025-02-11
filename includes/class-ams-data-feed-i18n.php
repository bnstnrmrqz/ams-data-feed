<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/bnstnrmrqz
 * @since      1.0.0
 *
 * @package    Ams_Data_Feed
 * @subpackage Ams_Data_Feed/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Ams_Data_Feed
 * @subpackage Ams_Data_Feed/includes
 * @author     Ben Steiner Marquez <bnstnrmrqz@gmail.com>
 */
class Ams_Data_Feed_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'ams-data-feed',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}

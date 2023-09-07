<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://https://kyoseicreative.com/
 * @since      1.0.0
 *
 * @package    Kyose_Personal_Values
 * @subpackage Kyose_Personal_Values/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Kyose_Personal_Values
 * @subpackage Kyose_Personal_Values/includes
 * @author     MJ of Kyosei <mjsuarez@kyoseicreative.com>
 */
class Kyose_Personal_Values_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'kyose-personal-values',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}

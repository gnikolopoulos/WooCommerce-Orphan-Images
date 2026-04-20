<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://interactive-design.gr
 * @since      1.0.0
 *
 * @package    Wc_Orphan_Images
 * @subpackage Wc_Orphan_Images/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wc_Orphan_Images
 * @subpackage Wc_Orphan_Images/includes
 * @author     George Nikolopoulos <georgen@interactive-design.gr>
 */
class Wc_Orphan_Images_i18n
{

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain()
	{
		load_plugin_textdomain(
			'wc-orphan-images',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}

}

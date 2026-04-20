<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://interactive-design.gr
 * @since      1.0.0
 *
 * @package    Wc_Orphan_Images
 * @subpackage Wc_Orphan_Images/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Wc_Orphan_Images
 * @subpackage Wc_Orphan_Images/includes
 * @author     George Nikolopoulos <georgen@interactive-design.gr>
 */
class Wc_Orphan_Images_Deactivator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate()
	{
		global $wpdb;

    $main_table_name = $wpdb->prefix . "orphan_images";
    $ignores_table_name = $wpdb->prefix . "ignored_images";

    $wpdb->query("DROP TABLE IF EXISTS $main_table_name;");
    $wpdb->query("DROP TABLE IF EXISTS $ignores_table_name;");
	}

}

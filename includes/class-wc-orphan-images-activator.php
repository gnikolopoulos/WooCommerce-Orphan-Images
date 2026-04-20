<?php

/**
 * Fired during plugin activation
 *
 * @link       https://interactive-design.gr
 * @since      1.0.0
 *
 * @package    Wc_Orphan_Images
 * @subpackage Wc_Orphan_Images/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wc_Orphan_Images
 * @subpackage Wc_Orphan_Images/includes
 * @author     George Nikolopoulos <georgen@interactive-design.gr>
 */
class Wc_Orphan_Images_Activator
{

  /**
   * Short Description. (use period)
   *
   * Long Description.
   *
   * @since    1.0.0
   */
  public static function activate()
  {
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();
    $main_table_name = $wpdb->prefix . "orphan_images";
    $ignores_table_name = $wpdb->prefix . "ignored_images";

    $main_sql = "CREATE TABLE $main_table_name (
      attachment_id int(10) NOT NULL AUTO_INCREMENT,
      created_at datetime DEFAULT NOW() NOT NULL,
      PRIMARY KEY  (attachment_id)
    ) $charset_collate;";

    $ignores_sql = "CREATE TABLE $ignores_table_name (
      attachment_id int(10) NOT NULL AUTO_INCREMENT,
      created_at datetime DEFAULT NOW() NOT NULL,
      PRIMARY KEY  (attachment_id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $main_sql );
    dbDelta( $ignores_sql );
  }

}

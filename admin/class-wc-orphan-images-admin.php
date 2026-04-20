<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://interactive-design.gr
 * @since      1.0.0
 *
 * @package    Wc_Orphan_Images
 * @subpackage Wc_Orphan_Images/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wc_Orphan_Images
 * @subpackage Wc_Orphan_Images/admin
 * @author     George Nikolopoulos <georgen@interactive-design.gr>
 */
class Wc_Orphan_Images_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version )
	{
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wc-orphan-images-admin.js', [], $this->version, true );

		wp_localize_script( $this->plugin_name, 'OrphanImages', ['ajaxurl' => admin_url('admin-ajax.php')]);
	}

	/**
	 * Adds the button that executes the custom SQL as well as the dropdown option
	 *
	 * @since    1.0.0
	 */
	public function add_button()
	{
		global $pagenow;

    if ($pagenow !== 'upload.php') return;

    ?>
    <button type="button" class="button" id="run-sql">Update ohpan images table</button>

    <span id="orphan-images-filter-mount"></span>
    <?php
	}

	/**
	 * Adds the required query params if the orphan filter is selected
	 *
	 * @since    1.0.0
	 *
	 * @param    WP_Query    $query    WP_Query object
	 */
	public function capture_filter($query)
	{
		if (!is_admin() || !$query->is_main_query()) return;

		if (empty($_GET['attachment-filter'])) return;

    if ($_GET['attachment-filter'] === 'orphan') {
      $query->set('orhpan_images_filter', 1);
    }

    if ($_GET['attachment-filter'] === 'ignored') {
      $query->set('ignored_images_filter', 1);
    }
	}

	/**
	 * Apply the filter to the query
	 * For an image to appear on the list
	 * its ID must exist in the orphan_images table, but not the ignored_images table
	 *
	 * @since    1.0.0
	 *
	 * @param    array       $clauses    Array of existing query clauses
	 * @param    WP_Query    $query      WP_Query object
	 * @return   array 									 Updated clauses array
	 */
	public function apply_filter($clauses, $query): array
	{
		if (!is_admin()) {
			return $clauses;
		}

		if ($query->get('post_type') !== 'attachment') {
			return $clauses;
		}

		global $wpdb;

		$main_table = $wpdb->prefix . 'orphan_images';
    $ignores_table = $wpdb->prefix . 'ignored_images';

    if ($query->get('orhpan_images_filter')) {
      $clauses['where'] .= "
        AND {$wpdb->posts}.post_type = 'attachment'
        AND {$wpdb->posts}.post_mime_type LIKE 'image/%'
        AND {$wpdb->posts}.ID IN (
          SELECT attachment_id
          FROM {$main_table}
        )
        AND {$wpdb->posts}.ID NOT IN (
          SELECT attachment_id
          FROM {$ignores_table}
        )
      ";
    }

    if ($query->get('ignored_images_filter')) {
      $clauses['where'] .= "
        AND {$wpdb->posts}.post_type = 'attachment'
        AND {$wpdb->posts}.post_mime_type LIKE 'image/%'
        AND {$wpdb->posts}.ID IN (
          SELECT attachment_id
          FROM {$ignores_table}
        )
      ";
    }

    return $clauses;
	}

	/**
	 * Adds an option to ignore a specific image
	 *
	 * @since    1.0.0
	 *
	 * @param    array      $actions    Existing post actions array
	 * @param    WP_Posy    $post       WP_Post oject
	 * @return   array                  Updated post actions array
	 */
	public function row_actions($actions, $post): array
	{
    if ($post->post_type !== 'attachment') return $actions;

    global $wpdb;

    $existing = $wpdb->get_var( "SELECT attachment_id FROM {$wpdb->prefix}ignored_images WHERE attachment_id = {$post->ID}" );

    if ($existing == NULL) {
	    $url = wp_nonce_url(
	      admin_url('admin-post.php?action=ignore_image&attachment_id=' . $post->ID),
	      'ignore_image_' . $post->ID
	    );

	    $actions['ignore_image'] = sprintf(
	      '<a href="%s" class="image-actions">Ignore image</a>',
	      esc_url($url)
	    );
	  } else {
	  	$url = wp_nonce_url(
	      admin_url('admin-post.php?action=unignore_image&attachment_id=' . $post->ID),
	      'unignore_image_' . $post->ID
	    );

	    $actions['unignore_image'] = sprintf(
	      '<a href="%s" class="image-actions">Un-Ignore image</a>',
	      esc_url($url)
	    );
	  }

    return $actions;
	}

	/**
	 * Adds the selected image's ID to the ignores table
	 *
	 * @since    1.0.0
	 */
	public function admin_ignore_image()
	{
    global $wpdb;

    $id = isset($_GET['attachment_id']) ? (int) $_GET['attachment_id'] : 0;
    if (!$id) {
    	wp_die('Missing attachment ID');
    }

    check_admin_referer('ignore_image_' . $id);

    $table = $wpdb->prefix . 'ignored_images';
    $wpdb->insert($table, ['attachment_id' => $id], ['%d']);

    set_transient(
      'orphan_images_notice',
      'Image added to the ignore list',
      30
    );

    $redirect = wp_get_referer() ?: admin_url('upload.php');
    wp_redirect($redirect);
    exit;
  }

  /**
	 * Removed the selected image's ID from the ignores table
	 *
	 * @since    1.0.0
	 */
	public function admin_unignore_image()
	{
    global $wpdb;

    $id = isset($_GET['attachment_id']) ? (int) $_GET['attachment_id'] : 0;
    if (!$id) {
	    wp_die('Missing attachment ID');
    }

    check_admin_referer('unignore_image_' . $id);

    $table = $wpdb->prefix . 'ignored_images';
    $wpdb->delete($table, ['attachment_id' => $id], ['%d']);

    set_transient(
      'orphan_images_notice',
      'Image removed from the ignore list',
      30
    );

    $redirect = wp_get_referer() ?: admin_url('upload.php');
    wp_redirect($redirect);
    exit;
  }

  /**
   * Run the SQL to fill the orpham_images table, after truncating it
   *
   * @since    1.0.0
   */
  public function ajax_run_sql()
  {
    global $wpdb;

    $wpdb->query("TRUNCATE {$wpdb->prefix}orphan_images;");
    $wpdb->query("INSERT INTO {$wpdb->prefix}orphan_images (attachment_id) SELECT p.ID FROM {$wpdb->prefix}posts p LEFT JOIN ( SELECT DISTINCT attachment_id FROM ( SELECT CAST(pm.meta_value AS UNSIGNED) AS attachment_id FROM {$wpdb->prefix}postmeta pm WHERE pm.meta_key = '_thumbnail_id' UNION ALL SELECT jt.value FROM {$wpdb->prefix}postmeta pm JOIN JSON_TABLE( CONCAT('[', pm.meta_value, ']'), '$[*]' COLUMNS (value INT PATH '$') ) jt WHERE pm.meta_key = '_product_image_gallery' AND pm.meta_value <> '' ) x ) used_ids ON used_ids.attachment_id = p.ID WHERE p.post_type = 'attachment' AND used_ids.attachment_id IS NULL;");

    set_transient(
      'orphan_images_notice',
      'SQL executed successfully',
      30
    );

    wp_send_json_success();
  }

  /**
   * Renders admin messages for the plugin
   *
   * @since    1.0.0
   */
  public function render_notice()
  {
    $msg = get_transient('orphan_images_notice');

    if (!$msg) return;

    delete_transient('orphan_images_notice');

    echo '<div class="notice notice-success is-dismissible"><p>' . esc_html($msg) . '</p></div>';
  }

}

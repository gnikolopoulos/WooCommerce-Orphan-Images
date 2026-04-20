<?php

/**
 *
 * @link              https://interactive-design.gr
 * @since             1.0.0
 * @package           Wc_Orphan_Images
 *
 * @wordpress-plugin
 * Plugin Name:       Woocommerce Orphan Images
 * Plugin URI:        https://interactive-design.gr
 * Description:       Displays a list of images that are not used as featured images or media gallery entries for any product.
 * Version:           1.0.0
 * Author:            George Nikolopoulos
 * Author URI:        https://interactive-design.gr/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       wc-orphan-images
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'WC_ORPHAN_IMAGES_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wc-orphan-images-activator.php
 */
function activate_wc_orphan_images()
{
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wc-orphan-images-activator.php';
	Wc_Orphan_Images_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wc-orphan-images-deactivator.php
 */
function deactivate_wc_orphan_images()
{
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wc-orphan-images-deactivator.php';
	Wc_Orphan_Images_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wc_orphan_images' );
register_deactivation_hook( __FILE__, 'deactivate_wc_orphan_images' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wc-orphan-images.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wc_orphan_images()
{
	$plugin = new Wc_Orphan_Images();
	$plugin->run();
}
run_wc_orphan_images();

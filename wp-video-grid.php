<?php
/**
 * Plugin Name: WordPress Video Grid
 * Plugin URI: https://ajithrn.com/wp-video-grid
 * Description: Plugin to manage and display videos in grid
 * Version: 1.5.0
 * Author: Ajith
 * Author URI: https://ajithrn.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: wp-video-grid
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

// Define plugin constants
define( 'WP_VIDEO_GRID_VERSION', '1.5.0' );
define( 'WP_VIDEO_GRID_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WP_VIDEO_GRID_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include required files
require_once WP_VIDEO_GRID_PLUGIN_DIR . 'src/PostType.php';
require_once WP_VIDEO_GRID_PLUGIN_DIR . 'src/Taxonomy.php';
require_once WP_VIDEO_GRID_PLUGIN_DIR . 'src/MetaBoxes.php';
require_once WP_VIDEO_GRID_PLUGIN_DIR . 'src/Frontend.php';
require_once WP_VIDEO_GRID_PLUGIN_DIR . 'src/Admin.php';
require_once WP_VIDEO_GRID_PLUGIN_DIR . 'src/Ajax.php';
require_once WP_VIDEO_GRID_PLUGIN_DIR . 'src/Settings.php';
require_once WP_VIDEO_GRID_PLUGIN_DIR . 'blocks/video-grid/block.php';
require_once WP_VIDEO_GRID_PLUGIN_DIR . 'blocks/single-video/block.php';

/**
 * Initialize the plugin
 */
function wp_video_grid_init() {
    new WP_Video_Grid\PostType();
    new WP_Video_Grid\Taxonomy();
    new WP_Video_Grid\MetaBoxes();
    new WP_Video_Grid\Frontend();
    new WP_Video_Grid\Admin();
    new WP_Video_Grid\Ajax();
    new WP_Video_Grid\Settings();
    new WP_Video_Grid\Blocks\Video_Grid_Block();
    new WP_Video_Grid\Blocks\Single_Video_Block();
}
add_action( 'plugins_loaded', 'wp_video_grid_init' );

/**
 * Enqueue admin scripts
 */
function wp_video_grid_admin_scripts() {
    wp_localize_script( 'wp-video-grid-admin', 'wp_video_grid', array(
        'nonce' => wp_create_nonce( 'wp_video_grid_nonce' )
    ) );
}
add_action( 'admin_enqueue_scripts', 'wp_video_grid_admin_scripts' );

/**
 * Activation hook
 */
function wp_video_grid_activate() {
    
    // Add default option for data deletion
    add_option('wp_video_grid_delete_data', 'no');
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'wp_video_grid_activate' );

/**
 * Deactivation hook
 */
function wp_video_grid_deactivate() {
    // Deactivation code here
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'wp_video_grid_deactivate' );

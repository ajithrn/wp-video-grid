<?php
/**
 * WP Video Grid - Admin
 *
 * This file handles the admin-specific functionality of the plugin.
 *
 * @package WP_Video_Grid
 * @version 1.2.0
 */

namespace WP_Video_Grid;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Admin class.
 */
class Admin {

    /**
     * Constructor.
     */
    public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
        add_filter( 'plugin_action_links_' . plugin_basename( WP_VIDEO_GRID_PLUGIN_DIR . 'wp-video-grid.php' ), array( $this, 'add_settings_link' ) );
    }

    /**
     * Enqueue admin scripts and styles.
     *
     * @param string $hook The current admin page.
     */
    public function enqueue_admin_scripts( $hook ) {
        $post_type = get_post_type();

        // Only enqueue on wp-video-grid post type pages.
        if ( 'wp-video-grid' !== $post_type ) {
            return;
        }

        // Enqueue styles.
        wp_enqueue_style(
            'wp-video-grid-admin',
            WP_VIDEO_GRID_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            WP_VIDEO_GRID_VERSION
        );

        // Enqueue scripts.
        wp_enqueue_script(
            'wp-video-grid-admin',
            WP_VIDEO_GRID_PLUGIN_URL . 'assets/js/admin.js',
            array( 'jquery' ),
            WP_VIDEO_GRID_VERSION,
            true
        );

        // Localize the script with new data.
        $localization_array = array(
            'nonce'   => wp_create_nonce( 'wp_video_grid_nonce' ),
            'ajaxurl' => admin_url( 'admin-ajax.php' )
        );
        wp_localize_script( 'wp-video-grid-admin', 'wp_video_grid', $localization_array );

        // Enqueue WordPress media scripts.
        wp_enqueue_media();
    }

    /**
     * Add settings link to plugin action links
     *
     * @param array $links Existing plugin action links
     * @return array Modified plugin action links
     */
    public function add_settings_link( $links ) {
      $settings_link = '<a href="' . admin_url( 'options-general.php?page=wp-video-grid-settings' ) . '">' . __( 'Settings', 'wp-video-grid' ) . '</a>';
      array_unshift( $links, $settings_link );
      return $links;
    }
}

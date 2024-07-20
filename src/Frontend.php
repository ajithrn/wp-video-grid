<?php
/**
 * WP Video Grid - Frontend
 *
 * @package WP_Video_Grid
 * @version 1.0.0
 */

namespace WP_Video_Grid;

defined( 'ABSPATH' ) || exit;

/**
 * Class Frontend
 * Handles frontend functionality for the WP Video Grid plugin
 */
class Frontend {
    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'wp_ajax_nopriv_wp_video_grid_get_embed', array( $this, 'get_embed' ) );
        add_action( 'wp_ajax_wp_video_grid_get_embed', array( $this, 'get_embed' ) );
    }

    /**
     * Enqueue frontend scripts and styles
     */
    public function enqueue_scripts() {
        wp_enqueue_style( 'wp-video-grid', WP_VIDEO_GRID_PLUGIN_URL . 'assets/css/wp-video-grid.css', array(), WP_VIDEO_GRID_VERSION );
        wp_enqueue_script( 'wp-video-grid', WP_VIDEO_GRID_PLUGIN_URL . 'assets/js/frontend.js', array( 'jquery' ), WP_VIDEO_GRID_VERSION, true );
        
        wp_localize_script( 'wp-video-grid', 'wp_video_grid', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'wp_video_grid_nonce' )
        ));
    }

    /**
     * AJAX handler for getting video embed code
     */
    public function get_embed() {
        check_ajax_referer( 'wp_video_grid_nonce', 'nonce' );

        $video_url = isset( $_POST['video_url'] ) ? esc_url_raw( $_POST['video_url'] ) : '';

        if ( empty( $video_url ) ) {
            wp_send_json_error( array( 'message' => __( 'Invalid video URL', 'wp-video-grid' ) ) );
        }

        // Add autoplay and related video parameters
        $args = array(
            'autoplay' => 1,
            'mute' => 1,  // Mute is often required for autoplay
            'controls' => 1,
            'rel' => 0
        );

        $embed = wp_oembed_get( $video_url, $args );

        if ( $embed ) {
            // Modify the embed code to force autoplay
            $embed = preg_replace('/(src="[^"]+)/', '$1&autoplay=1&mute=1', $embed);
            wp_send_json_success( array( 'embed' => $embed ) );
        } else {
            wp_send_json_error( array( 'message' => __( 'Unable to generate embed for this URL', 'wp-video-grid' ) ) );
        }
    }
}

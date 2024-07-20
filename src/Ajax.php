<?php
/**
 * WP Video Grid - Ajax Handler
 *
 * @package WP_Video_Grid
 * @version 1.0.0
 */

namespace WP_Video_Grid;

defined( 'ABSPATH' ) || exit;

/**
 * Class Ajax
 * Handles AJAX requests for the plugin
 */
class Ajax {
    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'wp_ajax_wp_video_grid_fetch_thumbnail', array( $this, 'fetch_thumbnail' ) );
        add_action( 'wp_ajax_wp_video_grid_check_embed', array( $this, 'check_embed' ) );
    }

    /**
     * Fetch thumbnail for a given video URL
     */
    public function fetch_thumbnail() {
        check_ajax_referer( 'wp_video_grid_nonce', 'nonce' );

        if ( ! current_user_can( 'edit_posts' ) ) {
            wp_send_json_error( array( 'message' => __( 'Permission denied.', 'wp-video-grid' ) ) );
        }

        $video_url = isset( $_POST['video_url'] ) ? esc_url_raw( $_POST['video_url'] ) : '';
        $metaboxes = new MetaBoxes();
        $thumbnail_url = $metaboxes->get_video_thumbnail( $video_url );

        if ( $thumbnail_url ) {
            wp_send_json_success( array( 'thumbnail_url' => $thumbnail_url ) );
        } else {
            wp_send_json_error( array( 'message' => __( 'Unable to fetch thumbnail.', 'wp-video-grid' ) ) );
        }
    }

    /**
     * Check if a video URL can be embedded
     */
    public function check_embed() {
        check_ajax_referer( 'wp_video_grid_nonce', 'nonce' );

        if ( ! current_user_can( 'edit_posts' ) ) {
            wp_send_json_error( array( 'message' => __( 'Permission denied.', 'wp-video-grid' ) ) );
        }

        $video_url = isset( $_POST['video_url'] ) ? esc_url_raw( $_POST['video_url'] ) : '';

        if ( empty( $video_url ) ) {
            wp_send_json_error( array( 'message' => __( 'Please provide a valid video URL.', 'wp-video-grid' ) ) );
        }

        $embed = wp_oembed_get( $video_url );

        if ( $embed ) {
            wp_send_json_success( array( 'embed' => $embed ) );
        } else {
            wp_send_json_error( array( 'message' => __( 'This URL does not support embedding or is not a valid video URL.', 'wp-video-grid' ) ) );
        }
    }
}

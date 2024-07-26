<?php
/**
 * WP Video Grid - Frontend
 *
 * @package WP_Video_Grid
 * @version 1.5.0
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

    /**
     * Render a single video item
     *
     * @param WP_Post $video The video post object.
     * @param string  $video_source The video source URL.
     * @param string  $thumbnail The thumbnail URL.
     * @param string  $display_type The display type (inline or popup).
     * @param string  $video_type The video type (external or self-hosted).
     * @return string The HTML for the video item.
     */
    public static function render_video_item( $video, $video_source, $thumbnail, $display_type, $video_type ) {
        ob_start();
        ?>
        <div class="wp-video-grid-item" 
            data-video-url="<?php echo esc_url( $video_source ); ?>" 
            data-display-type="<?php echo esc_attr( $display_type ); ?>" 
            data-video-type="<?php echo esc_attr( $video_type ); ?>">
            <div class="wp-video-grid-item-inner">
                <div class="thumbnail-container">
                    <img src="<?php echo esc_url( $thumbnail ); ?>" 
                        alt="<?php echo esc_attr( $video->post_title ); ?>" 
                        class="video-thumbnail">
                    <div class="play-button"></div>
                    <div class="loading-spinner"></div>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}

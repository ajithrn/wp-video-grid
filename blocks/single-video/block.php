<?php
/**
 * WP Video Grid - Single Video Block
 *
 * @package WP_Video_Grid
 * @version 1.4.0
 */

namespace WP_Video_Grid\Blocks;

defined( 'ABSPATH' ) || exit;

/**
 * Class Single_Video_Block
 * Handles the single video block functionality
 */
class Single_Video_Block {
    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'init', array( $this, 'register_block' ) );
        add_action( 'rest_api_init', array( $this, 'register_rest_fields' ) );
    }

    /**
     * Register the block
     */
    public function register_block() {
        if ( ! function_exists( 'register_block_type' ) ) {
            return;
        }

        // Use the global WP_Block_Type_Registry class
        if ( \WP_Block_Type_Registry::get_instance()->is_registered( 'wp-video-grid/single-video' ) ) {
            return;
        }

        wp_register_script(
            'wp-video-grid-single-video-editor',
            WP_VIDEO_GRID_PLUGIN_URL . 'blocks/single-video/block-editor.js',
            array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-api-fetch', 'wp-data' ),
            WP_VIDEO_GRID_VERSION
        );

        register_block_type( 'wp-video-grid/single-video', array(
            'editor_script' => 'wp-video-grid-single-video-editor',
            'render_callback' => array( $this, 'render_block' ),
            'attributes' => array(
                'videoId' => array(
                    'type' => 'number',
                ),
                'displayType' => array(
                    'type' => 'string',
                    'default' => 'inline',
                ),
            ),
        ) );
    }

    /**
     * Register REST API fields
     */
    public function register_rest_fields() {
        register_rest_field( 'wp-video-grid', 'video_thumbnail', array(
            'get_callback' => array( $this, 'get_video_thumbnail' ),
            'schema' => null,
        ));
    }

    /**
     * Get featured media source URL
     *
     * @param array $object The object data.
     * @return string|false The featured media source URL or false.
     */
    public function get_video_thumbnail( $object ) {
        $post_id = $object['id'];
        $custom_thumbnail = get_post_meta( $post_id, '_custom_thumbnail', true );
        $auto_thumbnail = get_post_meta( $post_id, '_auto_thumbnail', true );
        
        if ( $custom_thumbnail ) {
            return wp_get_attachment_image_url( $custom_thumbnail, 'medium' );
        } elseif ( $auto_thumbnail ) {
            return $auto_thumbnail;
        }
        
        return WP_VIDEO_GRID_PLUGIN_URL . 'assets/images/default-video-thumbnail.jpg';
    }

    /**
     * Render the block
     *
     * @param array $attributes Block attributes.
     * @return string Rendered block HTML.
     */
      public function render_block( $attributes ) {
        $video_id = isset( $attributes['videoId'] ) ? intval( $attributes['videoId'] ) : 0;
        $display_type = isset( $attributes['displayType'] ) ? sanitize_text_field( $attributes['displayType'] ) : 'inline';

        if ( ! $video_id ) {
            return '<p>' . __( 'Please select a video.', 'wp-video-grid' ) . '</p>';
        }

        $video = get_post( $video_id );

        if ( ! $video || 'wp-video-grid' !== $video->post_type ) {
            return '<p>' . __( 'Invalid video selected.', 'wp-video-grid' ) . '</p>';
        }

        $video_type = get_post_meta( $video_id, '_video_type', true );
        $video_url = get_post_meta( $video_id, '_video_url', true );
        $video_file = get_post_meta( $video_id, '_video_file', true );
        
        $thumbnail = $this->get_video_thumbnail( array( 'id' => $video_id ) );
        $video_source = $video_type === 'external' ? $video_url : $video_file;

        ob_start();
        ?>
        <div class="wp-video-grid-item" data-video-url="<?php echo esc_url( $video_source ); ?>" data-display-type="<?php echo esc_attr( $display_type ); ?>" data-video-type="<?php echo esc_attr( $video_type ); ?>">
            <div class="wp-video-grid-item-inner">
                <div class="thumbnail-container">
                    <img src="<?php echo esc_url( $thumbnail ); ?>" alt="<?php echo esc_attr( $video->post_title ); ?>" class="video-thumbnail">
                    <div class="play-button"></div>
                    <div class="loading-spinner"></div>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}

new Single_Video_Block();


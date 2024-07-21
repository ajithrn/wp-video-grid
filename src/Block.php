<?php
/**
 * WP Video Grid - Gutenberg Block
 *
 * @package WP_Video_Grid
 * @version 1.1.1
 */

namespace WP_Video_Grid;

defined( 'ABSPATH' ) || exit;

/**
 * Class Block
 * Handles the Gutenberg block functionality
 */
class Block {
    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'init', array( $this, 'register_block' ) );
        add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ) );
        add_action( 'rest_api_init', array( $this, 'register_rest_route' ) );
    }

    /**
     * Register the REST API route for block preview
     */
    public function register_rest_route() {
        register_rest_route( 'wp-video-grid/v1', '/preview', array(
            'methods' => 'POST',
            'callback' => array( $this, 'get_preview' ),
            'permission_callback' => function() {
                return current_user_can( 'edit_posts' );
            },
        ) );
    }

    /**
     * Get the block preview
     *
     * @param WP_REST_Request $request The REST request object.
     * @return WP_REST_Response The REST response.
     */
    public function get_preview( $request ) {
        $attributes = $request->get_params();
        $preview = $this->render_block( $attributes );
        return new \WP_REST_Response( array( 'preview' => $preview ), 200 );
    }

    /**
     * Register the Gutenberg block
     */
    public function register_block() {
        if ( ! function_exists( 'register_block_type' ) ) {
            return;
        }

        register_block_type( 'wp-video-grid/video-grid', array(
            'editor_script' => 'wp-video-grid-editor',
            'editor_style'  => 'wp-video-grid-editor',
            'render_callback' => array( $this, 'render_block' ),
            'attributes'    => array(
                'totalVideos' => array(
                    'type' => 'number',
                    'default' => 6,
                ),
                'category' => array(
                    'type' => 'string',
                    'default' => '',
                ),
                'videosPerGrid' => array(
                    'type' => 'number',
                    'default' => 3,
                ),
                'displayType' => array(
                    'type' => 'string',
                    'default' => 'inline',
                ),
            ),
        ) );
    }

    /**
     * Enqueue block editor assets
     */
    public function enqueue_block_editor_assets() {
        wp_enqueue_script(
            'wp-video-grid-editor',
            WP_VIDEO_GRID_PLUGIN_URL . 'assets/js/block-editor.js',
            array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor' ),
            filemtime( WP_VIDEO_GRID_PLUGIN_DIR . 'assets/js/block-editor.js' )
        );

        wp_enqueue_style(
            'wp-video-grid-editor',
            WP_VIDEO_GRID_PLUGIN_URL . 'assets/css/wp-video-grid.css',
            array(),
            filemtime( WP_VIDEO_GRID_PLUGIN_DIR . 'assets/css/wp-video-grid.css' )
        );

        wp_localize_script( 'wp-video-grid-editor', 'wpVideoGridData', array(
            'categories' => $this->get_video_categories(),
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'wp_video_grid_nonce' )
        ));
    }

    /**
     * Get video categories
     *
     * @return array Array of category options.
     */
    private function get_video_categories() {
        $categories = get_terms( array(
            'taxonomy' => 'wp-video-grid-category',
            'hide_empty' => false,
        ) );

        $category_options = array(
            array( 'label' => __( 'All Categories', 'wp-video-grid' ), 'value' => '' )
        );

        foreach ( $categories as $category ) {
            $category_options[] = array(
                'label' => $category->name,
                'value' => $category->slug,
            );
        }

        return $category_options;
    }

    /**
     * Render the block
     *
     * @param array $attributes Block attributes.
     * @return string Rendered block HTML.
     */
    public function render_block( $attributes ) {
        $total_videos = $attributes['totalVideos'];
        $category = $attributes['category'];
        $videos_per_grid = $attributes['videosPerGrid'];
        $display_type = $attributes['displayType'];

        $args = array(
            'post_type' => 'wp-video-grid',
            'posts_per_page' => $total_videos,
        );

        if ( ! empty( $category ) ) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'wp-video-grid-category',
                    'field' => 'slug',
                    'terms' => $category,
                ),
            );
        }

        $videos = get_posts( $args );

        ob_start();

        if ( empty( $videos ) ) {
          $add_new_link = admin_url( 'post-new.php?post_type=wp-video-grid' );
          $manage_categories_link = admin_url( 'edit-tags.php?taxonomy=wp-video-grid-category&post_type=wp-video-grid' );
          ?>
            <div class="wp-video-grid-empty">
                <h4><?php _e( 'No videos found.', 'wp-video-grid' ); ?></h4>
                <p><?php _e( 'To display videos:', 'wp-video-grid' ); ?></p>
                <ul>
                    <li><?php printf( __( '<a href="%s">Add new videos</a> using the WP Video Grid post type.', 'wp-video-grid' ), esc_url( $add_new_link ) ); ?></li>
                    <li><?php printf( __( '<a href="%s">Manage categories</a> and make sure you have selected the correct category (if applicable).', 'wp-video-grid' ), esc_url( $manage_categories_link ) ); ?></li>
                    <li><?php _e( 'Verify that your videos are published and not in draft status.', 'wp-video-grid' ); ?></li>
                </ul>
            </div>
            <?php
        } else {
            ?>
            <div class="wp-video-grid" style="display: grid; grid-template-columns: repeat(<?php echo esc_attr( $videos_per_grid ); ?>, 1fr); gap: 20px;">
                <?php foreach ( $videos as $video ) : ?>
                    <?php
                    $video_type = get_post_meta( $video->ID, '_video_type', true );
                    $video_url = get_post_meta( $video->ID, '_video_url', true );
                    $video_file = get_post_meta( $video->ID, '_video_file', true );
                    $custom_thumbnail = get_post_meta( $video->ID, '_custom_thumbnail', true );
                    $auto_thumbnail = get_post_meta( $video->ID, '_auto_thumbnail', true );
                    
                    $thumbnail = $custom_thumbnail ? wp_get_attachment_image_url( $custom_thumbnail, 'medium' ) : $auto_thumbnail;
                    
                    if ( ! $thumbnail ) {
                        $thumbnail = WP_VIDEO_GRID_PLUGIN_URL . 'assets/images/default-video-thumbnail.jpg';
                    }
                    
                    $video_source = $video_type === 'external' ? $video_url : $video_file;
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
                <?php endforeach; ?>
            </div>
            <?php
        }
        return ob_get_clean();
    }
}

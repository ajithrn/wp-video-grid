<?php
/**
 * WP Video Grid - Meta Boxes
 *
 * @package WP_Video_Grid
 * @version 1.1.0
 */

namespace WP_Video_Grid;

defined( 'ABSPATH' ) || exit;

/**
 * Class MetaBoxes
 * 
 * Handles the creation and management of custom meta boxes for the WP Video Grid plugin.
 *
 * @package WP_Video_Grid
 */
class MetaBoxes {

    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save_meta_boxes' ) );
    }

    /**
     * Add custom meta boxes
     */
    public function add_meta_boxes() {
        add_meta_box(
            'wp_video_grid_metabox',
            __( 'Video Details', 'wp-video-grid' ),
            array( $this, 'render_meta_box' ),
            'wp-video-grid',
            'normal',
            'high'
        );
    }

    /**
     * Render the meta box
     *
     * @param WP_Post $post The post object.
     */
    public function render_meta_box( $post ) {
        // Add nonce for security
        wp_nonce_field( 'wp_video_grid_meta_box', 'wp_video_grid_meta_box_nonce' );

        // Get current values
        $video_type = get_post_meta( $post->ID, '_video_type', true );
        $video_url = get_post_meta( $post->ID, '_video_url', true );
        $video_file = get_post_meta( $post->ID, '_video_file', true );
        $custom_thumbnail = get_post_meta( $post->ID, '_custom_thumbnail', true );
        $auto_thumbnail = get_post_meta( $post->ID, '_auto_thumbnail', true );

        // Start output buffering
        ob_start();
        ?>
        <div class="wp-video-grid-metabox">
            <?php $this->render_video_source_field( $video_type ); ?>
            <?php $this->render_video_upload_field( $video_type, $video_file ); ?>
            <?php $this->render_video_external_field( $video_type, $video_url ); ?>
            <?php $this->render_custom_thumbnail_field( $custom_thumbnail, $auto_thumbnail ); ?>
        </div>
        <?php
        // End output buffering and echo the content
        echo ob_get_clean();
    }

    /**
     * Render the video source field
     *
     * @param string $video_type The current video type.
     */
    private function render_video_source_field( $video_type ) {
        ?>
        <p>
            <label for="video_type"><?php _e( 'Video Source', 'wp-video-grid' ); ?></label>
            <select name="video_type" id="video_type">
                <option value="upload" <?php selected( $video_type, 'upload' ); ?>><?php _e( 'Upload Video', 'wp-video-grid' ); ?></option>
                <option value="external" <?php selected( $video_type, 'external' ); ?>><?php _e( 'External Video (YouTube/Vimeo)', 'wp-video-grid' ); ?></option>
            </select>
            <br>
            <i><?php _e( 'Choose whether to upload a video file or use an external video from YouTube or Vimeo.', 'wp-video-grid' ); ?></i>
        </p>
        <?php
    }

    /**
     * Render the video upload field
     *
     * @param string $video_type The current video type.
     * @param string $video_file The current video file URL.
     */
    private function render_video_upload_field( $video_type, $video_file ) {
        ?>
        <div id="video_upload_container" <?php echo $video_type === 'external' ? 'style="display:none;"' : ''; ?>>
            <p>
                <label for="video_file"><?php _e( 'Video File', 'wp-video-grid' ); ?></label>
                <input type="text" id="video_file" name="video_file" value="<?php echo esc_url( $video_file ); ?>" />
                <button type="button" id="upload_video_button" class="button"><?php _e( 'Upload Video', 'wp-video-grid' ); ?></button>
                <br>
                <i><?php _e( 'Upload or choose a video file from your media library.', 'wp-video-grid' ); ?></i>
            </p>
        </div>
        <?php
    }

    /**
     * Render the external video field
     *
     * @param string $video_type The current video type.
     * @param string $video_url The current video URL.
     */
    private function render_video_external_field( $video_type, $video_url ) {
        ?>
        <div id="video_external_container" <?php echo $video_type === 'upload' ? 'style="display:none;"' : ''; ?>>
            <p>
                <label for="video_url"><?php _e( 'Video URL', 'wp-video-grid' ); ?></label>
                <input type="text" id="video_url" name="video_url" value="<?php echo esc_url( $video_url ); ?>" />
                <button type="button" id="check_embed_button" class="button"><?php _e( 'Check Embed', 'wp-video-grid' ); ?></button>
                <br>
                <i><?php _e( 'Enter the URL of a YouTube or Vimeo video.', 'wp-video-grid' ); ?><br>
                <?php _e( 'Click "Check Embed" to verify if the video URL is valid and can be embedded. This will display a preview of the video embed.', 'wp-video-grid' ); ?></i>
            </p>
            <div id="embed_preview_container"></div>
        </div>
        <?php
    }

    /**
     * Render the custom thumbnail field
     *
     * @param int|string $custom_thumbnail The custom thumbnail ID.
     * @param string $auto_thumbnail The auto-generated thumbnail URL.
     */
    private function render_custom_thumbnail_field( $custom_thumbnail, $auto_thumbnail ) {
        ?>
        <p>
            <label for="custom_thumbnail"><?php _e( 'Custom Thumbnail', 'wp-video-grid' ); ?></label>
            <input type="hidden" id="custom_thumbnail" name="custom_thumbnail" value="<?php echo esc_attr( $custom_thumbnail ); ?>" />
            <button type="button" id="upload_thumbnail_button" class="button"><?php _e( 'Upload Thumbnail', 'wp-video-grid' ); ?></button>
            <button type="button" id="remove_thumbnail_button" class="button" <?php echo empty($custom_thumbnail) ? 'style="display:none;"' : ''; ?>><?php _e( 'Remove Thumbnail', 'wp-video-grid' ); ?></button>
            <br>
            <i><?php _e( 'Upload a custom thumbnail image. If not provided, the plugin will attempt to use an auto-generated thumbnail for external videos.', 'wp-video-grid' ); ?></i>
        </p>

        <div id="thumbnail_preview_container">
            <?php
            if ( $custom_thumbnail ) {
                echo wp_get_attachment_image( $custom_thumbnail, 'thumbnail', false, array( 'id' => 'thumbnail_preview' ) );
            } elseif ( $auto_thumbnail ) {
                echo '<img src="' . esc_url( $auto_thumbnail ) . '" id="thumbnail_preview" />';
            }
            ?>
        </div>
        <?php
    }

    /**
     * Save the meta box data
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save_meta_boxes( $post_id ) {
        // Check if our nonce is set.
        if ( ! isset( $_POST['wp_video_grid_meta_box_nonce'] ) ) {
            return;
        }

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $_POST['wp_video_grid_meta_box_nonce'], 'wp_video_grid_meta_box' ) ) {
            return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // Check the user's permissions.
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        // Sanitize user input and update the meta fields.
        $fields = array(
            '_video_type' => 'sanitize_text_field',
            '_video_url' => 'esc_url_raw',
            '_video_file' => 'esc_url_raw',
            '_custom_thumbnail' => 'absint',
        );

        foreach ( $fields as $key => $sanitize_callback ) {
            if ( isset( $_POST[ltrim($key, '_')] ) ) {
                $value = call_user_func( $sanitize_callback, $_POST[ltrim($key, '_')] );
                update_post_meta( $post_id, $key, $value );
            } elseif ( $key === '_custom_thumbnail' ) {
                delete_post_meta( $post_id, $key );
            }
        }

        // Fetch and save auto thumbnail for external videos
        if ( $_POST['video_type'] === 'external' && ! empty( $_POST['video_url'] ) ) {
            $auto_thumbnail = $this->get_video_thumbnail( esc_url_raw( $_POST['video_url'] ) );
            if ( $auto_thumbnail ) {
                update_post_meta( $post_id, '_auto_thumbnail', $auto_thumbnail );
            }
        } else {
            delete_post_meta( $post_id, '_auto_thumbnail' );
        }
    }

    /**
     * Get the thumbnail URL for a video
     *
     * @param string $url The URL of the video.
     * @return string|false The URL of the thumbnail, or false if not found.
     */
    public function get_video_thumbnail( $url ) {
        // YouTube video
        if ( preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match ) ) {
            $video_id = $match[1];
            return "https://img.youtube.com/vi/{$video_id}/0.jpg";
        } 
        // Vimeo video
        elseif ( preg_match( '%^https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)(?:[?]?.*)$%im', $url, $match ) ) {
            $video_id = $match[3];
            $data = file_get_contents( "http://vimeo.com/api/v2/video/{$video_id}.json" );
            $data = json_decode( $data );
            return $data[0]->thumbnail_large;
        }

        return false;
    }
}

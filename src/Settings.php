<?php
/**
 * WP Video Grid - Settings
 *
 * @package WP_Video_Grid
 * @version 1.2.0
 */

namespace WP_Video_Grid;

defined( 'ABSPATH' ) || exit;

/**
 * Settings class.
 *
 * Handles the plugin settings page and related functionality.
 */
class Settings {

    /**
     * Constructor.
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_settings_page'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    /**
     * Add settings page to WordPress admin menu.
     */
    public function add_settings_page() {
        add_options_page(
            'WP Video Grid Settings',
            'WP Video Grid',
            'manage_options',
            'wp-video-grid-settings',
            array($this, 'render_settings_page')
        );
    }

    /**
     * Register plugin settings.
     */
    public function register_settings() {
        register_setting('wp_video_grid_settings', 'wp_video_grid_delete_data');
    }

    /**
     * Render the settings page.
     */
    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('wp_video_grid_settings');
                do_settings_sections('wp_video_grid_settings');
                ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Delete data on uninstall</th>
                        <td>
                            <label>
                                <input type="checkbox" name="wp_video_grid_delete_data" value="yes" <?php checked('yes', get_option('wp_video_grid_delete_data', 'no')); ?> />
                                Delete all plugin data when uninstalling the plugin
                            </label>
                            <p class="description">If unchecked, all video posts and related data will be kept in the database after plugin uninstall.</p>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}

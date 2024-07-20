<?php
/**
 * WP Video Grid - Custom Taxonomy
 *
 * @package WP_Video_Grid
 * @version 1.0.0
 */

namespace WP_Video_Grid;

defined( 'ABSPATH' ) || exit;

/**
 * Class Taxonomy
 * Handles the custom taxonomy for WP Video Grid
 */
class Taxonomy {
    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'init', array( $this, 'register_taxonomy' ) );
    }

    /**
     * Register the custom taxonomy
     */
    public function register_taxonomy() {
        $labels = array(
            'name'              => _x( 'Video Categories', 'taxonomy general name', 'wp-video-grid' ),
            'singular_name'     => _x( 'Video Category', 'taxonomy singular name', 'wp-video-grid' ),
            'search_items'      => __( 'Search Video Categories', 'wp-video-grid' ),
            'all_items'         => __( 'All Video Categories', 'wp-video-grid' ),
            'parent_item'       => __( 'Parent Video Category', 'wp-video-grid' ),
            'parent_item_colon' => __( 'Parent Video Category:', 'wp-video-grid' ),
            'edit_item'         => __( 'Edit Video Category', 'wp-video-grid' ),
            'update_item'       => __( 'Update Video Category', 'wp-video-grid' ),
            'add_new_item'      => __( 'Add New Video Category', 'wp-video-grid' ),
            'new_item_name'     => __( 'New Video Category Name', 'wp-video-grid' ),
            'menu_name'         => __( 'Video Categories', 'wp-video-grid' ),
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'wp-video-grid-category' ),
        );

        register_taxonomy( 'wp-video-grid-category', array( 'wp-video-grid' ), $args );
    }
}

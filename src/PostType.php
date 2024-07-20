<?php
/**
 * WP Video Grid - Custom Post Type
 *
 * @package WP_Video_Grid
 * @version 1.0.0
 */

namespace WP_Video_Grid;

defined( 'ABSPATH' ) || exit;

/**
 * Class PostType
 * Handles the custom post type for WP Video Grid
 */
class PostType {
    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'init', array( $this, 'register_post_type' ) );
    }

    /**
     * Register the custom post type
     */
    public function register_post_type() {
        $labels = array(
            'name'                  => _x( 'WP Videos Grid', 'Post type general name', 'wp-video-grid' ),
            'singular_name'         => _x( 'WP Video Grid', 'Post type singular name', 'wp-video-grid' ),
            'menu_name'             => _x( 'WP Videos Grid', 'Admin Menu text', 'wp-video-grid' ),
            'name_admin_bar'        => _x( 'WP Video Grid', 'Add New on Toolbar', 'wp-video-grid' ),
            'add_new'               => __( 'Add New', 'wp-video-grid' ),
            'add_new_item'          => __( 'Add New Video', 'wp-video-grid' ),
            'new_item'              => __( 'New Video', 'wp-video-grid' ),
            'edit_item'             => __( 'Edit Video', 'wp-video-grid' ),
            'view_item'             => __( 'View Video', 'wp-video-grid' ),
            'all_items'             => __( 'All Videos', 'wp-video-grid' ),
            'search_items'          => __( 'Search Videos', 'wp-video-grid' ),
            'parent_item_colon'     => __( 'Parent Videos:', 'wp-video-grid' ),
            'not_found'             => __( 'No videos found.', 'wp-video-grid' ),
            'not_found_in_trash'    => __( 'No videos found in Trash.', 'wp-video-grid' ),
            'featured_image'        => _x( 'Video Thumbnail', 'Overrides the "Featured Image" phrase for this post type.', 'wp-video-grid' ),
            'set_featured_image'    => _x( 'Set video thumbnail', 'Overrides the "Set featured image" phrase for this post type.', 'wp-video-grid' ),
            'remove_featured_image' => _x( 'Remove video thumbnail', 'Overrides the "Remove featured image" phrase for this post type.', 'wp-video-grid' ),
            'use_featured_image'    => _x( 'Use as video thumbnail', 'Overrides the "Use as featured image" phrase for this post type.', 'wp-video-grid' ),
            'archives'              => _x( 'Video archives', 'The post type archive label used in nav menus.', 'wp-video-grid' ),
            'insert_into_item'      => _x( 'Insert into video', 'Overrides the "Insert into post"/"Insert into page" phrase (used when inserting media into a post).', 'wp-video-grid' ),
            'uploaded_to_this_item' => _x( 'Uploaded to this video', 'Overrides the "Uploaded to this post"/"Uploaded to this page" phrase (used when viewing media attached to a post).', 'wp-video-grid' ),
            'filter_items_list'     => _x( 'Filter videos list', 'Screen reader text for the filter links heading on the post type listing screen.', 'wp-video-grid' ),
            'items_list_navigation' => _x( 'Videos list navigation', 'Screen reader text for the pagination heading on the post type listing screen.', 'wp-video-grid' ),
            'items_list'            => _x( 'Videos list', 'Screen reader text for the items list heading on the post type listing screen.', 'wp-video-grid' ),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'wp-video-grid' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title', 'editor', 'thumbnail' ),
            'menu_icon'          => 'dashicons-format-video',
        );

        register_post_type( 'wp-video-grid', $args );
    }
}

<?php
/**
 * WP Video Grid - Uninstall
 *
 * @package WP_Video_Grid
 * @version 1.2.0
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Check if the user wants to delete data
if ( get_option( 'wp_video_grid_delete_data', 'no' ) === 'yes' ) {
    // Delete custom post type posts
    $posts = get_posts( array(
        'numberposts' => -1,
        'post_type' => 'wp-video-grid',
        'post_status' => 'any'
    ) );

    foreach ( $posts as $post ) {
        wp_delete_post( $post->ID, true );
    }

    // Delete custom taxonomy terms
    $terms = get_terms( array(
        'taxonomy' => 'wp-video-grid-category',
        'hide_empty' => false,
    ) );

    foreach ( $terms as $term ) {
        wp_delete_term( $term->term_id, 'wp-video-grid-category' );
    }

    // Delete plugin options
    delete_option( 'wp_video_grid_version' );
    delete_option( 'wp_video_grid_delete_data' );

    // Clear any cached data that has been removed
    wp_cache_flush();
} else {
    // Removing plugin-specific options:
    delete_option( 'wp_video_grid_version' );
    delete_option( 'wp_video_grid_delete_data' );
}

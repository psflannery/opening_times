<?php
/**
 * Jetpack Compatibility File
 * 
 * See: http://jetpack.me/
 *
 * @package Opening Times
 */


/**
 * Filter the list of Post Types available in the WordPress.com REST API.
 *
 * @param array $allowed_post_types Array of whitelisted Post Types.
 * @return array $allowed_post_types Array of whitelisted Post Types, including our Custom Post Types.
 *
 * @since Opening Times 1.0.0
 */
function opening_times_allow_post_type_wpcom( $allowed_post_types ) {
    $allowed_post_types[] = 'reading, articles';

    return $allowed_post_types;
}
add_filter( 'rest_api_allowed_post_types', 'opening_times_allow_post_type_wpcom');


/**
 * Remove Jetpack  CSS
 *
 * First, make sure Jetpack doesn't concatenate all its CSS
 * Then, remove each CSS file, one at a time
 *
 * @since Opening Times 1.0.0
 */

function opening_times_remove_all_jetpack_css() {
	wp_deregister_style( 'AtD_style' ); // After the Deadline
	wp_deregister_style( 'jetpack_likes' ); // Likes
	wp_deregister_style( 'jetpack_related-posts' ); //Related Posts
	wp_deregister_style( 'jetpack-carousel' ); // Carousel
	wp_deregister_style( 'grunion.css' ); // Grunion contact form
	wp_deregister_style( 'the-neverending-homepage' ); // Infinite Scroll
	wp_deregister_style( 'infinity-twentyten' ); // Infinite Scroll - Twentyten Theme
	wp_deregister_style( 'infinity-twentyeleven' ); // Infinite Scroll - Twentyeleven Theme
	wp_deregister_style( 'infinity-twentytwelve' ); // Infinite Scroll - Twentytwelve Theme
	wp_deregister_style( 'noticons' ); // Notes
	wp_deregister_style( 'post-by-email' ); // Post by Email
	wp_deregister_style( 'publicize' ); // Publicize
	wp_deregister_style( 'sharedaddy' ); // Sharedaddy
	wp_deregister_style( 'sharing' ); // Sharedaddy Sharing
	wp_deregister_style( 'stats_reports_css' ); // Stats
	wp_deregister_style( 'jetpack-widgets' ); // Widgets
	wp_deregister_style( 'jetpack-slideshow' ); // Slideshows
	wp_deregister_style( 'presentations' ); // Presentation shortcode
	wp_deregister_style( 'jetpack-subscriptions' ); // Subscriptions
	wp_deregister_style( 'tiled-gallery' ); // Tiled Galleries
	wp_deregister_style( 'widget-conditions' ); // Widget Visibility
	wp_deregister_style( 'jetpack_display_posts_widget' ); // Display Posts Widget
	wp_deregister_style( 'gravatar-profile-widget' ); // Gravatar Widget
	wp_deregister_style( 'widget-grid-and-list' ); // Top Posts widget
	wp_deregister_style( 'jetpack-widgets' ); // Widgets
}
add_filter( 'jetpack_implode_frontend_css', '__return_false' );
add_action('wp_print_styles', 'opening_times_remove_all_jetpack_css' );

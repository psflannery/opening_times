<?php
/**
 * Jetpack Compatibility File
 * 
 * See: http://jetpack.me/
 *
 * @package Opening Times
 */

/**
 * Jetpack setup function.
 *
 * See: https://jetpack.com/support/featured-content/
 *
 * @since Opening Times 1.0.1
 */
function opening_times_jetpack_setup() {
	// Add theme support for Featured Content.
	add_theme_support( 'featured-content', array(
		'filter'     => 'opening_times_get_featured_posts',
		'max_posts'  => 20,
		'post_types' => array( 'post', 'page', 'reading' ),
	) );
}
add_action( 'after_setup_theme', 'opening_times_jetpack_setup' );


/**
 * Getter function - to assign featured posts to a variable in a template file.
 *
 * @since Opening Times 1.0.1
 */
function opening_times_get_featured_posts() {
    return apply_filters( 'opening_times_get_featured_posts', array() );
}


/**
 * Conditional function - to avoid markup being printed and scripts being enqueued when they are not needed.
 *
 * @since Opening Times 1.0.1
 */
function opening_times_has_featured_posts( $minimum = 1 ) {
    if ( is_paged() )
        return false;
 
    $minimum = absint( $minimum );
    $featured_posts = apply_filters( 'opening_times_get_featured_posts', array() );
 
    if ( ! is_array( $featured_posts ) )
        return false;
 
    if ( $minimum > count( $featured_posts ) )
        return false;
    
    return true;
}


/**
 * Filter the list of Post Types available in the WordPress.com REST API.
 *
 * @param array $allowed_post_types Array of whitelisted Post Types.
 * @return array $allowed_post_types Array of whitelisted Post Types, including our Custom Post Types.
 *
 * @since Opening Times 1.0.0
 */
function opening_times_allow_post_type_wpcom( $allowed_post_types ) {
    $allowed_post_types[] = 'reading, news';

    return $allowed_post_types;
}
add_filter( 'rest_api_allowed_post_types', 'opening_times_allow_post_type_wpcom');


/**
 * Get WP Stats info for tracking ajax pages loads
 * 
 * @return string javascript for Jetpack stats.
 *
 * @since  Opening Times 2.0.6
 */
function slug_jetpack_stats_callback() {
	// Abort if Stats module isn't active
	if( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'stats' ) && ! Jetpack::is_module_active( 'infinite-scroll' ) ) {
		$settings = array();

		// Abort if user is logged in but logged-in users shouldn't be tracked.
		if ( is_user_logged_in() && function_exists( 'stats_get_options' ) ) {           
			$stats_options = stats_get_options();

			$track_loggedin_users = isset( $stats_options['reg_users'] ) ? (bool) $stats_options['reg_users'] : false;

			if ( ! $track_loggedin_users ) {
				return $settings;
			}

			// We made it this far, so gather the data needed to track IS views
			$settings['stats'] = 'blog=' . Jetpack_Options::get_option( 'id' ) . '&host=' . parse_url( get_option( 'home' ), PHP_URL_HOST ) . '&v=ext&j=' . JETPACK__API_VERSION . ':' . JETPACK__VERSION;

			// Pagetype parameter
			//$settings['stats'] .= '&x_pagetype=infinite-jetpack';
		}

	?>

	<script type="text/javascript">
		//<![CDATA[
		var ot = <?php echo json_encode( array( 'settings' => $settings ) ); ?>;
		//]]>
	</script>

	<?php
	}
}
add_action( 'wp_footer', 'slug_jetpack_stats_callback', 2 );


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
add_action( 'wp_print_styles', 'opening_times_remove_all_jetpack_css' );


/**
 * Remove Jetpack scripts
 *
 * @since Opening Times 1.0.1
 */
function opening_times_dequeue_jetpack_script() {
   wp_dequeue_script( 'jetpack-lazy-images' );
}
add_action( 'wp_print_scripts', 'opening_times_dequeue_jetpack_script', 100 );

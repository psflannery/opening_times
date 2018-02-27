<?php
/**
 * Custom queries.
 *
 * This file is used to house "getter" function, that fetch data from the database.
 *
 * @package Opening Times
 */

/**
 * Conditionally customise the main query
 *
 * @since Opening Times 1.0.0
 */
function opening_times_custom_queries( $query ) {
    /*
    if( $query->is_main_query() && !is_admin() ) {
		$query->set('posts_per_page', '-1');
    }
    */

    if( $query->is_main_query() && !is_admin() && !$query->is_feed() && is_post_type_archive( 'reading' ) ) {
    	$query->set( 'post_parent', 0 );
    }
}
add_action('pre_get_posts', 'opening_times_custom_queries');

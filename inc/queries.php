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
    if( $query->is_main_query() && !is_admin() ) {
		$query->set('posts_per_page', '-1');
    }

    if( $query->is_main_query() && !is_admin() && !$query->is_feed() && is_post_type_archive( 'reading' ) ) {
    	$query->set( 'post_parent', 0 );
    }
}
add_action('pre_get_posts', 'opening_times_custom_queries');


/**
 * Set up the AJAX query for Load More
 *
 * @link http://www.billerickson.net/infinite-scroll-in-wordpress
 *
 * @since Opening Times 1.0.0
 *
function opening_times_ajax_load_more() {
	$args = isset( $_POST['query'] ) ? array_map( 'esc_attr', $_POST['query'] ) : array();
	$args['post_type'] = isset( $args['post_type'] ) ? esc_attr( $args['post_type'] ) : 'post';
	$args['order'] = isset( $args['order'] ) ? esc_attr( $args['order'] ) : 'DESC';
	$args['orderby'] = isset( $args['orderby'] ) ? esc_attr( $args['orderby'] ) : 'date';
	$args['paged'] = esc_attr( $_POST['page'] );
	$args['post_status'] = 'publish';

	ob_start();

	$loop = new WP_Query( $args );

	if( $loop->have_posts() ):
		while( $loop->have_posts() ): $loop->the_post();

			get_template_part( 'template-parts/content', 'accordion' );

		endwhile; 
	endif; 

	wp_reset_postdata();
	
	$data = ob_get_clean();

	wp_send_json_success( $data );
	wp_die();
}
add_action( 'wp_ajax_opening_times_ajax_load_more', 'opening_times_ajax_load_more' );
add_action( 'wp_ajax_nopriv_opening_times_ajax_load_more', 'opening_times_ajax_load_more' );
*/


/**
 * Enqueue the javascript for Load More
 *
 * @since Opening Times 1.0.0
 *
function opening_times_load_more_js() {
	if ( is_singular() ) 
    	return;

	global $wp_query;

	$args = array(
		'url'   => admin_url( 'admin-ajax.php' ),
		'query' => $wp_query->query,
	);
	wp_localize_script( 'opening-times-main', 'otloadmore', $args );
}
add_action( 'wp_enqueue_scripts', 'opening_times_load_more_js' );
*/

/**
 * Enqueue the javascript for Load More
 *
 * @since Opening Times 1.0.0
 *
function opening_times_load_more_js() {
	if ( is_home() || is_front_page() || is_archive() || is_search() ) {
		global $wp_rewrite;
		
		wp_enqueue_script( 'opening-times-backbone-loop', get_template_directory_uri() . '/js/loop.js', array( 'jquery', 'backbone', 'underscore', 'wp-api'  ), $version, true );
		
		$queried_object = get_queried_object();
		
		$local = array(
			'loopType' => 'home',
			'queriedObject' => $queried_object,
			'pathInfo' => array(
				'author_permastruct' => $wp_rewrite->get_author_permastruct(),
				'host' => preg_replace( '#^http(s)?://#i', '', untrailingslashit( get_option( 'home' ) ) ),
				'path' => opening_times_get_request_path(),
				'use_trailing_slashes' => $wp_rewrite->use_trailing_slashes,
				'parameters' => opening_times_get_request_parameters(),
			),
		);
		
		if ( is_category() || is_tag() || is_tax() ) {
			$local['loopType'] = 'archive';
			$local['taxonomy'] = get_taxonomy( $queried_object->taxonomy );
		} elseif ( is_search() ) {
			$local['loopType'] = 'search';
			$local['searchQuery'] = get_search_query();
		} elseif ( is_author() ) {
			$local['loopType'] = 'author';
		}
		
		//set the page we're on so that Backbone can load the proper state
		if ( is_paged() ) {
			$local['page'] = absint( get_query_var( 'paged' ) ) + 1;
		}
		
		wp_localize_script( 'opening-times-backbone-loop', 'settings', $local );
	}
}
add_action( 'wp_enqueue_scripts', 'opening_times_load_more_js' );
*/

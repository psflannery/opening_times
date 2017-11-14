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

    /*
    if( $query->is_main_query() && !is_admin() && is_home() ) {
		$query->set('posts_per_page', '3');
    }
    */

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
 */
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


/**
 * Enqueue the javascript for Load More
 *
 * @since Opening Times 1.0.0
 */
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

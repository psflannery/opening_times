<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package Opening Times
 */

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
function opening_times_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'render'    => 'opening_times_infinite_scroll_render',
		'footer'    => 'page',
	) );
}
add_action( 'after_setup_theme', 'opening_times_jetpack_setup' );

/**
 * Custom render function for Infinite Scroll.
 */
function opening_times_infinite_scroll_render() {
	while ( have_posts() ) {
		the_post();
		get_template_part( 'template-parts/content', get_post_format() );
	}
}
<?php
/**
 * A Collection of functions to tidy up some of the Wordpress output
 *
 * Keeping as theme files rather than plugin as some of these may be required in some other iteration of the site.
 *
 * @package Opening Times
 */
 
/**
 * Clean up the Wordpress Head
 */
function opening_times_head_cleanup() {
	// EditURI link
	remove_action( 'wp_head', 'rsd_link' );
	// windows live writer
	remove_action( 'wp_head', 'wlwmanifest_link' );
	// index link
	remove_action( 'wp_head', 'index_rel_link' );
	// previous link
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
	// start link
	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
	// links for adjacent posts
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
	// WP version
	remove_action( 'wp_head', 'wp_generator' );
	// Remove category feeds
	remove_action('wp_head', 'feed_links_extra', 3);
	// Remove Post and Comment Feeds
	//remove_action('wp_head', 'feed_links', 2);
}
add_action('init', 'opening_times_head_cleanup');

/**
 * Remove injected CSS for recent comments widget
 */
function opening_times_remove_wp_widget_recent_comments_style() {
   if ( has_filter('wp_head', 'wp_widget_recent_comments_style') ) {
		remove_filter('wp_head', 'wp_widget_recent_comments_style' );
   }
}
add_filter( 'wp_head', 'opening_times_remove_wp_widget_recent_comments_style', 1 );

/**
 * Remove injected CSS from recent comments widget
 */
function opening_times_remove_recent_comments_style() {
	global $wp_widget_factory;
	if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
		remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
	}
}
add_action('wp_head', 'opening_times_remove_recent_comments_style', 1);

/**
 * Remove injected CSS from gallery
 */
function opening_times_gallery_style($css) {
  return preg_replace("!<style type='text/css'>(.*?)</style>!s", '', $css);
}
add_filter('gallery_style', 'opening_times_gallery_style');

/**
 * Remove the p from around imgs
 *
 * @link: http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/
 */
function opening_times_filter_ptags_on_images($content){
	return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}
add_filter('the_content', 'opening_times_filter_ptags_on_images');

/**
 * This removes the annoying […] to a Read More link
 */
function opening_times_excerpt_more($more) {
	global $post;
	return '...  <a class="excerpt-read-more" href="'. get_permalink($post->ID) . '" title="'. __('View ', 'opening_times') . get_the_title($post->ID).'">'. __('view', 'opening_times') .'</a>';
}
add_filter('excerpt_more', 'opening_times_excerpt_more');

/**
 * Remove Query String From Scripts and Stylesheets
 *
 * @since Opening Times 1.3.0
 *
 * @link: http://www.paulund.co.uk/remove-query-string-stylesheets
 */
function opening_times_remove_script_version( $src ){
    return remove_query_arg( 'ver', $src );
}
add_filter( 'script_loader_src', 'opening_times_remove_script_version' );
add_filter( 'style_loader_src', 'opening_times_remove_script_version' );
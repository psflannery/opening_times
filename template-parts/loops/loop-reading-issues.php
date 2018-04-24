<?php
/**
 * Template part for displaying the reading issues loop.
 *
 * @package Opening Times
 */

$args = array(
	'post_type'      => 'reading',
	'posts_per_page' =>	20,
	'post_parent'    => 0,
);

$reading_issues = new WP_Query( $args );

$current_issue = opening_times_the_slug( false );

if ( $reading_issues->have_posts() ) :

	echo '<div class="gradient-container list-group reading__issue-list sticky-top">';

	while ( $reading_issues->have_posts() ) : $reading_issues->the_post();

		if ( opening_times_the_slug( false ) === $current_issue ) {

			//the_title( '<a href="#" class="gradient-text list-group-item active d-block"><span class="d-block">', '</span>' . opening_times_reading_issue_title('<span class="small d-block">', '</span>', false) . '</a>' );
			
			the_title( '<a href="#" class="gradient-text list-group-item active d-block">' . opening_times_reading_issue_title('<span class="d-block">', '</span>', false) . '<span class="small d-block">', '</span></a>' );

		} else {

			//the_title( '<a href="' . esc_url( get_permalink() ) . '" class="gradient-text list-group-item list-group-item-action d-block" rel="bookmark"><span class="d-block">', '</span>' . opening_times_reading_issue_title('<span class="small d-block">', '</span>', false) . '</a>' );
			
			the_title( '<a href="' . esc_url( get_permalink() ) . '" class="gradient-text list-group-item list-group-item-action d-block" rel="bookmark">' . opening_times_reading_issue_title('<span class="d-block">', '</span>', false) . '<span class="small d-block">', '</span></a>' );
		}

	endwhile;
	wp_reset_postdata();

	echo '</div>';

endif;

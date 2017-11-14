<?php
/**
 * Template part for displaying the latest news loop.
 *
 * @package Opening Times
 */

if ( false === ( $latest_news = get_transient( 'opening_times_news_loop' ) ) ) :

	$args = array(
		'post_type'      => 'news',
		//'posts_per_page' =>	3,
	);

	$latest_news = new WP_Query( $args );

	set_transient( 'jag_shoes_venue_loop', $latest_news, 24 * HOUR_IN_SECONDS );

endif;

if ( $latest_news->have_posts() ) :

	echo '<div class="carousel">';

	while ( $latest_news->have_posts() ) : $latest_news->the_post();

		echo '<div class="carousel-cell">';

		get_template_part( 'template-parts/content', get_post_format() );

		echo '</div>';

	endwhile;
	wp_reset_postdata();

	echo '</div>';

	get_template_part( 'template-parts/content-blocks/block-prev-next-nav' );

else :

	get_template_part( 'template-parts/content', 'none' );

endif;

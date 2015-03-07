<?php
/**
 * The template for displaying the Journal Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Opening Times
 */

get_header(); ?>
	
	<main id="main" class="site-main accordion-issue container-fluid" role="main">
	
		<?php if ( have_posts() ) : ?>
		
			<?php while ( have_posts() ) : the_post(); ?>
			
				<?php get_template_part( 'template-parts/single', 'issue' ); ?>
					
			<?php endwhile; ?>

			<?php the_posts_navigation(); ?>

		<?php else : ?>

			<?php get_template_part( 'template-parts/content', 'none' ); ?>

		<?php endif; ?>
	
	</main>

<?php get_footer(); ?>
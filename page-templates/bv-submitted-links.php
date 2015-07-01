<?php
/*
 * Template Name: Submitted Links - Ben Vickers
 * Description: The template to display the links submitted by users in response to Ben Vickers' reading section.
 *
 * @package Opening Times
 */
 
get_header(); ?>

    <main id="main" class="site-main accordion gradienter container-fluid" role="main">
	
		<?php
			$args = array(
				'author_name' => 'anonymous',
				'post_type' => array( 'article' ),
				'posts_per_page' => -1,
				'order' => 'DESC',
				'orderby' => 'date',
			);

			$user_submit = new WP_Query( $args );
		?>

        <?php if (  $user_submit->have_posts() ) : ?>

            <?php while ( $user_submit->have_posts() ) : $user_submit->the_post(); ?>

				<?php get_template_part( 'template-parts/content', get_post_format() ); ?>

            <?php endwhile; ?>

            <?php the_posts_navigation(); ?>

        <?php else : ?>

            <?php get_template_part( 'template-parts/content', 'none' ); ?>

        <?php endif; ?>
		
		<?php wp_reset_postdata(); ?>

    </main>

<?php get_footer(); ?>
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

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header id="<?php opening_times_the_slug(); ?>" class="entry-header gradientee strap-header">
						<h2 class="header-details archive-header-details col-sm-3"><?php opening_times_taxonomy_no_link() ?></h2>
						<h1 class="header-details archive-header-details col-sm-4"><?php the_title(); ?></h1>
						<h3 class="header-details archive-header-details col-sm-2"><?php opening_times_category_no_link() ?></h3>
						<h3 class="header-details archive-header-details col-sm-1 header-details-last"><?php echo get_the_time('Y', $post->ID); ?></h3>
					</header>
					<div class="accordion-content clearfix">
						<div class="entry-content-wrap fitvids">
							<?php echo opening_times_featured_content(); ?>
							<div class="entry-content col-sm-7 col-lg-4">
								<?php
									global $post;
									$link_url = get_post_meta( $post->ID, '_ot_bv_link_submit_link', true );
								?>
							
								<a href="http://<?php the_title_attribute(); ?>" taget="_blank" class="featured-link"><?php the_title(); ?></a>
							
								<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'opening_times' ) ); ?>
								
								<?php
									wp_link_pages( array(
										'before' => '<div class="page-links">' . __( 'Pages:', 'opening_times' ),
										'after'  => '</div>',
									) );
								?>
							</div>
						</div>
						<footer class="entry-meta col-sm-7 col-lg-3 pseudo-content-divider-md-max collection-meta">

							<?php echo opening_times_takeover_meta(); ?>

							<?php echo opening_times_collection_meta(); ?>
							
							<?php opening_times_artist_bio(); ?>

							<?php $slug = home_url('/#'. opening_times_the_slug($echo=false)); ?>

							<ul class="ot-social-links ot-meta">
								<li><a href="<?php echo esc_url( $slug ); ?>" rel="bookmark" class="ot-permalink"><?php esc_html_e( 'Share Link', 'opening_times' ); ?></a></li>
								<li class="popout-link"><a href="http://www.facebook.com/sharer.php?u=<?php echo urlencode( $slug ); ?>&t=<?php echo urlencode( the_title() ); ?>" title="Share on Facebook" rel="nofollow" target="_blank" ><?php esc_html_e( 'Share on Facebook', 'opening_times' ); ?></a></li>
								<li class="popout-link"><a href="http://twitter.com/share?text=<?php echo urlencode( the_title() ); ?>&url=<?php echo urlencode( $slug ); ?>&via=otdac&related=<?php echo urlencode("Opening Times: digital art commissions"); ?>" title="Share on Twitter" rel="nofollow" target="_blank"><?php esc_html_e( 'Share on Twitter', 'opening_times' ); ?></a></li>
							</ul>

							<?php edit_post_link( __( 'Edit', 'opening_times' ), '<span class="edit-link">', '</span>'); ?>

						</footer>
					</div>
				</article>

            <?php endwhile; ?>

            <?php the_posts_navigation(); ?>

        <?php else : ?>

            <?php get_template_part( 'template-parts/content', 'none' ); ?>

        <?php endif; ?>
		
		<?php wp_reset_postdata(); ?>

    </main>

<?php get_footer(); ?>
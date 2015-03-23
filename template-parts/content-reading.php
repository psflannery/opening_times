<?php
/**
 * @package Opening Times
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header id="<?php opening_times_the_slug(); ?>" class="entry-header gradientee strap-header row-reading-header">
	<?php if ( !is_search() ) : ?>
	
		<h2 class="header-details col-sm-3 reading-header-details-first"><?php opening_times_taxonomy_no_link(); ?></h2>
		<h1 class="header-details col-sm-4 col-md-5"><?php the_title(); ?></h1>
		<h3 class="header-details col-sm-3 col-md-2 reading-header-details-last"><?php opening_times_category_no_link() ?></h3>
		
	<?php else : ?>
		<h1 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark" class="gradientee"><?php the_title(); ?></a></h1>
		
	<?php endif; ?>
	</header>

<?php if ( !is_search() ) : ?>
	
	<div class="accordion-content">
		<div class="entry-content-wrap fitvids">
		
			<?php get_template_part('template-parts/featured', 'content'); ?>
			
			<div class="entry-content">
			
				<?php get_template_part('template-parts/loop', 'collection_links'); ?>
				
				<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'opening_times' ) ); ?>
				<?php
					wp_link_pages( array(
						'before' => '<div class="page-links">' . __( 'Pages:', 'opening_times' ),
						'after'  => '</div>',
					) );
				?>
			</div>
		</div>

		<footer class="entry-meta content-divider">
		
			<?php opening_times_collection_meta(); ?>
						
			<?php $slug = home_url('/reading/#'. opening_times_the_slug($echo=false)); ?>

			<ul class="ot-social-links ot-meta">
				<li><a href="<?php echo esc_url( $slug ); ?>" rel="bookmark" class="ot-permalink"><?php _e( 'Share Link', 'opening_times' ); ?></a></li>
				<li class="popout-link"><a href="http://www.facebook.com/sharer.php?u=<?php echo urlencode( $slug ); ?>&t=<?php echo urlencode( the_title() ); ?>" title="Share on Facebook" rel="nofollow" target="_blank" >Share on Facebook</a></li>
				<li class="popout-link"><a href="http://twitter.com/share?text=<?php echo urlencode( the_title() ); ?>&url=<?php echo urlencode( $slug ); ?>&via=otdac&related=<?php echo urlencode("Opening Times: digital art commissions"); ?>" title="Share on Twitter" rel="nofollow" target="_blank">Share on Twitter</a></li>
			</ul>
					
			<?php edit_post_link( __( 'Edit', 'opening_times' ), '<span class="edit-link">', '</span>'); ?>
			
		</footer>
	</div>
	
<?php else : ?>
	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div>
<?php endif; // end if !is_search() ?>

</article>
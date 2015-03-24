<?php
/**
 * @package Opening Times
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header id="<?php opening_times_the_slug(); ?>" class="entry-header gradientee strap-header">		
        <h2 class="header-details archive-header-details col-sm-5"><?php echo get_post_meta( $post->ID, '_ot_institution_name', true ); ?></h2>
        <h1 class="header-details archive-header-details col-sm-3"><?php the_title(); ?></h1>
        <h3 class="header-details archive-header-details col-sm-2 header-details-last"><?php $postyear = get_the_time('Y', $post->ID); ?><?php echo $postyear; ?></h3>
    </header>

    <div class="accordion-content clearfix">

        <?php get_template_part('template-parts/featured', 'content'); ?>

        <div class="entry-content-wrap fitvids col-sm-5">
            <div class="entry-content">		

                <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'opening_times' ) ); ?>
                <?php
                    wp_link_pages( array(
                        'before' => '<div class="page-links">' . __( 'Pages:', 'opening_times' ),
                        'after'  => '</div>',
                    ) );
                ?>
            </div>

            <?php opening_times_takeover_meta(); ?>

            <footer class="entry-meta content-divider">

                <?php opening_times_collection_meta(); ?>

                <?php get_template_part('template-parts/bio', 'artist'); ?>

                <?php $slug = home_url('take-overs/#'. opening_times_the_slug($echo=false)); ?>

                <ul class="ot-social-links ot-meta">
                    <li><a href="<?php echo esc_url( $slug ); ?>" rel="bookmark" class="ot-permalink"><?php _e( 'Share Link', 'opening_times' ); ?></a></li>
                    <li class="popout-link"><a href="http://www.facebook.com/sharer.php?u=<?php echo urlencode( $slug ); ?>&t=<?php echo urlencode( the_title() ); ?>" title="Share on Facebook" rel="nofollow" target="_blank" >Share on Facebook</a></li>
                    <li class="popout-link"><a href="http://twitter.com/share?text=<?php echo urlencode( the_title() ); ?>&url=<?php echo urlencode( $slug ); ?>&via=otdac&related=<?php echo urlencode("Opening Times: digital art commissions"); ?>" title="Share on Twitter" rel="nofollow" target="_blank">Share on Twitter</a></li>
                </ul>

                <?php edit_post_link( __( 'Edit', 'opening_times' ), '<span class="edit-link">', '</span>'); ?>

            </footer>
        </div>
    </div>
</article>
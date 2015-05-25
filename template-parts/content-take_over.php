<?php
/**
 * @package Opening Times
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header id="<?php opening_times_the_slug(); ?>" class="entry-header gradientee strap-header">		
        <h2 class="header-details archive-header-details col-sm-5"><?php echo get_post_meta( $post->ID, '_ot_institution_name', true ); ?></h2>
        <h1 class="header-details archive-header-details col-sm-3"><?php the_title(); ?></h1>
        <h3 class="header-details archive-header-details col-sm-2 header-details-last"><?php echo get_the_time('Y', $post->ID); ?></h3>
    </header>

    <div class="accordion-content clearfix">

        <?php echo opening_times_featured_content(); ?>

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

            <?php echo opening_times_takeover_meta(); ?>

            <footer class="entry-meta content-divider">

                <?php echo opening_times_collection_meta(); ?>

                <?php opening_times_artist_bio(); ?>

                <?php $slug = home_url('take-overs/#'. opening_times_the_slug($echo=false)); ?>

                <ul class="ot-social-links ot-meta">
                    <li><a href="<?php echo esc_url( $slug ); ?>" rel="bookmark" class="ot-permalink"><?php esc_html_e( 'Share Link', 'opening_times' ); ?></a></li>
                    <li class="popout-link"><a href="http://www.facebook.com/sharer.php?u=<?php echo urlencode( $slug ); ?>&t=<?php echo urlencode( the_title() ); ?>" title="Share on Facebook" rel="nofollow" target="_blank" ><?php esc_html_e( 'Share on Facebook', 'opening_times' ); ?></a></li>
                    <li class="popout-link"><a href="http://twitter.com/share?text=<?php echo urlencode( the_title() ); ?>&url=<?php echo urlencode( $slug ); ?>&via=otdac&related=<?php echo urlencode("Opening Times: digital art commissions"); ?>" title="Share on Twitter" rel="nofollow" target="_blank"><?php esc_html_e( 'Share on Twitter', 'opening_times' ); ?></a></li>
                </ul>

                <?php edit_post_link( __( 'Edit', 'opening_times' ), '<span class="edit-link">', '</span>'); ?>

            </footer>
        </div>
    </div>
</article>
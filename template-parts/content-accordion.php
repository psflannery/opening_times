<?php
/**
 * @package Opening Times
 *
 * Displays the content for the Collection page along with the category and tag archives.
 * 
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Opening Times
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="accordion-header collapsed container-fluid gradient-text" role="tab" id="heading-<?php opening_times_the_slug(); ?>" data-toggle="collapse" data-parent="#accordion" data-target="#<?php opening_times_the_slug(); ?>" aria-expanded="false" aria-controls="<?php opening_times_the_slug(); ?>">
        <h2 class="mb-0 row">

            <?php
                opening_times_partner_name( '<span class="col-md-4">', '</span>' );
                the_title( '<span class="col-md-4 text-truncate">', '</span>' );
                opening_times_tax_no_link( 'category', '<span class="col-md-3 text-truncate hidden-md-down">', '</span>' );
                echo sprintf( '<span class="col text-truncate hidden-sm-down">%s</span>', get_the_time('Y', $post->ID) );
            ?>

        </h2>
    </header>
    <div id="<?php opening_times_the_slug(); ?>" class="collapse container-fluid" role="tabpanel" aria-labelledby="heading-<?php opening_times_the_slug(); ?>">
        <div class="accordion-content row">
            <div class="col-md-4">

                <?php opening_times_featured_content(); ?>

            </div>
            <div class="entry-content col-md-8 col-lg-4">

                <?php                 
                    opening_times_featured_links();

                    the_content( sprintf(
                        /* translators: %s: Name of current post. */
                        wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'opening_times' ), array( 'span' => array( 'class' => array() ) ) ),
                        the_title( '<span class="screen-reader-text">"', '"</span>', false )
                    ) );
                
                    wp_link_pages( array(
                        'before' => '<div class="page-links">' . __( 'Pages:', 'opening_times' ),
                        'after'  => '</div>',
                    ) );
                ?>

            </div>
            <div class="entry-meta col-md-8 col-lg-4 offset-md-4 offset-lg-0">

                <?php 
                    opening_times_collection_meta();
                    opening_times_tax_description( 'artists' );
                    echo opening_times_get_social_share(); 
                    edit_post_link( __( 'Edit', 'opening_times' ), '<span class="edit-link">', '</span>'); 
                ?>

            </div>
        </div>
    </div>
</article>

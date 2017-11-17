<?php
/**
 * @package Opening Times
 */
?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="accordion-header collapsed container-fluid gradient-text" role="tab" id="heading-<?php opening_times_the_slug(); ?>" data-toggle="collapse" data-parent="#accordion-<?php opening_times_the_parent_slug(); ?>" data-target="#<?php opening_times_the_slug(); ?>" aria-expanded="false" aria-controls="<?php opening_times_the_slug(); ?>">
        <h2 class="mb-0 row">

            <?php
                opening_times_partner_name( '<span class="col-sm-4 text-truncate">', '</span>' );
                the_title( '<span class="col-md-6 text-truncate-md">', '</span>' );
                opening_times_tax_no_link( 'category', '<span class="col-md-2 hidden-md-down text-truncate">', '</span>' );
            ?>

        </h2>
    </header>
    <div id="<?php opening_times_the_slug(); ?>" class="collapse container-fluid" role="tabpanel" aria-labelledby="heading-<?php opening_times_the_slug(); ?>">
        <div class="card-block row">
            <div class="col-12">

                <?php opening_times_featured_content(); ?>

                <div class="entry-content">

                    <?php 
                        opening_times_featured_links();

                        the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'opening_times' ) );
                    
                        wp_link_pages( array(
                            'before' => '<div class="page-links">' . __( 'Pages:', 'opening_times' ),
                            'after'  => '</div>',
                        ) );
                    ?>
                </div>
            </div>
        </div>
        <footer class="card-block entry-meta content-divider">

            <?php 
                opening_times_collection_meta();
                echo opening_times_get_social_share();
                edit_post_link( __( 'Edit', 'opening_times' ), '<span class="edit-link">', '</span>'); 
            ?>

        </footer>
    </div>
</div>

<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Opening Times
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header gradientee strap-header">
        <h1 class="header-details"><?php the_title(); ?></h1>
    </header>

    <div class="entry-content">
        <?php 
            the_content();

            wp_link_pages( array(
                'before' => '<div class="page-links">' . __( 'Pages:', 'opening_times' ),
                'after'  => '</div>',
            ) );
        ?>
    </div>

    <?php edit_post_link( __( 'Edit', 'opening_times' ), '<footer class="entry-meta"><span class="edit-link">', '</span></footer>' ); ?>

</article>

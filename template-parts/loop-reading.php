<?php
/**
 * The template used for displaying the Articles on the Reading Page.
 *
 * Creates a loop that lists all the articles in each Issue.
 * Used in the Reading Archive and the Single Reading Issue Page.
 *
 * @package Opening Times
 */
?>

<?php
    $attached_articles = get_post_meta( get_the_ID(), '_ot_attached_articles', true );

    if ( '' != $attached_articles ) :

        foreach ( $attached_articles as $attached_article ) :

            $post = get_post( $attached_article );
            setup_postdata($post);
            get_template_part( 'template-parts/content', 'reading' );

        endforeach;

    else :

        get_template_part( 'template-parts/content', 'none' );

    endif;

wp_reset_postdata();

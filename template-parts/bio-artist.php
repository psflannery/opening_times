<?php
/**
 * The template used for the the Artist bio.
 *
 * @package Opening Times
 */

global $post;
$artist_description = get_the_terms( $post->ID, 'artists');
if ( !is_post_type_archive( 'reading' ) && !is_singular( 'reading' ) ) : // display the artist bio if it exists, and don't display them on the reading pages.

    if ( '' != $artist_description ) :
        foreach ( $artist_description as $artist ) {
            if (  $artist->description ) {
                echo '<aside class="artist-bio ot-meta ot-bio" role="complementary">' . wpautop( wptexturize( $artist->description ) ) . '</aside>';
            }
        };
    endif;
		
endif; 
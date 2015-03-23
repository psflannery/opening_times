<?php
/**
 * Output the Collection Links
 *
 * @package: Opening Times
 */

$file_url = get_post_meta( $post->ID, '_ot_file', true );
$link_url = get_post_meta( $post->ID, "_ot_link_url", true );

if ( '' != $link_url ) :
	foreach ( $link_url as $link ) :
		if ( 'take-overs' != get_post_type() ) :
			echo '<a href="' . esc_url( $link ) . '" target="_blank" class="featured-link">' . esc_url( $link ) . '</a>';
		else :
			echo '<a href="' . esc_url( $link ) . '" target="_blank">' . esc_url( $link ) . '</a>';
		endif;
	endforeach ;
endif;

if ( '' != $file_url ) :
	echo '<a href="' . esc_url( $file_url ) . '" target="_blank" class="featured-link">' . esc_url( $file_url ) . '</a>';
endif;
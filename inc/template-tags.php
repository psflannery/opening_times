<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Opening Times
 */

if ( ! function_exists( 'opening_times_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function opening_times_posted_on() {
	$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string .= '<time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	printf( __( '<span class="posted-on">Posted on %1$s</span><span class="byline"> by %2$s</span>', 'opening_times' ),
		sprintf( '<a href="%1$s" rel="bookmark">%2$s</a>',
			esc_url( get_permalink() ),
			$time_string
		),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s">%2$s</a></span>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_html( get_the_author() )
		)
	);
}
endif;


/**
 * Returns true if a blog has more than 1 category.
 */
function opening_times_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'all_the_cool_cats', $all_the_cool_cats );
	}

	if ( '1' != $all_the_cool_cats ) {
		// This blog has more than 1 category so opening_times_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so opening_times_categorized_blog should return false.
		return false;
	}
}


/**
 * Flush out the transients used in opening_times_categorized_blog.
 */
function opening_times_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'opening_times_category_transient_flusher' );
add_action( 'save_post',     'opening_times_category_transient_flusher' );


/**
 * Display the tag list at the bottom of each post
 *
 */
function opening_times_tag_list() {
	/* translators: used between list items, there is a space after the comma */
	$category_list = get_the_category_list( __( ', ', 'opening_times' ) );

	/* translators: used between list items, there is a space after the comma */
	$tag_list = get_the_tag_list( '', __( ', ', 'opening_times' ) );

	if ( ! opening_times_categorized_blog() ) {
		// This blog only has 1 category so we just need to worry about tags in the meta text
		if ( '' != $tag_list ) {
			$meta_text = __( '<dl class="entry-meta-links ot-collection-meta dl-inline"><dt>Tags</dt><dd> %2$s </dd></dl>', 'opening_times' );
		}

	} else {
		// But this blog has loads of categories so we should probably display them here
		if ( '' != $tag_list ) {
			$meta_text = __( '<dl class="entry-meta-links ot-collection-meta dl-inline"><dt>Categories</dt><dd> %1$s </dd><dt>Tags</dt><dd> %2$s</dd></dl>', 'opening_times' );
		} else {
			$meta_text = __( '<dl class="entry-meta-links ot-collection-meta dl-inline"><dt>Categories</dt><dd> %1$s </dd></dl>', 'opening_times' );
		}

	} // end check for categories on this blog

	printf(
		$meta_text,
		$category_list,
		$tag_list,
		get_permalink()
	);
}


/**
 * Display the category name without it being wrapped in a link
 */
function opening_times_category_no_link() {
	$categories = get_the_category_list( __( ', ', 'opening_times' ) );
	echo strip_tags( $categories );
}


/**
 * Display the taxonomy name without it being wrapped in a link
 */
function opening_times_taxonomy_no_link() {
	global $post;
	$terms_as_text = get_the_term_list( $post->ID, 'artists', '', ', ', '' );
	$terms_as_text .= get_the_term_list( $post->ID, 'authors', '', ', ', '' );
	echo strip_tags( $terms_as_text );
}

/**
 * Outputs the residency and take-over dates in a pretty format.
 */
function opening_times_event_dates() {
	global $post;

	$meta_resindency_sd = get_post_meta( $post->ID, '_ot_residency_start_date', true );
	$meta_resindency_ed = get_post_meta( $post->ID, '_ot_residency_end_date', true );

	$meta_takeover_sd = get_post_meta( $post->ID, '_ot_take_over_start_date', true );
	$meta_takeover_ed = get_post_meta( $post->ID, '_ot_take_over_end_date', true );

	if( '' != $meta_resindency_sd ):
		//convert to pretty formats
		$clean_resindency_sd = date( "F Y", $meta_resindency_sd );
		$clean_resindency_ed = date( "F Y", $meta_resindency_ed );

		//output the date
		$residencydate = '';
		$residencydate .= ' ' . $clean_resindency_sd;
		$residencydate .= ' - ' . $clean_resindency_ed;
		return $residencydate;
	endif;

	if( '' != $meta_takeover_sd ):
		//convert to pretty formats
		$clean_takeover_sd = date( "d F Y", $meta_takeover_sd );
		$clean_takeover_ed = date( "d F Y", $meta_takeover_ed );

		//output the date
		$takeoverdate = '';
		$takeoverdate .= '' . $clean_takeover_sd;
		$takeoverdate .= ' - ' . $clean_takeover_ed;
		return $takeoverdate;
	endif;
}


/**
 * Prints HTML with meta information for the collection pages.
 */
function opening_times_collection_meta() {

	// Set up the function variables
	global $post;
	$file_url = get_post_meta( $post->ID, '_ot_file', true );
	$link_url = get_post_meta( $post->ID, "_ot_link_url", true );
	$postyear = get_the_time('Y', $post->ID);
	$user_description = get_the_author_meta('description');
	$residency_start_date = get_post_meta( $post->ID, '_ot_residency_start_date', true );

	$meta = '<dl class="ot-collection-meta ot-meta dl-inline">';
	if ( get_the_terms( $post->ID, 'artists') ):
		$meta .= '<dt>' . esc_html( 'Artist', 'opening_times' ) . '</dt>';
		$meta .= '<dd>' . get_the_term_list( $post->ID, 'artists', ' ', ', ', '' ) . '</dd>';
	endif;
	if ( in_category( 'editorial-introduction' ) ):
		$meta .= '<dt>' . esc_html( 'Author', 'opening_times' ) . '</dt>';
		$meta .= '<dd>' . the_author_posts_link() . '</dd>';
	endif;
	if( get_the_category_list() ) :
		$meta .= '<dt>' . esc_html( 'Category', 'opening_times' ) . '</dt>';
		$meta .= '<dd>' . ' ' . get_the_category_list( ', ' ) . '</dd>';
	endif;
	if( get_the_tag_list() ) :
		$meta .= '<dt>' . esc_html( 'Tags', 'opening_times' ) . '</dt>';
		$meta .= '<dd>' . get_the_tag_list( ' ', ', ', '' ) . '</dd>';
	endif;		
	if ( !is_post_type_archive( array ( 'reading', 'take-overs' ) ) ) :
		$meta .= '<dt>' . esc_html( 'Year', 'opening_times' ) . '</dt>';
		$meta .= '<dd> <a rel="ajax" href="' . get_year_link( $postyear ) . '">' . $postyear . '</a></dd>';
	endif;
	$meta .= '</dl>';

	if ( in_category( 'residency' ) && '' != $residency_start_date ):
		$meta .= '<dl class="ot-collection-meta ot-meta dl-inline">';
		$meta .= '<dt>' . esc_html( 'Dates', 'opening_times' ) . '</dt>';
		$meta .= '<dd>' . opening_times_event_dates() . '</dd>';
		$meta .= '</dl>';
	endif;

return $meta;
}


/**
 * Prints HTML with meta information for the Take-overs.
 */
function opening_times_takeover_meta() {
	if ( 'take-overs' == get_post_type() ) : 
		$takeover = '<dl class="ot-event-meta ot-meta dl-inline">';
		$takeover .= '<dt>' . esc_html( 'Website', 'opening_times' ) . '</dt>';
		$takeover .= '<dd>' . opening_times_collection_links() . '</dd>';
		$takeover .= '<dt>' . esc_html( 'Dates', 'opening_times' ) . '</dt>';
		$takeover .= '<dd>' . ' ' . opening_times_event_dates() . '</dd>';
		$takeover .= '</dl>'; 

		return $takeover;
	endif;
}


/**
 * The template tag for the the Artist bio.
 */
function opening_times_artist_bio() {
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
}


/**
 * The template used for the User Description AKA the Editor Bio
 */
function opening_times_editor_bio() {
	global $post;
	$user_description = get_the_author_meta('description');
	$user_url = get_the_author_meta('user_url');

	if ( '' != $user_description ) :
		$editor = '<aside class="editor-bio">';
	if ( 'article' == get_post_type() ) :
		$editor .= '<p>' . esc_html( 'Selected by: ', 'opening_times' ) . '<span>' . the_author_posts_link() . '</span></p>';
	endif;

	$editor .= wpautop( wptexturize( $user_description ) );
	if ( '' != $user_url ) :
		$editor .= '<p><a href="' . esc_url( $user_url ) . '" target="_blank">' . esc_html( $user_url ) . '</a></p>';
	endif;

	$editor .= '</aside>';

	return $editor;
	endif;
}


/**
 * Return, not echo, the future content thumbnail `get_template_part`
 *
 * @link https://kovshenin.com/2013/get_template_part-within-shortcodes/
 */
function ot_return_future_content_thumbnail() {
	ob_start();
	get_template_part( 'img/inline', 'future-content-thumbnail.svg' );
	return ob_get_clean();
}


/**
 * Output the featured content
 */
function opening_times_featured_content() {
	global $post;
	$oembed = get_post_meta( $post->ID, '_ot_embed_url', true );
	$link_url = get_post_meta( $post->ID, '_ot_link_url', true );
	$file_url = get_post_meta( $post->ID, '_ot_file', true );

	if ( '' != get_the_post_thumbnail() ) :
		if ( '' != $link_url  ) :
			$featured = '<figure class="featured-image ' . opening_times_thumbnail_float() . '"><a href="' . reset( $link_url ) . '" target="_blank">' . get_the_post_thumbnail( $post->ID, 'accordion-thumb') . '</a></figure>';
		else :
			$featured = '<figure class="featured-image ' . opening_times_thumbnail_float() . '">' . get_the_post_thumbnail( $post->ID, 'accordion-thumb') .'</figure>';
		endif;

		return $featured;

	elseif ( '' != $oembed ) :
		// If there is no thumbnail, but there is an embed, and we're not in the reading section or take-overs section. This will format the posts the appear in the archives.
		if ( !is_post_type_archive( array ( 'reading', 'take-overs' ) ) && !is_singular( array ( 'reading', 'take-overs', 'article' ) ) ) :
			$featured = '<figure class="col-sm-3">' . apply_filters( 'the_content', $oembed ) . '</figure>';

		// If there is no thumbnail, but there is an embed, and we ARE in the TAKE-OVERS section
		elseif ( '' != $oembed && ( is_post_type_archive( 'take-overs' ) || is_singular( 'take-overs' ) ) ) :
			$featured = '<figure class="col-sm-5 fitvids">' . apply_filters( 'the_content', $oembed ) . '</figure>';

		// If there is no thumbnail, but there is an embed, and we ARE in the READING section
		elseif ( '' != $oembed ) :
			$featured = '<figure>' . apply_filters( 'the_content', $oembed ) . '</figure>';

		endif;

		return $featured;

	elseif ( !is_post_type_archive( 'reading' ) && !is_singular( array ( 'reading', 'article' ) ) ) :
		// None of the above, everything is empty
		$featured = '<figure class="featured-image col-sm-3">' . ot_return_future_content_thumbnail() . '</figure>';

		return $featured;

	endif;
}


/**
 * Output the Collection Links
 */
function opening_times_collection_links() {
	global $post;
	$file_url = get_post_meta( $post->ID, '_ot_file', true );
	$link_url = get_post_meta( $post->ID, "_ot_link_url", true );
	$links = '';

	if ( '' != $link_url ) :
		foreach ( $link_url as $link ) :
			if ( 'take-overs' != get_post_type() ) :
				$links .= '<a href="' . esc_url( $link ) . '" target="_blank" class="featured-link">' .  esc_html( $link ) . '</a>';
			else :
				$links = '<a href="' . esc_url( $link ) . '" target="_blank">' . esc_html( $link ) . '</a>';
			endif;
		endforeach;
		return $links;
	endif;

	if ( '' != $file_url ) :
		$file = '<a href="' . esc_url( $file_url ) . '" target="_blank" class="featured-link">' .  esc_html( $file_url ) . '</a>';
		return $file;
	endif;
}


/**
 * Output the the name of the person who submitted a link
 */
function opening_times_link_submitter() {
	global $post;
	$submitted_by = get_post_meta( $post->ID, '_ot_bv_link_submit_name', true );

	if ( '' != $submitted_by ) :
		echo $submitted_by;
	else :
		esc_html_e( 'Anonymous', 'opening_times' );
	endif;
}

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
 * Outputs the residency and project dates in a pretty format.
 */
function opening_times_event_dates() {
	global $post;

	$meta_resindency_sd = get_post_meta( $post->ID, '_ot_residency_start_date', true );
	$meta_resindency_ed = get_post_meta( $post->ID, '_ot_residency_end_date', true );

	$meta_project_sd = get_post_meta( $post->ID, '_ot_project_start_date', true );
	$meta_project_ed = get_post_meta( $post->ID, '_ot_project_end_date', true );

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

	if( '' != $meta_project_sd ):
		//convert to pretty formats
		$clean_project_sd = date( "d F Y", $meta_project_sd );
		$clean_project_ed = date( "d F Y", $meta_project_ed );

		//output the date
		$project = '';
		$project .= '' . $clean_project_sd;
		$project .= ' - ' . $clean_project_ed;
		return $project;
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
	if ( !is_post_type_archive( array ( 'reading', 'projects' ) ) ) :
		$meta .= '<dt>' . esc_html( 'Year', 'opening_times' ) . '</dt>';
		$meta .= '<dd><a rel="ajax" href="' . get_year_link( $postyear ) . '">' . $postyear . '</a></dd>';
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
 * Prints HTML with meta information for the Projects.
 */
function opening_times_project_meta() {
	if ( 'projects' == get_post_type() ) : 
		$project = '<dl class="ot-event-meta ot-meta dl-inline">';
		$project .= '<dt>' . esc_html( 'Website', 'opening_times' ) . '</dt>';
		$project .= '<dd>' . opening_times_collection_links() . '</dd>';
		$project .= '<dt>' . esc_html( 'Dates', 'opening_times' ) . '</dt>';
		$project .= '<dd>' . ' ' . opening_times_event_dates() . '</dd>';
		$project .= '</dl>'; 

		return $project;
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
			$editor .= '<p>' . esc_html( 'Selected by: ', 'opening_times' ) . '<span><a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '">' . get_the_author() . '</a></span></p>';
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
 *
 * @return string Featured media HTML.
 */
function opening_times_featured_content() {
	global $post;
	$oembed = get_post_meta( $post->ID, '_ot_embed_url', true );
	$link_url = get_post_meta( $post->ID, '_ot_link_url', true );
	$iframe_src = get_post_meta( $post->ID, '_ot_iframe_url', true );
	$iframe_height = get_post_meta( $post->ID, '_ot_iframe_height', true );
    
    //if ( '' != get_the_post_thumbnail() || '' != $oembed || '' != $iframe_src ) :
    if ( '' != ( get_the_post_thumbnail() || $oembed || $iframe_src ) ) :
    // we have something in at least 1 of the media containers
        if ( '' != get_the_post_thumbnail() ) :
        // we have a thumbnail
            if ( '' != $link_url ) :
            // ...and we have a link
                $featured = '<figure class="featured-image ' . opening_times_thumbnail_float() . '"><a href="' . reset( $link_url ) . '" target="_blank">' . opening_times_post_thumbnail() . '</a></figure>';
            else :
            // ...otherwise
                $featured = '<figure class="featured-image ' . opening_times_thumbnail_float() . '">' . opening_times_post_thumbnail() .'</figure>';
            endif;
            return $featured;
        elseif ( '' != $iframe_src ) :
        // we have an iframe
        	if ( ! is_single() ) :
            	$featured = '<div class="featured-image col-sm-5"><iframe src="about:blank" data-src="' . $iframe_src . '" width="100%" height="' . $iframe_height . '" frameborder="0"></iframe></div>';
            else :
            	$featured = '<div class="featured-image col-sm-5"><iframe src="' . $iframe_src . '" width="100%" height="' . $iframe_height . '" frameborder="0"></iframe></div>';
            endif;
            return $featured;
        elseif ( '' != $oembed ) :
        // we have an oembed
            if ( !is_post_type_archive( array ( 'reading', 'projects' ) ) && !is_singular( array ( 'reading', 'projects', 'article' ) ) ) :
            // we are in an "accordion" archive - ie. everything BUT reading or take-overs
                $featured = '<figure class="col-sm-3">' . apply_filters( 'the_content', $oembed ) . '</figure>';
            elseif ( '' != $oembed && ( is_post_type_archive( 'projects' ) || is_singular( 'projects' ) ) ) :
            // we are in the "take-over" archive, or a single "take-over" page
                $featured = '<figure class="col-sm-5 fitvids">' . apply_filters( 'the_content', $oembed ) . '</figure>';
            else :
            // we are in the reading section
                $featured = '<figure>' . apply_filters( 'the_content', $oembed ) . '</figure>';
            endif;
            return $featured;
        endif;
    else :
    // all 3 media containers are empty
		if ( !is_post_type_archive( array ( 'reading' ) ) && !is_singular( array ( 'reading', 'article' ) ) ) :
        // and we are not in the reading section
        	$featured = '<figure class="featured-image col-sm-3">' . ot_return_future_content_thumbnail() . '</figure>';
        	return $featured;
        endif;
    endif;
}

/**
 * Displays dependent on view.
 *
 */
function opening_times_post_thumbnail() {
	global $post;
	if ( ! is_post_type_archive( 'projects' ) ) :

		return get_the_post_thumbnail( $post->ID, 'accordion-thumb', array( 'alt' => the_title_attribute( 'echo=0' ) ) );

	else :

		return get_the_post_thumbnail( $post->ID, 'large', array( 'alt' => the_title_attribute( 'echo=0' ) ) );

	endif;
}

/**
 * Output the Collection Links
 */
function opening_times_collection_links() {
	global $post;
	$file_url = get_post_meta( $post->ID, '_ot_file', true );
	$link_url = get_post_meta( $post->ID, "_ot_link_url", true );
	$submit_url = get_post_meta( $post->ID, '_ot_bv_link_submit_link', true );
	$links = '';

	if ( '' != $link_url ) :
		foreach ( $link_url as $link ) :
			if ( 'projects' != get_post_type() ) :
				$links .= '<a href="' . esc_url( $link ) . '" target="_blank" class="featured-link">' .  esc_html( $link ) . '</a>';
			else :
				$links = '<a href="' . esc_url( $link ) . '" target="_blank">' . esc_html( $link ) . '</a>';
			endif;
		endforeach;
		return $links;
	endif;

	if ( '' != $submit_url ) :
		$links = '<a href="' . esc_url( $submit_url ) . '" target="_blank" class="featured-link">' . esc_url( $submit_url ) . '</a>';
		return $links;
	endif;

	if ( '' != $file_url ) :
		$file = '<a href="' . esc_url( $file_url ) . '" target="_blank" class="featured-link">' .  esc_html( $file_url ) . '</a>';
		return $file;
	endif;
}

/**
 * Output the After Reading List content
 *
 * Postscript/Footnote
 * Submission Form
 *
 * Attatched to `after_reading_list` action hook.
 *
 * @since Opening Times 1.4.4
 */
function opening_times_after_reading_list() {
	global $post;

	$footnote = wpautop( get_post_meta( $post->ID, '_ot_after_reading_footnote', true ) );
	$article_submit = get_post_meta( $post->ID, '_ot_after_reading_post_submit', true );

	$after = '';
	if ( '' != $footnote ) :
		$after .= '<div>';
		$after .= $footnote;
		$after .= '</div>';
	endif;
	if ( '' != $article_submit ) :
		$after .= ot_do_frontend_form_submission_shortcode();
	endif;

	echo $after;
}
add_action( 'after_reading_list', 'opening_times_after_reading_list', 1 );

/**
 * Output the statement of responsibilty for the Reading List article
 *
 * Displays Author or Artist repsonsible for text
 * ...or submitter, if a user submitted post.
 *
 * Submission Form
 *
 * @since Opening Times 1.4.7
 */
function opening_times_reading_list_author_name(){
	global $post;
	$submit_url = get_post_meta( $post->ID, '_ot_bv_link_submit_link', true );

	$name = '';
	if ( '' != $submit_url ) :
		$name .= opening_times_bv_link_submitter();
	else:
		$name .= opening_times_taxonomy_no_link();
	endif;

	return $name;
}

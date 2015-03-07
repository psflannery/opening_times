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
		echo $residencydate;
	endif;
    
    if( '' != $meta_takeover_sd ):
		//convert to pretty formats
		$clean_takeover_sd = date( "d F Y", $meta_takeover_sd );
		$clean_takeover_ed = date( "d F Y", $meta_takeover_ed );

		//output the date
		$takeoverdate = '';
		$takeoverdate .= '' . $clean_takeover_sd;
		$takeoverdate .= ' - ' . $clean_takeover_ed;
		echo $takeoverdate;
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
	?>
	
	<dl class="ot-collection-meta ot-meta dl-inline">
		<?php if ( get_the_terms( $post->ID, 'artists') ): ?>
			<dt><?php _e( 'Artist', 'opening_times' ); ?></dt>
			<dd><?php echo get_the_term_list( $post->ID, 'artists', '', ', ', '' ); ?></dd>
		<?php endif; ?>
		<?php if ( in_category( 'editorial-introduction' ) ): ?>
			<dt><?php _e( 'Author', 'opening_times' ); ?></dt>
			<dd><?php the_author_posts_link(); ?></dd>
		<?php endif; ?>
		<?php if( get_the_category_list() ) : ?>
			<dt><?php _e( 'Category', 'opening_times' ); ?></dt>
			<dd><?php echo get_the_category_list( ', ' ); ?></dd>
		<?php endif; ?>
		<?php if( get_the_tag_list() ) : ?>
			<dt><?php _e( 'Tags', 'opening_times' ); ?></dt>
			<dd><?php echo get_the_tag_list( '', ', ', '' ); ?></dd>
		<?php endif; ?>			
		<?php if ( !is_post_type_archive( array ( 'reading', 'take-overs' ) ) ) : ?>
			<dt><?php _e( 'Year', 'opening_times' ); ?></dt>
			<dd><a rel="ajax" href="<?php echo get_year_link( $postyear ); ?>"><?php echo $postyear; ?></a></dd>
		<?php endif; ?>
	</dl>
    
    <?php if ( in_category( 'residency' ) && '' != $residency_start_date ): ?>
        <dl class="ot-collection-meta ot-meta dl-inline">
            <dt><?php _e( 'Dates', 'opening_times' ); ?></dt>
            <dd><?php opening_times_event_dates(); ?></dd>
        </dl>
	<?php endif;
}


/**
 * Prints HTML with meta information for the Take-overs.
 */
function opening_times_takeover_meta() {
    if ( 'take-overs' == get_post_type() ) : ?>
    
        <dl class="ot-event-meta ot-meta dl-inline">
            <dt><?php _e( 'Website', 'opening_times' ); ?></dt>
            <dd><?php get_template_part('template-parts/loop', 'collection_links'); ?></dd>
            <dt><?php _e( 'Dates', 'opening_times' ); ?></dt>
            <dd><?php opening_times_event_dates(); ?></dd>
        </dl>   
    <?php endif;
}
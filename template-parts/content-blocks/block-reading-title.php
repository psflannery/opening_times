<?php
/**
 * The template used for displaying the Readising Issue Title.
 *
 * @package Opening Times
 */

/*
function reading_title_markup_before() {
     
    $before_title = '<div><span class="issue-title__author">';
    return $before_title;
     
}
add_filter( 'reading_author_before', 'reading_title_markup_before' );
 */

do_action( 'opening-times-before-reading-header' );
?>

<header class="issue-title mx-auto col-md-8">
	<h2>
	
	<?php
		opening_times_partner_name( 
			apply_filters( 'reading_author_before', '<span class="issue-title__author">' ), 
			apply_filters( 'reading_author_after', '</span>' )
	 	);
		opening_times_reading_issue_title( 
			apply_filters( 'reading_title_before', '<span class="issue-title__sub">' ),
			apply_filters( 'reading_title_after', '</span>' ) 
		);
	?>
	
	</h2>

	<?php opening_times_reading_issue_standfirst( '<div class="row"><div class="col-md-8 issue-title__standfirst">', '</div></div>' ); ?>
</header>

<?php do_action( 'opening-times-after-reading-header' ); ?>

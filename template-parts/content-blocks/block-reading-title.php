<?php
/**
 * The template used for displaying the Readising Issue Title.
 *
 * @package Opening Times
 */

do_action( 'opening-times-before-reading-header' );
?>

<header class="entry-title__container mx-auto col-md-8">
	<h2>
	
	<?php
		opening_times_partner_name( 
			apply_filters( 'reading_author_before', '<span class="entry-title__author">' ), 
			apply_filters( 'reading_author_after', '</span>' )
	 	);
		opening_times_reading_issue_title( 
			apply_filters( 'reading_title_before', '<span class="entry-title--lg">' ),
			apply_filters( 'reading_title_after', '</span>' ) 
		);
	?>
	
	</h2>

	<?php opening_times_reading_issue_standfirst( '<div class="row"><div class="col-md-8 issue-title__standfirst">', '</div></div>' ); ?>
</header>

<?php do_action( 'opening-times-after-reading-header' ); ?>

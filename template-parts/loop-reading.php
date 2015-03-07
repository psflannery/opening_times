<?php
/**
 * The template used for displaying the Articles on the Reading Page.
 *
 * Creates a loop to list all the child pages of each Issue
 * Used in the Reading Archive and the Single Reading Issue Page
 *
 * @package Opening Times
 */
?>

<?php
$args = array(
	'post_parent' => $post->ID,
	'post_type' => 'article',
	'order' => 'DESC', 
	'numberposts' => -1,
);
$articles = new WP_query($args); ?>

<?php if ($articles->have_posts()) : ?>

	<?php while ($articles->have_posts()) : $articles->the_post(); ?>

		<?php get_template_part( 'template-parts/content', 'reading' ); ?>

   <?php endwhile; ?>
   
<?php else : ?>

	<?php get_template_part( 'template-parts/content', 'none' ); ?>
	
<?php endif; ?>
 
<?php // reset the query
wp_reset_postdata();

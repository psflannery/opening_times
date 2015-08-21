<?php
/**
 * The Sidebar containing the widget area in the Mailing List drop-down.
 *
 * @package Opening Times
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
    return;
}
?>

<section id="mailing-list" class="info-panel" aria-hidden="true" role="complementary">
    
    <div class="pseudo-content-divider-top">
    	
    	<?php dynamic_sidebar( 'sidebar-1' ); ?>

	</div>

</section>

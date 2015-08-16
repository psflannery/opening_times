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

<section id="mailing-list" class="info-panel pseudo-content-divider-top" aria-hidden="true" role="complementary">
    
    <?php dynamic_sidebar( 'sidebar-1' ); ?>

</section>

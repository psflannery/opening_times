<?php
/**
 * The Sidebar containing the widget area in the About drop-down.
 *
 * @package Opening Times
 */

if ( ! is_active_sidebar( 'sidebar-2' ) ) {
    return;
}
?>

<section id="about" class="info-panel info-columns" aria-hidden="true" role="complementary">

    <?php dynamic_sidebar( 'sidebar-2' ); ?>

</section>

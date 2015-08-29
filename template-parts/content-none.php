<?php
/**
 * The template part for displaying a message that posts cannot be found.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Opening Times
 */
?>

<section class="no-results not-found">
    <header class="page-header">
        <h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'opening_times' ); ?></h1>
    </header>

    <div class="page-content">
        <?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

            <p><?php printf( __( 'Ready to publish the first post? <a href="%1$s">Get started here</a>.', 'opening_times' ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>

        <?php elseif ( is_search() ) : ?>

            <p><?php esc_html_e( 'Sorry, we didn\'t find any results.', 'opening_times' ); ?></p>

        <?php else : ?>

            <p><?php esc_html_e( 'We\'ve not published anything here yet. Try searching for something else.', 'opening_times' ); ?></p>

        <?php endif; ?>
    </div>
</section>

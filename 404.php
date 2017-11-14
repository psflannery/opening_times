<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package Opening Times
 */

get_header(); ?>

    <main id="main" class="site-main" role="main">
        <section class="error-404 not-found">
            <header class="page-header text-center">
                <h1 class="page-title"><?php esc_html_e( 'Not Found', 'opening_times' ); ?></h1>
            </header>
            <div class="message404 center-fixed">
            	<span class="screen-reader-text"><?php esc_html_e( '404', 'opening_times' ); ?></span>
            </div>
        </section>
    </main>

<?php 
get_footer(); ?>

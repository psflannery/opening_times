<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package Opening Times
 */

get_header(); ?>

    <main id="main" class="site-main" role="main">

        <section class="error-404 not-found">
            <header class="page-header">
                <h1 class="page-title"><?php esc_html_e( 'Not Found', 'opening_times' ); ?></h1>
            </header>
        </section>

    </main>

<?php get_footer(); ?>
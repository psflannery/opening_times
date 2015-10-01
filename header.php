<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Opening Times
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/img/apple-icon-touch.png">
    <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/img/favicon.ico">

    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <div id="page" class="hfeed site">

        <header id="masthead" class="site-header" role="banner">
            <h1 class="site-title"><span class="screen-reader-text"><?php bloginfo( 'name' ); ?></span></h1>
            <nav id="site-navigation" class="main-navigation" role="navigation">
                <a class="skip-link screen-reader-text screen-reader-text-focusable" href="#content"><?php esc_html_e( 'Skip to content', 'opening_times' ); ?></a>

                <?php 
                    wp_nav_menu( array( 
                        'theme_location' => 'primary',
                        'container' => false,
                        'depth' => '1',
                        'menu_class' => 'navigation-menu menu'
                    ) ); 
                ?>
                <?php 
                    wp_nav_menu( array( 
                        'theme_location' => 'social',
                        'container' => false,
                        'depth' => '1',
                        'menu_class' => 'social-menu menu'
                    ) );
                ?>

            </nav>
            <div id="info" class="dropdown site-info"></div>
        </header>

        <?php do_action('after_header'); ?>

        <div class="center-fixed site-logo-container">

            <?php get_template_part('img/inline', 'ot-logo-black.svg'); ?>

        </div>

        <div id="content" class="site-content">
            <h1 class="menu-toggle"><span class="screen-reader-text"><?php esc_html_e( 'Menu', 'opening_times' ); ?></span></h1>
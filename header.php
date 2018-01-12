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
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="profile" href="http://gmpg.org/xfn/11">

    <?php wp_head(); ?>
    <?php echo opening_times_get_additional_header_scripts(); ?>
    
</head>
<body <?php body_class(); ?>>

    <?php do_action( 'opening-times-before-page' ); ?>

    <div id="page" class="hfeed">
        <div id="scene" class="sceneElement site">

            <?php do_action('before_header'); ?>

            <header id="masthead" class="site-header" role="banner">
                <div class="site-branding">

                    <?php
                    if ( is_front_page() && is_home() ) : ?>
                        <h1 class="site-title"><span class="screen-reader-text"><?php bloginfo( 'name' ); ?></span></h1>
                    <?php else : ?>
                        <p class="site-title"><span class="screen-reader-text"><?php bloginfo( 'name' ); ?></span></p>
                    <?php 
                    endif;
                     
                    $description = get_bloginfo( 'description', 'display' );
                    
                    if ( $description || is_customize_preview() ) :
                    ?>
                        <p class="site-description screen-reader-text"><?php echo $description; ?></p>

                    <?php 
                    endif; ?>
                </div>
                <button type="button" class="btn btn-link offcanvas-toggle hidden-md-up my-2" data-toggle="offcanvas" data-target="#site-navigation">
                    <span class="screen-reader-text"><?php esc_html_e( 'Menu', 'opening_times' ); ?></span>
                    <?php echo opening_times_get_svg_icon( array( 'icon' => 'hamburger' ) ); ?>
                </button>
                <a class="skip-link screen-reader-text screen-reader-text-focusable" href="#content"><?php esc_html_e( 'Skip to content', 'opening_times' ); ?></a>
                <nav id="site-navigation" class="main-navigation navbar navbar-default navbar-offcanvas navbar-offcanvas-touch" role="navigation">
                    
                    <?php 
                        wp_nav_menu( array( 
                            'theme_location' => 'primary',
                            'container' => '',
                            'depth' => '1',
                            'menu_class' => 'navigation-menu menu nav'
                        ) );

                        $mailing_list = '<li class="menu-item nav-item"><a href="#collapse-mailing" class="nav-link" data-toggle="collapse" aria-expanded="false" aria-controls="collapse-mailing">' . esc_html ( 'Mailing List', 'opening_times' ) . '</a></li>';

                        $search = '<li class="menu-item nav-item__search expanding-search"><button class="btn expanding-search__btn nav-link hidden-sm-down" data-toggle="search-expand">' . esc_html( 'Search', 'opening_times' ) . '</button>' . get_search_form( false ) . '</li>';
                    
                        echo opening_times_get_social_menu( $mailing_list, $search );
                    ?>

                </nav>
                <div id="info" class="dropdown site-info toggle-wrap">

                    <?php
                        $sidebars = array(
                            'about',
                            'news',
                            'mailing-list'
                        );

                        foreach ( $sidebars as $sidebar ) {
                            get_sidebar( $sidebar );
                        }
                    ?>

                </div>
            </header>

            <?php do_action('after_header'); ?>

            <div id="content" class="site-content">

<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Opening Times
 */
?>

            </div><!-- #content -->

            <?php do_action('before_footer'); ?>

            <footer id="colophon" class="site-footer container-fluid" role="contentinfo">
                <div class="row">

            	<?php
                    openining_times_ace_link( '<div class="col-md-3 ace-link">', '</div>' );

                    opening_times_footer_text( '<div class="col footer__info-text small">', '</div>' );

                    wp_nav_menu( array( 
                        'theme_location' => 'info',
                        'container_class' => 'col-md-3',
                        'depth' => '1',
                        'menu_class' => 'navigation-menu menu nav flex-column small'
                    ) );

                    get_sidebar('footer');
                ?>
                
                </div>
            </footer>

            <?php do_action( 'opening-times-after-footer' ); ?>

        </div><!-- #scene -->
    </div><!-- #page -->

    <?php do_action( 'opening-times-after-page' ); ?>

    <div class="center-fixed site-logo-container">

        <?php opening_times_do_svg( 'ot-logo-black.svg' ); ?>

    </div>

<?php wp_footer(); ?>
<?php echo opening_times_get_additional_footer_scripts(); ?>

</body>
</html>

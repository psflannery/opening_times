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
    </div><!-- #page -->

    <?php get_template_part( 'template-parts/dropdown', 'about' ); ?>
    <?php get_template_part( 'template-parts/dropdown', 'mailing_list' ); ?>

    <footer id="colophon" class="site-footer" role="contentinfo">
        <?php $ac_link = get_theme_mod( 'ot_arts_council_link' ); ?>
        <?php if ( '' != $ac_link ) : ?>
            <a href="<?php echo esc_url( $ac_link ); ?>" target="_blank"><?php get_template_part('img/inline', 'ac_black.svg'); ?></a>
        <?php endif; ?>
    </footer>

<?php wp_footer(); ?>
</body>
</html>
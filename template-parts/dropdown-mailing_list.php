<?php
/**
 * The template used for displaying the Mailing List dropdown.
 *
 * @package Opening Times
 */
?>

<section id="mailing-list" class="info-panel pseudo-content-divider-top" aria-hidden="true" role="complementary">

    <?php $email = get_theme_mod( 'ot_email_address' ); ?>
    <?php if ( '' != $email ) : ?>
    
        <h1><span class="ot-email"><?php echo esc_url( $email ) ?></span></h1>
        
    <?php endif; ?>

    <?php $mail_subscribe = get_theme_mod( 'ot_mailing_list' ); ?>
    <?php if ( '' != $mail_subscribe ) : ?>
        
        <?php get_sidebar(); ?>

    <?php endif; ?>
    
</section>

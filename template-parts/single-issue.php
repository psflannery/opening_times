<?php
/**
 * The template for displaying the Single Issue pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Opening Times
 */
?>
<section id="<?php opening_times_the_slug(); ?>" class="new-editor accordion-reading strap-container clearfix">
    <header class="editor-title strap-header issue-meta gradientee">
        <h2 class="header-details col-sm-4"><?php the_author(); ?></h2>

        <?php $editor_title = get_post_meta( $post->ID, '_ot_editor_title', true ); ?>
        <?php if ( '' != $editor_title ) : ?>
        
            <h2 class="header-details col-sm-3"><?php echo $editor_title ?></h2>

        <?php endif; ?>

        <h2 class="header-details col-sm-3 header-details-last"><?php the_title(); ?></h2>
    </header>

    <div class="reading-issue-wrap accordion-content clearfix">
        <div class="editor-wrap col-sm-4">
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <div class="entry-content">
                
                    <?php the_content(); ?>		
                    
                </div>
            </article>
            <div class="content-divider">
            
                <?php echo opening_times_editor_bio(); ?>
                
            </div>
        </div>
        <div class="editor-selection accordion col-sm-6 gradienter">
        
            <?php get_template_part( 'template-parts/loop', 'reading' ); ?>	
            
            <?php opening_times_after_reading_list(); ?>
            
        </div>
    </div>
</section>

<?php
/**
 * @package Opening Times
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header id="<?php opening_times_the_slug(); ?>" class="entry-header">
        <h1 class="entry-title"><a href="<?php the_permalink(); ?>" rel="ajax" class="gradientee"><?php the_title(); ?></a></h1>
    </header>

    <div class="entry-summary search-result">
        <?php the_excerpt(); ?>
    </div>
</article>
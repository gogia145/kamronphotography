<?php
/*
Template Name: Page: Horizontal
 */
maybe_get_header();

if ( have_posts() ): the_post();
?>
    <div id="stage" <?php village_background('stage') ?>>
        <div class="horizontal__page js__winsize js__scroll--horizontal scroll--horizontal section__block">
            <?php the_content(); ?>
        </div>
    </div> <!-- #stage -->

<?php else: ?>
    <?php maybe_get_header() ?>
    <?php get_template_part( '404' ); ?>
<?php endif; ?>
<?php maybe_get_footer(); ?>

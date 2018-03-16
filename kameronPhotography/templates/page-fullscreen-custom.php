<?php
/*
Template Name: Custom Fullscreen Page
 */
maybe_get_header( 'fullscreen' );

if ( have_posts() ): the_post();
?>
<div id="stage" <?php village_background('fs') ?>>
	<?php the_content() ?>
</div> <!-- #stage -->

<?php else: ?>
    <?php get_template_part( '404' ); ?>
<?php endif; ?>
<?php maybe_get_footer(); ?>

<?php 
$has_thumbnail = has_post_thumbnail();
$post_class = array("content__inner", "entry");

if( $has_thumbnail ) {
	$post_class[] = "has-thumbnail";
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( $post_class ); ?>>
	
	<?php if ( has_post_thumbnail() ): ?>
		<a class="entry__thumbnail" href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail("large"); ?>
		</a>
	<?php endif; ?>

	<div class="entry__excerpt">
	
		<?php get_template_part('parts/header', get_post_type() ) ?>
		
			
		<?php
		if ( Village::get_theme_mod( 'use_excerpts', true ) ): 
		?>
			
			<?php  the_excerpt(false);  ?>
	 		<a href="<?php the_permalink(); ?>" class="readmore">
				<?php _e("Continue Reading &rarr;", "village"); ?>
			</a>
		
		<?php else: ?>
			<?php the_content(__("Continue Reading &rarr;", "village")); ?>
		<?php endif;
		?>
	
	</div>

</article><!-- #post-## -->
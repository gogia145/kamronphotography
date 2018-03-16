<article id="post-<?php the_ID(); ?>" <?php post_class(array("content__inner", "entry--single" ) ); ?>>
	
	<?php get_template_part('parts/header', get_post_type() ); ?>
	
	<div class="entry__content">

	<?php if ( has_post_thumbnail() ): ?>
		<?php if ( ! is_single() ): ?>
		<a class="post-thumbnail" href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail(); ?>
		</a>
		<?php elseif( Village::is_enabled('show_featured_image_in_post') ): ?>
			<?php the_post_thumbnail(); ?>
		<?php endif; ?>

	<?php endif; ?>
	
		
	<?php the_content(); ?>
	<?php
		wp_link_pages( array(
			'before' => '<div class="page-links">' . __( 'Pages:', 'camilla' ),
			'after'  => '</div>',
		) );
	?>

	</div>

</article><!-- #post-## -->
<?php maybe_get_header(); ?>

<div id="stage" <?php village_background('stage') ?>>
	<main id="primary" class="container" role="main">
		<div class="js__scroll js__winsize">
			<div<?php village_background('content')?>>
				<?php if ( have_posts() ) : ?>
					
					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'parts/content', get_post_format() ); ?>
					<?php endwhile; ?>

					<?php if ( comments_open() || '0' != get_comments_number() ) : ?>
						<?php comments_template(); ?>
					<?php endif; ?>

				<?php else : ?>
					<?php get_template_part( 'parts/content', 'none' ); ?>
				<?php endif; ?>				

			</div> <!-- .content -->
		</div> <!-- .js__scroll -->
	</main> 	

</div><!-- #stage -->

<?php maybe_get_footer(); ?>

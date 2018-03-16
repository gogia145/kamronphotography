<?php maybe_get_header(); ?>

<div id="stage" <?php village_background('stage') ?>>
	<main id="primary" class="container" role="main">
		<div class="js__scroll js__winsize">

				<?php if ( have_posts() ) : ?>

					<?php /* Start the Loop */ ?>
					<?php while ( have_posts() ) : the_post(); ?>

						<?php get_template_part( 'parts/entry', get_post_format() ); ?>

					<?php endwhile; ?>
					
					<?php village_paging_nav(); ?>

				<?php else : ?>
					<?php get_template_part( 'content', 'none' ); ?>
				<?php endif; ?>			


			
		</div> <!-- .js__scroll -->
	</main> 	

</div><!-- #stage -->

<?php maybe_get_footer(); ?>

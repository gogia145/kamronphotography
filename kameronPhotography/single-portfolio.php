<?php maybe_get_header(); ?>
		<?php if ( have_posts() ) : ?>
			
			<?php while ( have_posts() ) : the_post(); ?>
				<?php if ( !post_password_required() ): ?>
					<div id="stage" class="stage--gallery">
						<?php get_template_part( 'parts/content', 'portfolio' ); ?>
					</div>
					
				<?php else: ?>
					<div id="stage" <?php village_background('stage') ?>>
						<main id="primary" class="container" role="main">
							<div class="js__scroll js__winsize">	
								<div class="password-icon ion-ios7-locked-outline"></div>
								<?php the_content(); ?>
							</div>
						</main>
					</div>
				<?php endif; ?>
			<?php endwhile; ?>

			<?php if ( comments_open() || '0' != get_comments_number() ) : ?>
				<?php comments_template(); ?>
			<?php endif; ?>

		<?php else : ?>
			<?php get_template_part( 'content', 'none' ); ?>
		<?php endif; ?>				

<?php maybe_get_footer(); ?>

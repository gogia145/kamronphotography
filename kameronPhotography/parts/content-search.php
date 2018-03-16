<article id="post-<?php the_ID(); ?>" <?php post_class("entry-item"); ?>>
	<div class="content__inner">
		
		<header class="entry-header">	
			<h1 class="entry-title">
				<a class="js__items--link" href="<?php the_permalink(); ?>">
					<?php the_title() ?>
				</a>
			</h1>

			<div class="entry-meta">
				<i class="icon-calendar-empty"></i>
				<?php the_post_date(); ?>
				&nbsp;&nbsp;&nbsp;
				<i class="icon-folder-open-alt"></i>
				<?php the_category(", "); ?>

				<a href="<?php comments_link(); ?>" class="comment-count">
					<i class="icon-comment-alt"></i>
					<?php echo get_comments_number(); ?>
				</a>
			</div>

		</header>
		
			
		<?php the_excerpt(); ?>

	</div>

</article><!-- #post-## -->
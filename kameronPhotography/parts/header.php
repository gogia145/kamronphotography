<header class="entry__header">	
	<h1 class="entry__title">
		<?php if ( ! is_single() ): ?>
			<a class="js__items--link" href="<?php the_permalink(); ?>">
				<?php the_title() ?>
			</a>
		<?php else: ?>
			<?php the_title(); ?>
		<?php endif; ?>
	</h1>

	<div class="entry__meta">
		<?php if ( Village::is_enabled("display_post_date", true ) ): ?>
			<i class="icon ion-ios7-calendar"></i>
			<?php the_post_date(); ?>
		<?php endif ?>
					
		<?php if ( Village::is_enabled("display_post_categories", true ) ): ?>
			&nbsp;&nbsp;&nbsp;
			<i class="icon ion-folder"></i>
			<?php the_category(", "); ?>
		<?php endif ?>

		<?php if ( Village::is_enabled("display_post_tags", false) ): ?>
			&nbsp;&nbsp;&nbsp;
			<i class="icon ion-pricetag"></i>
			<?php the_tags( "", ", " ); ?>
		<?php endif; ?>

		<?php if ( comments_open() ): ?>
			&nbsp;&nbsp;&nbsp;
			<a href="<?php comments_link(); ?>" class="comment-count">
				<i class="icon ion-chatbubble"></i>
				<?php echo get_comments_number(); ?>
			</a>
		<?php endif; ?>
	</div>

</header>

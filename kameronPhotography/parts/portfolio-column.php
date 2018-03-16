<?php

// Can't beccessed directly
if ( !defined( 'ABSPATH' ) ) return;

/**
 * Parent template part is supposed to set the following variables
 * @use set_query_var() to set the variables in parent template part
 */
if( !isset( $gallery_options, $description, $image_id ) ) return;

$thumbnail = wp_get_attachment_image_src( $image_id, "portfolio_tall_thumbnail" );
$thumb_width = $thumbnail[1];

$container = array(
	'class' => array( 'hscol', 'pfentry', 'js__hscol', 'js__pfentry' ),
	'data' => array(
		'entry-width' => $thumb_width,
	),
	'style' => array(
		'width' => $thumb_width . 'px',
	),
);


if ( empty( $description ) ) {
	$container['class'][] = 'no-content';
}

if( empty( $thumb_width ) ) {
	$container['class'][] = 'no-thumbnail';
}


?>
<div<?php Village::render_attributes( $container ); ?>>

	<div id="gallery-<?php the_ID();?>" data-gallery-id="<?php the_ID();?>" class="pfentry__image">
		<?php if ( $gallery_options['enable_gallery'] ): ?>
			<?php

				$js_link = array('js__link');
				if ( post_password_required() ) {
					$js_link[] = 'js__direct';
				}

			?>
			<a href="<?php the_permalink(); ?>"<?php Village::render_class($js_link); ?>>
				<?php echo wp_get_attachment_image( $image_id, 'portfolio_tall_thumbnail' ); ?>
			</a>
		<?php else: ?>
			<?php echo wp_get_attachment_image( $image_id, 'portfolio_tall_thumbnail' ); ?>
		<?php endif; ?>

	</div>


	<?php

	//-----------------------------------*/
	// Descriptions ( if enabled )
	//-----------------------------------*/

	?>
	<?php if ( $gallery_options['enable_description'] && ( $entry_title || $description) ): ?>
		<div class="pfentry__info">
			<div class="pfentry__info__wrapper">


				<?php if ( $description ): ?>
					<div class="js__scroll">
				<?php endif ?>

					<h2 class="pfentry__title">
						<?php if ( $gallery_options['enable_gallery'] ): ?>
							<a href="<?php the_permalink(); ?>"<?php Village::render_class($js_link); ?>>
								<?php  echo $entry_title ?>
							</a>
						<?php else: ?>
							<?php echo $entry_title; ?>
						<?php endif; ?>
					</h2>

					<div class="pfentry__content">
						<?php
						// I already have the content
						// Don't have to re-run the_content() function
						echo wpautop($description);
						?>
					</div>

				<?php if ( $description ): ?>
					</div> <!-- .js_scroll -->
				<?php endif ?>

			</div>

			<div class="pfentry__toggle close js__close">
				<i class="ion-ios7-close-empty"></i>
			</div>

		</div> <!-- .pfentry -->

		<div class="pfentry__toggle open js__open">
			<i class="ion-ios7-information-outline"></i>
		</div>

	<?php endif; ?>

</div> <?php // end <div$container> ?>
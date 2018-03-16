<?php
maybe_get_header();

$gallery_options = array(
	'enable_gallery' => Village::is_enabled( 'gallery_enable', true ),
	'enable_description' => Village::is_enabled( 'portfolio_enable_desc', true )
);

$background_image = Village::get_theme_mod( 'portfolio_background_image' );
$background_color = Village::get_theme_mod( 'portfolio_background_color' );

$stage = array(
	'id' => 'stage',
	'class' => array( 'stage' ),
	'style' => array(),
);

if ( !empty( $background_image['url'] ) ) {
	$stage['style']['background-image'] = "url('$background_image[url]');";
}

if ( $background_color ) {
	$stage['style']['background-color'] = "$background_color";
}

if ( $gallery_options['enable_gallery'] ) {
	$stage['class'][] = 'has-gallery';
}

$stage['class'][] = Village::get_theme_mod('portfolio_description_text_preset', 'dark-font');


?>
	<div<?php Village::render_attributes( $stage ); ?>>
			<div class="js__scroll--horizontal scroll--horizontal js__winsize">
			<?php if ( have_posts() ) : ?>

			<?php /* Start the Loop */ ?>
			<?php
				// We only need to set gallery_options once.
				set_query_var( 'gallery_options', $gallery_options);

				while ( have_posts() ) {

					the_post();

					if ( $gallery_options['enable_gallery'] || has_post_thumbnail() ) {

						$description = false;

						if ( ! post_password_required() ) {
							$description = get_the_content();
						}

						set_query_var( 'image_id', get_post_thumbnail_id() );
						set_query_var( 'description', $description );
						set_query_var( 'entry_title', get_the_title() );

						get_template_part( 'parts/portfolio-column' );

					} elseif ( ! post_password_required() ) {

						get_template_part( 'parts/portfolio-columns-loop' );

					}

			} // endwhile

			?>

				<?php village_paging_nav(); ?>

			<?php else : ?>

				<?php get_template_part( 'content', 'none' ); ?>

			<?php endif; ?>

			</div> <!-- .js__scroll -->

	</div> <!-- #stage -->
<?php maybe_get_footer(); ?>

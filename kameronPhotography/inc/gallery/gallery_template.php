<?php 
$gallery_classes = parse_gallery_cookies();

$gallery_data = new Village_Gallery_Data( get_the_ID() );
$gallery['data']['gallery'] = $gallery_data->get();

if( ! $gallery_data->has_descriptions() ) {
	$gallery_classes[] = 'is-full';
	$gallery_classes[] = 'no-sidebar';

	if(($key = array_search('show-sidebar', $gallery_classes)) !== false) {
	    unset($gallery_classes[$key]);
	}
}

$gallery_classes = array_unique($gallery_classes);
?>
<div id="gallery"<?php Village::render_class($gallery_classes);?>>
	
	<!-- Sidebar -->
	<?php if ( ! $gallery_data->has_descriptions() ): ?>
	<div id="gallery__sidebar">
		
		<?php if ( ! Village::get_theme_mod('gallery_force_sidebar', false ) ): ?>
		<div id="gallery__sidebar__close" class="gallery__button">
			<i class="icon ion-ios7-close-empty"></i>
		</div>
		<?php endif; ?>
		
		<div class="content">
			<h3 class="title"></h3>
			<div class="desc"></div>
		</div>

	</div> <!-- .gallery__sidebar -->
	<?php endif; ?>

	<!-- Stage for Fotorama -->
	<div><div id="gallery__stage"<?php Village::render_attributes($gallery);?>></div></div>

	<!-- Buttons -->
	<div id="gallery__close" class="gallery__button">
		<i class="icon ion-ios7-close-empty"></i>
	</div>
	

	<div class="gallery__interaction">			
		
	<?php if ( Village::is_enabled('gallery_enable_thumbnails', true) && ! Village::is_enabled('gallery_force_thumbnails', false) ): ?>
		<div id="gallery__thumbs__toggle" class="button">
			<i class="thumbs__close icon ion-ios7-arrow-down"></i>
			<i class="thumbs__show icon ion-ios7-arrow-up"></i>
		</div>
	<?php endif ?>


		<?php 
		$gallery_sharing = array_filter( array_values( (array) Village::get_theme_mod('gallery_sharing') ) );
		if ( !empty( $gallery_sharing ) ): ?>
		<div id="gallery__share" class="button">
			<div class="share-toggle">
				<i class="icon ion-share"></i>
			</div>

			<div class="share__networks">
				<i class="share__networks__arrow"></i>
				<div class="sharrre">

				</div>
			</div>
		</div>
		<?php endif ?>

		<div id="gallery__sidebar__open" class="button">
			<i class="icon ion-more"></i>
		</div>


	</div> <!-- .gallery__interaction -->
	
</div> <!-- #gallery-000 -->



<?php if( ! is_pjax() && true === false ): ?>

<noscript>
	<?php foreach ($images as $image): ?>
	<div class="nojs-img">
		<a href="<?php echo $image['image'] ?>">
			<img src="<?php echo ( $enable_thumbnails ) ? $image['thumb'] : $image['image']; ?>"/>
		</a>

		<?php if ( $image['caption'] ): ?>
			<h3 class="title"><?php echo $image['caption'] ?></h3>
		<?php endif; ?>				

		<?php if ( $image['desc'] ): ?>
			<p class="desc"><?php echo $image['desc'] ?></p>
		<?php endif; ?>
	</div>
	<?php endforeach; ?>
</noscript>
<?php endif; ?>

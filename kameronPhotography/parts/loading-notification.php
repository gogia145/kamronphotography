<?php 
$spinner_class = array("spinner");
$loading_image = Village::get_theme_mod( 'loading_image' );

if ( !empty( $loading_image ) && !empty( $loading_image['url']) ) {
	$loading_image = $loading_image['url'];
	
	if( Village::is_enabled('loading_spin_css3', false ) ) {
		$spinner_class[] = "spinner--rotating";
	}

} else {
	$loading_image = get_template_directory_uri() . '/assets/images/loading.gif';
	$spinner_class[] = "spinner--default";
}

?>
<div id="loading">
	<div<?php Village::render_class( $spinner_class )?>>
		<img src="<?php echo $loading_image ?>" alt="<?php _e('Loading Image', 'village') ?>">
	</div>
</div>
<?php
// Require Once, Require in the main template directory
$template_directory = get_template_directory();

//-----------------------------------*/
// Include Advanced Custom Fields
//-----------------------------------*/
define( 'ACF_LITE', true );


if ( class_exists( 'acf_field' ) ) {
	// Video URL Meta:
	require_once $template_directory . '/inc/acf/attachment_meta.php';
	
	// ACF Repeater
	require_once $template_directory . '/inc/acf/acf-repeater/acf-repeater.php';
	
	// ACF Gallery
	require_once $template_directory . '/inc/acf/acf-gallery/acf-gallery.php';
	
	// Add settings
	require_once $template_directory . '/inc/acf/acf-settings.php';

}

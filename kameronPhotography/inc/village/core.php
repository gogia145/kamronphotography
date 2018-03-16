<?php
$theme_dir = get_template_directory();
$theme_url = get_template_directory_uri();

$village_dir = $theme_dir . '/inc/village';
$village_url = $theme_url . '/inc/village';

/* -----------------------------------*/
/* 		Load Village Core Class
/* -----------------------------------*/
require_once $village_dir . '/Village.class.php';

// Initialize Village Class
Village::init();

require_once $village_dir . '/Village_PJAX.php';
require_once $village_dir . '/Village_CSS.php';
require_once $village_dir . '/Village_JavaScript_Options.php';

//-----------------------------------*/
// Add Redux
//-----------------------------------*/
if ( class_exists( 'ReduxFramework' ) &&  !isset( $redux_demo ) && file_exists( $theme_dir . '/inc/options.php' ) ) {
	require_once $theme_dir . '/inc/TV_Parser.php';
	require_once $theme_dir . '/inc/options.php';
}

if( ! function_exists('village_remove_redux_demo') ) {
	
	function village_remove_redux_demo() { // Be sure to rename this function to something more unique
	    if ( class_exists('ReduxFrameworkPlugin') ) {
	        remove_filter( 'plugin_row_meta', array( ReduxFrameworkPlugin::get_instance(), 'plugin_metalinks'), null, 2 );
	    }
	    if ( class_exists('ReduxFrameworkPlugin') ) {
	        remove_action('admin_notices', array( ReduxFrameworkPlugin::get_instance(), 'admin_notices' ) );    
	    }
	}
	add_action('init', 'village_remove_redux_demo');
	
}



/* -----------------------------------*/
/* 		Actions:
/* -----------------------------------*/
add_action( 'after_setup_theme', array( 'Village', 'setup' ) );


/* -----------------------------------*/
/* 		Filters
/* -----------------------------------*/
add_filter( 'body_class', array( 'Village', 'filter_body_class' ) );
// add_filter( 'village_print_items', 'village_print_items', 10, 2);

/* -----------------------------------*/
/* 		Dashboard
/* -----------------------------------*/
// This Conditional Tag checks if the Dashboard or the administration panel is attempting to be displayed.
if ( is_admin() ) {
	// Load / Require Plugins if current user can activate them
	if ( current_user_can( 'activate_plugins' ) ) {
		require_once $village_dir . '/init_plugins/initialize_plugins.php';
	}
}


//-----------------------------------*/
// Page Builder
//-----------------------------------*/
if ( class_exists('AQ_Page_Builder')  ) {
	
	aq_unregister_block( "aq_text_block" );
	aq_unregister_block( "aq_slider_block" );
	aq_unregister_block( "aq_column_block" );
	aq_unregister_block( "aq_clear_block" );
	aq_unregister_block( "aq_tabs_block" );
	aq_unregister_block( "aq_alert_block" );
	aq_unregister_block( "aq_widgets_block" );
	aq_unregister_block( "AQ_Editor_Block" );

	$builder_dir = $village_dir . '/page-builder';

	function override_aqpb_styles() {
		$theme_dir = trailingslashit( get_template_directory_uri() );
		
		$custom_css = $theme_dir . "css/admin.css";
		$custom_javascript = $theme_dir . "js/admin.js";
		
		wp_register_style( 'aqpb-custom-blocks', $custom_css, array(), null, 'all');
		wp_register_script('village-pagebuilder', $custom_javascript, array('jquery'), null, true);
		
		wp_enqueue_style ('aqpb-custom-blocks');
		wp_enqueue_script('village-pagebuilder');

		wp_dequeue_script( 'aqpb-js' );
		wp_enqueue_script( 'aqpb-js-modified', $theme_dir . 'assets/javascript/overrides/aqpb.js', array('jquery', 'jquery-ui-droppable'), time(), true);

	}
	// add_action('admin_enqueue_scripts', 'override_aqpb_styles', 1001);
	add_action('aq-page-builder-admin-enqueue', 'override_aqpb_styles');
	



	// require_once( $builder_dir . '/admin_options.php');
	require_once( $builder_dir . '/aqua_functions.php');
	require_once( $builder_dir . '/_Village_Block.php' );
	
	require_once( $builder_dir . '/Village_Block_Service.php' );
	require_once( $builder_dir . '/Village_Block_Text.php' );
	require_once( $builder_dir . '/Village_Block_Image.php' );
	
	


}




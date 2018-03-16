<?php

$easing_list = apply_filters( 'village_available_easing', array(
	'easeInQuad',
	'easeOutQuad',
	'easeInOutQuad',
	'easeInCubic',
	'easeOutCubic',
	'easeInOutCubic',
	'easeInQuart',
	'easeOutQuart',
	'easeInOutQuart',
	'easeInQuint',
	'easeOutQuint',
	'easeInOutQuint',
	'easeInSine',
	'easeOutSine',
	'easeInOutSine',
	'easeInExpo',
	'easeOutExpo',
	'easeInOutExpo',
	'easeInCirc',
	'easeOutCirc',
	'easeInOutCirc',
	'easeInElastic',
	'easeOutElastic',
	'easeInOutElastic',
	'easeInBack',
	'easeOutBack',
	'easeInOutBack',
	'easeInBounce',
	'easeOutBounce',
	'easeInOutBounce'
) );


//-----------------------------------*/
// Variables Used:
//-----------------------------------*/

$args     = array();
$tabs     = array();
$sections = array();

//-----------------------------------*/
// Helper Functions
//-----------------------------------*/

function village_get_templates( $args = null ) {
	$args['post_type'] = 'template';

	$pages  = get_posts( $args );
	$out[0] = "Disabled";
	foreach ( $pages as $page ) {
		$out[ $page->ID ] = $page->post_title;
	}

	return $out;
}

// DEBUG
$args['dev_mode'] = false;


/**
 * <<< Theme Options: Begin >>>
 */

//-----------------------------------*/
// Tab: Home
//-----------------------------------*/
$sections[] = array(
	'title'  => __( 'General Settings', 'village' ),
	'icon'   => 'el-icon-cogs',
	'fields' => array(
		//-----------------------------------*/
		// Site Index
		//-----------------------------------*/
		array(
			'id'     => TV_Parser::make_int(),
			'type'   => 'section',
			'title'  => 'Site Index',
			'indent' => true,
		),
		array(
			'id'    => TV_Parser::make_int(),
			'title' => '',
			'type'  => 'info',
			'desc'  => '
				If you\'re using a full-screen slider page at the root of your site
				- you have to tell the theme which page to treat as the site index. <br>
				For example - this URL is used when clicking on the Logo to return to Index <br>
			  ',
		),
		array(
			'id'      => 'index_type',
			'title'   => 'Select your Index Type',
			'type'    => 'switch',
			'on'      => 'Wordpress Page',
			'off'     => 'Custom URL',
			'default' => 1,
		),
		array(
			'id'       => 'index_page',
			'title'    => 'Index Page',
			'type'     => 'select',
			'data'     => 'page',
			'required' => array( 'index_type', 'equals', '1' ),
		),
		array(
			'id'       => 'index_url',
			'title'    => 'Index URL',
			'type'     => 'text',
			'required' => array( 'index_type', 'equals', '0' ),
		),
		array(
			'id'     => TV_Parser::make_int(),
			'type'   => 'section',
			'title'  => 'Loading Animation',
			'indent' => true,
		),
		//-----------------------------------*/
		// Loading Animation
		//-----------------------------------*/

		array(
			'id'      => 'loading_enabled',
			'js'      => 'loading_enabled',
			'title'   => 'Loading Animation',
			'type'    => 'switch',
			'default' => 1,
		),
		array(
			'id'          => 'loading_background',
			'title'       => 'Loading Background Color',
			'type'        => 'color_rgba',
			'transparent' => false,
			'default'     => array(
				'color' => '#000000',
				'alpha' => '0.8'
			),
			'validate'    => 'colorrgba',
			'css'         => array(
				'#loading' =>
					array(
						'background-color' => '%%value%%',
					),
			),
			'required'    => array( 'loading_enabled', 'equals', '1' ),
		),
		array(
			'id'       => "loading_image",
			'title'    => 'Loading Image',
			'desc'     => 'You can upload a custom loading spinner here.',
			'type'     => 'media',
			'default'  => get_template_directory_uri() . '/assets/images/loading.gif',
			'required' => array( 'loading_enabled', 'equals', '1' ),
		),
		array(
			'id'       => 'loading_spin_css3',
			'title'    => 'Spin "Loading Image" With CSS3',
			'desc'     => '<a href="http://caniuse.com/#feat=css-animation" target="_blank">Browser support is limited for this feature</a>.',
			'type'     => 'switch',
			'default'  => 0,
			'required' => array(
				array( 'loading_enabled', 'equals', '1' ),
				array( 'loading_image', 'not_empty', '1' )
			),
		),


	)
);


//-----------------------------------*/
// Header / Navigation
//-----------------------------------*/
$sections[] = array(
	'title'  => __( 'Site Navigation', 'village' ),
	'icon'   => 'el-icon-lines',
	'fields' => array(


		array(
			'id'   => "site_logo",
			'type' => 'media',
		),
		array(
			'id'     => TV_Parser::make_int(),
			'type'   => 'section',
			'title'  => 'Style & Colors',
			'indent' => true,
		),
		array(
			'id'      => 'header_width',
			'js'      => 'size.header_width',
			'title'   => "Menu Width",
			'desc'    => 'Remember that this is going to affect the menu width on Mobile Devices too!',
			'type'    => 'slider',
			"default" => "220",
			"min"     => "160",
			"step"    => "10",
			"max"     => "310",
		),
		array(
			'id'      => 'header_toggle_width',
			'js'      => 'size.header_toggle_width',
			'title'   => "Menu Toggle Width",
			'desc'    => 'The toggle appears on Mobile Devices and in situations when the header is hidden ( for example when a Gallery is opened )',
			'type'    => 'slider',
			"default" => "44",
			"min"     => "32",
			"step"    => "2",
			"max"     => "128",
		),
		array(
			'id'          => 'header_color',
			'title'       => 'Menu Background Color',
			'type'        => 'color',
			'default'     => '#161616',
			'validate'    => 'color',
			'transparent' => false,
			'css'         => array(
				'.site-header' =>
					array(
						'background-color' => '%%value%%',
					),
			),
		),
		array(
			'id'    => "navigation_background_image",
			'title' => 'Menu Background Image',
			'desc'  => '',
			'type'  => 'media',
		),
		array(
			'id'          => 'navigation_font_color_scheme',
			'title'       => 'Menu Font Scheme',
			'type'        => 'select',
			'options'     => array( "light-font" => "Light", "dark-font" => "Dark" ),
			'description' => 'Typically you will want to select dark font colors for light backgrounds, and light fonts for dark backgrounds. For example if your background color is black, select "Light Font". The default setting is "Dark Font" because the default background color is almost white.',
			'default'     => 'light-font',
		),
		array(
			'id'          => 'menu_link_color',
			'title'       => 'Menu Link Color',
			'type'        => 'color_rgba',
			'transparent' => false,
			'default'     => array(
				'color' => '#000000',
				'alpha' => '1.0'
			),
			'validate'    => 'colorrgba',
			'css'         => array(
				'.main-menu .menu-item a' =>
					array(
						'color' => '%%value%%',
					),
			),
		),
		array(
			'id'          => 'menu_link_color_hover',
			'title'       => 'Menu Link Color: On Hover',
			'type'        => 'color_rgba',
			'transparent' => false,
			'default'     => array(
				'color' => '#000000',
				'alpha' => '1.0'
			),
			'validate'    => 'colorrgba',
			'css'         => array(
				'.main-menu .menu-item a:not(.no-link):hover' =>
					array(
						'color' => '%%value%%',
					),
			),
		),
		array(
			'id'          => 'menu_link_color_active',
			'title'       => 'Menu Link Color: Active',
			'type'        => 'color_rgba',
			'transparent' => false,
			'default'     => array(
				'color' => '#000000',
				'alpha' => '0.9'
			),
			'validate'    => 'colorrgba',
			'css'         => array(
				'.main-menu .menu-item.current-menu-item:not(.current-menu-parent) > a' =>
					array(
						'color' => '%%value%%',
					),
			),
		),
		array(
			'id'          => 'header_toggle_color',
			'title'       => 'Header Toggle: Background Color',
			'type'        => 'color_rgba',
			'transparent' => false,
			'default'     => array(
				'color' => '#000000',
				'alpha' => '0.5'
			),
			'validate'    => 'colorrgba',
			'transparent' => false,
			'css'         => array(
				'.header--toggleable .site-header .header__toggle' =>
					array(
						'background-color' => '%%value%%',
					),
			),
		),
		array(
			'id'          => 'header_toggle_icon_color',
			'title'       => 'Header Toggle: Icon Color',
			'type'        => 'color_rgba',
			'transparent' => false,
			'default'     => array(
				'color' => '#FFFFFF',
				'alpha' => '1.0'
			),
			'validate'    => 'colorrgba',
			'css'         => array(
				'.header--toggleable .site-header .header__toggle .icon' =>
					array(
						'color' => '%%value%%',
					),
			),
		),
		array(
			'id'     => TV_Parser::make_int(),
			'type'   => 'section',
			'title'  => 'Background Parallax',
			'indent' => true,
		),
		array(
			'id'      => 'navigation_parallax_enable',
			'js'      => 'on_scroll.enable',
			'title'   => 'Enable Background Parallax',
			'type'    => 'switch',
			'default' => '0',
			'on'      => 'Yes',
			'off'     => 'No',
		),
		array(
			'id'       => 'navigation_parallax_advanced',
			'title'    => 'Enable Advanced Modification ?',
			'type'     => 'switch',
			'default'  => '0',
			'required' => array( 'navigation_parallax_enable', 'equals', '1' ),
			'on'       => 'Yes',
			'off'      => 'No',
		),
		array(
			'id'       => 'navigation_parallax_enable_x',
			'js'       => 'on_scroll.x',
			'title'    => 'X Axis',
			'type'     => 'switch',
			'default'  => '1',
			'required' => array(
				array( 'navigation_parallax_enable', 'equals', '1' ),
				array( 'navigation_parallax_advanced', 'equals', '1' )
			),
			'on'       => 'Enable',
			'off'      => 'Disable',
		),
		array(
			'id'       => 'navigation_parallax_speed_x',
			'js'       => 'on_scroll.speed_x',
			'title'    => 'X Axis: Speed',
			'type'     => 'slider',
			"default"  => "100",
			"min"      => "1",
			"step"     => "1",
			"max"      => "3000",
			'required' => array(
				array( 'navigation_parallax_enable', 'equals', '1' ),
				array( 'navigation_parallax_enable_x', 'equals', '1' ),
				array( 'navigation_parallax_advanced', 'equals', '1' )
			),
		),
		array(
			'id'       => 'navigation_parallax_enable_y',
			'js'       => 'on_scroll.y',
			'title'    => 'Y Axis',
			'type'     => 'switch',
			'default'  => '1',
			'required' => array(
				array( 'navigation_parallax_enable', 'equals', '1' ),
				array( 'navigation_parallax_advanced', 'equals', '1' )
			),
			'on'       => 'Enable',
			'off'      => 'Disable',
		),
		array(
			'id'       => 'navigation_parallax_speed_y',
			'js'       => 'on_scroll.speed_y',
			'title'    => 'Y Axis: Speed',
			'type'     => 'slider',
			"default"  => "100",
			"min"      => "1",
			"step"     => "1",
			"max"      => "3000",
			'required' => array(
				array( 'navigation_parallax_enable', 'equals', '1' ),
				array( 'navigation_parallax_enable_y', 'equals', '1' ),
				array( 'navigation_parallax_advanced', 'equals', '1' )
			),
		),
		array(
			'id'       => 'navigation_parallax_force',
			'js'       => 'on_scroll.force',
			'title'    => 'Force Parallax on Touch Devices',
			'subtitle' => 'Only enable if you really know what you\'re doing',
			'desc'     => 'This will override options above and enabling this will considerably impact the site performance on Mobile Browsers!',
			'type'     => 'switch',
			'default'  => '0',
			'required' => array(
				array( 'navigation_parallax_enable', 'equals', '1' ),
				array( 'navigation_parallax_advanced', 'equals', '1' )
			),
		),

	),
);

//-----------------------------------*/
// Tab: Blog
//-----------------------------------*/
$sections[] = array(
	'title'  => __( 'Blog', 'village' ),
	'icon'   => 'el-icon-pencil',
	'fields' => array(
		array(
			'id'      => 'show_featured_image_in_post',
			'desc'    => 'Should the featured image be automatically shown when a single post is open?',
			'default' => '0',
			'type'    => 'switch',
		),
		array(
			'id'      => 'display_post_categories',
			'default' => '1',
			'type'    => 'switch',
		),
		array(
			'id'      => 'display_post_date',
			'default' => '1',
			'type'    => 'switch',
		),
		array(
			'id'      => 'display_post_tags',
			'default' => '1',
			'type'    => 'switch',
		),
		array(
			'id'      => 'use_excerpts',
			'type'    => 'switch',
			'title'   => "Use Wordpress Excerpts",
			"default" => '1',
		),

	)
);


//-----------------------------------*/
// Tab: Portfolio
//-----------------------------------*/
$sections[] = array(
	'title'  => "Portfolio",
	'icon'   => 'el-icon-picture',
	'fields' => array(

		array(
			'id'      => 'portfolio_enable_desc',
			'js'      => 'portfolio.has_desc',
			'title'   => 'Enable Portfolio Descriptions',
			'default' => '1',
			'type'    => 'switch',
		),
		array(
			'id'       => 'portfolio_enable_desc_hover',
			'js'       => 'portfolio.detect_hover',
			'title'    => 'Show Descriptions on Hover',
			'default'  => '1',
			'type'     => 'switch',
			'required' => array( 'portfolio_enable_desc', 'equals', '1' ),
		),
		array(
			'id'     => TV_Parser::make_int(),
			'type'   => 'section',
			'title'  => 'Style & Colors',
			'indent' => true,
		),
		//-----------------------------------*/
		// Portfolio Background
		//-----------------------------------*/
		array(
			'id'    => "portfolio_background_image",
			'title' => 'Background Image',
			'type'  => 'media',
		),
		array(
			'id'          => "portfolio_background_color",
			'title'       => 'Background Color',
			'type'        => 'color',
			'transparent' => 'false',
			'default'     => '#ffffff',
			'transparent' => false,
		),
		array(
			'id'       => 'portfolio_description_text_preset',
			'title'    => 'Portfolio Text Preset',
			'type'     => 'select',
			'options'  => array( "light-font" => "Light", "dark-font" => "Dark" ),
			'default'  => 'dark-font',
			'required' => array( 'portfolio_enable_desc', 'equals', '1' ),
		),
		//-----------------------------------*/
		// Portfolio Overlay
		//-----------------------------------*/
		array(
			'title'       => 'Description Background Color',
			'id'          => 'portfolio_description_color',
			'transparent' => false,
			'type'        => 'color_rgba',
			"default"     => array(
				'color' => '#FFFFFF',
				'alpha' => '0.82',
			),
			// 'transparent' => false,
			'css'         => array(
				'.layout--regular .pfentry__info' =>
					array(
						'background-color' => '%%value%%',
					)
			),
			'required'    => array( 'portfolio_enable_desc', 'equals', '1' ),
		),
		array(
			'title'       => 'Description Active: Entry Background Color',
			'id'          => 'portfolio_description_image_fade_color',
			'desc'        => 'The color that is on top of the image when the description is active.',
			'type'        => 'color',
			'transparent' => false,
			"default"     => '#000000',
			'css'         => array(
				'.pfentry' =>
					array(
						'background-color' => '%%value%%',
					)
			),
			'required'    => array( 'portfolio_enable_desc', 'equals', '1' ),
		),
		array(
			'title'         => 'Description Active: Image Opacity',
			'id'            => 'portfolio_description_image_fade_opacity',
			'desc'          => 'The opacity of the image when a description is active',
			'type'          => 'slider',
			'min'           => '0',
			'max'           => '100',
			'step'          => '1',
			'default'       => '40',
			'js'            => 'portfolio.fade_opacity',
			'apply_filters' => 'decimal',
			'css'           => array(
				'.layout--regular .display--desc .pfentry__image img' =>
					array(
						'opacity' => '%%value%%',
					)
			),
			'required'      => array( 'portfolio_enable_desc', 'equals', '1' ),
		),

	),
);

$sections[] = array(
	'title'  => 'Gallery',
	'icon'   => 'el-icon-photo',
	'fields' => array(

		array(
			'id'      => 'gallery_enable',
			'js'      => 'gallery.enable',
			'title'   => 'Enable Gallery',
			'default' => '1',
			'type'    => 'switch',
		),
		array(
			'id'       => 'gallery_root',
			'js'       => 'gallery.root_url',
			'title'    => 'Gallery Return Link',
			'desc'     => 'Where to go when closing the gallery - if previous URL not found in browser history',
			'type'     => 'select',
			'data'     => 'page',
			'required' => array(
				array( 'gallery_enable', 'equals', '1' ),
			),
		),
		array(
			'id'       => 'gallery_fit',
			'js'       => 'gallery.fit',
			'title'    => "Image Fit Style",
			'desc'     => 'You can choose the way images are fitted onto the screen.
			You can read more about these options and see an illustrated graphic how they work in <a href="http://help.themevillage.net/knowledgebase/4-ways-to-fit-an-image-into-gallery/">our knowledgebase.</a>',
			'type'     => 'select',
			"default"  => "cover",
			'options'  => TV_Parser::selectify( array( 'none', 'contain', 'cover', 'scaledown' ) ),
			'required' => array(
				array( 'gallery_enable', 'equals', '1' ),
			),
		),
		array(
			'id'       => 'gallery_force_sidebar',
			'js'       => 'gallery.force_sidebar',
			'title'    => 'Force Enable Sidebar',
			'desc'     => 'Gallery image descriptions are going to be visible without an option to hide them',
			'default'  => '0',
			'type'     => 'switch',
			'required' => array(
				array( 'gallery_enable', 'equals', '1' ),
			),
		),
		array(
			'id'       => 'gallery_transition',
			'js'       => 'gallery.transition',
			'title'    => "Gallery Image Transition Style",
			'type'     => 'select',
			"default"  => "slide",
			'options'  => TV_Parser::selectify( array( "slide", "crossfade", "dissolve" ) ),
			'required' => array(
				array( 'gallery_enable', 'equals', '1' ),
			),
		),
		array(
			'id'       => 'gallery_loop',
			'js'       => 'gallery.loop',
			'title'    => 'Loop Gallery',
			'desc'     => '<em>Note: also enables navigating to the last image by going left when gallery is opened</em>',
			'subtitle' => 'When the last image is reached in the gallery - return to start ?',
			'default'  => '0',
			'type'     => 'switch',
			'required' => array(
				array( 'gallery_enable', 'equals', '1' ),
			),

		),
		//-----------------------------------*/
		//  Section: Thumbnails
		//-----------------------------------*/
		array(
			'id'       => TV_Parser::make_int(),
			'type'     => 'section',
			'title'    => 'Gallery Thumbnails',
			'indent'   => true,
			'required' => array(
				array( 'gallery_enable', 'equals', '1' ),
			),
		),
		array(
			'id'       => 'gallery_enable_thumbnails',
			'js'       => 'gallery.thumbnails',
			'title'    => 'Display Thumbnails in Gallery ?',
			'default'  => '1',
			'type'     => 'switch',
			'required' => array(
				array( 'gallery_enable', 'equals', '1' ),
			),
		),
		array(
			'id'       => 'gallery_force_thumbnails',
			'js'       => 'gallery.force_thumbnails',
			'title'    => 'Force Enable Thumnails',
			'desc'     => 'Gallery image thumbnails are going to be visible without an option to hide them',
			'default'  => '0',
			'type'     => 'switch',
			'required' => array(
				array( 'gallery_enable', 'equals', '1' ),
				array( 'gallery_enable_thumbnails', 'equals', '1' ),
			),
		),
		array(
			'id'       => 'gallery_enable_thumbnail_overlay',
			'js'       => 'gallery.thumbnails_overlay',
			'title'    => 'Overlay Thumbnails on Images',
			'default'  => '1',
			'type'     => 'switch',
			'required' => array(
				array( 'gallery_enable', 'equals', '1' ),
				array( 'gallery_enable_thumbnails', 'equals', '1' ),
			),

		),
		array(
			'id'       => 'gallery_thumbnails_height',
			'js'       => 'gallery.thumbnails_height',
			'title'    => 'Thumbnail Height ( in pixels )',
			'type'     => 'slider',
			"default"  => "100",
			"min"      => "30",
			"step"     => "5",
			"max"      => "250",
			'required' => array(
				array( 'gallery_enable', 'equals', '1' ),
				array( 'gallery_enable_thumbnails', 'equals', '1' ),
			),
		),
		//-----------------------------------*/
		// Section: Detect Mouse Movement
		//-----------------------------------*/
		array(
			'id'       => TV_Parser::make_int(),
			'type'     => 'section',
			'title'    => 'Detect Mouse Movement',
			'indent'   => true,
			'required' => array(
				array( 'gallery_enable', 'equals', '1' ),
			),

		),
		array(
			'id'       => 'gallery_mouse_timeout',
			'js'       => 'gallery.mouse_timeout',
			'title'    => 'Detect Mouse Movement',
			'default'  => '1',
			'type'     => 'switch',
			'required' => array(
				array( 'gallery_enable', 'equals', '1' ),
			),

		),
		array(
			'id'       => 'gallery_mouse_timeout_ms',
			'js'       => 'gallery.mouse_timeout_ms',
			'title'    => 'Mouse Timeout Duration',
			'desc'     => 'Duration to keep mouse still before hiding all of the controls',
			'type'     => 'slider',
			"default"  => "900",
			"min"      => "400",
			"step"     => "50",
			"max"      => "4000",
			'required' => array( 'gallery_mouse_timeout', 'equals', '1' ),
		),
		//-----------------------------------*/
		// Autoplay
		//-----------------------------------*/
		array(
			'id'       => TV_Parser::make_int(),
			'type'     => 'section',
			'title'    => 'Autoplay',
			'indent'   => true,
			'required' => array(
				array( 'gallery_enable', 'equals', '1' ),
			),

		),
		array(
			'id'       => 'gallery_autoplay',
			'js'       => 'gallery.autoplay',
			'title'    => 'Enable Autoplay',
			'default'  => '0',
			'type'     => 'switch',
			'required' => array(
				array( 'gallery_enable', 'equals', '1' ),
			),

		),
		array(
			'id'       => 'gallery_autoplay_stop',
			'js'       => 'gallery.autoplay_stop',
			'title'    => 'Stop when slider is touched',
			'default'  => '1',
			'type'     => 'switch',
			'required' => array(
				array( 'gallery_enable', 'equals', '1' ),
				array( 'gallery_autoplay', 'equals', '1' ),
			),
		),
		array(
			'id'       => 'gallery_autoplay_duration',
			'js'       => 'gallery.autoplay_duration',
			'title'    => 'Autoplay: Slide Duration',
			'desc'     => 'How many microseconds show each slide for ?',
			'type'     => 'slider',
			"default"  => "3000",
			"min"      => "500",
			"step"     => "250",
			"max"      => "15000",
			'required' => array(
				array( 'gallery_enable', 'equals', '1' ),
				array( 'gallery_autoplay', 'equals', '1' ),
			),
		),
		//-----------------------------------*/
		// Social Sharing
		//-----------------------------------*/
		array(
			'id'       => TV_Parser::make_int(),
			'type'     => 'section',
			'title'    => 'Social Sharing',
			'indent'   => true,
			'required' => array(
				array( 'gallery_enable', 'equals', '1' ),
			),

		),
		array(
			'id'       => 'gallery_sharing',
			'js'       => 'gallery.networks',
			'type'     => 'checkbox',
			'title'    => "Active Social Networks",
			'mode'     => 'sortable',
			'desc'     => "Pick which social networks you want to use",
			'options'  => array(
				'googleplus' => 'Google+',
				'facebook'   => 'Facebook',
				'twitter'    => 'Twitter',
				'pinterest'  => 'Pinterest',
			),
			'default'  => array(
				'googleplus' => false,
				'facebook'   => false,
				'twitter'    => false,
				'pinterest'  => false,
			),
			'required' => array(
				array( 'gallery_enable', 'equals', '1' ),
			),
		),
		//-----------------------------------*/
		// Section: Gallery Description
		//-----------------------------------*/
		array(
			'id'       => TV_Parser::make_int(),
			'type'     => 'section',
			'title'    => 'Image Description',
			'indent'   => true,
			'required' => array(
				array( 'gallery_enable', 'equals', '1' ),
			),

		),
		array(
			'id'       => 'gallery_enable_sidebar',
			'title'    => 'Display Gallery Sidebar',
			'default'  => '1',
			'type'     => 'switch',
			'required' => array(
				array( 'gallery_enable', 'equals', '1' ),
			),
		),
		//-----------------------------------*/
		// Section: Gallery Colors
		//-----------------------------------*/
		array(
			'id'          => 'gallery_background_color',
			'type'        => 'color',
			'default'     => '',
			'validate'    => 'color',
			'transparent' => false,
			'css'         => array(
				'#gallery__stage' =>
					array(
						'background-color' => '%%value%%',
					)
			),
		),
		array(
			'id'          => 'gallery_sidebar_background_color',
			'type'        => 'color',
			'default'     => '',
			'validate'    => 'color',
			'transparent' => false,
			'css'         => array(
				'#gallery__sidebar' =>
					array(
						'background-color' => '%%value%%',
						'border-color'     => '%%value%%',
					)
			),
		),
		array(
			'id'          => 'gallery_sidebar_font_color',
			'type'        => 'color',
			'default'     => '',
			'validate'    => 'color',
			'transparent' => false,
			'css'         => array(
				'#gallery__sidebar, #gallery__sidebar .title ' =>
					array(
						'color' => '%%value%%',
					)
			),
		),

	) // close 'fields'
);


//-----------------------------------*/
// Tab: Colors
//-----------------------------------*/
$sections[] = array(
	'title'  => 'Style',
	'icon'   => 'el-icon-brush',
	'fields' => array(

		//-----------------------------------*/
		// Generic
		//-----------------------------------*/
		array(
			'id'     => TV_Parser::make_int(),
			'type'   => 'section',
			'title'  => 'Generic',
			'start'  => true,
			'indent' => true, // Indent all options below until the next 'section' option is set.

		),
		array(
			'id'          => 'body_background_color',
			'type'        => 'color',
			'default'     => '#FFFFFF',
			'validate'    => 'color',
			'transparent' => false,
			'css'         => array(
				'body, .js body, .stage' =>
					array(
						'background-color' => '%%value%%',
					)
			),
		),
		array(
			'id'          => 'default_font_color_scheme',
			'type'        => 'select',
			'options'     => array( "light-font" => "Light", "dark-font" => "Dark" ),
			'description' => 'Typically you will want to select dark font colors for light backgrounds, and light fonts for dark backgrounds. For example if your background color is black, select "Light Font". The default setting is "Dark Font" because the default background color is almost white.',
			'default'     => 'dark-font',
		),
		array(
			'id'          => 'site_fadein_color',
			'title'       => 'Site Fade In: Color',
			'desc'        => 'When window is refreshed, site is going to fade in from this color',
			'type'        => 'color',
			'default'     => '#000000',
			'validate'    => 'color',
			'transparent' => false,
			'css'         => array(
				'.js body' =>
					array(
						'background-color' => '%%value%%',
					)
			),
		),
		array(
			'id'       => 'site_fadein_duration',
			'title'    => 'Site Fade In: Duration',
			'subtitle' => 'In ms ( 1000ms equals 1 second )',
			'type'     => 'slider',
			'min'      => '0',
			'max'      => '1200',
			'step'     => '25',
			'default'  => '600',
			'css'      => array(
				'.js body' =>
					array(
						'-moz-transition-duration'    => '%%value%%ms;',
						'-ms-transition-duration'     => '%%value%%ms;',
						'-webkit-transition-duration' => '%%value%%ms;',
						'transition-duration'         => '%%value%%ms;',
					)
			),
		),
		//-----------------------------------*/
		// Fonts & Links
		//-----------------------------------*/
		array(
			'id'     => TV_Parser::make_int(),
			'type'   => 'section',
			'title'  => '"Dark Font" Scheme Text and Link Colors',
			'start'  => true,
			'indent' => true, // Indent all options below until the next 'section' option is set.

		),
		array(
			'id'          => 'dark_font_color',
			'type'        => 'color',
			'default'     => '#161616',
			'validate'    => 'color',
			'transparent' => false,
			'css'         => array(
				'body, .light-font, .light-font .dark-font' =>
					array(
						'color' => '%%value%%',
					)
			),
		),
		array(
			'id'          => 'dark_link_color',
			'type'        => 'color',
			'default'     => '#161616',
			'validate'    => 'color',
			'transparent' => false,
			'css'         => array(
				'a, .light-font a, .light-font .dark-font a' =>
					array(
						'color' => '%%value%%',
					)
			),
		),
		array(
			'id'          => 'dark_link_color_hover',
			'type'        => 'color',
			'default'     => '#101010',
			'validate'    => 'color',
			'transparent' => false,
			'css'         => array(
				'a:hover, .light-font a:hover, .light-font .dark-font a:hover' =>
					array(
						'color' => '%%value%%',
					)
			),
		),
		array(
			'id'     => TV_Parser::make_int(),
			'type'   => 'section',
			'title'  => '"Light Font" Scheme Text and Link Colors',
			'start'  => true,
			'indent' => true, // Indent all options below until the next 'section' option is set.

		),
		array(
			'id'          => 'light_font_color',
			'type'        => 'color',
			'default'     => '#fcfcfc',
			'validate'    => 'color',
			'transparent' => false,
			'css'         => array(
				'.light-font, .dark-font .light-font' =>
					array(
						'color' => '%%value%%',
					)
			),
		),
		array(
			'id'          => 'light_link_color',
			'type'        => 'color',
			'default'     => '#e8e8e8',
			'validate'    => 'color',
			'transparent' => false,
			'css'         => array(
				'.light-font a, .dark-font .light-font a' =>
					array(
						'color' => '%%value%%',
					)
			),
		),
		array(
			'id'          => 'light_link_color_hover',
			'type'        => 'color',
			'default'     => '#e8e8e8',
			'validate'    => 'color',
			'transparent' => false,
			'css'         => array(
				'.light-font a:hover, .dark-font .light-font a:hover' =>
					array(
						'color' => '%%value%%',
					)
			),
		),
		//-----------------------------------*/
		// Widgets
		//-----------------------------------*/

		array(
			'id'     => TV_Parser::make_int(),
			'type'   => 'section',
			'title'  => 'Shortcodes',
			'start'  => true,
			'indent' => true, // Indent all options below until the next 'section' option is set.
		),
		array(
			'id'          => 'text_widget_background_color',
			'type'        => 'color',
			'default'     => '#161616',
			'validate'    => 'color',
			'transparent' => false,
			'css'         => array(
				'#secondary .widget_text'               =>
					array(
						'background-color' => '%%value%%',
					),
				'#secondary .widget_text__image .arrow' =>
					array(
						'border-bottom-color' => '%%value%%',
					)
			),
		),
		array(
			'id'            => 'pricing_table_background',
			'type'          => 'color',
			'default'       => '#161616',
			'validate'      => 'color',
			'transparent'   => false,
			'apply_filters' => 'rgba',
			'css'           => array(
				'.village-pricing' =>
					array(
						'background-color' => '%%value%%',
					),
			),
		),
		array(
			'id'      => 'pricing_table_background_opacity',
			'type'    => 'slider',
			"default" => "60",
			"min"     => "1",
			"step"    => "1",
			"max"     => "100",
		),
		array(
			'id'          => 'pricing_table_price_background',
			'type'        => 'color',
			'default'     => '#FFFFFF',
			'validate'    => 'color',
			'transparent' => false,
			'css'         => array(

				'.village-pricing .village-pricing__price'         =>
					array(
						'background-color' => '%%value%%',
					),
				'.village-pricing .village-pricing__price::before' =>
					array(
						'border-bottom-color' => '%%value%%'
					),
			),
		),
		array(
			'id'          => 'pricing_table_text_color',
			'type'        => 'color',
			'default'     => '#FFFFFF',
			'validate'    => 'color',
			'transparent' => false,
			'css'         => array(
				'.village-pricing .village-pricing__item, .village-pricing .village-pricing__title' =>
					array(
						'color' => '%%value%%',
					),
			),
		),
		array(
			'id'          => 'pricing_table_price_text_color',
			'type'        => 'color',
			'default'     => '#161616',
			'validate'    => 'color',
			'transparent' => false,
			'css'         => array(
				'.village-pricing .village-pricing__price' =>
					array(
						'color' => '%%value%%',
					),
			),
		),
		array(
			'id'          => 'form_color',
			'title'       => 'Form Border Color',
			'type'        => 'color_rgba',
			'default'     => array(
				'color' => '#929611',
				'alpha' => '1.0',
			),
			'validate'    => 'colorrgba',
			'transparent' => false,
			'css'         => array(
				'input[type=text], input[type=email], input[type=search], textarea' =>
					array(
						'border-color' => '%%value%%',
					),
			),
		),
		array(
			'id'          => 'form_color_active',
			'title'       => 'Form Border Color: Active',
			'type'        => 'color_rgba',
			'default'     => array(
				'color' => '#929611',
				'alpha' => '0.75',
			),
			'validate'    => 'colorrgba',
			'transparent' => false,
			'css'         => array(
				'input[type=text]:active, input[type=text]:focus, input[type=email]:active, input[type=email]:focus, input[type=search]:active, input[type=search]:focus, textarea:active, textarea:focus' =>
					array(
						'border-color' => '%%value%%',
					),
			),
		),
		array(
			'id'          => 'blockquote_background',
			'type'        => 'color_rgba',
			'default'     => array(
				'color' => '#929611',
				'alpha' => '1.0',
			),
			'validate'    => 'colorrgba',
			'transparent' => false,
			'css'         => array(
				'blockquote' =>
					array(
						'background-color' => '%%value%%',
					),
			),
		),
		array(
			'id'          => 'blockquote_text',
			'type'        => 'color_rgba',
			'default'     => array(
				'color' => '#FFFFFF',
				'alpha' => '1.0',
			),
			'validate'    => 'colorrgba',
			'transparent' => false,
			'css'         => array(
				'.light-font blockquote, blockquote' =>
					array(
						'color' => '%%value%%',
					),
			),
		),
		//-----------------------------------*/
		// Buttons
		//-----------------------------------*/
		array(
			'id'     => TV_Parser::make_int(),
			'type'   => 'section',
			'title'  => 'Buttons',
			'start'  => true,
			'indent' => true, // Indent all options below until the next 'section' option is set.
		),
		array(
			'id'          => 'dark_font_button_background',
			'title'       => 'Dark Font: Button Background',
			'type'        => 'color',
			'default'     => '#0d0d0d',
			'validate'    => 'color',
			'transparent' => false,
			'css'         => array(
				'.village-button__wrapper:hover::before, .dark-font .village-button__wrapper:hover::before, .light-font .dark-font .village-button__wrapper:hover::before ' =>
					array(
						'background-color' => '%%value%%',
					),
				'.village-button__wrapper, .scroll-note .village-button__wrapper, .dark-font .village-button__wrapper, .light-font .dark-font .village-button__wrapper'     =>
					array(
						'border-color' => '%%value%%',
					)
			),
		),
		array(
			'id'          => 'dark_font_button_color',
			'title'       => 'Dark Font: Button Text Color',
			'type'        => 'color',
			'default'     => '#fcfcfc',
			'validate'    => 'color',
			'transparent' => false,
			'css'         => array(
				'.village-button__wrapper:hover .village-button__inner, .dark-font .village-button__wrapper:hover .village-button__inner, .light-font .dark-font .village-button__wrapper:hover .village-button__inner' =>
					array(
						'color' => '%%value%%',
					),
			),
		),
		array(
			'id'          => 'light_font_button_background',
			'title'       => 'Light Font: Button Background',
			'type'        => 'color',
			'default'     => '#fcfcfc',
			'validate'    => 'color',
			'transparent' => false,
			'css'         => array(
				'.light-font .village-button__wrapper:hover::before, .dark-font .light-font .village-button__wrapper:hover::before' =>
					array(
						'background-color' => '%%value%%',
					),
				'.light-font .village-button__wrapper, .dark-font .light-font .village-button__wrapper'                             =>
					array(
						'border-color' => '%%value%%',
					)
			),
		),
		array(
			'id'          => 'light_font_button_color',
			'title'       => 'Light Font: Button Text Color',
			'type'        => 'color',
			'default'     => '#0d0d0d',
			'validate'    => 'color',
			'transparent' => false,
			'css'         => array(
				'.light-font .village-button__wrapper:hover .village-button__inner, .dark-font .light-font .village-button__wrapper:hover .village-button__inner ' =>
					array(
						'color' => '%%value%%',
					),
			),
		),


	),
);


$sections[] = array(
	'title'  => 'Fullscreen Slider',
	'icon'   => 'el-icon-repeat',
	'fields' => array(


		array(
			'id'          => 'slider_animation_speed',
			'js'          => 'slider.animation_speed',
			'title'       => 'Fade Duration',
			'description' => "Value in miliseconds. 1 second equals 1000 miliseconds, so if you want for 2,3s - you need to multiply that by 1000 ( which is 2300 )",
			'type'        => 'slider',
			"default"     => "1300",
			"min"         => "0",
			"step"        => "50",
			"max"         => "3000",
		),
		array(
			'id'          => 'slider_duration',
			'js'          => 'slider.duration',
			'title'       => 'Slide Duration',
			'description' => "Value in miliseconds. 1 second equals 1000 miliseconds, so if you want for 2,3s - you need to multiply that by 1000 ( which is 2300 )",
			'type'        => 'slider',
			"default"     => "3300",
			"min"         => "500",
			"step"        => "100",
			"max"         => "10000",
		),
		array(
			'id'      => 'slider_animation',
			'js'      => 'slider.animation',
			'title'   => 'Slider Transition type',
			'type'    => 'select',
			'options' => TV_Parser::selectify( array( "fade", "slide" ) ),
			'default' => 'fade',
		),
		array(
			'id'          => 'slider_animation_easing',
			'js'          => 'slider.animation_easing',
			'title'       => 'Animation Easing when showing content',
			'description' => 'You can find more information <a href="http://easings.net/">about easing here</a>. Various easing and animation style, and overall timing combinations lets you achieve diferent effects.',
			'type'        => 'select',
			'options'     => array_combine( $easing_list, $easing_list ),
			'default'     => 'easeInOutQuad',
		),
	),


);


$sections[] = array(
	'title'  => __( 'Scroll Settings', 'village' ),
	'icon'   => 'el-icon-resize-horizontal',
	'fields' => array(),
);

//-----------------------------------*/
// Sub Section: Scroll Notification
//-----------------------------------*/
$sections[] = array(
	'title'      => __( 'Scroll Notification', 'village' ),
	'subsection' => true,
	'fields'     => array(
		array(
			'id'      => 'scroll_note_enable',
			'js'      => 'scroll_notification.enable',
			'title'   => 'Enable Scroll Notification',
			'desc'    => 'If your visitors are tech-savvy - they might we able to figure out how to navigate your site on their own :)',
			'type'    => 'switch',
			'default' => 1,
		),
		array(
			'id'       => 'scroll_note_title',
			'title'    => 'Scroll Title',
			'type'     => 'text',
			'default'  => '',
			'required' => array( 'scroll_note_enable', 'equals', '1' ),
		),
		array(
			'id'       => 'scroll_note_content',
			'title'    => 'Scroll Message',
			'type'     => 'textarea',
			'default'  => '',
			'required' => array( 'scroll_note_enable', 'equals', '1' ),
		),
		array(
			'id'       => 'scroll_note_ok',
			'title'    => 'Scroll "Ok" Button Text',
			'type'     => 'text',
			'default'  => '',
			'required' => array( 'scroll_note_enable', 'equals', '1' ),
		),
		array(
			'id'       => 'scroll_note_theme',
			'title'    => 'Scroll Note Theme',
			'type'     => 'select',
			'default'  => 'light',
			'options'  => array(
				'light' => 'Light',
				'dark'  => 'Dark',
			),
			'required' => array( 'scroll_note_enable', 'equals', '1' ),

		),
		array(
			'id'       => TV_Parser::make_int(),
			'type'     => 'section',
			'title'    => 'Advanced',
			'indent'   => true, // Indent all options below until the next 'section' option is set.
			'required' => array( 'scroll_note_enable', 'equals', '1' ),
		),
		array(
			'id'      => 'scroll_note_cookies',
			'title'   => 'Enable Scroll Notification Cookies',
			'js'      => 'scroll_notification.enable_cookies',
			'desc'    => 'Without cookies you\'re might annoy your visitors by telling them to scroll every time they open the site...',
			'type'    => 'switch',
			'default' => 1,
		),
		array(
			'id'       => 'scroll_note_cookies_count',
			'title'    => 'Times to Display the notification',
			'js'       => 'scroll_notification.times',
			'desc'     => 'This is going to be reset when the cookie expires...',
			'type'     => 'slider',
			'min'      => '1',
			'max'      => '20',
			'step'     => '1',
			'default'  => '2',
			'required' => array(
				array( 'scroll_note_cookies', 'equals', '1' ),
				array( 'scroll_note_enable', 'equals', '1' ),
			)
		),
		array(
			'id'       => 'scroll_note_cookies_exp',
			'js'       => 'scroll_notification.expires',
			'title'    => 'Cookie Expires after',
			'type'     => 'select',
			'options'  => array(
				1   => '1 Day',
				3   => '3 Days',
				7   => '7 Days',
				30  => '30 Days',
				90  => '90 Days',
				365 => '365 Days',
			),
			'default'  => 30,
			'required' => array(
				array( 'scroll_note_cookies', 'equals', '1' ),
				array( 'scroll_note_enable', 'equals', '1' ),
			)
		),

	)
);

//-----------------------------------*/
// Tab: Scroll Settings
//-----------------------------------*/
$sections[] = array(
	'title'      => __( 'Scrollbar Style', 'village' ),
	//	'icon' => 'el-icon-resize-horizontal',
	'subsection' => true,
	'fields'     => array(

		array(
			'id'         => 'scrollbar_initial_opacity',
			'title'      => 'Scrollbar initial opacity',
			'type'       => 'slider',
			"default"    => "0.5",
			"min"        => "0.05",
			"step"       => "0.01",
			"max"        => "1",
			"resolution" => 0.01,
			'css'        => array(
				'.iScrollHorizontalScrollbar, .iScrollVerticalScrollbar' =>
					array(
						'opacity' => '%%value%%',
					),
			),
		),
		array(
			'id'          => 'scroll_background',
			'title'       => 'Scrollbar Background Color',
			'type'        => 'color_rgba',
			'transparent' => false,
			'default'     => array(
				'color' => '#000000',
				'alpha' => '0.25'
			),
			'validate'    => 'colorrgba',
			'css'         => array(
				'.iScrollHorizontalScrollbar.iScrollLoneScrollbar, .iScrollVerticalScrollbar.iScrollLoneScrollbar' =>
					array(
						'background-color' => '%%value%%',
					),
			),
		),
		array(
			'id'          => 'scroll_foreground',
			'title'       => 'Indicator Color',
			'type'        => 'color_rgba',
			'transparent' => false,
			'default'     => array(
				'color' => '#000000',
				'alpha' => '0.25'
			),
			'validate'    => 'colorrgba',
			'css'         => array(
				'.iScrollHorizontalScrollbar .iScrollIndicator, .iScrollVerticalScrollbar .iScrollIndicator' =>
					array(
						'background-color' => '%%value%%',
					),
			),
		),
		array(
			'id'      => 'scrollbar_width',
			'title'   => 'Vertical Scrollbar: Width',
			'desc'    => '<em>Default: 12</em>',
			'type'    => 'slider',
			"default" => "12",
			"min"     => "4",
			"step"    => "1",
			"max"     => "100",
			'css'     => array(
				'.iScrollVerticalScrollbar' =>
					array(
						'width' => '%%value%%px',
					),
			),
		),
		array(
			'id'      => 'scrollbar_indicator_width',
			'title'   => 'Vertical Scrollbar: Indicator Width',
			'desc'    => 'Should be set to "Vertical Scrollbar: Width - 6px". <em>By default set to 6</em>',
			'type'    => 'slider',
			"default" => "6",
			"min"     => "4",
			"step"    => "1",
			"max"     => "100",
			'css'     => array(
				'.iScrollVerticalScrollbar .iScrollIndicator' =>
					array(
						'width' => '%%value%%px',
					),
			),
		),
		array(
			'id'      => 'scrollbar_height',
			'title'   => 'Horizontal Scrollbar: Height',
			'type'    => 'slider',
			"default" => "8",
			"min"     => "4",
			"step"    => "1",
			"max"     => "100",
			'css'     => array(
				'.iScrollHorizontalScrollbar' =>
					array(
						'height' => '%%value%%px',
					),
			),
		),

	)
);


$sections[] = array(
	'title'      => __( 'Advanced Settings', 'village' ),
	'subsection' => true,
	'fields'     => array(


		array(
			'id'    => TV_Parser::make_int(),
			'type'  => 'info',
			'title' => 'Advanced Scroll Settings',
			'style' => 'warning',
			'desc'  => "Only use if you really know what you're doing or somebody who does told you to. These settings are going to modify the user experience when scrolling throughout the site.",
		),
		array(
			'id'      => 'hs_scrollbar',
			'js'      => 'scroll.hs_scrollbar',
			'title'   => 'Enable Horizontal Scrollbar',
			'default' => '1',
			'type'    => 'switch',
		),
		array(
			'id'      => 'keyboard_scroll',
			'js'      => 'scroll.keyboard_scroll',
			'title'   => 'Enable Keyboard Scroll',
			'default' => '1',
			'type'    => 'switch',
		),
		array(
			'id'       => 'scroll_snapping',
			'js'       => 'scroll.snapping',
			'title'    => 'Enable Scroll Snapping',
			'subtitle' => "Option added only in case you want to bring back Scroll Snapping. We strongly recommend that you don't enable this option, as it makes for very poor user experience.",
			'default'  => '0',
			'type'     => 'switch',
		),
		array(
			'id'      => 'scroll_speedX',
			'js'      => 'scroll.speedX',
			'default' => 30,
			'type'    => 'slider',
			"min"     => "5",
			"step"    => "5",
			"max"     => "250",
		),
		array(
			'id'      => 'scroll_speedY',
			'js'      => 'scroll.speedY',
			'default' => 30,
			'type'    => 'slider',
			"min"     => "5",
			"step"    => "5",
			"max"     => "250",
		),
	)
);


/*---------------------------*/
/*   Advanced Modification
/*---------------------------*/
$sections[] = array(
	'title'  => 'Advanced',
	'icon'   => 'el-icon-wrench',
	'fields' => array(

		array(
			'id'       => 'load_kamilla_fonts',
			'type'     => 'switch',
			'title'    => "Load default theme fonts?",
			'subtitle' => "Turn this off if you're using your own fonts.",
			"default"  => 1,
		),
		array(
			"id"       => "custom_css",
			"title"    => 'Custom CSS',
			'mode'     => 'css',
			'theme'    => 'monokai',
			'subtitle' => 'Quickly add some CSS the theme. Use with caution, only if you know what you are doing.',
			'type'     => 'ace_editor',
			'validate' => 'css',
		),
		array(
			'id'       => 'enable_pjax',
			'js'       => 'enable_pjax',
			"title"    => 'Enable Theme AJAX',
			'subtitle' => 'When disabled, theme AJAX Page Loading and Smooth Transitions are going to be removed. Disable if you have problems with plugin compitability',
			'default'  => '1',
			'type'     => 'switch',
		),
		array(
			'id'       => 'custom_disable_pjax',
			'js'       => 'ignore_pjax',
			"title"    => 'Custom Selectors on which to disable AJAX',
			'subtitle' => 'You can add your own selectors to dynamically disable PJAX on your content.',
			'validate' => 'no_html',
			'type'     => 'multi_text',
			'required' => array(
				array( 'enable_pjax', 'equals', '1' ),
			)
		),
		array(
			'id'     => TV_Parser::make_int(),
			'type'   => 'section',
			'title'  => 'Modify Portfolio URLs',
			'start'  => true,
			'indent' => true, // Indent all options below until the next 'section' option is set.

		),
		array(
			'id'    => TV_Parser::make_int(),
			'type'  => 'info',
			'style' => 'warning',
			'title' => '',
			'desc'  => __( '<h3 style="color: black;"">Warning!</h3>
					<strong>Modifying the URLs on a live site will break your existing links! </strong><br>
					After making changes, be sure to <a href="http://help.themevillage.net/knowledgebase/reset-permalinks" target="_BLANK">reset your permalinks</a><br>
					You\'re responsible for clearing any caching, setting up redirects if needed, etc.
					 to make the site work properly after these settings have been changed. <br>
					 Now you can click "Yes, I want to modify URLs" to enable URL modification.
			', 'village' ),
		),
		array(
			'id'      => 'allow_url_modification',
			'type'    => 'switch',
			'title'   => "Still want to modify URLs ?",
			"default" => 0,
			'on'      => "Yes, I want to modify URLs",
			'off'     => "Don't change the URLs",
		),
		array(
			'id'       => 'portfolio_slug',
			'type'     => 'text',
			'title'    => "Portfolio Slug",
			'required' => array( 'allow_url_modification', 'equals', array( '1' ) ),
			'desc'     => '
				When <a href="http://help.themevillage.net/knowledgebase/how-to-create-pretty-looking-permalinks/" target="_BLANK">pretty permalinks</a> are enabled,
				URL for your portfolio entries is going to look like ' . get_site_url() . '/{slug}/your-work-name',
			"default"  => "portfolio",
		),
		array(
			'id'       => 'taxonomy_slug',
			'type'     => 'text',
			'title'    => "Portfolio Taxonomy Slug",
			'required' => array( 'allow_url_modification', 'equals', array( '1' ) ),
			'desc'     => '
				When <a href="http://help.themevillage.net/knowledgebase/how-to-create-pretty-looking-permalinks/" target="_BLANK">pretty permalinks</a> are enabled,
				URL for listing a specific category of portfolio entries is going look like ' . get_site_url() . '/{slug}/',
			"default"  => "projects",
		),


	),

);


/**
 * <<< Theme Options: End >>>
 */


//-----------------------------------*/
// Setting Up Redux Framework:
//-----------------------------------*/
$theme_data = wp_get_theme();

$args['display_name']    = $theme_data->get( 'Name' );
$args['display_version'] = $theme_data->get( 'Version' );
$args['menu_title']      = __( "Theme Options" );
// Disable Redux CSS Output
$args['output'] = false;

$args['share_icons'][] = array(
	'url'   => 'http://twitter.com/Theme_Village',
	'title' => 'Follow us on Twitter',
	'icon'  => 'el el-twitter'
);
$args['share_icons'][] = array(
	'url'   => 'http://www.facebook.com/themevillage.net',
	'title' => 'Like us on Facebook',
	'icon'  => 'el el-facebook'
);
// global $Theme_Village_Options;
$args['opt_name']  = Village::$key;
$args['page_slug'] = 'cameron_options';


$parsed_sections = TV_Parser::parse_sections( $sections );
do_action( 'before_redux_setup', $parsed_sections );

$Theme_Village_Options = new ReduxFramework( $parsed_sections, $args, $tabs );


// remove_action( 'init', array( $Theme_Village_Options, "_tracking" ) , 3 );

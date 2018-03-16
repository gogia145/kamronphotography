<?php

// Prevent NextGen Gallery from Breaking the theme
define('NGG_DISABLE_RESOURCE_MANAGER', TRUE); 

// Require Once, Require in the main template directory
$template_directory = get_template_directory();
// Village Helpers, etc.

require_once $template_directory . '/inc/portfolio/register_portfolio.php';
require_once $template_directory . '/inc/village/core.php';
require_once $template_directory . '/inc/widgets/text.php';

if ( get_theme_mod( '__THEME_UPGRADED', false ) !== true ) {
	require_once $template_directory . '/inc/acf/Migrate_Kameron.php';    
}
if ( class_exists( 'acf_field' ) ) {
	require_once $template_directory . '/inc/acf/ACF_Includes.php';
}
 
require_once $template_directory . '/inc/gallery/gallery_functions.php'; 

if( ! class_exists( 'Village_Gallery_Data') ) {
	require_once $template_directory . '/inc/gallery/Village_Gallery_Data.php';   	
}


/**
 * Custom template tags for this theme.
 */
require $template_directory . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require $template_directory . '/inc/extras.php';


// Disable Contact Form 7 AJAX:
remove_action( 'wp_enqueue_scripts', 'wpcf7_enqueue_scripts' );

if( ! function_exists('remove_wpcf_pjax') ) {
	function remove_wpcf_pjax( $url ) {
		return str_replace('pjax=true', '', $url);
	}
	add_filter('wpcf7_form_action_url', 'remove_wpcf_pjax');
}

/* -----------------------------------*/
/* 		Register Widget Areas
/* -----------------------------------*/
if ( !function_exists( "village_widgets_init" ) ) {
	function village_widgets_init() {

		register_sidebar( array(
				'name'          => __( 'Sidebar', 'village' ),
				'id'            => 'sidebar-1',
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			) );

		register_sidebar( array(
				'name'          => __( 'Menu Widget Area', 'village' ),
				'id'            => 'menu-widgets',
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			) );

	}
	add_action('init', 'village_widgets_init');
}





/* -----------------------------------*/
/* 	    Enqueue Scripts and Styles
/* -----------------------------------*/
if ( !function_exists( "village_scripts" ) ) {
	function village_scripts() {

		/* -----------------------------------*/
		/* 		Register / Deregister
		/* -----------------------------------*/
		// Register Fonts
		wp_register_style( 'bebas-font', get_template_directory_uri() . '/css/font.css' );

		// We're fetching our own style
		wp_register_style( 'style', get_template_directory_uri() . '/css/app.css' );

		// Contact Form 7
		wp_dequeue_style( 'contact-form-7' );



		/* -----------------------------------*/
		/* 		Enqueue
		/* -----------------------------------*/
		wp_enqueue_style( 'style' );

		// Let the user disable loading Camilla fonts if she wants her own fonts
		if ( Village::is_enabled( "load_kamilla_fonts", true ) ){
			wp_enqueue_style( 'bebas-font' );
		}

		// All compressed libraries are found in in `/assets/javascript/libs/`
		wp_enqueue_script(
			'compressed-libs',
			get_template_directory_uri() . '/js/libs.js',
			array( 'jquery' ),
			null,
			true // In Footer ?
		);

		// Main JavaScript file (Compressed coffeescript from `/assets/javascript/app.coffee/` )
		wp_enqueue_script(
			'app',
			get_template_directory_uri() . '/js/app.js',
			array( 'jquery', 'underscore' ),
			null,
			true // In Footer ?
		);

		wp_localize_script( 'app', "LANG", array(
				'show_all' => __( 'Show All', 'village' ),
			) );

		/* -----------------------------------*/
		/* 		WP Comments
		/* -----------------------------------*/
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' , $in_footer = true );
		}
	}

	add_action( 'wp_enqueue_scripts', 'village_scripts', 1000 );
}


/**
 * Enqueue Child Theme Stylesheet, preferably only when a child theme is active
 */
if ( !function_exists( "village_scripts_child" ) ) {
	function village_scripts_child() {
		wp_register_style( 'child-style', get_stylesheet_directory_uri() . '/style.css' );
		wp_enqueue_style( 'child-style' );
	}

	if ( is_child_theme() ) {
		add_action( 'wp_enqueue_scripts', 'village_scripts_child', 1001 );
	}
}


add_image_size( 'portfolio_tall_thumbnail',
				null,
				Village::get_theme_mod( "portfolio_thumb_height", 560 ),
				$crop = false );

add_image_size( 'portfolio_mini_thumbnail',
               	null,
               	Village::get_theme_mod('gallery_thumbnail_height', 100),
				$crop = false);



if ( !function_exists( "dotdotdot" ) ){
	function dotdotdot() { return "..."; }
	add_filter( 'excerpt_more', 'dotdotdot' );
}

if ( ! function_exists( "modify_popular_posts") ) {
	function modify_popular_posts( $html ) {

		$html = str_replace('<span class="wpp-date">posted on', '<span class="wpp-date"><i class="icon-calendar-empty"></i>', $html);

		return $html;
	}

	add_filter('wpp_html', 'modify_popular_posts');
}



//-----------------------------------*/
// Unregister Pure Portfolio Taxonomies
//-----------------------------------*/

function unregister_portfolio_taxonomies(){
	if ( ! Village::is_enabled('enable_project_types', false) ) {
		register_taxonomy( 'project-types', array() );
	}
	register_taxonomy( 'skills', array() );
}
add_action('init', 'unregister_portfolio_taxonomies');

//-----------------------------------*/
// No fallback for portfolio images if none
//-----------------------------------*/
add_filter('village_portfolio_project_images_fallback', '__return_false');


function village_images_key() { return Village::$key . "images"; }
add_filter('village_portfolio_meta_key', 'village_images_key');

//-----------------------------------*/
// Default Content Width
//-----------------------------------*/
if ( ! isset( $content_width ) ) $content_width = 1000;



//-----------------------------------*/
// Maybe Functions
//-----------------------------------*/
// Theese functions replace the classic get_header(), get_sidebar() and get_footer()
// In order to dynamically not show the header, sidebar & footer
// Because they're explicitly unneeded with AJAX Requests

if ( ! function_exists( "maybe_get_footer" ) ) {
	function maybe_get_footer() {
		if ( isset( $_GET['pjax']) ) {
			return;
		} else {
			get_footer();
		}
	}
}
if ( ! function_exists( "maybe_get_header" ) ) {
	function maybe_get_header( $header = '' ) {
		if ( is_pjax() ) {
			return;
		} else {
			get_header( $header );
		}
	}
}

if ( ! function_exists( "maybe_get_sidebar" ) ) {
	/**
	 * Maybe get sidebar ?
	 * @param  (array) $args Wordpress Sidebar args
	 */
	function maybe_get_sidebar( $args = null ) {

		if ( is_pjax() ) {
			return;
		}

		if ( Village::get_theme_mod( "layout_style" ) === "layout--sidebar" ) {
			get_sidebar( $args );
		}
	}

}

if( ! function_exists( 'village_background') ) {
	function village_background( $class = "", $echo = true) {
		$css = Village::get_theme_mods( array('background_color', 'background_image' ) );

		if ( isset( $css['background_image'] ) ) {
			$css['background_image'] = "url('" . wp_get_attachment_url( $css['background_image'] ) . "')";
		}

		if ( ! is_array( $class ) ) {
			$class = explode(' ', $class);	
		}
		
		$style_preset = Village::get_theme_mods( array( 'style_preset' ) , null );
		$class = array_merge( $class, $style_preset );

		$el = array(
		            'style' => $css,
		            'class' => $class
		            );

		$el = apply_filters('village/content_background', $el);

		if ( $echo ) {
			Village::render_attributes( $el );	
		} else {
			return $el;
		}
		
	}
}



if( !function_exists( 'parse_gallery_cookies') ) {
	/**
	 * Parse Cookies for Galleries
	 * @return array of classes that should be set on the container
	 */
	function parse_gallery_cookies() {
		$out = array();

		# Read gallery sidebar satus cookies
		if( !empty( $_COOKIE['gallery'] ) ) {
			$gc = json_decode( stripslashes($_COOKIE['gallery']), ARRAY_A );

			if( is_array( $gc ) ) {
				
				array_push($out, 'init');

				# Sidebar
				if ( isset( $gc['sidebar'] ) && $gc['sidebar'] === false ) {
					array_push($out, 'is-full');
				}

				# Thumbnails
				// if ( isset( $gc['thumbs'] ) && $gc['thumbs'] === true ) {
				// 	array_push($out, 'show-thumbnails');
				// }

			}

		}


		return $out;
	}
}



if ( ! function_exists( "sanitize_html_classes" ) && function_exists( "sanitize_html_class" ) ) {
	/**
	 * sanitize_html_class works just fine for a single class
	 * Some times le wild <span class="blue hedgehog"> appears, which is when you need this function,
	 * to validate both blue and hedgehog,
	 * Because sanitize_html_class doesn't allow spaces.
	 *
	 * @uses   sanitize_html_class
	 * @param  (mixed: string/array) $class   "blue hedgehog goes shopping" or array("blue", "hedgehog", "goes", "shopping")
	 * @param  (mixed) $fallback Anything you want returned in case of a failure
	 * @return (mixed: string / $fallback )
	 */
	function sanitize_html_classes( $class, $fallback = null) {

		// Explode it, if it's a string
		if ( is_string( $class ) ) {
			$class = explode(" ", $class);
		} 


		if ( is_array( $class ) && count( $class ) > 0 ) {
			$class = array_map("sanitize_html_class", $class);
			return implode(" ", $class);
		}
		else { 
			return sanitize_html_class( $class, $fallback );
		}
	}
}




if( !function_exists( 'custom_home_url') ) {

	function custom_home_url() {

		$index_type = Village::get_theme_mod('index_type', false);

		if( $index_type !== false ) {

			// 1 = Wordpress Page ID
			// 0 = Custom URL
			if( (int) $index_type === 1 ) {

				$index_page_id = Village::get_theme_mod('index_page', false);

				if( $index_page_id ) {
					return get_permalink( $index_page_id );
				}

			} else {
				$index_page_url = Village::get_theme_mod( 'index_url', false );
				
				if( $index_page_url ) {
					return esc_url_raw( $index_page_url );
				}
				
			}
		}

		// If All else fails, return the regular home_url()
		return get_home_url( '/' );
	}
}


if( ! function_exists('kameron_maybe_disable_pjax') ) {
	
	/**
	 * This is how to utilize 'village_enable_pjax' filter
	 * We're disabling "fullscreen" templates to be laoded via AJAX
	 * @param  (bool) $enable
	 * @return (bool) false ( disable ) if template-fullscreen
	 */
	
	function kameron_maybe_disable_pjax( $enable ) {

		if( $enable === false ) { return false; }

		if( is_page_template( 'templates/page-fullscreen.php' ) ) {
			
			return false;
		}
		
		return $enable;
	}

	add_filter('village_enable_pjax', 'kameron_maybe_disable_pjax');
}




if( ! function_exists('parse_gallery_root_url') ) {
	/**
	 * This is how to utilize 'themevillage_javascript_variables' filter
	 * The "root_url" is actually a "Page ID" that we need to transform into an URL
	 * @param  (array) Village JavaScript options
	 * @return (array) Modified options
	 */
	function parse_gallery_root_url( $settings ) {
		// Check Permalink Structure
		global $wp_rewrite;
		
		if( isset( $settings['config']['gallery']['root_url'] ) && is_numeric( $settings['config']['gallery']['root_url'] ) ) {

			$root_url = get_permalink( $settings['config']['gallery']['root_url'] );

		} else {

			$root_url = custom_home_url('/');

		}

		$settings['config']['gallery']['root_url'] = $root_url;
		$settings['wp']['pretty_permalinks'] = ($wp_rewrite->permalink_structure != "");

		return $settings;
	}	
	
	add_filter('themevillage_javascript_variables', 'parse_gallery_root_url');
}



if( ! function_exists('is_pjax') ) {
	function is_pjax() {

		if( !empty( $_POST ) ) {
			return false;
		}

		if( isset( $_GET['pjax'] ) && 'true' === $_GET['pjax'] ) {
			return true;
		}

		return false;
	}
}	





//-----------------------------------*/
// Disable Jetpack Photon
//-----------------------------------*/
if ( class_exists( 'Jetpack' ) && method_exists('Jetpack', 'deactivate_module') && Jetpack::is_module_active('photon') ) {
	Jetpack::deactivate_module( 'photon' );
}


	



//-----------------------------------*/
// Change Password Form
//-----------------------------------*/
if( ! function_exists('village_change_password_form') ) {
	function village_change_password_form() {
	    global $post;
	    $label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );

	    if( get_post_type() === 'portfolio' ) {
	    	$title = esc_attr__( "Enter", "village" );
	    } else {
	    	$title = esc_attr__( "Submit", "village" );
	    }

	    $o = 

	    '<form class="password-form" action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post">
	    ' . __( "<span class=\"pwf__title\">This is a password protected area.</span> <span class=\"pwf__subtitle\">Please enter your password to continue.</span>" ) . '
		    <input name="post_password" id="' . $label . '" type="password" size="20" maxlength="20" />
		    <input type="submit" name="Submit" value="' . $title . '" />
	    </form>
	    ';
	    return $o;
	}
	add_filter( 'the_password_form', 'village_change_password_form' );
}



if( ! function_exists('change_private_title_format') ) {
	 
	function change_private_title_format( $format ) {
		if ( get_post_type() === 'portfolio' ) {
			return '%s' . '<span class="private">(' . _('Password Protected') . ')</span>' ;	
		}
		else {
			return $format;
		}
	    
	}

	add_filter( 'private_title_format', 'change_private_title_format' );
	add_filter( 'protected_title_format', 'change_private_title_format' );

}




/**
 * Remove canonical tag from wp_head for Portfolio Items
 * For better control over "social sharing thumbnails" ( facebook looks for canonical URL to determine thumbnails)
 */
if( ! function_exists('remove_canonical_portfolio') ) {
	function remove_canonical_portfolio() {
		if( get_post_type( get_the_ID() ) === 'portfolio' ) {
			remove_action('wp_head', 'rel_canonical');		
		}
	}
	add_action('wp_head', 'remove_canonical_portfolio', 5);
}









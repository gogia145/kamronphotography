<?php

function hex_to_rgb( $hex ) {
		$hex = str_replace("#", "", $hex);
		$color = array();
		
		if( is_string( $hex) && strlen($hex) == 6) {
			
			$color['r'] = hexdec(substr($hex, 0, 2));
			$color['g'] = hexdec(substr($hex, 2, 2));
			$color['b'] = hexdec(substr($hex, 4, 2));
			
			return $color;
		} else {
			return $hex;
		}
		
}
class Village_Filter_CSS_Values {	
	
	public static function decimal( $value, $option ) {
		return (float) $value / 100;
	}

	public static function rgba( $value, $option ) {

		$opacity = Village::get_theme_mod( $option . "_opacity", false );

		if ( $opacity != false && $opacity != 100 ) {
			
			$rgb = hex_to_rgb( $value );
			$alpha = (float) $opacity / 100;

			if ( is_array( $rgb ) ) {
				return "rgba(" . implode(",", $rgb) . "," . $alpha . ")";
			}

		}
		

		return $value;

	}

} // End Village_Filter_CSS_Values


class Village_CSS {

	static $css_string = "";
	/**
	 * Initialize Village CSS Function
	 */
	public static function init() {		
		
		// Advanced CSS Generation
		self::css_custom();

		// Generate CSS Based on Redux Config
		add_action( 'before_redux_setup', array( __CLASS__, "css_from_config" ), 10, 1 );

		// Add the generated CSS to wp_head
		add_action( 'wp_head', array( __CLASS__, "generate" ) );
	}


	/**
	 * Generate CSS From Redux Framework Config
	 * Attached with `before_redux_setup` action
	 * @param  (array) $sections Redux Sections
	 */
	public static function css_from_config( $sections ) {
		
		$out = array();

		foreach ($sections as $section) {
			foreach ( $section['fields'] as $field) {
				
				if ( isset( $field['css'] ) ) {
					$filters = ( isset( $field['apply_filters']) ) ? $field['apply_filters'] : false;
					self::$css_string .= self::css_from_option( $field['id'], $field['default'], $field['css'], $field['type'], $filters );
				}
			}
		}
	}


	/**
	 * Advanced CSS Calculations that were inconvenient to do on-the-fly
	 */
	private static function css_custom() {	

		$css = "";

		//-----------------------------------*/
		// Header Background Color
		//-----------------------------------*/
		$header_content = (int) Village::get_theme_mod( "header_width", 220 );
		$header_toggle_width = (int) Village::get_theme_mod( "header_toggle_width", 44 );

		if ( $header_content !== 220 || $header_toggle_width !== 44 ) {
			$header_width = $header_content + $header_toggle_width;

			$header_width = $header_width . "px";
			$header_content = $header_content . "px";
			$header_toggle_width = $header_toggle_width . "px";

			$css .= "


	#header, #header .header__content {
		width: $header_content;
	} 

	.header--toggleable #header {
		width: $header_width;
	}

	#header .header__toggle {
		width: $header_content;
	}

	.header--toggleable.touch #header .toggle {
		height: $header_toggle_width;
	}
	
	.header--toggleable #header .header__toggle {
		width: $header_toggle_width;
	}
	
	.layout--regular.header--toggleable #gallery {
		left: $header_toggle_width;
	}




	@media screen and (min-width: 768px) {
		.header--toggleable #header .header__hover-area {
			width: $header_width;
		}
	}


	@media screen and (max-width: 767px) {
		.header--toggleable #header,
		.header--toggleable #header .header__hover-area {
			width: $header_content;
		}
		
		.header--toggleable #header .header__toggle {
			height: $header_toggle_width;
			right: -$header_toggle_width;
		}
	}




			";

		}
		
		self::$css_string .= $css;

		$custom_css = Village::get_theme_mod("custom_css", false);
		
		if( $custom_css ) {
			self::$css_string .= $custom_css;
		}

	}

	private static function get_css_option( $option, $default_value ) {
		$value = Village::get_theme_mod( $option, $default_value );

		if( $value === $default_value ) { 
			return false;
		} else {
			return $value;
		}

	}

	private static function css_from_option( $option, $default_value, $css, $type, $filter = false ) {
		
		$value = self::get_css_option( $option, $default_value );
		$output = "";

		if(	$type === 'color_rgba' && isset( $value['color'] ) ) {
			
			$rgb = hex_to_rgb( $value['color'] );
			$alpha = $value['alpha'];

			if( is_array( $rgb ) && !empty( $alpha ) ) {
				$value = "rgba(" . implode(",", $rgb) . "," . $alpha . ")";
			}

		}
	
		if ( $value !== false ) {	
			
			if ( $filter !== false ) {
				$value = apply_filters( 'village_css_filter_' . $filter, $value, $option );
			}

			foreach ( $css as $selector => $properties ) {
				$output .= "{$selector} {";
				foreach ( $properties as $property => $value_template ) {	


					if( is_string( $value ) ) {
						$value = str_replace('%%value%%', $value, $value_template);
						$output .= "{$property}: {$value};";
					}
				}

				$output .= "} ";
			}
		}
		return $output;
	}


	public static function generate() {

		$out = '<style type="text/css">';
		$out .= self::$css_string;
		$out .= '</style>';

		$out = str_replace( "a a:hover", "a:hover", $out );
		$out = str_replace( "&gt;", ">", $out );
		echo  preg_replace( '/\s+/', ' ', $out );

	}



} // End Village_CSS










/**
 * Get a list of `Village_Filter_CSS_Values` methods and use them as Wordpress Filters
 */
$methods = get_class_methods( 'Village_Filter_CSS_Values' );
foreach ($methods as $method) {
	add_filter( 'village_css_filter_' . $method , array( 'Village_Filter_CSS_Values', $method), 10, 2 );
}

//-----------------------------------*/
// Initialize Village CSS
//-----------------------------------*/
Village_CSS::init();











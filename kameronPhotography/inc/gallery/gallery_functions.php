<?php
if( !function_exists( 'parse_gallery_cookies') ) {
	/**
	 * Parse Cookies for Galleries
	 * @return array of classes that should be set on the container
	 */
	function parse_gallery_cookies() {
		$out = array();

		# Read gallery sidebar satus cookies
		if( !empty( $_COOKIE['village_gallery'] ) ) {
			$gc = json_decode( stripslashes($_COOKIE['village_gallery']), ARRAY_A );

			if( is_array( $gc ) ) {
				
				// array_push($out, 'init');

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

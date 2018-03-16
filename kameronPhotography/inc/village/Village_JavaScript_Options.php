<?php 

function village_js_flipster( $array, $value ) {
	$out = array();
	$key = $array[0];
	array_shift( $array );
	
	if( count( $array ) > 0 ) {
		$out[ $key ] = village_js_flipster( $array, $value );	
	} else {
		$out[$key] = $value;
	}
	
	return $out;
}

class Village_JavaScript_Options extends Village {

	public static function init() {
		// __VILLAGE_VARS are quite important, give them a priority of 2
		add_action('wp_footer', array( __CLASS__, 'print_options' ), 2 );
		add_action('redux/options/' . self::$key . '/validate',  array( __CLASS__, "on_redux_save" ) );
	}

	public static function get( $values ) {
		$out = array();
		$truthy_fields = array('switch');
		$sections = $GLOBALS['Theme_Village_Options'] -> sections;
		
		foreach ($sections as $section) {
			foreach ($section['fields'] as $field) {
				
				// Is this field meant for JavaScript?
				if ( ! isset ( $field['js']) || $field['js'] == false ) {
					continue;
				}

				// Just to be sure, validate that the value exists
				if( isset( $values[ $field['id'] ] ) ) {
					$value = $values[ $field['id'] ];	
				} elseif( isset( $field['default'] ) ) {
					$value = $field['default'];
				} else {
					continue;
				}
				


				// Some special attention to particular fields
				if( in_array( $field['type'], $truthy_fields) ) {
					$value = (bool) $value;
				}

				if( is_numeric( $value ) ) {
					$value = $value + 0;
				}

				// Setup the key
				if( is_string( $field['js'] ) ) {
					$js_key = $field['js'];
				} else {
					$js_key = $field['id'];
				}

				// Add key to output
				$out[ $js_key ] = $value;
			}
		}

		return $out;
	}

	public static function dots_to_array( $array ) {
		$out = array();
		foreach ($array as $key => $value) {
			// Skip if no dots in key
			if( ! strstr($key, '.') ) { 
				$out[ $key ] = $value;
				continue; 
			}

			$keys = explode(".", $key);
			$out = array_merge_recursive($out, village_js_flipster( $keys, $value ) );
		}

		return $out;
	}

	public static function on_redux_save( $values ) {
		$options = self::get( $values );
		$parsed_options = self::dots_to_array( $options );
		update_option( Village::$key . "js_options", $parsed_options );
	}

	public static function print_options() {
		$config = get_option( Village::$key . "js_options" );

		$js_vars = array(	
			'config' => $config,
			'isMobile' => wp_is_mobile(),
		);

		?>
			<script type="text/javascript">
				var __VILLAGE_VARS = <?php echo json_encode( apply_filters('themevillage_javascript_variables', $js_vars ) ); ?>
			</script>
		<?php
	}

}

add_action( 'init', array('Village_JavaScript_Options', 'init') );


<?php
/**
 * The class to register, update and display blocks
 *
 * It provides an easy API for people to add their own blocks
 * to the Aqua Page Builder
 *
 * @package Aqua Page Builder
 */



if( !class_exists('Village_Block') && class_exists("AQ_Block") ) {
	class Village_Block extends AQ_Block {
		var $size_words = array( 
							"one-whole",
							"one-twelfth",
							"one-sixth",
							"one-fourth",
							"one-third",
							"five-twelfths",
							"one-half",
							"seven-twelfths",
							"two-thirds",
							"three-fourths",
							"five-sixths",
							"eleven-twelfths",
							"one-whole"
						);
		var $font_size = array('h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'regular', 'small');
		var $font_weight = array(
					'100' => 'Ultra Light',
					'200' => 'Light',
					'300' => 'Book',
					'400' => 'Normal',
					'500' => 'Medium',
					'600' => 'Semi Bold',
					'700' => 'Bold',
					'800' => 'Extra Bold',
					'900' => 'Black',
				);
		var $text_kses = array( 
							"strong" => array(), 
							"br" => array(),
							"em" => array(), 
							"h1" => array(), 
							"h2" => array(), 
							"h3" => array(), 
							"h4" => array(), 
							"h5" => array(),
							"h6" => array()
						 );

	 	/**
	 	 * Block callback function
	 	 *
	 	 * Sets up some default values. Unless you know exactly what you're
	 	 * doing, DO NOT override this function
	 	 */
	 	function block_callback($instance) {
	 		//insert block options into instance
	 		$instance = is_array($instance) ? wp_parse_args($instance, $this->block_options) : $this->block_options;
	 		
	 		//insert the dynamic block_id
	 		$this->block_id = 'aq_block_' . $instance['number'];
	 		$instance['block_id'] = $this->block_id;

	 		// Remove child "Templates"
	 		remove_shortcode( 'template' );
	 		
	 		//display the block
	 		$this->before_block($instance);
	 		$this->block($instance);
	 		$this->after_block($instance);

	 	}

	 	function sanitize_int($string) {
	 		return filter_var($string, FILTER_SANITIZE_NUMBER_INT);
	 	}

	 	function get_column_name($size) {
	 		$size = $this -> sanitize_int($size);
	 		return $this->size_words[$size];
	 	}
	 	

		function prettify_for_select($array) {
			$out = array();

			foreach ( $array as $key => $var ) {
				$out[$var] = ucwords( str_replace( '-', ' ', $var ) );
			}


			return $out;
		}

	 	function update($new_instance, $old_instance) {
	 		$new_instance = array_map('wp_kses_post', array_map('stripslashes', $new_instance));
	 		return $new_instance;
	 	}



	 	/* block header */
	 	function before_block($instance) {
	 		extract($instance);
	 		$size_word = $this->get_column_name($size);

	 		$class = array($id_base, "column", "cf", $size_word);
	 		
	 		if ( $first ) {
	 			$class[] = "first";
	 		}
	 		
	 		$element_id = $template_id.'-'.$number;
	 		
	 		echo '<div id="'. $element_id .'"' . Village::render_class($class, $echo = false) . '>';

	 	}
	 	
	 	/* block footer */
	 	function after_block($instance) {
	 		echo '</div>';	
	 	}
	 	
	}
}
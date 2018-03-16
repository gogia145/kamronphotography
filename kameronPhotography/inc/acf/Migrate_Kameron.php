<?php

if( ! function_exists('preg_grep_keys') ) {
	function preg_grep_keys($pattern, $input, $flags = 0) {
	    return array_intersect_key($input, array_flip(preg_grep($pattern, array_keys($input), $flags)));
	}
}
class Village_Upgradable_Gallery {

	private $pID;

	//-----------------------------------*/
	// Old Data
	//-----------------------------------*/
	private $old_key = 'gallery';
	private $new_key = "village_gallery";

	// Not used right now
	// But might be useful in future for cleanups
	private $keys = array(
		'gallery' => 'field_5314647c80f5a',
		'image' => 'field_53146e2d2676a',
		'title' => 'field_5314700d05886',
		'description' => 'field_53146e602676b',
		'video_url' => 'field_53146e772676c',
	);


	function __construct( $post_id ) {
		$this -> pID = $post_id;
		$prefix = Village::$key;

	}




	function upgrade() {

		$meta = $this -> get_previous_meta();
		

		foreach ($meta as $k => $v) {
			
			// Remove empty entries from the array
			$v = array_filter($v);

			if ( empty( $v['image'] ) ){
				continue;
			} else {
				$new_post_gallery[] = $v['image'];	
			}

			$ID = $v['image'];

			if( !empty($v['title']) )
				$this -> update_attachment_caption( $ID, $v['title']);

			if( !empty($v['description']) )
				$this -> update_attachment_description( $ID, $v['description']);

			if( !empty($v['video_url']) )
				$this -> update_attachment_video_url( $ID, $v['video_url']);

		}




		if( !empty( $new_post_gallery ) ) {
			update_post_meta( $this->pID, $this->new_key, $new_post_gallery );
		}
		
	}


	function get_previous_meta() {

		$raw_meta = get_post_meta( $this->pID  );	
		$meta = preg_grep_keys('/^kameron_options_gallery.*/', $raw_meta);

		$out = array();
		foreach ($meta as $rKey => $val) {

			$num = (int) preg_replace('/[^\d]+/', '', $rKey);
			$key = str_replace("kameron_options_gallery_{$num}_", '', $rKey);
			
			$out[$num][$key] = (is_array($val)) ? $val[0] : $val ;

		}


		return $out;

		
	}


	function update_attachment_caption( $ID, $val ) {
		wp_update_post(array(
           'ID' => $ID,
           'post_excerpt' => $val,
		));
	}

	function update_attachment_description( $ID, $val ) {
		wp_update_post(array(
           'ID' => $ID,
           'post_content' => $val,
		));
	}

	function update_attachment_video_url( $ID, $val ) {
		$existing_data = get_post_meta( $ID, '_attachment_video_url');
		if( empty( $existing_data ) ) {
			update_post_meta( $ID, '_attachment_video_url', $val );	
		}
	}

	function cleanup(){ 
	
	}
}




$posts = get_posts( array(
    'nopaging' => true,
    'post_type' => 'portfolio',
    'fields' => 'ids',
));
$__THEME_UPGRADED = get_theme_mod( '__THEME_UPGRADED', false );


if( is_numeric( $__THEME_UPGRADED ) ) {
	$start_from = array_search($__THEME_UPGRADED, $posts) + 1;
	$posts = array_slice( $posts, $start_from );
}

foreach ($posts as $pID) {
	$gallery = new Village_Upgradable_Gallery( $pID );
	$gallery->upgrade();

}


set_theme_mod( '__THEME_UPGRADED', true );
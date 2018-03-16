<?php

class Village_Gallery_Data {

	private $ID;
	private $has_descriptions;

	function __construct( $post_id ) {

		$this->ID = $post_id;
		$this->has_descriptions = false;

	}

	public function get() {
		return $this->get_portfolio_images();
	}

	public function has_descriptions() {
		return $this -> has_descriptions;
	}

	function get_image_url( $image_id, $size ) {
		$image = wp_get_attachment_image_src( $image_id, $size );
		$image_url = $image[0];

		return $image_url;
	}

	function get_thumbnail( $image_id, $size ) {
		
		$thumb = wp_get_attachment_image_src( $image_id, $size);
		
		return array(
			'thumb' => $thumb[0],
			'thumbwidth' => $thumb[1],
			'thumbheight' => $thumb[2]
		);

	}

	function sanitize_text( $string ) {

		$string = str_replace('"', "'", $string);
		$string = htmlspecialchars($string, ENT_QUOTES);

		return $string;
	}

	function get_image( $image_id ) {
		
		$attachment_data = wp_prepare_attachment_for_js( $image_id );
		$image = wp_array_slice_assoc( $attachment_data, array('sizes', 'caption', 'description') );


		// Format Data
		$image['id'] = $image_id;
		$image['img'] = $image['sizes']['full']['url'];
		$image['desc'] = $image['description'];
		unset($image['sizes'], $image['description']);

		if( ! $this->has_descriptions && !empty( $image['description'] ) ) {
			$this->has_descriptions = true;
		}

		if ( isset($image['desc']) )
			$image['desc'] =  $this->sanitize_text( $image['desc'] );

		if ( isset($image['caption']) )
			$image['caption'] = $this->sanitize_text( wp_strip_all_tags( $image['caption'] ) );

		return $image;

	}

	function get_portfolio_images() {
		$enable_thumbnails = Village::is_enabled("gallery_enable_thumbnails", true);
		

		$images = get_post_meta( $this->ID, "village_gallery", true );

		if ( ! $images ) {
			return array();
		}

		$out = array();

		foreach ( $images as $key => $image_id ) {

			$image = $this->get_image( $image_id );

			if( $enable_thumbnails ) {
				$image += $this->get_thumbnail( $image_id, 'portfolio_mini_thumbnail' );
			}

			if( $video = get_post_meta( $image_id, '_attachment_video_url', true ) ) {
				$image['video'] = esc_url_raw( $video );
			}

			$out[] = $image;
		}

		return $out;
}



}
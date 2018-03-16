<?php
if( !class_exists('Village_Block_Image') && class_exists('Village_Block') ) {
	class Village_Block_Image extends Village_Block {
		var $user_options, $icon_names;
		var $defaults = array(
                'image_id' => '',
				'custom_css_class' => '',
			)
		;
		//set and create block
		function __construct() {
			$block_options = array(
				'name' => 'Image',
				'size' => 'span12',
				'resizable' => false
			);
			//create the block
			parent::__construct('Village_Block_Image', $block_options);
		}
	
		function form($instance) {
			$args = wp_parse_args($instance, $this->defaults);

			$image = self::get_image( $args['image_id'] );
			?>
			<div class="village-image-uploader">
				<input name="<?php echo 'aq_blocks['.$instance["block_id"].'][image_id]'; ?>" type="hidden" class="image_id" value="<?php echo $args['image_id'] ?>">
					
				<label for="<?php echo $this->get_field_id('image_url') ?>">
					Image <br> 
					<a href="#" class="village-upload button-primary" rel="image">
						<?php _e('Select / Upload Image', 'village') ?>
					</a>

				</label>
				
				
				<div class="reference-image js__ref">
					<?php if($image): ?>
						<img src="<?php echo $image['url'] ?>" />
					<?php endif; ?>
				</div>
			</div>

			<?php
			
		}

	 	function before_block($instance) {}
	 	function after_block($instance) {}

	 	function get_image( $image_id ) {
	 		if( empty( $image_id) ) {
	 			return false;
	 		}

	 		$image = wp_get_attachment_image_src( $image_id, 'portfolio_tall_thumbnail' );

	 		if( is_array( $image ) ) {
		 		return array(
					'url' => $image[0],
					'width' => $image[1],
					'height' => $image[2],
				);
	 		} else {
	 			return false;
	 		}


	 	}

		function block($i) {
			$args = wp_parse_args($i, $this->defaults);
		
			$image = self::get_image( $args['image_id'] );
 			
 			if( $image === false ) {
 				return;
 			}

			$parent = array();
 			$parent['class'] = array("hscol", "hentry", "js__hscol");
 			$parent['style']['width'] = $image['width'] . 'px';
 			$parent['data']['entry-width'] = $image['width'];

			?>
			 <div<?php Village::render_attributes( $parent );?>>
	   			
		 		<div class="pfentry__image">
					<?php echo wp_get_attachment_image( $args['image_id'], 'portfolio_tall_thumbnail' ); ?>
				</div>

		 	</div> <!-- .hscol.hentry -->
			<?php
			
		}
		
	}
	aq_register_block("Village_Block_Image");
}
<?php
if( !class_exists('Village_Block_Service') && class_exists('Village_Block') ) {
	class Village_Block_Service extends Village_Block {
		var $user_options, $icon_names;
		var $defaults = array(
				'title' => '',
				'content' => '',
				'button_text' => '',
				'button_url' => '',
				"background_image" => '',
				"featured_image" => '',
				'style_preset' => '',
				"background_color" => '',
				"background_image_opacity" => '100',
				'enable_scrollbar' => '',
				'custom_css_class' => '',
				'align_text' => 'text-left',
				'button_title' => '',
				'button_url' => '',
			)
		;
		//set and create block
		function __construct() {
			$block_options = array(
				'name' => 'Service',
				'size' => 'span12',
				'resizable' => false
			);
			

			$this->user_options = array(
				'style_preset' => array(
					"" => "",
					"dark-font" => "Dark Font",
					"light-font" => "Light Font",
				),
				'align_options' => array(
					'text-left' => 'Align Left',
					'text-center' => 'Align Center',
					'text-right' => 'Align Right' 
				 )

			);

			//create the block
			parent::__construct('Village_Block_Service', $block_options);
		}
	
		function form($instance) {
			extract( wp_parse_args($instance, $this->defaults) );
			
		
			?>
			<p class="description">
				<label for="<?php echo $this->get_field_id('featured_image') ?>">
					Featured Image <br> 
					<?php echo aq_field_upload('featured_image', $block_id, $featured_image) ?>
				</label>
				
				<?php if($featured_image): ?>
					<div class="reference-image">
						<img src="<?php echo $featured_image ?>" />
					</div>
				<?php endif; ?>
			</p>

			<p class="description">
				<label for="<?php echo $this->get_field_id('title') ?>">
					Title<br/>
					<?php echo aq_field_input('title', $block_id, $title); ?>
				</label>
			</p>

			<p class="description">
				<label for="<?php echo $this->get_field_id('content') ?>">
					Content<br/>
					<?php echo aq_field_textarea('content', $block_id, $content) ?>
				</label>
			</p>


			<div class="column">
				<h4 class="hndle">Service Button <em>(Optional)</em></h4>
				
				<p class="description">
					<label for="<?php echo $this->get_field_id('button_title') ?>">
						Button Title<br/>
						<?php echo aq_field_input('button_title', $block_id, $button_title); ?>
					</label>
				</p>
				<p class="description">
					<label for="<?php echo $this->get_field_id('button_url') ?>">
						Button Link<br/>
						<?php echo aq_field_input('button_url', $block_id, $button_url); ?>
					</label>
				</p>

			</div>


			<div class="column">
				<h3 class="hndle"> Visual Options</h3>
				<p class="description">
					<label for="<?php echo $this->get_field_id('style_preset') ?>">
						Style Preset<br/>
						<?php echo aq_field_select('style_preset', $block_id, $this->user_options['style_preset'], $style_preset) ?>
					</label>
				</p>

				<p class="description">
					<label for="<?php echo $this->get_field_id( 'align_text' ) ?>">
						Align Text: 
						<?php echo aq_field_select( 'align_text', $block_id, $this->user_options['align_options'], $align_text ) ?>
					</label>
				</p>

				<p class="description">
					
					<label for="<?php echo $this->get_field_id('background_image') ?>">
						Background Image <br> 
						<?php echo aq_field_upload('background_image', $block_id, $background_image) ?>
					</label>
					
					<?php if($background_image): ?>
						<div class="reference-image" style="<?php
							if ( $background_color ) {
								echo "background-color: $background_color};";
							}
						 ?>;">
							<img style="<?php 
							 if ( $background_image_opacity ) {
							 	$opacity = $background_image_opacity/100;
							 	echo "opacity: $opacity;";
							 }
							 ?>" src="<?php echo $background_image ?>" />
						</div>
					<?php endif; ?>
				</p>

				<p class="description">
					<label for="<?php echo $this->get_field_id('background_color') ?>">
						Background Color<br/>
						<?php echo aq_field_color_picker('background_color', $block_id, $background_color) ?>
					</label>
				</p>
			
				<p class="description">
					<label for="<?php echo $this->get_field_id('background_image_opacity') ?>">
						Background Image Opacity <br/>
						<?php echo aq_field_input('background_image_opacity', $block_id, $background_image_opacity, 'min', 'number') ?> %
					</label>
				</p>
				<br/><hr/><br/>
				<p class="description">
					<label for="<?php echo $this->get_field_id('custom_css_class') ?>">
						Custom CSS Class<br/>
						<?php echo aq_field_input('custom_css_class', $block_id, $custom_css_class) ?>
					</label>
				</p>		
				<br/>

				<p class="description half">
					<label for="<?php echo $this->get_field_id('enable_scrollbar') ?>">
						<?php echo aq_field_checkbox('enable_scrollbar', $block_id, $enable_scrollbar); ?>
						Enable Scrollbar ?
					</label><br>
					<em> Note: Please check this when you absolutley need it. The less of these you have, the better the performance of your website</em>
				</p>

			</div>


			<?php
			
		}

	 	function before_block($instance) {}
	 	function after_block($instance) {}

		function block($i) {
			$args = wp_parse_args($i, $this->defaults);

			$parent = array();
			$child = array();
			$background = array();


			$parent['class'] = array("hscol", "service", "js__service", "js__static");
			$background["class"] = array("sticky-background");
			$child['class'] = array( "service__container" );
			
			if ( $args['custom_css_class'] ) {
				$classes = sanitize_html_classes( $args['custom_css_class'], "" );
				$classes = (array) explode(" ", $classes);
				$parent['class'] = array_merge($parent['class'], $classes);
			}

			if ( $args['enable_scrollbar'] ) {
				$child['class'][] = "js__scroll";
			}

			if ( $args['background_color'] ) {
				$parent['style']["background-color"] = sanitize_hex_color ( $args['background_color'] );
			}
		
			if ( $args['background_image_opacity'] ) {
				$background['style']["opacity"] = $args['background_image_opacity'] / 100;
			}

			if ( $args['background_image'] ) {	
				$background['style']['background-image'] = "url('$args[background_image]')";
			} else {
				$parent['class'][] = 'no-background-image';
			}
			
			if ( $args['style_preset'] ) {
				$parent['class'][] = $args['style_preset'];
			}

			if( $args['align_text'] && $args['align_text'] !== 'text-left' ) {
				$parent['class'][] = sanitize_html_class( $args['align_text'] );
			}
 
			?>
				 <div<?php Village::render_attributes( $parent );?>>
	   					 
   					 <?php if (  !empty( $background['style'] ) || !empty( $background['data'] )  ): ?>
						<div <?php Village::render_attributes( $background ); ?>></div>
					 <?php endif; ?>
					 
					 <div<?php Village::render_attributes( $child ); ?>>
						<div class="service__content">
							<?php if ( $args['featured_image'] ): ?>
								<div class="service__image"><img src="<?php echo $args['featured_image']; ?>" alt="<?php echo $args['title'] ?>"/></div>
							<?php endif ?>

							<?php if ( $args['title'] ): ?>
								<div class="service__title"><?php echo $args['title']; ?></div>
							<?php endif ?>

							 <div class="service__description">
								<?php echo do_shortcode( $args['content'] ) ?>
							</div>
			
							<?php if ( $args['button_url'] && $args['button_title']): ?>
								<div class="service__link">
									<div class="village-button__wrapper">
										<a href="<?php echo esc_url_raw( $args['button_url'] );?>" class="village-button__inner js__wrapped">
											<?php echo $args['button_title']; ?>
										</a>
									</div>
								</div>
							<?php endif ?>
						</div>
					 </div> <?php // end div$child  ?>

			 	</div> <!-- .hscol.service -->
			<?php
			
		}
		
	}
	aq_register_block("Village_Block_Service");
}
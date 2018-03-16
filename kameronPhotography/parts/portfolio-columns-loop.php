<?php


$gallery_data = new Village_Gallery_Data( get_the_ID() );
$images = $gallery_data->get();

foreach( $images as $image ) {

	set_query_var( 'image_id', $image['id'] );
	set_query_var( 'description', $image['desc'] );
	set_query_var( 'entry_title', $image['caption'] );

	get_template_part( 'parts/portfolio-column' );

}
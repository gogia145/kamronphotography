<div class="content__inner--page">
	<?php the_content(); ?>
	
	<?php
		wp_link_pages( array(
			'before' => '<div class="page-links">' . __( 'Pages:', 'camilla' ),
			'after'  => '</div>',
		) );
	?>

</div>
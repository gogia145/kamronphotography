	
	<?php 
		if ( Village::get_theme_mod('loading_enabled', false ) ) {
			get_template_part('parts/loading-notification'); 	
		}
	?>

	</div><!-- #content -->
</div><!-- #page -->

<?php get_template_part( 'parts/scroll-notification' ); ?>

<?php wp_footer(); ?>

</body>
</html>
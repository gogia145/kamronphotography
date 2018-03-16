<?php 
	$ntf_times = Village::get_theme_mod('scroll_note_cookies_count', 2);
	if ( 
	    ! wp_is_mobile() 
	    && Village::is_enabled('scroll_note_enable', true) 
	    && ( ! isset( $_COOKIE['icanhaz-scroll'] ) || $ntf_times > $_COOKIE['icanhaz-scroll'] ) 
    ):
?>

<?php
	$default_ntf_title = __('Scroll!', 'village');
	$default_ntf_content = __("Scroll down with your mousewheel or trackpad to see more content!", 'village');
	$default_ntf_ok = __('Got it!', 'village');

	// Get Options
	$ntf_theme = Village::get_theme_mod('scroll_note_theme', 'dark');
	$ntf_title = Village::get_theme_mod('scroll_note_title', $default_ntf_title );
	$ntf_content = Village::get_theme_mod('scroll_note_content', $default_ntf_content );
	$ntf_ok = Village::get_theme_mod('scroll_note_ok', $default_ntf_ok );


	// Setup Classes
	$scroll_note_class = array('scroll-note', 'scroll-note--' . $ntf_theme );

	if ( $ntf_theme === 'dark' ) {
		$scroll_note_class[] = 'light-font';
	} else {
		$scroll_note_class[] = 'dark-font';
	}
?>
<div id="scroll-note"<?php Village::render_class( $scroll_note_class ); ?>>
	<div class="scroll-note__message">
		<div class="scroll-note__icon">
			<img src="<?php echo get_template_directory_uri(); ?>/assets/images/scroll-theme-<?php echo $ntf_theme; ?>.gif" alt="Scroll Illustration">
		</div>
		<h3 class="scroll-note__title"><?php echo esc_html($ntf_title); ?></h3>
		<div class="scroll-note__content"><?php echo wp_kses_post($ntf_content); ?></div>
		<div class="village-button__wrapper">
			<a id="scroll-i-get-it" href="#" class="village-button__inner js__wrapped"><?php echo esc_html($ntf_ok); ?></a>
		</div>
	</div>
</div>
<?php endif; // End notification ?>

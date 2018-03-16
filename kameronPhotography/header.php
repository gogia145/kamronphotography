<?php
// Generic Settings
$site_logo = Village::get_theme_mod( "site_logo", null );

if ( $site_logo && is_array( $site_logo ) && ! empty( $site_logo['url'] ) ) {
	$site_logo = $site_logo['url'];
} else {
	$site_logo = null;
}

$site_title = get_bloginfo( 'name' );

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
<!-- The quickest way for us to know that JavaScript is turned on -->
<script type="text/javascript">document.documentElement.className = 'js';</script>

<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0,user-scalable=no">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<?php do_action( 'before' ); ?>
	<?php 

		$header_class = array('site-header');
		$header_class[] = Village::get_theme_mod('navigation_font_color_scheme', 'light-font');
		
	 ?>
	<header id="header" class="<?php echo implode(" ", $header_class); ?>">
		<div id="header-toggle" class="header__hover-area">
				
			<!-- Header Background -->
			<?php if ( $navigation_background_image = Village::get_theme_mod("navigation_background_image", false) ): ?>
				<i id="header__background" 
					style="background-image: url('<?php echo $navigation_background_image['url'] ?>');"
					data-height="<?php echo $navigation_background_image['height']; ?>"
					data-width="<?php echo $navigation_background_image['width']; ?>"
				></i>
			<?php endif; ?>

			<!-- Scroll Container & Content -->
			<div class="header__content js__scroll js__stage_height">				
					
					<div class="site-branding">
						<h1 class="site-title <?php if ( null != $site_logo ) echo " image"; ?>">
							<!-- BEGIN Logo -->
							<a class="site-logo" href="<?php echo custom_home_url(); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
								<?php if ( null != $site_logo ): ?>
									<img src="<?php echo esc_url( $site_logo ); ?>" alt="<?php echo $site_title; ?>" />
									<?php echo $site_title; ?>
								<?php else: ?>   
									<?php echo $site_title; ?>
								<?php endif; ?>
							</a>
							<!-- END Logo -->
						</h1>
					</div> <!-- .site-branding -->

					<nav id="navigation" class="site-navigation" role="navigation">
						<div class="screen-reader-text skip-link"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'village' ); ?>"><?php _e( 'Skip to content', 'village' ); ?></a></div>
						<?php
							// The regular menu that's always there:
							wp_nav_menu(
								array( 
									'theme_location' => 'primary',
									'menu_id' => 'main-menu',
									'container_class' => 'main-menu',
									'fallback_cb' => 'village_menu_fallback_function',
								)
							); 
						?>
					
					</nav><!-- #navigation -->

				<?php if ( is_dynamic_sidebar( 'menu-widgets' ) ): ?>
				<div class="header-widgets">
					<?php dynamic_sidebar( 'menu-widgets' ); ?>
				</div>
				<?php endif ?>

			</div> <!-- .header__content -->

			<div class="js__toggle header__toggle">
				<i class="icon ion-navicon"></i>
			</div>
		
		</div> <!-- .header__hover-area -->
	</header>
	<div id="content" class="site-content">
<?php
// PHPish whitespace doesn't get rendered























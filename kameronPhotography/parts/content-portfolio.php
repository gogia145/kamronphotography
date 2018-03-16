<?php 
$thumb_key = 'portfolio_mini_thumbnail';

$gallery_classes = parse_gallery_cookies();
$gallery_descriptions = false;

$enable_thumbnails = Village::is_enabled('gallery_enable_thumbnails');

?>

<?php 
//-----------------------------------*/
// Social Sharing Image:
//-----------------------------------*/
if ( !empty( $_GET['image']) ) {

	$gallery_data = new Village_Gallery_Data( get_the_ID() );
	
	$images = $gallery_data->get();

	$image_key = ( int ) $_GET['image'];


	if( !empty( $images[$image_key]) ) {
		# JavaScript indexes images starting from 1
		$image = $images[$image_key];
	}

}
?>

<?php if( isset( $image ) && !empty( $image['img']) ): ?>
	<img class="visually-hidden social-thumbnail" src="<?php echo $image['img']; ?>" alt="<?php the_title(); ?>"/>

	<script type="text/javascript">
		window.location = "<?php echo get_permalink( get_the_ID() ) . '#' . $image_key+1; ?>";
	</script>
<?php endif; ?>

<article id="post-<?php the_ID(); ?>" <?php post_class("entry-item"); ?> data-url="<?php the_permalink() ?>">

		<header class="entry-header">
			<h1 class="entry-title">
					<?php the_title() ?>
			</h1>
		</header>

		<?php get_template_part( '/inc/gallery/gallery_template' ); ?>
		
		<div class="entry-content">
			<?php the_content(); ?>
		</div>


	

</article><!-- #post-## -->

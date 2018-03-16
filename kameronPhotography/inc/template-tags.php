<?php


/**
 * Get the correct post date with the correct format
 * @uses  get_the_time()
 * @uses  get_option()
 */
if ( !function_exists( 'the_post_date') ) {
	function the_post_date() {
		echo get_the_time(get_option('date_format'));
	}
}


/**
 * Wordpress is a little unreliable getting the current page.
 * @return int Page Number
 */
if ( !function_exists( 'the_post_date') ) {
	function get_current_page() {
		if ( get_query_var('paged') ) { 
			return get_query_var('paged'); 
		}
		elseif ( get_query_var('page') ) { 
			return get_query_var('page'); 
		}
		else { 
			return 1; 
		}
	}
}


/**
 * Do Exactly what the function name says
 * Because sometimes you may want either a post format or a post 
 */
if ( !function_exists( 'the_post_date') ) {
	function get_post_format_or_type() {
		if ( $format = get_post_format() ) {
			return $format;
		} else {
			return get_post_type();
		}
	}
}


/* -----------------------------------*/
/* 		In case sanitize_hex_color doesn't exist
/* -----------------------------------*/
if ( ! function_exists( "sanitize_hex_color") ) {
	function sanitize_hex_color( $color ) {
		if ( '' === $color )
			return '';

		// 3 or 6 hex digits, or the empty string.
		if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) )
			return $color;

		return null;
	}
}

/**
 * Get the widget count
 * @param  (string) $sidebar_id Widget area ID
 * @return (int)    Widget Count
 */
if ( ! function_exists( "get_widget_count") ) {
	function get_widget_count( $sidebar_id ) {

		global $_wp_sidebars_widgets;
		if ( empty( $_wp_sidebars_widgets ) )
			$_wp_sidebars_widgets = get_option( 'sidebars_widgets', array() );
		
		$sidebars = $_wp_sidebars_widgets;


	    if( !isset( $sidebars[$sidebar_id] ) ) {
	    	return false;
        }
	    
	    return count( $sidebars[$sidebar_id] );
	}
}



if ( ! function_exists( 'village_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 */
function village_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;

	if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) : ?>

	<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
		<div class="comment-body">
			<?php _e( 'Pingback:', 'village' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( 'Edit', 'village' ), '<span class="edit-link">', '</span>' ); ?>
		</div>

	<?php else : ?>

	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
		
		<?php if ( 0 != $args['avatar_size'] ): ?>
		<div class="comment-author-image">
			<?php echo get_avatar( $comment, 200 ); ?>
		</div>
		<?php endif; ?>

		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php printf( __( '%s', 'village' ), sprintf( '<span class="fn">%s</span>', get_comment_author_link() ) ); ?>
				</div><!-- .comment-author -->

				<div class="comment-metadata">
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
						<time datetime="<?php comment_time( 'c' ); ?>">
							<?php printf( _x( '%1$s', '1: date', 'village' ), get_comment_date() ); ?>
						</time>
					</a>
					<?php edit_comment_link( __( 'Edit', 'village' ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- .comment-metadata -->

				<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'village' ); ?></p>
				<?php endif; ?>
			</footer><!-- .comment-meta -->

			<div class="comment-content">
				<?php comment_text(); ?>
			</div><!-- .comment-content -->

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- .comment-body -->

	<?php
	endif;
}
endif; // ends check for village_comment()




if ( ! function_exists( 'village_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @return void
 */
function village_paging_nav() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
	<nav class="navigation paging-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'village' ); ?></h1>
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'village' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'village' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

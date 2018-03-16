<?php    
class Village_PJAX{

	public function __construct(){
		add_action('template_redirect', array($this, 'sniff_requests'), 0);
	}	

	public function sniff_requests(){

		if( isset( $_GET['pjax'] ) 
		&&  $_GET['pjax'] === 'true' 
		&& apply_filters( 'village_enable_pjax', true ) === true
		){
			$this->return_template_in_json();
			exit; // We shouldn't even get here
		}

	}	

	protected function return_template_in_json(){
		$ob = ob_start();
		
		$response['title'] = wp_title( '|', false, 'right' );
		$response['body_classes'] = get_body_class();


		// Copied from WP Core template-loader.php
		$template = false;
		if     ( is_404()            && $template = get_404_template()            ) :
		elseif ( is_search()         && $template = get_search_template()         ) :
		elseif ( is_front_page()     && $template = get_front_page_template()     ) :
		elseif ( is_home()           && $template = get_home_template()           ) :
		elseif ( is_post_type_archive() && $template = get_post_type_archive_template() ) :
		elseif ( is_tax()            && $template = get_taxonomy_template()       ) :
		elseif ( is_attachment()     && $template = get_attachment_template()     ) :
			remove_filter('the_content', 'prepend_attachment');
		elseif ( is_single()         && $template = get_single_template()         ) :
		elseif ( is_page()           && $template = get_page_template()           ) :
		elseif ( is_category()       && $template = get_category_template()       ) :
		elseif ( is_tag()            && $template = get_tag_template()            ) :
		elseif ( is_author()         && $template = get_author_template()         ) :
		elseif ( is_date()           && $template = get_date_template()           ) :
		elseif ( is_archive()        && $template = get_archive_template()        ) :
		elseif ( is_comments_popup() && $template = get_comments_popup_template() ) :
		elseif ( is_paged()          && $template = get_paged_template()          ) :
		else :
			$template = get_index_template();
		endif;

		include( $template );

		# Capture the content $template
		$response['content'] = ob_get_clean();
		
		
		# Set headers & return JSON
		header('content-type: application/json; charset=utf-8');
	    echo json_encode($response)."\n";

	    exit;
	}
}
$pjax = new Village_PJAX();











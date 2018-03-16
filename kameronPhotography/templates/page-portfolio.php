<?php
/*
* Template Name: Portfolio
*/

//-----------------------------------*/
// Setup Portfolio Query
//-----------------------------------*/
    
$args = array( 
    'post_type' => 'portfolio',
	'posts_per_page' => -1
	);

query_posts( $args );

get_template_part( 'archive', 'portfolio' );

wp_reset_query();


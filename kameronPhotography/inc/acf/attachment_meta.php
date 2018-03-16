<?php 

//-----------------------------------*/
// Add attachment meta for attachment Video URL
//-----------------------------------*/
function attachment_video_url_edit( $form_fields, $post ) {
    $field_value = get_post_meta( $post->ID, '_attachment_video_url', true );
    $form_fields['video_url'] = array(
        'value' => $field_value ? $field_value : '',
        'label' => __( 'Video URL' ),
    );
    return $form_fields;
}
add_filter( 'attachment_fields_to_edit', 'attachment_video_url_edit', 10, 2 );

function attachment_video_url_save( $attachment_id ) {
    if ( isset( $_REQUEST['attachments'][$attachment_id]['video_url'] ) ) {
        $video_url = $_REQUEST['attachments'][$attachment_id]['video_url'];
        update_post_meta( $attachment_id, '_attachment_video_url', $video_url );
    }
}
add_action( 'edit_attachment', 'attachment_video_url_save' );


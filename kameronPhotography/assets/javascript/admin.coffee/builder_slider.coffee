$ = jQuery


class Image_Uploader 

	constructor: ( $el, att ) ->
		
		@check_height( att )
		@add_image_data( $el, att )
		@add_preview( $el, att )


	check_height: ( att ) ->
		if att.height < 560
			alert "You have selected an image with less than 560px height. This may be an issue."


	add_image_data: ( $el, att ) ->

		$input_id = $el.find('.image_id')

		if $input_id.length is 1
			$input_id.attr 'value', att.id


	add_preview: ( $el, att ) ->
		$ref = $el.find('.js__ref')
		$ref_image = $ref.find('img')

		if $ref_image.length is 0
			$ref.append("""<img src="#{att.url}"/>""")
		else
			$ref_image.attr('src', att.url)

			


$(document).on "village:media_selected", '.village-upload', (e, attachment) ->
	$target = $(this).closest('.village-image-uploader')
	if $target.length is 1
		atts = attachment.attributes
		new Image_Uploader( $target, atts )

	

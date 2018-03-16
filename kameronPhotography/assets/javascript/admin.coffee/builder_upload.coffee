$ = jQuery

frame = false
$(document).on "click", ".village-upload", (e) ->
	e.preventDefault()

	$clicked = $( this )

	# If the media frame already exists, reopen it.
	if frame
		frame.open()
		return

	# Setup a frame
	frame = wp.media.frames.village_uploader = wp.media
		library:
			orderby: "date"
			type: "image"
	
	frame.on 'select', ->
		attachment = frame.state().get('selection').first()	
		if attachment.attributes.type is "image"
			$clicked.trigger( "village:media_selected", attachment, frame )

	frame.open()

	return
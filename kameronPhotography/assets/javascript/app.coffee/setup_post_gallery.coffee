

return if App.config.wp_galleries is false

Gallery = false

$(document).on 'click', '.single-post .gallery a, .page .gallery a', (e) ->
	
	$this = $(this)
	return if not $this.is('a')

	e.preventDefault()
	App.preventLoad()
	

	
	$gallery = $this.closest('.gallery')
	
	gallery_id = $gallery.attr('id') + $gallery.closest('.post').attr('id')



	if not Gallery or Gallery.maybe_reopen( gallery_id ) is false
		$('#popup__gallery').velocity('stop').hide()
		Gallery.destroy() if Gallery isnt false
		Gallery = new Post_Gallery( gallery_id )

	$images = $gallery.find('a')
	index = $images.index( $this )

	if not Gallery.reopen()
		data = Gallery.parse_data( $images )

	Gallery.open(index, data)



$(document).on 'click', '#popup__gallery, #popup__gallery__close .icon', (e) ->
	return unless e.currentTarget is e.target
	if Gallery
		Gallery.close()

$(document).on 'keydown', (e) ->
	# if keyCode is ESC key
	if Gallery and e.keyCode is 27
		Gallery.close()




class Post_Gallery
	constructor: ( ID ) ->
		
		@_ID = ID
		@_REOPEN = false
		@fotorama = false


		if $$('#popup__gallery', true).length is 0
			$("""
				<div id="popup__gallery" data-gallery-id="#{ID}">
					<div id="popup__gallery__close" class="gallery__button">
						<i class="icon ion-ios7-close-empty"></i>
					</div>
					<div id="popup__stage"></div>
				</div>
		   	""").appendTo( $$('body') )

			$$('#popup__gallery', true)
			$$('#popup__stage', true)

		return

	destroy: ->
		@fotorama.destroy()

		$$('#popup__gallery').remove()
		$$('#popup__gallery', true)
		$$('#popup__stage', true)

	maybe_reopen: ( ID ) ->

		if @_ID is ID
			@_REOPEN = true
		else
			@_REOPEN = false

		return @_REOPEN

	reopen: ->
		return @_REOPEN

	parse_data: ( $links ) ->
		
		for a in $links
			$a = $(a)
			img: $a.attr('href')
			thumb: $a.find('img').attr('src')

	open: ( index, data ) ->
		
		if @_REOPEN
			@fotorama = $$('#popup__stage').data('fotorama')
			@fotorama.show(index: index, time: 0)
		else
			@setup( index, data )
			@fotorama = $$('#popup__stage').data('fotorama')
		
		
		$$('#popup__gallery').velocity
			properties: 'fadeIn'
			options: 
				display: 'block'



	close: ->
		$$('#popup__gallery').velocity
			properties: 'fadeOut'
			options:
				display: 'none'


	setup: ( index, data ) ->
		$$('#popup__stage', true).fotorama
			data: data
			fit: 'scaledown'
			height: App.win.height * 0.9
			width: App.win.width * 0.9
			shadows: false
			startindex: index




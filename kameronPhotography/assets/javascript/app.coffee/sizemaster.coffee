#
# Lobal ( locally global ) Variables
# 
CONFIG = App.config.size
SIZE = {}
FRAME = {}
ADMIN_BAR = null

class CSS_Handler
	constructor: ->
		@previous_style = ""

	update: =>
		style = if App.is.responsive 
		then 	@get_responsive() 
		else 	@get_regular()

		return if @previous_style is style


		if $$('#js-style').length isnt 1
			@_writeDOM( style )

		else		
			$$('#js-style').html( style )

		@previous_style = style
		
		return
				

	_writeDOM: ( css )->
		head = document.head || document.getElementsByTagName('head')[0]
	
		style = document.createElement('style')
		style.type = 'text/css'
		style.id = 'js-style'

		style.appendChild( document.createTextNode(css) )
		head.appendChild(style)

		$$('#js-style', true)

	get_responsive: ->
		"""
		#gallery {
			height: #{App.win.height}px;
			width: #{App.win.width}px;			
		}

		.js__stage_height {
			height: #{App.win.height}px;
		}

		"""

	get_regular: ->
		"""
			body {
				height: #{FRAME.height}px;
			}

			.stage .hscol {
				height: #{SIZE.entry.height}px;
			}
			
			.hscol.js__service {
				width: #{SIZE.entry.height}px;
				margin-top: #{SIZE.entry.margin}px;
			}

			.stage, .js__winsize, .js__page {
				height: #{SIZE.content.height}px;
				width: #{SIZE.content.width}px;
			}
			
			.js__stage_height {
				height: #{FRAME.height}px;
			}
			
			.js__hscol {
				margin-top: #{SIZE.entry.margin}px;	
			}

			#gallery {
				width: #{SIZE.container.width}px;
				height: #{SIZE.container.height}px;
			}
			
		"""




class Entry_Handler
	constructor: ->
		@entries = {}

	get: ->
		$$('.stage:not(.closing-stage)', true)
			.find('.hscol')
			.not('.service')

	set_responsive: ->
		@entries.css('width', '')


	update: =>
		@entries = @get()
		return if @entries.length is 0

		if App.is.responsive
			@set_responsive()
		
		else if SIZE.short
			@scaleXY()
		
		else
			@scaleX()


	scaleX: ->
		$$('html').removeClass('layout--short')
		
		$first = @entries.first()

		if( $first.data('entryWidth') isnt $first.width() )
			@entries.each (key, el) ->
				# Get el width
				$el = $(el)
				width = $el.data('entryWidth')

				if width > 0
					# resize according to diff ratio
					$el.width( width )


	scaleXY: ->
		$$('html').addClass('layout--short')
		
		@entries.each (key, el) ->
			# Get el width
			$el = $(el)
			width = $el.data('entryWidth')

			if width > 0
				# resize according to diff ratio
				new_width = Math.ceil( width * SIZE.entry.ratio )

				$el.width( new_width )




class Sizemaster
	setup_size: ->
		FRAME = _.clone App.win

		if ADMIN_BAR is null
			ADMIN_BAR = ( document.getElementById('wpadminbar')? )

		if ADMIN_BAR
			FRAME.height -= 32
		
		margin 	= 	Math.floor( ( FRAME.height - CONFIG.image_height ) / 2 )
		height 	= 	Math.floor( FRAME.height - ( margin * 2 ) ) - 1
		width 	= 	CONFIG.image_width

		ratio 	= 	0
		short 	= 	( margin < 10 )

		# Scale things down when there is not enough room
		if short
			# Force Create a 5% margin
			margin = Math.round FRAME.height * 0.05

			# Adjust entry height
			height = FRAME.height - ( margin * 2 )

		# Calculate the ratio of the new entry height based on our vertical_margin
		ratio = ( height / CONFIG.image_height )

		if short
			# Resize the default entry width  according to the diff ratio
			width = CONFIG.image_width * ratio

		
		enough_space = FRAME.width >= (CONFIG.image_width + CONFIG.header_width)

		container = 
			width: FRAME.width - CONFIG.header_toggle_width
			height: FRAME.height
		
		content =
			width: if enough_space 
			then FRAME.width - CONFIG.header_width
			else container.width 

			height: FRAME.height


		SIZE = 
			container: container
			content: content
			entry:
				width:	width
				height:	height
				margin:	margin
				ratio:	ratio
			
			short:	short


		return


	# Set layout classes only on responsive event
	set_layout_classes: ->
		if App.is.responsive
			$$("html")
				.removeClass("layout--regular")
				.addClass("layout--responsive")
		else
			$$("html")
				.removeClass("layout--responsive")
				.addClass("layout--regular")


	change_app_state: ->
		enough_space = FRAME.width >= (CONFIG.image_width + CONFIG.header_width)

		if App.is.responsive is false and enough_space
			App.is.regular = true
		else
			App.is.regular = false

		Hooks.doAction "theme.ready"

		App.Header.setup( not App.is.regular )

		if App.is.regular and not App.Gallery
			FRAME.width -= CONFIG.header_width
		else
			FRAME.width -= CONFIG.header_toggle_width # if not Modernizr.touch




# //-----------------------------------*/
# // Camera, Lights, ACTION!
# //-----------------------------------*/
    
CSS = new CSS_Handler()
Entries = new Entry_Handler()
Master = new Sizemaster()

on_content_load = ->
	Entries.update()

on_window_resize = ->
	Master.setup_size() if not App.is.responsive
	CSS.update()
	
	Master.change_app_state()
	Entries.update()
	
	
	

	if App.Gallery and App.Gallery.fotorama
		App.Gallery.fotorama.resize(FRAME)
	
# Sizemaster should run after Responsive Sizer
Hooks.addAction "theme.resized", _.once(Master.set_layout_classes), 20
Hooks.addAction "theme.responsive", Master.set_layout_classes

Hooks.addAction "theme.resized", on_window_resize, 30

$(window).on "pronto.render", on_content_load








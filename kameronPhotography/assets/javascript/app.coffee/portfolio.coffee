# Respond to clicks anywhere on the image
$$('#content').on 'click', '.pfentry__image, .pfentry__info, .pfentry__info .js__link', (e) ->

	$this = $(e.target)
	return if ( $this.is('a') or $this.is('i') ) and not $this.is('.js__link')

	$el = $this
			.closest('.js__hscol')
			.find( '.js__link' )

	url = $el.attr('href')

	if url?
		if $el.hasClass('js__direct')
			window.location = url
		else
			App.Header.set_to( 'responsive' )
			App.Header.hide()
			App.Load(url)

	return



return unless App.config.portfolio.has_desc

ENABLE_HOVER = ( not Modernizr.touch and App.config.portfolio.detect_hover )

fade_opacity = App.config.portfolio.fade_opacity / 100

info_box =
	open: ( $container ) ->
		return if App.is.responsive
		return if $container.hasClass('no-thumbnail')

		$container
			.find('.pfentry__info')
			.velocity('stop')
			.velocity
				properties:
					opacity: 1
					scale: [1, 0.9]
				options:
					display: 'block'
					duration: 250

		if fade_opacity < 1

			$container
				.find('.pfentry__image img')
				.velocity('stop')
				.velocity
					properties:
						opacity: [fade_opacity, 1]
					options:
						duration: 600

		$container
			.find('.js__open')
			.velocity('stop')
			.velocity('fadeOut', duration: 250)
	
	close: ( $container ) ->
		return if App.is.responsive
		return if $container.hasClass('no-thumbnail')

		$container
			.find('.pfentry__info')
			.velocity('stop')
			.velocity
				properties: "reverse"
				options:
					display: 'none'

		if fade_opacity < 1
			$container
				.find('.pfentry__image img')
				.velocity('stop')
				.velocity
					properties: "reverse"
					options:
						duration: 600

		$container
			.find('.js__open')
			.velocity('stop')
			.velocity('fadeIn')

# //-----------------------------------*/
# // Click Events
# //-----------------------------------*/
$$('#content').on 'click', '.js__hscol .js__open, .js__hscol .js__close', ->
	return if App.is.responsive
	$el = $(this)
	$container = $el.closest('.js__hscol')

	if $el.hasClass('js__open')
		
		$container.addClass("js__clicklock") if ENABLE_HOVER
		info_box.open( $container )
	
	if $el.hasClass('js__close')
		
		$container.removeClass("js__clicklock") if ENABLE_HOVER
		info_box.close( $container )

	return

# //-----------------------------------*/
# // Hover Events
# //-----------------------------------*/
if ENABLE_HOVER
	$$('#page').hoverIntent
		selector: '.js__pfentry'
		timeout: 175
		interval: 25
		sensitivity: 6
		over: ->
			$container = $(this)
			info_box.open $container

		out: ->
			$container = $(this)
			return if $container.hasClass("js__clicklock")
			info_box.close $container

PARALLAX = 
	x: App.config.on_scroll.x
	y: App.config.on_scroll.y
	speed_x: App.config.on_scroll.speed_x / 100
	speed_y: App.config.on_scroll.speed_y / 100
	container_x: App.config.size.header_width + App.config.size.header_toggle_width
	height: $$('#header__background').data("height")
	width: $$('#header__background').data("width")


get_navalax_pos = ( position, max_position, item_size, container_size, speed_modifier  ) ->
	ratio = ( item_size - container_size ) / max_position * speed_modifier
	return position * -ratio



scroll_settings = 
	enable_parallax: App.config.on_scroll.enable
	force_parallax: App.config.on_scroll.force
	
	callbacks:
		y: ->

			$$('#header__background').css
				"background-position-y": get_navalax_pos( @y, @maxScrollY, PARALLAX.height, App.win.height, PARALLAX.speed_y )

		x: ->
			$$('#header__background').css
				"background-position-x": get_navalax_pos( @x, @maxScrollX, PARALLAX.width, PARALLAX.container_x, PARALLAX.speed_x )



App.Scroll = new Scroll_Handle( scroll_settings )
App.Header_Scroll = new Scroll_Handle
							x: false
							y: 
								keyBindings: false
								snap: false




$(window).on "load iscroll:refresh debouncedresize", -> 
	App.Scroll.refresh()


setup_scroll = ->
	return if App.is.responsive
	
	App.Scroll.setup $$('#stage')
	$$('.js__scroll').scrollTop(0).scrollLeft(0)

Hooks.addAction "theme.ready", _.once ->
	# Only setup iScroll if App isn't responsive
	return if App.is.responsive
	
	setup_scroll()	

	$(window).on "load hashchange", (e) ->
		return unless window.location.hash
		return if App.Scroll.active.y.length is 0
		
		if $$( window.location.hash ).length
			App.Scroll.active.y[0].scrollToElement( window.location.hash, 0 )
			$$('.js__scroll').scrollTop(0).scrollLeft(0)


# 1001 pretty much means the last thing you do
# So we're putting off setting up a new scrollbar as long as we can.
# This probably should be the last thing we do after stage.complete
Hooks.addAction "stage.complete", setup_scroll, 1001


# When the switch occurs:
Hooks.addAction "theme.responsive", (responsive) ->
	if responsive is true
		App.Scroll.destroy()
	
	# "else" may be undefined
	# We want to be sure "responsive" is FALSE
	if responsive is false
		App.Util.delay 100, ->
			App.Scroll.setup( $$('#stage') )
			return

# Initialize Header scroller
$(window).one "load", ->
	App.Header_Scroll.setup( $$('#header') )

# When stuff is resized, - refresh iScroll
Hooks.addAction "theme.ready", ->
	App.Scroll.refresh()
	App.Header_Scroll.refresh()







# //-----------------------------------*/
# // Keyboard Scrolling
# //-----------------------------------*/

if App.config.scroll.keyboard_scroll and not App.config.scroll.snapping
	
	arrows = [37..40]
	STEP = 1
	MULTIPLIER = 1

	$(document).on "keyup", (e) ->
		
		if e.keyCode is 16
			MULTIPLIER = 1

		return if STEP is 1
		
		if e.keyCode in arrows
			STEP = 1

		return


	scroll_with_arrows = (e) ->

		return unless e.keyCode in arrows

		
		key = e.keyCode

		# Up or Down
		if key is 38 or key is 40
			return unless App.Scroll.active.y[0]

			MOVE = App.config.scroll.speedY * MULTIPLIER * STEP

			e.preventDefault()

			iS = App.Scroll.active.y[0]
					
			MAX = iS.maxScrollY
			Y = iS.y

			# If Direction is DOWN
			if key is 40
				MOVE *= -1
				if ( Y + MOVE ) < MAX
					MOVE = MAX - Y
			else
				if MOVE + Y >= 0
					MOVE = 0 - Y

			
			iS.scrollBy(0, MOVE )


		# Left or Right
		if key is 37 or key is 39
			return unless App.Scroll.active.x[0]

			MOVE = App.config.scroll.speedX * MULTIPLIER * STEP

			e.preventDefault()

			iS = App.Scroll.active.x[0]
					
			MAX = iS.maxScrollX
			X = iS.x

			if key is 39 # RIGHT 
				MOVE *= -1
				if ( X + MOVE ) < MAX
					MOVE = MAX - X
			
			else # LEFT
				if MOVE + X >= 0
					MOVE = 0 - X

			iS.scrollBy( MOVE, 0 )



		STEP += 0.5 if STEP < 30

		return 

	$(document).on "keydown", (e) ->
			return if App.is.gallery or App.is.responsive

			# Multiply the scroll when SHIFT is pressed
			if e.keyCode is 16
				MULTIPLIER = 10

			scroll_with_arrows(e)




	$(document).on "keyup", (e) ->
		
		return unless e.keyCode in [33..36]
		
		if App.Scroll.active.x[0]
			iS = App.Scroll.active.x[0]
			AXIS = "x"
		
		else if App.Scroll.active.y[0]
			iS = App.Scroll.active.y[0]
			AXIS = "y"

		return unless iS


		key = e.keyCode


		# End
		if key is 35
			iS.scrollTo(iS.maxScrollX, iS.maxScrollY, 200)

		# Home
		if key is 36
			iS.scrollTo(0,0,200)


		return if AXIS is "x"

		# PgDown
		if key is 34
			iS.scrollBy(0, App.win.height*-0.75, 200)

		# PgUp
		if key is 33
			iS.scrollBy(0, App.win.height*0.75, 200)



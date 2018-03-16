class Header_Class
	constructor: ->
		@mode = null

		@is_setup = $.Deferred()
		@is_setup.promise()

		
		@state =
			hiding: false
			showing: false
			visible: false

		# Hooks.addAction "theme.ready", _.once(@setup_sizes), 5

		$.when( @is_setup ).then ->
			Hooks.addAction "theme.responsive", @setup_mode, 5
			Hooks.addAction "theme.regular", @setup_mode, 5

	toggle: =>

		return if @state.hiding or @state.showing

		if @state.visible
			@hide()
		else
			@show()

	setup_mode: ( mode ) ->
		
		if not _.isString( mode ) or App.is.responsive
			if App.is.responsive
				mode = 'mobile'
			else if App.is.regular
				mode = 'regular'
			else
				mode = 'responsive'

		mode = Hooks.applyFilters("header.mode", mode)


		
		if @mode isnt mode
			@setup_sizes( mode )
			if @_set(mode)
				@mode = mode
		return



	setup_sizes: ( mode ) =>
		c = App.config.size
		
		
		
		previous_size = _.clone(@size)

		if mode is 'regular'
			header_width = c.header_width
			transform = c.header_width

		if mode is 'responsive'
			header_width = c.header_width + c.header_toggle_width
			transform = c.header_width			

		if mode is 'mobile'
			header_width = c.header_width
			transform = c.header_width		

		

		@size = 
			toggle_width: c.header_toggle_width
			width: header_width
			transform: transform
		
		if not _.isEqual(@size, previous_size)
			Hooks.doAction( 'header.resized' )


		return @size

	maybe_transform: (num) ->

		if @state.visible or @state.showing
			return 0
		else
			return num

	setup: ( responsive = false ) ->
		# if not responsive
		# 	mode = 'regular'
		# else
		# 	mode = 'responsive'
		@is_setup.resolve()
		@setup_mode()

		# @_set( mode )
		# @mode = mode
		
		Hooks.doAction 'header.ready'



	# is_tmp: ->
	# 	(@saved_mode isnt null)

	is_visible: ->
		# (not @is_tmp() and @mode is 'regular')
		@state.visible

	show: =>
		return if @state.showing or @state.visible is true

		@state.showing = true
		$$('#header').velocity
			properties:
				translateX: 0
			options:
				easing: 'ease-out'
				duration: if App.Ready then 400 else 0
				complete: => 
					@state.visible = true
					@state.showing = false
					return
	hide: =>
		return if @mode is 'regular'
		return if @state.hiding is true or @state.visible is false

		@state.hiding = true

		$$('#header').velocity
			properties:
				translateX: -@size.transform
			options:
				easing: 'ease-out'
				duration: if App.Ready then 400 else 0
				complete: => 
					@state.visible = false
					@state.hiding = false
					
					return

	reset: ->
		# We don't have an actual "Previous State"
		return if @mode is null
		return if @mode is 'mobile'
		# return if not @is_tmp()
		# return if @mode is @saved_mode
		


		# if @saved_mode is 'regular'
		# 	@show()
		# else
		# 	@hide()

		# @mode = @saved_mode
		# @saved_mode = null
		# @_set( @mode )

	_set: ( mode ) ->
		# Don't overwrite the mode if there is a saved mode
		# Saved mode means that something is temoprary going on
		# and is going to take care of resetting everything to normal
		if @is_setup.state() isnt 'resolved'
			return false


		# @setup_sizes( mode )
		Hooks.doAction( "header.set", mode )
		

		duration = if App.Ready then 200 else 0


		if mode is 'regular'
			$$('.header__toggle').velocity
				properties: 
					'fadeOut'
				options:
					display: 'none'
					duration: duration
			
			$$('#header').velocity
				properties:
					width: @size.width
					translateX: 0
				options:
					duration: duration * 1.5
			
			$$("html")
				.addClass('header--regular')
				.removeClass('header--toggleable')

		if mode is 'responsive'	or mode is 'mobile'			
			
			$$('.header__toggle').velocity	
				properties: 'fadeIn'
				options:
					display: 'table'
					duration: duration
			
			$$('#header').velocity
				properties:
					width: @size.width
					translateX: @maybe_transform( -@size.transform )
				options:
					duration: duration * 1.5
   

			$$("html")
				.removeClass('header--regular')
				.addClass('header--toggleable')
			
		return true



	set_to: ( mode = 'regular', temporary = false ) ->

		# We're not shooting blanks here
		return if mode is @mode
		
		$.when( @is_setup ).then =>			
			@setup_mode( mode )

		return this

class Scroll_Handle
	constructor: ( settings ) ->
		@init = false

		@active = 
			x: []
			y: []

		defaults = 
			force_parallax: false
			
			callbacks:
				x: null
				y: null

			x: # Horizontal
				interactiveScrollbars: true
				mouseWheel: true
				mouseWheelSpeed: App.config.scroll.speedX
				scrollX: true
				scrollY: false
				tap: "click" if Modernizr.touch
				keyBindings: ( App.config.scroll.keyboard_scroll )
				snap: App.config.scroll.snapping
				
				hasHorizontalScroll: true
				hasVerticalScroll: false


				# Optimize options for Touch Devices
				scrollbars: if App.config.scroll.hs_scrollbar and not Modernizr.touch then 'custom' else false
				shrinkScrollbars: if Modernizr.touch then 'clip' else 'scale'
				bounce: not Modernizr.touch # If touch then false, else true

			y: # Vertical
				interactiveScrollbars: true
				mouseWheel: true
				mouseWheelSpeed: App.config.scroll.speedY
				scrollbars: 'custom'
				scrollX: false
				tap: "click" if Modernizr.touch
				keyBindings: if App.config.scroll.keyboard_scroll then up: 40, down: 38 else false
				snap: App.config.scroll.snapping

		

		@settings = $.extend true, {}, defaults, settings


	setup: ( $page ) ->
		# Always clean up before proceeding
		@destroy()
		
		if @settings.x
			@setup_horizontal( $page )

		if @settings.y
			@setup_vertical( $page )
	
		@maybe_hide_active_scrollbars() 

		@init = true

		return

	setup_vertical: ( $page ) ->
		# Find Scroll containers
		$scroll = $page.find(".js__scroll")

		# Call quits if no containers
		return if $scroll.length is 0

		enable_parallax = ( @settings.enable_parallax and ( ( @settings.callbacks.y and not Modernizr.touch ) or @settings.force_parallax ) )

		# Loop over containers
		for scroller, key in $scroll
			$scroller = $(scroller)
			$scroller.children().wrapAll('<div class="js__scroll__canvas"/>')

			
			is_nested = ( $scroller.closest(".js__scroll--horizontal").length > 0 )


			if enable_parallax or is_nested is true
				@settings.y.probeType = 3


			@active.y[key] = new IScroll scroller, @settings.y

			if is_nested
				@active.y[key].on "scroll", @maybe_stop_propagation
			
			else if enable_parallax
				@active.y[key].on "scroll", @settings.callbacks.y


	setup_horizontal: ( $page ) ->
		
		if $page.find(".js__scroll--horizontal").length > 0
			
			enable_parallax = ( @settings.enable_parallax and ( ( @settings.callbacks.y and not Modernizr.touch ) or @settings.force_parallax ) )

			# Horizontal Scroll
			for scroller, key in $page.find(".js__scroll--horizontal")
				$scroller = $(scroller)
				
				$scroller.removeClass("scroll--horizontal")

				total_width = @get_total_width( $scroller.find(".hscol") )
				$scroller.children().wrapAll("<div class=\"js__scroll__canvas\" style=\"width: #{total_width}px;\"/>")
				
				# Listen for parallax only on the first horizontal element
				if key is 0 and enable_parallax
					@settings.x.probeType = 3
				else
					@settings.x.probeType = 0
				
				# Create iScroll
				@active.x[key] = new IScroll scroller, @settings.x

				# Maybe attach event on Scroll ?
				if key is 0 and enable_parallax
					@active.x[key].on "scroll", @settings.callbacks.x

				


	# NOTE:
	# No FAT ARROW here,
	# @ ( `this` ) refers to the current iScroll Object
	maybe_stop_propagation: ->		
			if @y <= @maxScrollY or @y is 0
				@options.stopPropagation = false
			else
				@options.stopPropagation = true
			return

	



	destroy: ( $page )->
		return if @active.x.length is 0 and @active.y.length is 0

		iS.destroy() for iS in @active.x
		iS.destroy() for iS in @active.y

		@active = 
			x: []
			y: []

		if $('.js__scroll__canvas').length > 0
			$$('#stage').find(".js__scroll__canvas").children().unwrap()


	refresh: =>

		return false if @init is false

		# Horizontal 
		for scroller in @active.x
			# Set up $scroller
			$scroller = $(scroller.scroller)
			
			# Hide scrollbar before refreshing
			$(scroller.wrapper).removeClass("hide-scrollbar")
			
			# Get the new width
			total_width = @get_total_width( $scroller.find(".hscol") )
			$scroller.width( total_width )

			# Refresh iScroll X
			scroller.refresh() 


		# Vertical
		for scroller in @active.y	
			$(scroller.wrapper).removeClass("hide-scrollbar")
			scroller.refresh() 
		
		@maybe_hide_active_scrollbars() 

		return

	maybe_hide_active_scrollbars: ->
		for scrollbar, key in @active.x.concat(@active.y)
			@maybe_hide_scrollbar( scrollbar )

	maybe_hide_scrollbar: (IS) ->
			return unless IS.indicators

			indicator = IS.indicators[0]

			scrollbar_is_visible = ( ( indicator.sizeRatioY < 0 ) or ( indicator.sizeRatioX < 0 ) )

			if scrollbar_is_visible
				$( IS.wrapper ).removeClass("hide-scrollbar")
			else
				$( IS.wrapper ).addClass("hide-scrollbar")

	# Get the total width of an array of elements
	get_total_width: ( $elements ) -> 
		width = 0

		$elements.each ->
			width += $(this).outerWidth( true )

		# Compensate for $doubling ( padding )
		width += 80


		return width
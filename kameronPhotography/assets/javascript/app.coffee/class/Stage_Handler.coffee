class Stage_Handler
	
	UNIQUE_COUNT = 0
	DFDs = []
	PURGERY = []
	ON_DONE = new $.Callbacks()


	STAGES_OPENED = 0
	STAGES_OPENED_COMPLETE = 0

	constructor: ->
		@update_animations()

		$(window).on "debouncedresize load", @update_animations

	update_animations: =>
		if App.is.regular or ( App.Gallery and not App.is.responsive )
			@animations = 
				out: App.config.animations.out 
				in: App.config.animations.in
		else	
			@animations = 
				out: App.config.animations.responsive_out
				in: App.config.animations.responsive_in

		return

	# For adding external DFDs like AJAX
	add_promise: ->
		$dfd = new $.Deferred()
		$dfd.promise()

		DFDs.push $dfd

		return $dfd
	
	close: ->
		$stage = $$('#stage', true)
		return if $stage.length is 0

		# Change the ID's, add in-animation class
		$stage = $$('#stage')
					.removeAttr('id')
					.addClass('closing-stage')
		
		# I promise to hide
		$hide = new $.Deferred()
		$hide.promise()

		DFDs.push $hide

		# Animate Away!		
		if $stage.find('#gallery').length > 0
			$container = $stage
		else
			$stage.children().wrapAll('<div class="fade-away-container"/>')
			$container = $stage.find('.fade-away-container')
		
		$container.animo
			animation: @animations.out
			duration: 1.5
			timing: "ease-in"
			keep: true
			, -> 
				$hide.resolve()
				PURGERY.push $stage

		Hooks.doAction 'stage.close'



	purge: ->
		# Make elements go to the abyss...
		for $el in PURGERY
			$el.remove()

		# Anyone left-out ?
		if $$('.closing-stage', true).length > 0
			$$('.closing-stage').remove()
		
		# Clean the purgery
		PURGERY = []

		return 

	add: (data) ->
		ID = ++UNIQUE_COUNT

		$show = new $.Deferred()
		$show.promise()
		DFDs.push $show

		$new_stage = $(data.content)

		$new_stage
			.filter('#stage')
			.first()
			.removeClass('opening-stage closing-stage')
			.attr( 'id', "stage-tmp-#{ID}" )
			.addClass('opening-stage')
			.css('z-index', 50 + ID)


		# Animate-in new stage
		$new_stage.animo
			animation: @animations.in
			duration: 0.6
			timing: "ease-out"
			keep: false
			, =>
				$show.resolve()
				

		@wait_to_complete( ID, DFDs.length )

		# ON_DONE.add ( LAST_ID ) ->
		# 	if LAST_ID isnt ID
		# 		$new_stage.remove() 
		
		return $new_stage		

	wait_to_complete: ( ID, dfd_length )->
		return if ID isnt UNIQUE_COUNT

		$.when.apply( null, DFDs ).done =>
			# Don't do it if there is an ID mismach
			return if ID isnt UNIQUE_COUNT
			return if DFDs.length isnt dfd_length

			$$("#stage-tmp-#{ID}", true)
				.attr('id', 'stage')
				.removeClass('opening-stage')
				.css('z-index', '')
			
			$$('#stage', true)

			$$('.opening-stage', true).remove()

			ON_DONE.fire( ID )
			ON_DONE.empty()

			Hooks.doAction( 'stage.complete' )

			@purge()

			UNIQUE_COUNT = 0
			DFDs = []

			return
		
	

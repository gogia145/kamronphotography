return if $$('#fullscreen-gallery').length is 0
return if $$('#enter-site').length is 0

defaults =
	play: App.config.slider.duration
	animation: App.config.slider.animation
	animation_speed: App.config.slider.animation_speed
	animation_easing: App.config.slider.animation_easing
	# inherit_height_from: '.js__bgslide'
	key_navigation: false
	pagination: false

setup_slides = ( refresh = false ) ->
	$slider = $$('#fullscreen-gallery')
							
	slide_config = $.extend {}, defaults, $slider.data("slidesConfig")
	
	$slider.superslides( slide_config )

	if $slider.find('img').length is 1
		$slider.superslides('stop')
	


load_site = _.once ->
	if not Modernizr.cssanimations
		window.location.href = $$('#enter-site').attr('href')
		return

	$$('html').animo
		animation: 'do-fadeOutUp'
		duration: 0.6
		keep: true
		, ->
			window.location.href = $$('#enter-site').attr('href')
			return


# //-----------------------------------*/
# // Events
# //-----------------------------------*/    
$(document).ready(setup_slides)


fit_into_bounds = ( rect, bounds ) ->
	rectRatio = rect.width / rect.height
	boundsRatio = bounds.width / bounds.height

	if rectRatio > boundsRatio
		output = 
			width: bounds.width
			height: rect.height * ( bounds.width / rect.width )

	else
		output = 
			width: rect.width * ( bounds.height / rect.height )
			height: bounds.height

	return output

scale_into_bounds = ( source, bounds ) ->

	if not source or not source.maxWidth or not source.maxHeight
		return {
			width: 0, 
			height: 0
		}

	rect = 
		width: source.maxWidth
		height: source.maxHeight


	bounds_ratio = App.win.height / App.win.width
	bounds = 
		width: App.win.width * source.width
		height: (App.win.width * source.width) * bounds_ratio


	dimensions = fit_into_bounds rect, bounds


	if dimensions.width > rect.width
		return rect

	return dimensions

if $$('#enter-site').length > 0 then do ->

	logo = {}
	link = {}

	# Get the Logo & Link values only once
	Hooks.addAction 'theme.resized', _.once ->
		logo = $$('.fs__overlay__logo').data()
		if logo
			logo.ratio = logo.maxWidth / logo.maxHeight
			logo.height = logo.width / logo.ratio
		else
			logo = false

		
		link = $$('.fs__overlay__link').data()
		if link.maxWidth
			link.ratio = link.maxWidth / link.maxHeight
			link.height = link.width / link.ratio
		else
			link = false

		return


	$(document).one 'click mousewheel scroll', (e) ->
		e.preventDefault()
		load_site()



	Hooks.addAction "theme.resized", ->
		$$('#stage.fs').css
			height: App.win.height
			width: App.win.width

		
		logo_dimensions = scale_into_bounds(logo, App.win)
		$$('.fs__overlay__logo img').css logo_dimensions

	
		# //-----------------------------------*/
		# // Resize "Enter Site" Button Dimensions
		# //-----------------------------------*/
		link_dimensions = scale_into_bounds(link, App.win)


		$$('.fs__overlay__link img').css link_dimensions
		$$('.fs__overlay').css 'height', link_dimensions.height + logo_dimensions.height








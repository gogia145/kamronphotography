"use strict"

$ = jQuery
$$ = $.q

$.fx.speeds._default = 700
$.cookie.json = true
# For development purposes - disable animation with Velocity Mock
# $.Velocity.mock = true
window.blockFotoramaData = true

if not window.__VILLAGE_VARS
	window.__VILLAGE_VARS = {}

_WP_Global = __VILLAGE_VARS.wp

__config_defaults = 
	enable_pjax: true
	ignore_pjax: []
	refresh_timeout: 1500
	wp_gallery: true

	loading_enabled: true
	
	# TODO:
	animations:
		responsive_out: 'do-fadeOut'
		responsive_in: 'do-fadeIn'
		out: 'do-fadeOutUp'
		in: 'do-fadeInUp'

	portfolio:
		has_desc: true
		detect_hover: true
		fade_opacity: 40
	
	# TODO: Sizes!	
	size:
		header_toggle_width: 44
		header_width: 220
		image_height: 560
		image_width: 900

	on_scroll:
		enable: true
		force: false
		x: true
		y: true
		speed_x: 100
		speed_y: 100
		
	scroll:
		speedX: 30
		speedY: 30
		hs_scrollbar: true
		keyboard_scroll: true
		snapping: false


	gallery:
		enable: true
		root_url: '/'

		fit: 'cover' 
		transition: "slide"
		loop: false

		thumbnails: true
		thumbnails_overlay: true
		thumbnails_height: 100
		

		mouse_timeout: true
		mouse_timeout_ms: 900

		autoplay: false
		autoplay_stop: true
		autoplay_duration: 3000

		force_thumbnails: false
		force_sidebar: false

		networks: []
	
	slider:
		duration: 3000
		animation: "fade"
		animation_speed: 1300
		animation_easing: "easeInOutQuad"

	scroll_notification:
		enable: true
		enable_cookies: true
		times: 2
		expires: 7



	

Hooks = window.wp.hooks

App = 
	# Config from the Theme
	config: $.extend true, {}, __config_defaults, __VILLAGE_VARS.config 
	
	# States ( need refactor )
	is:
		responsive: null 	# don't know yet
		regular: null 		# don't know yet
		gallery: false 		# I think no...
		
	# Sniffs are going to be here
	sniff: {} 

	win:
		height: 0
		width: 0


	# Some Globals ( initialized later )
	Page: 		false	# Page_Mover()
	Loading:	false 	# Loading_Spinner()
	Util: 		false	# Village_Utilities()
	Location: 	false	# Village_Location()

	Ready: 		false	# Theme is ready for element animation. Before ready use duration 0.
	Load: -> 			# Function Defined in pjax.coffee
	preventLoad: ->		# Optionally prevent the load
		App._isLoadPrevented = true
		return
	_isLoadPrevented: false




#DEBUG:
# window.$$ = $.q
# window.App = App

get_viewport_size = ->
	e = window
	a = "inner"
	
	unless "innerWidth" of window
		a = "client"
		e = document.documentElement or document.body
	
	width: e[a + "Width"]
	height: e[a + "Height"]
	
App.set_window_size = ->
	App.win = get_viewport_size()
	Hooks.doAction "theme.resized"
	return

debounced_window_size = _.debounce App.set_window_size, 300
$(document).ready App.set_window_size
$(window).on "resize orientationchange", debounced_window_size



# //-----------------------------------*/
# // Give Access to the outside world
# //-----------------------------------*/
window.Kameron_Theme = App


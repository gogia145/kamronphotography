return unless App.config.scroll_notification.enable

class Notify
	CONF = App.config.scroll_notification
	COOKIE_KEY = 'icanhaz-scroll'
	COUNT = 0
	
	constructor: ->
		user_knows = $.cookie(COOKIE_KEY, Number)

		if not _.isUndefined(user_knows)
			COUNT = user_knows

	should_attach_event: ->

		if COUNT < CONF.times || CONF.enable_cookies is false
			return true
		else
			return false


	maybe_show: ->
		
		# If no cookies, quickly fade in and that's it
		if CONF.enable_cookies is false
			$$('#scroll-note').fadeIn()
			return
			
		if COUNT < CONF.times
			if $$('body').hasClass('is-horizontal')
				$$('#scroll-note').fadeIn()

	hide: ->
		$$('#scroll-note').fadeOut 500, =>
			if CONF.enable_cookies
				++COUNT
				$.cookie( COOKIE_KEY , COUNT, { expires: CONF.expires, path: '/' } )


Hooks.addAction "theme.ready", _.once ->
	Notification = new Notify()

	# Only attach event if we're ever going to display the messages
	if Notification.should_attach_event()
		debounced_notification = _.debounce(Notification.maybe_show, 400)

		$(window).on "load", debounced_notification
		Hooks.addAction "stage.complete", debounced_notification, 1000
		$$('#scroll-i-get-it').on "click", (e) ->
			e.preventDefault()
			Notification.hide()

Header = new Header_Class()

Hooks.addAction "stage.complete", ->
	$$('#stage, .js__scroll--horizontal').css('overflow', '')

Hooks.addAction "header.set", (mode) ->
	if mode is 'responsive'
		$$('#stage, .js__scroll--horizontal').css('overflow', 'visible')


	

# Hover Header
$$('#header-toggle').hoverIntent
	over: Header.show
	out: Header.hide
	timeout: 200
	interval: 50

# Toggle Header
$$('#header-toggle .js__toggle').click( Header.toggle )


$(window).on "pronto.request", ->
	if Modernizr.touch
		Header.hide()
		
	unless App.is.responsive
		$$('#header__background').velocity
			properties:
				'background-position-y': 0
				'background-position-x': 0
			options:
				duration: 1200
				easing: "ease-in"


App.Header = Header



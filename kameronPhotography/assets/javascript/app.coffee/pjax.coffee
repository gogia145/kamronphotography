return unless window.history.pushState
return unless App.config.enable_pjax

Stage = new Stage_Handler()
$ajax = new $.Deferred()
$render = new $.Deferred()

# Track the clicked url
clicked_url = false


# //-----------------------------------*/
# // Functions
# //-----------------------------------*/
decodeEntities = do ->
	virtual_decoder = document.createElement('div')
	
	decode_html = (str) ->
		if str and typeof str is 'string'

			str = str.replace(/<script[^>]*>([\S\s]*?)<\/script>/gmi, '')
			str = str.replace(/<\/?\w(?:[^"'>]|"[^"]*"|'[^']*')*>/gmi, '')
			
			virtual_decoder.innerHTML = str
			str = virtual_decoder.textContent
			virtual_decoder.textContent = ''

		return str

	return decode_html


# Globalize Loading Function
# Don't use Pronto because it needs to be debounced...
_requested_url = false
load_url = ( url ) ->
	return false if url is "#"

	_requested_url = url
	
	url = Hooks.applyFilters "stage.load_url", url
	Hooks.doAction "stage.load", url

	$.pronto('load', url)

App.Load = _.debounce( load_url, 250, true )

# //-----------------------------------*/
# // Listeners
# //-----------------------------------*/
# On Request ( on Click )
R = false
$(window).on "pronto.request", ->
	return if R is true
	R = true
	$ajax = Stage.add_promise()

	Stage.close()
	return

# On Load Complete
$(window).on "pronto.load", ->
	$ajax.resolve()
	R = false
	return

# Move Manually on Error
$(window).on "pronto.error", ->
	if _requested_url is false
		_requested_url = App.config.gallery.root_url
	
	window.location.href = _requested_url
	return

$(window).on "pronto.render", ->
	_requested_url = false

	$render.resolve()
	$ajax.resolve()
	
	return


# This is how we render:
on_render = (data) ->	
	# Promise to render, but only after Pronto is complete
	# Otherwise ge get a bad state saved ( #stage-away, etc. )
	$render = Stage.add_promise()


	$new_stage = Stage.add( data )
	$$('#content').prepend $new_stage

	$.when( $new_stage ).done ->
		data.body_classes.push("loaded")
		$$('body').attr( 'class', data.body_classes.join(" ") )
		$$('head title').text( decodeEntities( data.title ) )



# //-----------------------------------*/
# // Initialize / Start Pronto
# //-----------------------------------*/
if App.config.ignore_pjax.length > 0
	disable_pjax_on_selectors = ->
		selectors = App.config.ignore_pjax
						.join(', ')
						.replace(/[^\s\w\d\.\-_#,]/g, '')
		$$( selectors ).addClass('no-pjax')
	
	$(window).on "load", disable_pjax_on_selectors
	Hooks.addAction "stage.complete", disable_pjax_on_selectors


$(document).ready ->

	# Listen for .no-pjax class in the main menu
	$$('#main-menu')
		.find('.menu-item.no-pjax')
		.removeClass('no-pjax')
		.find('> a')
		.addClass('no-pjax')

	$.pronto
		selector: '.js__pjax'
		requestKey: "pjax",
		force: true
		target:
			title: 'title'
			content: '#content'
		render: on_render



$(document).on 'click', 'a:not(.no-pjax)',  (e) ->
	$el = $(this)
	
	url = e.currentTarget

	if e.which > 1 or
		e.metaKey or
		e.ctrlKey or
		e.shiftKey or
		e.altKey or
		(window.location.protocol isnt url.protocol or window.location.host isnt url.host) or
		url.target is "_blank" 
			return


	href = $el.attr('href')
	if href? and href isnt "#"
		e.preventDefault()
		
		# Defer:
		# give other events time to complete 
		# and _maybe prevent_ App.Load
		_.defer ->
			if App._isLoadPrevented
				App._isLoadPrevented = false
			else
				App.Load(href)

			return 








# //-----------------------------------*/
# // Slow Load Refresher
# //-----------------------------------*/
_timeout = false
_timeout_duration = App.config.refresh_timeout || 1500

cancel_content_refresh = ->
	return if _timeout is false
	
	clearTimeout(_timeout)
	_timeout = false
	
	return

content_refresh = ->
	if _timeout
		App.Scroll.refresh()
		cancel_content_refresh()

	_timeout = setTimeout(content_refresh, _timeout_duration)
	return

# Initialize
$(document).on "ready", content_refresh
Hooks.addAction "stage.complete", content_refresh

# Stop when images are loaded
stop_refresh = ->	
	$$('#content', true).imagesLoaded().always(cancel_content_refresh)

$(window).on "load", stop_refresh
Hooks.addAction "stage.complete", stop_refresh
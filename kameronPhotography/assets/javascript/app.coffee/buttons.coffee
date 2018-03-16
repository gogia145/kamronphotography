


# Wrap buttons when stage or window has loaded

wrap_buttons = ->
	$("button, input[type=submit], .village-button")
		.not('.js__wrapped')
		.addClass('js__wrapped village-button__inner')
		.wrap('<div class="village-button__wrapper"/>')

Hooks.addAction 'stage.complete', wrap_buttons, 500
$(document).ready(wrap_buttons)
return unless App.config.loading_enabled

is_loading = false

start_loading = ->
	return if is_loading
	is_loading = true
	
	$$('#loading')
		.velocity('stop')
		.velocity('fadeIn')

stop_loading = ->
	$$('#loading')
		.velocity('stop')
		.velocity
			properties: 'fadeOut'
			options:
				complete: ->
					is_loading = false
					return



# Start loading animation as soon as possible
Hooks.addAction "stage.load", start_loading, 5

# Stopping Loading is the last thing we do
Hooks.addAction "stage.complete", stop_loading, 5001

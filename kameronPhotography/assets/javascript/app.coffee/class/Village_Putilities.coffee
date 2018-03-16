class Village_Utilities
	# Syntactic Sugar for CoffeeScript setTimeout
	delay: (ms, func) -> setTimeout func, ms
	to_$: (el) ->
		if _.isString(el) then $(el) else el
	
	random_time: -> _.random(500, 1000) / 1000


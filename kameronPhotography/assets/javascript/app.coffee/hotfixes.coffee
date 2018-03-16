# Fix accidental actions with ?pjax=true
# Just in case
Hooks.addAction "stage.complete", ->
	$('form').each ->
		$form = $(this)
		action = $form.attr('action')

		return unless action

		if action.indexOf('pjax=true') > -1
			action = action.replace('pjax=true', '')
			$form.attr('action', action)

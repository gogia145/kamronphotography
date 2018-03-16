$ = jQuery

options_change = (e) ->
	$elem = $(e.target)
	$parent = $elem.closest(".description")
	$options = $elem.closest(".block-options")

	if $parent.hasClass("js__opacity")
		return adjust_opacity(e, $elem, $options)

	if $parent.hasClass("js__section_title")
		return change_title(e, $elem, $options)


adjust_opacity = (e, $elem, $options) ->
	opacity =  $elem.val()
	opacity_css = opacity / 100

	unless 100 >= opacity >= 0
		$elem.css "background-color": "#fc5c5c"
		e.preventDefault()
		return false
	else
		$elem.css "background-color": ""

	$image = $options.find(".reference-image img")

	if $image.length > 0
		$image.stop().animate opacity: opacity_css, 100

change_title = (e, $elem, $options) ->
	title = $elem.val()
	$title = $options.find(" > .section__title")

	if $title.length
		$title.text( title )




# //-----------------------------------*/
# // On Load
# //-----------------------------------*/
    
init_options = ->
	$options = $(".js__options")
	
	$options
		.not("is-closed")
		.addClass("is-closed")
		.find(".column")
		.slideUp()

	$options.find(".reference-image img").not(".has-preview").each (key, val) -> 
		$this = $(val)
		$this.addClass("has-preview")
		$this.clone()
			.addClass("tiny-preview js__toggle_options")
			.appendTo $this.closest(".js__options")

$(document).on "ready", init_options




$('#blocks-to-edit').on "click", '.js__toggle_options', (e) ->
	e.preventDefault()
	$this = $(e.currentTarget)
	$options = $this.closest(".js__options")
	
	$options.toggleClass("is-closed")
	$options.toggleClass("is-open")
	$options.children(".column").slideToggle()
	$options.children(".tiny-preview").fadeToggle()


$('#blocks-to-edit').on "change input", '.js__options', options_change

$('#blocks-to-edit').on "sortstop", ( e , UI ) ->
	init_options()












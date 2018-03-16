App.Util 		= 	new Village_Utilities()

# //-----------------------------------*/
# // Fade In On Document Load
# //-----------------------------------*/
document_loaded = _.once -> 
	$(document.body).addClass("loaded")
	App.Util.delay 600, ->
		App.Ready = true
		return
Hooks.addAction "theme.ready", document_loaded , 5

# //-----------------------------------*/
# 		Setup Globals 
# //-----------------------------------*/
# 
# Sniff Browsers
# Uncomment as needed?
# 

# UA = navigator.userAgent
# App.sniff.iOS = /(iPhone|iPod)/.test(UA)
# App.sniff.iPad = /iPad/.test(UA)
# App.sniff.isMobile = if App.sniff.iOS || App.sniff.mobileWebkit || __VILLAGE_VARS.isMobile then true else false
# App.sniff.firefox = /'firefox'/.test(UA)


#
# Responsive Checks
# 
check_if_responsive = ->
	is_responsive = 
		if App.win.width < 768
		then true
		else false

	
	was_responsive = 
		if App.is.responsive is null
		then is_responsive
		else App.is.responsive


	if was_responsive and not is_responsive
		App.is.responsive = false
		Hooks.doAction( "theme.responsive", false )
		return

	if not was_responsive and is_responsive
		$(window)
			.scrollTop(0)
			.scrollLeft(0)
		
		App.is.responsive = true
		Hooks.doAction( "theme.responsive", true )
		return

	App.is.responsive = is_responsive

	return

Hooks.addAction("theme.resized", check_if_responsive, 5)


#
# FitVids
#
setup_fitvids = -> 
	$$('#stage').fitVids()
	return

$(window).load ->
	setup_fitvids()

	$$('#main-menu')
		.find('a')
		.filter( -> not this.href || this.href is "#" )
		.addClass('no-link')
		# .each ->
		# 	$el = $(this)
		# 	$parent = $el.parent()
		# 	text = $el.text()

		# 	$el.remove()
		# 	$parent.prepend("<span class=\"menu-item-empty\">#{text}</span>")

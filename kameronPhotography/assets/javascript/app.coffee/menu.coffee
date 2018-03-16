$$('#main-menu').on "click", "a", ->
	
	$el = $(this)
	href = $el.attr('href')

	return if not href or href is '#'

	$$('#main-menu .menu-item')
		.removeClass('current-menu-item')
	
	$(this)
		.closest('.menu-item')
		.addClass('current-menu-item')


Hooks.addAction "stage.complete", ->
	href = window.location.href

	if $('#main-menu .current-menu-item a').attr('href') isnt href	
				
		# Some Regex for your Bunghole
		rpat = /[^a-zA-Z0-9]/g
		flat_href = href.replace(rpat, '')

		# Find me that current item
		$$('#main-menu .menu-item')
			.removeClass('current-menu-item')
			.find('a')
			.filter( -> ( this.href.replace(rpat, '') is flat_href )  )
			.closest('.menu-item')
			.addClass('current-menu-item')




close_icon = "ion-arrow-up-b"
open_icon = "ion-arrow-down-b"

$(document).ready ->
	$('.menu-item-has-children:not(.no-dropdown) > a').addClass('no-pjax')

	$menu_items = $('#main-menu > .menu-item-has-children:not(.no-dropdown)').not('.no-link')

	$menu_items.find( '> .sub-menu').hide()

	$menu_items.find( '> a')
		.wrapInner('<span class="toggle-text"/>')
		.append """
			<span class="toggle-icon">
				<i class="open icon #{open_icon}"></i>
			</span> 
		"""

$('.menu-item-has-children:not(.no-dropdown) > a').on 'click', (e) ->
	$el = $(this).closest('.menu-item-has-children')
	$sub = $el.find('.sub-menu').first()
	$icon = $el.find('.icon').first()

	e.preventDefault()

	if $el.hasClass('is-open')
	
		# Remove is-submenu-open only if there is a single element with ".is-open"
		if $('#navigation .is-open').length is 1
			$('#navigation').removeClass('is-submenu-open')
		
		$el.removeClass('is-open')

		# Close		
		$sub.velocity('slideUp', App.Header_Scroll.refresh )
			
		$icon
			.velocity('fadeOut', 100, -> $icon.removeClass( close_icon ).addClass( open_icon ) )
			.velocity('fadeIn')

	else
		$('#navigation').addClass('is-submenu-open')
		$el.addClass('is-open')
		
		# Open
		$sub.velocity('slideDown', App.Header_Scroll.refresh )
		
		$icon
			.velocity('fadeOut', 100, -> $icon.removeClass( open_icon ).addClass( close_icon ) )
			.velocity('fadeIn' )













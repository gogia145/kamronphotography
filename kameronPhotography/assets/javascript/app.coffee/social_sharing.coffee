# Skip this if Galleries are not enabled
return unless App.config.gallery.enable
return if App.config.gallery.networks.length is 0

network_data =
	googleplus:
		item_class: "googleplus"
		item_data: "googlePlus"

	facebook:
		item_class: "facebook"
		item_data: "facebook"
	
	twitter:
		item_class: "twitter"
		item_data: "twitter"
	
	pinterest:
		item_class: "pinterest"
		item_data: "pinterest"

Sharrre_Config = 
	template: ""
	share: {}


for key, value of App.config.gallery.networks
	item = network_data[key]
	
	network_exists = ( value != "" )
	Sharrre_Config.share[item.item_data] = network_exists

	if network_exists	
		Sharrre_Config.template += """
			<a class="network #{item.item_class}" data-social-network="#{item.item_data}">
		        <i class="icon ion-social-#{item.item_class}"></i>		 
			 </a>
		"""

return if Sharrre_Config.template == ""


SHARRRE = false

setup_social_sharing = (e, fotorama) ->
	gallery_cover_image = fotorama.data[ fotorama.activeIndex ].img

	$sharrre = $$('#gallery__share', true).find('.sharrre')

	$sharrre.sharrre
			share: Sharrre_Config.share
			urlCurl: ''
			buttons:
				pinterest:
					 media: gallery_cover_image

			enableHover: false
			template: Sharrre_Config.template

	# Set up the instance
	SHARRRE = $sharrre.data('plugin_sharrre')
	
	# Refresh jQache
	$$('#gallery__share .share__networks', true)

	return

$$('#content').on 'fotorama:ready', _.debounce(setup_social_sharing, 200)
$$('#content').on 'click', '.network', ->
	return unless SHARRRE

	$el = $(this)

	network = $el.data('socialNetwork')
	return unless network?

	SHARRRE.update(window.location.href, '')
	SHARRRE.openPopup( network )

###
	Hover Intent
###
network_toggle =
	show: ->
		return unless App.Gallery
		$$('#gallery__share .share__networks').velocity
			properties:
				translateY: [0, 15]
				opacity: [1, 0]
			options:
				duration: 200
				easing: "easeInQuad"
				display: "block"

	hide: ->
		return unless App.Gallery
		$$('#gallery__share .share__networks')
			.velocity('reverse', display: 'none')
		return


if not Modernizr.touch
	$$('#page').hoverIntent
		selector: '#gallery__share'
		timeout: 350
		interval: 50

		over: network_toggle.show
		out: network_toggle.hide

$$('#page').on "click", '#gallery__share', ->
	$el = $(this)

	if $el.hasClass('is-open')
		$el.removeClass('is-open')
		network_toggle.hide()
	else
		$el.addClass('is-open')
		network_toggle.show()


$('#gallery__share').on 'click', '.network', (e) ->
	return unless SHARRRE
	
	$el = $(this)
	network = $el.data('socialNetwork')
	

	url = $$('.entry-item').data('url')
	current_image = App.Gallery.fotorama.activeIndex || 0
	

	divider = if _WP_Global.pretty_permalinks then "?" else "&"

	share_url = "#{url}#{divider}image=#{current_image}"

	
	SHARRRE.options.buttons.pinterest.media = $('.fotorama__active img').first().attr('src')

	return unless network?

	SHARRRE.update( share_url, '')
	SHARRRE.openPopup( network )

	e.preventDefault()



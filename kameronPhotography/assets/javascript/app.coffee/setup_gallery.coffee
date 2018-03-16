# Skip this if Galleries are not enabled
return unless App.config.gallery.enable

# ----------------------------------*/
# Implementation
# ----------------------------------*/

###
    Track the document URL
    To figure out if we're going history or pronto back
###
document_url = null
$(window).on "pronto.request", ->
    document_url = window.location.href
    return



# ----------------------------------*/
# Gallery Setup
# ----------------------------------*/
    
# Gallery Instance Variable
App.Gallery = false

gallery_view_defaults = 
    thumbs: true
    sidebar: true


setup_gallery_now = ->
    
    # Clean up & Refesh Data
    $$.clear()
    App.Gallery = false

    # Fetch new data
    gallery_data = $('#gallery__stage').data('gallery')
    
    
    # Create a new gallery
    if gallery_data?    
        App.Gallery = new Gallery_Handle(gallery_view_defaults, gallery_data) 
        App.Header.setup_mode()
        App.Header.hide()

        return true

setup_gallery = ->
    if document.getElementById('gallery')?
        if App.Gallery and App.Gallery.closing isnt false
            $.when( App.Gallery.closing ).done( setup_gallery_now )
        else
            setup_gallery_now()
    else
        App.Gallery = false
        return


gallery_auto_close = ->
    if App.Gallery.close? and not App.Gallery.closing
        App.Gallery.close()
        App.Header.setup_mode()
    return

gallery_resolve_dfd = ->
    if App.Gallery and App.Gallery.closing.promise?
        App.Gallery.destroy()
        App.Gallery.closing.resolve()
    

# Initialize Gallery 
# if $$('#gallery').length > 0
Hooks.addAction "header.ready", _.once(setup_gallery), 5

Hooks.addAction 'stage.close', gallery_auto_close
Hooks.addAction "stage.complete", setup_gallery, 25
Hooks.addAction 'stage.complete', gallery_resolve_dfd, 30

Hooks.addAction 'theme.resized', ->
    return if not App.Gallery
    return if not App.Gallery.fotorama

    App.Gallery.fotorama.resize
        width: App.win.width
        height: App.win.height

# ----------------------------------*/
# Listen for various clicks
# ----------------------------------*/
$(document).on 'click', '#gallery__close', (e) ->
    e.preventDefault()
    e.stopPropagation()

    if App.Gallery.close?
        App.Gallery.close( document_url )
        App.Header.setup_mode()

$$('#header').on 'click', 'a', (e) ->
    $this = $(this)
    return if $this.is('.no-pjax')

    if App.Gallery 
        url = $this.attr('href')
        if url and url isnt '#'
            App.Gallery.close( url )
            App.Header.setup_mode()


# When a gallery is open
# Filter the "Header Mode" to be only "responsive
# Unless it's a Mobile device
Hooks.addFilter "header.mode", ( mode ) ->    
    if App.Gallery and App.Gallery.closing is false and not App.is.responsive
        mode = 'responsive'
    return mode



$(document).on 'click', '#gallery__thumbs__toggle', (e) ->
    App.Gallery.toggle_thumbnails()

$(document).on 'click', '#gallery__sidebar__close', (e) ->
    App.Gallery.hide_sidebar( not App.is.responsive )

$(document).on 'click', '#gallery__sidebar__open', (e) ->
    App.Gallery.show_sidebar( not App.is.responsive )

Hooks.addAction "theme.ready", ->
    # return unless Gallery
    if App.Gallery and App.Gallery.fotorama
        App.Gallery.fotorama.resize()


# //-----------------------------------*/
# // Detect Mouse Movement
# //-----------------------------------*/
if App.config.gallery.mouse_timeout
    # Localally Global References
    TIMEOUT = null
    BLOCK_ACTION = false
    CONTROLS_HIDDEN = false
    
    # Kind of config
    MOUSE_TIMEOUT = App.config.gallery.mouse_timeout_ms
    TOGGLE_CLASS = "js__mouse-not-moving fotorama__wrap--no-controls"
    BLOCK_SELECTORS = '#gallery__share, .gallery__button, .fotorama__caption, .fotorama__wrap--video, .fotorama__nav--thumbs'

    # Maybe turn this into an option in the future?
    if Modernizr.touch 
        MOUSE_TIMEOUT *= 2

    hide_gallery_controls = ->
        return if BLOCK_ACTION
        return if CONTROLS_HIDDEN
        
        # Hide Controls
        $$('#gallery').addClass( TOGGLE_CLASS )
        CONTROLS_HIDDEN = true
        return

    show_gallery_controls = ->
        return if BLOCK_ACTION
        return unless CONTROLS_HIDDEN
        
        # Show Controls
        $$('#gallery').removeClass( TOGGLE_CLASS )
        CONTROLS_HIDDEN = false
        return


    debounced_hide_gallery_controls = _.debounce( hide_gallery_controls, MOUSE_TIMEOUT )

    mouse_check_movement = ->
        # Quits if no gallery
        return unless App.Gallery

        show_gallery_controls()
        debounced_hide_gallery_controls()

        return
    
    mouse_throttling_timeout = Math.round(MOUSE_TIMEOUT / 2)
    throttle_mouse_check = _.throttle( mouse_check_movement, mouse_throttling_timeout, trailing: false )
    
    # Attach Events
    $(document).on 'mousemove touchend', throttle_mouse_check

    $(document).on 'mouseenter', BLOCK_SELECTORS, ->
        return unless App.Gallery
        return if App.Gallery.disabled is true
        BLOCK_ACTION = true
        return

    $(document).on 'mouseleave', BLOCK_SELECTORS, ->
        return unless App.Gallery
        return if App.Gallery.disabled is true
        BLOCK_ACTION = false
        return

    $(document).on 'fotorama:loadvideo', ->        
        hide_gallery_controls()
        BLOCK_ACTION = true
        return

    $(document).on 'fotorama:unloadvideo', ->        
        BLOCK_ACTION = false
        show_gallery_controls()
        return
    


# if App.is.responsive
#     $(window).on 'scroll touchmove', (e) ->
#         if Gallery.disabled is false
#             e.preventDefault()
#             e.stopPropagation()
#             return false













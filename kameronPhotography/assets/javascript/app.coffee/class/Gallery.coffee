class Gallery_Handle
    # It's either there or not there
    # Find out only once
    HISTORY_SUPPORT = window.history and window.history.pushState and window.history.replaceState
    CONF = App.config.gallery

    ### 
        Initialize
    ###
    constructor: ( external_view_defaults, data )->
        
        # Parse Data
        @data = @parse_images( data )

        # No fotorama yet
        @currentID = 0
        @fotorama = false
        @disabled = false
        @previous_data = {}
        @events_attached = false


        @closing = false

        view_defaults = 
            thumbs: false
            sidebar: false

               
        @view = $.extend true, {}, view_defaults, external_view_defaults, $.cookie('village_gallery')
        
        @back_url = CONF.root_url
        if CONF.thumbnails_overlay
            $$('#gallery').addClass('overlay-thumbs')

        
        if @data.descriptions is 0
            @destroy_sidebar()            
        else 
            @prepare_sidebar()
            
            if @view.sidebar is false or App.is.responsive
                @hide_sidebar()
            else
                @show_sidebar()

        if not CONF.thumbnails
            @view.thumbs = false
            
        if CONF.force_thumbnails
            @view.thumbs = true
        
        if CONF.force_sidebar
            @show_sidebar()


        # Setup & Init Events
        @setup_fotorama(@data.images)
        @setup_layout()

        if @fotorama
            @attach_events()     
    
    setup_layout: ->
        return unless @fotorama

        current =
            sidebar: $$('#gallery').hasClass('is-full')
            thumbs: $$('#gallery').hasClass('with-thumbs')

        changed = false

        if @view.thumbs isnt current.thumbs
            changed = true

            if @view.thumbs is true
                @show_thumbnails()
            else
                @hide_thumbnails()

        if @view.sidebar isnt current.sidebar
            changed = true

            if @view.sidebar is true 
                @show_sidebar()
            else
                @hide_sidebar()


        if changed
            @fotorama.resize()

        return this
    
    setup_fotorama: ( images ) ->

        # Setup Fotorama Settings
        fotorama_settings =
            fit: CONF.fit
            transition: CONF.transition

            # Size
            width: App.win.width
            height: if CONF.thumbnails_overlay then App.win.height else "100%"
            
            # Thumbnails
            thumbheight: CONF.thumbnails_height
            nav: if CONF.thumbnails and @view.thumbs is true then "thumbs" else false
            thumbfit: 'scaledown'

            # Autoplay
            autoplay: if CONF.autoplay then CONF.autoplay_duration else false
            stopautoplayontouch: CONF.autoplay_stop
            loop: CONF.loop

            # The actual data
            data: images
            hash: true

            # Enable keyboard navigation
            keyboard: true

        $$('#gallery__stage', true).fotorama(fotorama_settings)
        

        @fotorama = $$('#gallery__stage').data('fotorama')
      
        $$('#gallery .fotorama__nav', true)

        return this

    close: ( previous_url = false ) ->
        @closing = new $.Deferred()
        @closing.promise()

        # Not sure what, but something-sometimes is sending in "false" instead of (bool) false
        if not previous_url or previous_url is false or previous_url is window.location.href or previous_url is "false"
            previous_url = @back_url

        App.Load(previous_url)
        return @closing


    disable_addons: =>
        @disabled = true
        $$('#gallery').addClass('disable-addons')

        return

    enable_addons: =>
        @disabled = false
        $$('#gallery').removeClass('disable-addons')

        return


    destroy: ->
        @detach_events()
        @fotorama.destroy() if @fotorama

        $$('#gallery').remove()

        @fotorama = false
        
        # Clear jQache
        $$.clear()
        
        return

    


    ###
        Sidebar Functions
    ###
    destroy_sidebar: ->
        $$('#gallery').addClass('is-full no-sidebar')
        @view.sidebar = false
        @detach_events()

    prepare_sidebar: ->
        $$('#gallery').removeClass('no-sidebar')
        @attach_events()



    update_sidebar: ( data, instant = false ) ->

        return if @view.sidebar is false
        return if @previous_data is data
        
        if instant is true
            @update_sidebar_content( data )
            $$('#gallery__sidebar .content').show()
        else
            $$('#gallery__sidebar .content')
                .velocity('stop')
                .velocity
                    properties: "fadeOut"
                    options: 
                        duration: 200
                        complete: =>
                            if data.desc or data.caption
                                @update_sidebar_content(data)
                                $$('#gallery__sidebar .content').velocity('fadeIn')
                            else 
                                @hide_sidebar()
    
    update_sidebar_content: ( data ) ->
        return if data is @previous_data
        $$('#gallery__sidebar .title').html( data.caption )
        $$('#gallery__sidebar .desc').html( data.desc )
        @previous_data = data
        return

    hide_sidebar: ( resize = true ) =>
        
        return if @view.sidebar is false

        $$('#gallery')
            .addClass('is-full')
            .removeClass('show-sidebar')
        
        @fotorama.resize()      if resize and @fotorama #exists
        
        # Set current status
        @view.sidebar = false

        $.cookie('village_gallery', @view, path: '/') 
                
        return


    show_sidebar: ( resize = true ) =>

        data = @data.images[ @currentID ]
        return unless data.caption or data.desc

        # $$('#gallery__sidebar').show()
        $$('#gallery')
            .addClass('show-sidebar')
            .removeClass('is-full')
        
        if resize is true and @fotorama #exists
            @fotorama.resize()      
        
        # Set current status
        @view.sidebar = true
        $.cookie('village_gallery', @view, path: '/')


        @setup_current_image( true )

        return


    ###
        Thumbnails Toggle
    ###
    show_thumbnails: =>
        thumb_height = App.config.gallery.thumbnails_height

        if @view.thumbs isnt true
            @fotorama.setOptions(nav: "thumbs")
            $$('#gallery .fotorama__nav', true)
        
        # Animate 
        $$('#gallery .gallery__interaction').velocity 
            properties:
               translateY: [ -thumb_height, 0 ]
            options:
                duration: 200
                easing: "easeIn"

        $$('#gallery .fotorama__nav').velocity
            properties: 
                translateY: [ 0, thumb_height ]
            options:
                duration: 200
                easing: "easeIn"
                display: "block"
                complete: =>
                    # Set current status
                    @view.thumbs = true
                    $.cookie('village_gallery', @view, path: '/')
                    $$('#gallery').addClass('show-thumbnails')  

                    unless App.config.gallery.thumbnails_overlay
                        @fotorama.resize( height: App.win.height - thumb_height ) 

    hide_thumbnails: =>
        
        thumb_height = App.config.gallery.thumbnails_height

        $$('#gallery .gallery__interaction').velocity 
            properties:
               translateY: [ 0, -thumb_height ]
            options:
                duration: 200
                easing: "easeOut"

        $$('#gallery .fotorama__nav').velocity
                properties:
                    translateY: [ thumb_height, 0 ]
                options:
                    duration: 200
                    easing: "easeOut"
                    display: "none"
                    complete: (els) =>        
                        # Set current status
                        @view.thumbs = false
                        $.cookie('village_gallery', @view, path: '/')
                        $$('#gallery').removeClass('show-thumbnails')  

                        unless App.config.gallery.thumbnails_overlay
                            @fotorama.resize( height: App.win.height ) 

    toggle_thumbnails: ->
        if @view.thumbs is true
            @hide_thumbnails()
        else
            @show_thumbnails()


    ### 
        Slider Navigation
    ###
    maybe_hide_description: ( index ) ->
        image = @data.images[ index ]

        if image.desc
            $$('#gallery').removeClass('no-sidebar')
            
            if CONF.force_sidebar
                @show_sidebar()
        else
            $$('#gallery').addClass('no-sidebar')
            @hide_sidebar()




    set_fotorama_image: (e, fotorama) =>

        @set_current_id( fotorama.activeIndex )

        if @data.descriptions > 0 and @data.descriptions isnt @data.images.length
            @maybe_hide_description( fotorama.activeIndex )
        
        @setup_current_image()
                

    set_current_id: ( ID ) ->
        if ID is @currentID
            return false
        else
            @currentID = ID
            return true

    setup_current_image: ( instant ) ->
        
        if @view.sidebar
            data = @data.images[ @currentID ]
            
            @update_sidebar( data, instant )




     parse_images: ( data ) ->
        parsed = {}
        images = []

        descriptions = 0
        captions = 0
        
        for i in data
            images.push
                img: i.img
                thumb: i.thumb
                caption: i.caption
                desc: i.desc
                thumbratio: i.thumbwidth / i.thumbheight
                video: i.video

            if i.desc
                descriptions++

            if i.caption
                captions++

        parsed = 
            images: images
            descriptions: descriptions
            captions: captions

        return parsed

    # //-----------------------------------*/
    # // Events
    # //-----------------------------------*/
    detach_events: ->
        return unless @events_attached
        $$('#gallery__stage').off 'fotorama:showend', @set_fotorama_image
        $$('#gallery__stage').off 'fotorama:loadvideo', @disable_addons
        $$('#gallery__stage').off 'fotorama:unloadvideo', @enable_addons
        @events_attached = false
        return
    
    attach_events: ->   
        return if @events_attached
        $$('#gallery__stage').on 'fotorama:showend', @set_fotorama_image
        $$('#gallery__stage').on 'fotorama:loadvideo', @disable_addons
        $$('#gallery__stage').on 'fotorama:unloadvideo', @enable_addons
        @events_attached = true
        return  


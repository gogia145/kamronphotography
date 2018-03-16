(function() {
  var BLOCK_ACTION, BLOCK_SELECTORS, CONTROLS_HIDDEN, MOUSE_TIMEOUT, TIMEOUT, TOGGLE_CLASS, debounced_hide_gallery_controls, document_url, gallery_auto_close, gallery_resolve_dfd, gallery_view_defaults, hide_gallery_controls, mouse_check_movement, mouse_throttling_timeout, setup_gallery, setup_gallery_now, show_gallery_controls, throttle_mouse_check;

  if (!App.config.gallery.enable) {
    return;
  }


  /*
      Track the document URL
      To figure out if we're going history or pronto back
   */

  document_url = null;

  $(window).on("pronto.request", function() {
    document_url = window.location.href;
  });

  App.Gallery = false;

  gallery_view_defaults = {
    thumbs: true,
    sidebar: true
  };

  setup_gallery_now = function() {
    var gallery_data;
    $$.clear();
    App.Gallery = false;
    gallery_data = $('#gallery__stage').data('gallery');
    if (gallery_data != null) {
      App.Gallery = new Gallery_Handle(gallery_view_defaults, gallery_data);
      App.Header.setup_mode();
      App.Header.hide();
      return true;
    }
  };

  setup_gallery = function() {
    if (document.getElementById('gallery') != null) {
      if (App.Gallery && App.Gallery.closing !== false) {
        return $.when(App.Gallery.closing).done(setup_gallery_now);
      } else {
        return setup_gallery_now();
      }
    } else {
      App.Gallery = false;
    }
  };

  gallery_auto_close = function() {
    if ((App.Gallery.close != null) && !App.Gallery.closing) {
      App.Gallery.close();
      App.Header.setup_mode();
    }
  };

  gallery_resolve_dfd = function() {
    if (App.Gallery && (App.Gallery.closing.promise != null)) {
      App.Gallery.destroy();
      return App.Gallery.closing.resolve();
    }
  };

  Hooks.addAction("header.ready", _.once(setup_gallery), 5);

  Hooks.addAction('stage.close', gallery_auto_close);

  Hooks.addAction("stage.complete", setup_gallery, 25);

  Hooks.addAction('stage.complete', gallery_resolve_dfd, 30);

  Hooks.addAction('theme.resized', function() {
    if (!App.Gallery) {
      return;
    }
    if (!App.Gallery.fotorama) {
      return;
    }
    return App.Gallery.fotorama.resize({
      width: App.win.width,
      height: App.win.height
    });
  });

  $(document).on('click', '#gallery__close', function(e) {
    e.preventDefault();
    e.stopPropagation();
    if (App.Gallery.close != null) {
      App.Gallery.close(document_url);
      return App.Header.setup_mode();
    }
  });

  $$('#header').on('click', 'a', function(e) {
    var $this, url;
    $this = $(this);
    if ($this.is('.no-pjax')) {
      return;
    }
    if (App.Gallery) {
      url = $this.attr('href');
      if (url && url !== '#') {
        App.Gallery.close(url);
        return App.Header.setup_mode();
      }
    }
  });

  Hooks.addFilter("header.mode", function(mode) {
    if (App.Gallery && App.Gallery.closing === false && !App.is.responsive) {
      mode = 'responsive';
    }
    return mode;
  });

  $(document).on('click', '#gallery__thumbs__toggle', function(e) {
    return App.Gallery.toggle_thumbnails();
  });

  $(document).on('click', '#gallery__sidebar__close', function(e) {
    return App.Gallery.hide_sidebar(!App.is.responsive);
  });

  $(document).on('click', '#gallery__sidebar__open', function(e) {
    return App.Gallery.show_sidebar(!App.is.responsive);
  });

  Hooks.addAction("theme.ready", function() {
    if (App.Gallery && App.Gallery.fotorama) {
      return App.Gallery.fotorama.resize();
    }
  });

  if (App.config.gallery.mouse_timeout) {
    TIMEOUT = null;
    BLOCK_ACTION = false;
    CONTROLS_HIDDEN = false;
    MOUSE_TIMEOUT = App.config.gallery.mouse_timeout_ms;
    TOGGLE_CLASS = "js__mouse-not-moving fotorama__wrap--no-controls";
    BLOCK_SELECTORS = '#gallery__share, .gallery__button, .fotorama__caption, .fotorama__wrap--video, .fotorama__nav--thumbs';
    if (Modernizr.touch) {
      MOUSE_TIMEOUT *= 2;
    }
    hide_gallery_controls = function() {
      if (BLOCK_ACTION) {
        return;
      }
      if (CONTROLS_HIDDEN) {
        return;
      }
      $$('#gallery').addClass(TOGGLE_CLASS);
      CONTROLS_HIDDEN = true;
    };
    show_gallery_controls = function() {
      if (BLOCK_ACTION) {
        return;
      }
      if (!CONTROLS_HIDDEN) {
        return;
      }
      $$('#gallery').removeClass(TOGGLE_CLASS);
      CONTROLS_HIDDEN = false;
    };
    debounced_hide_gallery_controls = _.debounce(hide_gallery_controls, MOUSE_TIMEOUT);
    mouse_check_movement = function() {
      if (!App.Gallery) {
        return;
      }
      show_gallery_controls();
      debounced_hide_gallery_controls();
    };
    mouse_throttling_timeout = Math.round(MOUSE_TIMEOUT / 2);
    throttle_mouse_check = _.throttle(mouse_check_movement, mouse_throttling_timeout, {
      trailing: false
    });
    $(document).on('mousemove touchend', throttle_mouse_check);
    $(document).on('mouseenter', BLOCK_SELECTORS, function() {
      if (!App.Gallery) {
        return;
      }
      if (App.Gallery.disabled === true) {
        return;
      }
      BLOCK_ACTION = true;
    });
    $(document).on('mouseleave', BLOCK_SELECTORS, function() {
      if (!App.Gallery) {
        return;
      }
      if (App.Gallery.disabled === true) {
        return;
      }
      BLOCK_ACTION = false;
    });
    $(document).on('fotorama:loadvideo', function() {
      hide_gallery_controls();
      BLOCK_ACTION = true;
    });
    $(document).on('fotorama:unloadvideo', function() {
      BLOCK_ACTION = false;
      show_gallery_controls();
    });
  }

}).call(this);

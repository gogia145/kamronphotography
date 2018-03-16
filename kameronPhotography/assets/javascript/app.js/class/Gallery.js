var Gallery_Handle,
  bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

Gallery_Handle = (function() {
  var CONF, HISTORY_SUPPORT;

  HISTORY_SUPPORT = window.history && window.history.pushState && window.history.replaceState;

  CONF = App.config.gallery;


  /* 
      Initialize
   */

  function Gallery_Handle(external_view_defaults, data) {
    this.set_fotorama_image = bind(this.set_fotorama_image, this);
    this.hide_thumbnails = bind(this.hide_thumbnails, this);
    this.show_thumbnails = bind(this.show_thumbnails, this);
    this.show_sidebar = bind(this.show_sidebar, this);
    this.hide_sidebar = bind(this.hide_sidebar, this);
    this.enable_addons = bind(this.enable_addons, this);
    this.disable_addons = bind(this.disable_addons, this);
    var view_defaults;
    this.data = this.parse_images(data);
    this.currentID = 0;
    this.fotorama = false;
    this.disabled = false;
    this.previous_data = {};
    this.events_attached = false;
    this.closing = false;
    view_defaults = {
      thumbs: false,
      sidebar: false
    };
    this.view = $.extend(true, {}, view_defaults, external_view_defaults, $.cookie('village_gallery'));
    this.back_url = CONF.root_url;
    if (CONF.thumbnails_overlay) {
      $$('#gallery').addClass('overlay-thumbs');
    }
    if (this.data.descriptions === 0) {
      this.destroy_sidebar();
    } else {
      this.prepare_sidebar();
      if (this.view.sidebar === false || App.is.responsive) {
        this.hide_sidebar();
      } else {
        this.show_sidebar();
      }
    }
    if (!CONF.thumbnails) {
      this.view.thumbs = false;
    }
    if (CONF.force_thumbnails) {
      this.view.thumbs = true;
    }
    if (CONF.force_sidebar) {
      this.show_sidebar();
    }
    this.setup_fotorama(this.data.images);
    this.setup_layout();
    if (this.fotorama) {
      this.attach_events();
    }
  }

  Gallery_Handle.prototype.setup_layout = function() {
    var changed, current;
    if (!this.fotorama) {
      return;
    }
    current = {
      sidebar: $$('#gallery').hasClass('is-full'),
      thumbs: $$('#gallery').hasClass('with-thumbs')
    };
    changed = false;
    if (this.view.thumbs !== current.thumbs) {
      changed = true;
      if (this.view.thumbs === true) {
        this.show_thumbnails();
      } else {
        this.hide_thumbnails();
      }
    }
    if (this.view.sidebar !== current.sidebar) {
      changed = true;
      if (this.view.sidebar === true) {
        this.show_sidebar();
      } else {
        this.hide_sidebar();
      }
    }
    if (changed) {
      this.fotorama.resize();
    }
    return this;
  };

  Gallery_Handle.prototype.setup_fotorama = function(images) {
    var fotorama_settings;
    fotorama_settings = {
      fit: CONF.fit,
      transition: CONF.transition,
      width: App.win.width,
      height: CONF.thumbnails_overlay ? App.win.height : "100%",
      thumbheight: CONF.thumbnails_height,
      nav: CONF.thumbnails && this.view.thumbs === true ? "thumbs" : false,
      thumbfit: 'scaledown',
      autoplay: CONF.autoplay ? CONF.autoplay_duration : false,
      stopautoplayontouch: CONF.autoplay_stop,
      loop: CONF.loop,
      data: images,
      hash: true,
      keyboard: true
    };
    $$('#gallery__stage', true).fotorama(fotorama_settings);
    this.fotorama = $$('#gallery__stage').data('fotorama');
    $$('#gallery .fotorama__nav', true);
    return this;
  };

  Gallery_Handle.prototype.close = function(previous_url) {
    if (previous_url == null) {
      previous_url = false;
    }
    this.closing = new $.Deferred();
    this.closing.promise();
    if (!previous_url || previous_url === false || previous_url === window.location.href || previous_url === "false") {
      previous_url = this.back_url;
    }
    App.Load(previous_url);
    return this.closing;
  };

  Gallery_Handle.prototype.disable_addons = function() {
    this.disabled = true;
    $$('#gallery').addClass('disable-addons');
  };

  Gallery_Handle.prototype.enable_addons = function() {
    this.disabled = false;
    $$('#gallery').removeClass('disable-addons');
  };

  Gallery_Handle.prototype.destroy = function() {
    this.detach_events();
    if (this.fotorama) {
      this.fotorama.destroy();
    }
    $$('#gallery').remove();
    this.fotorama = false;
    $$.clear();
  };


  /*
      Sidebar Functions
   */

  Gallery_Handle.prototype.destroy_sidebar = function() {
    $$('#gallery').addClass('is-full no-sidebar');
    this.view.sidebar = false;
    return this.detach_events();
  };

  Gallery_Handle.prototype.prepare_sidebar = function() {
    $$('#gallery').removeClass('no-sidebar');
    return this.attach_events();
  };

  Gallery_Handle.prototype.update_sidebar = function(data, instant) {
    if (instant == null) {
      instant = false;
    }
    if (this.view.sidebar === false) {
      return;
    }
    if (this.previous_data === data) {
      return;
    }
    if (instant === true) {
      this.update_sidebar_content(data);
      return $$('#gallery__sidebar .content').show();
    } else {
      return $$('#gallery__sidebar .content').velocity('stop').velocity({
        properties: "fadeOut",
        options: {
          duration: 200,
          complete: (function(_this) {
            return function() {
              if (data.desc || data.caption) {
                _this.update_sidebar_content(data);
                return $$('#gallery__sidebar .content').velocity('fadeIn');
              } else {
                return _this.hide_sidebar();
              }
            };
          })(this)
        }
      });
    }
  };

  Gallery_Handle.prototype.update_sidebar_content = function(data) {
    if (data === this.previous_data) {
      return;
    }
    $$('#gallery__sidebar .title').html(data.caption);
    $$('#gallery__sidebar .desc').html(data.desc);
    this.previous_data = data;
  };

  Gallery_Handle.prototype.hide_sidebar = function(resize) {
    if (resize == null) {
      resize = true;
    }
    if (this.view.sidebar === false) {
      return;
    }
    $$('#gallery').addClass('is-full').removeClass('show-sidebar');
    if (resize && this.fotorama) {
      this.fotorama.resize();
    }
    this.view.sidebar = false;
    $.cookie('village_gallery', this.view, {
      path: '/'
    });
  };

  Gallery_Handle.prototype.show_sidebar = function(resize) {
    var data;
    if (resize == null) {
      resize = true;
    }
    data = this.data.images[this.currentID];
    if (!(data.caption || data.desc)) {
      return;
    }
    $$('#gallery').addClass('show-sidebar').removeClass('is-full');
    if (resize === true && this.fotorama) {
      this.fotorama.resize();
    }
    this.view.sidebar = true;
    $.cookie('village_gallery', this.view, {
      path: '/'
    });
    this.setup_current_image(true);
  };


  /*
      Thumbnails Toggle
   */

  Gallery_Handle.prototype.show_thumbnails = function() {
    var thumb_height;
    thumb_height = App.config.gallery.thumbnails_height;
    if (this.view.thumbs !== true) {
      this.fotorama.setOptions({
        nav: "thumbs"
      });
      $$('#gallery .fotorama__nav', true);
    }
    $$('#gallery .gallery__interaction').velocity({
      properties: {
        translateY: [-thumb_height, 0]
      },
      options: {
        duration: 200,
        easing: "easeIn"
      }
    });
    return $$('#gallery .fotorama__nav').velocity({
      properties: {
        translateY: [0, thumb_height]
      },
      options: {
        duration: 200,
        easing: "easeIn",
        display: "block",
        complete: (function(_this) {
          return function() {
            _this.view.thumbs = true;
            $.cookie('village_gallery', _this.view, {
              path: '/'
            });
            $$('#gallery').addClass('show-thumbnails');
            if (!App.config.gallery.thumbnails_overlay) {
              return _this.fotorama.resize({
                height: App.win.height - thumb_height
              });
            }
          };
        })(this)
      }
    });
  };

  Gallery_Handle.prototype.hide_thumbnails = function() {
    var thumb_height;
    thumb_height = App.config.gallery.thumbnails_height;
    $$('#gallery .gallery__interaction').velocity({
      properties: {
        translateY: [0, -thumb_height]
      },
      options: {
        duration: 200,
        easing: "easeOut"
      }
    });
    return $$('#gallery .fotorama__nav').velocity({
      properties: {
        translateY: [thumb_height, 0]
      },
      options: {
        duration: 200,
        easing: "easeOut",
        display: "none",
        complete: (function(_this) {
          return function(els) {
            _this.view.thumbs = false;
            $.cookie('village_gallery', _this.view, {
              path: '/'
            });
            $$('#gallery').removeClass('show-thumbnails');
            if (!App.config.gallery.thumbnails_overlay) {
              return _this.fotorama.resize({
                height: App.win.height
              });
            }
          };
        })(this)
      }
    });
  };

  Gallery_Handle.prototype.toggle_thumbnails = function() {
    if (this.view.thumbs === true) {
      return this.hide_thumbnails();
    } else {
      return this.show_thumbnails();
    }
  };


  /* 
      Slider Navigation
   */

  Gallery_Handle.prototype.maybe_hide_description = function(index) {
    var image;
    image = this.data.images[index];
    if (image.desc) {
      $$('#gallery').removeClass('no-sidebar');
      if (CONF.force_sidebar) {
        return this.show_sidebar();
      }
    } else {
      $$('#gallery').addClass('no-sidebar');
      return this.hide_sidebar();
    }
  };

  Gallery_Handle.prototype.set_fotorama_image = function(e, fotorama) {
    this.set_current_id(fotorama.activeIndex);
    if (this.data.descriptions > 0 && this.data.descriptions !== this.data.images.length) {
      this.maybe_hide_description(fotorama.activeIndex);
    }
    return this.setup_current_image();
  };

  Gallery_Handle.prototype.set_current_id = function(ID) {
    if (ID === this.currentID) {
      return false;
    } else {
      this.currentID = ID;
      return true;
    }
  };

  Gallery_Handle.prototype.setup_current_image = function(instant) {
    var data;
    if (this.view.sidebar) {
      data = this.data.images[this.currentID];
      return this.update_sidebar(data, instant);
    }
  };

  Gallery_Handle.prototype.parse_images = function(data) {
    var captions, descriptions, i, images, j, len, parsed;
    parsed = {};
    images = [];
    descriptions = 0;
    captions = 0;
    for (j = 0, len = data.length; j < len; j++) {
      i = data[j];
      images.push({
        img: i.img,
        thumb: i.thumb,
        caption: i.caption,
        desc: i.desc,
        thumbratio: i.thumbwidth / i.thumbheight,
        video: i.video
      });
      if (i.desc) {
        descriptions++;
      }
      if (i.caption) {
        captions++;
      }
    }
    parsed = {
      images: images,
      descriptions: descriptions,
      captions: captions
    };
    return parsed;
  };

  Gallery_Handle.prototype.detach_events = function() {
    if (!this.events_attached) {
      return;
    }
    $$('#gallery__stage').off('fotorama:showend', this.set_fotorama_image);
    $$('#gallery__stage').off('fotorama:loadvideo', this.disable_addons);
    $$('#gallery__stage').off('fotorama:unloadvideo', this.enable_addons);
    this.events_attached = false;
  };

  Gallery_Handle.prototype.attach_events = function() {
    if (this.events_attached) {
      return;
    }
    $$('#gallery__stage').on('fotorama:showend', this.set_fotorama_image);
    $$('#gallery__stage').on('fotorama:loadvideo', this.disable_addons);
    $$('#gallery__stage').on('fotorama:unloadvideo', this.enable_addons);
    this.events_attached = true;
  };

  return Gallery_Handle;

})();

(function() {
  var ADMIN_BAR, CONFIG, CSS, CSS_Handler, Entries, Entry_Handler, FRAME, Master, SIZE, Sizemaster, on_content_load, on_window_resize,
    bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

  CONFIG = App.config.size;

  SIZE = {};

  FRAME = {};

  ADMIN_BAR = null;

  CSS_Handler = (function() {
    function CSS_Handler() {
      this.update = bind(this.update, this);
      this.previous_style = "";
    }

    CSS_Handler.prototype.update = function() {
      var style;
      style = App.is.responsive ? this.get_responsive() : this.get_regular();
      if (this.previous_style === style) {
        return;
      }
      if ($$('#js-style').length !== 1) {
        this._writeDOM(style);
      } else {
        $$('#js-style').html(style);
      }
      this.previous_style = style;
    };

    CSS_Handler.prototype._writeDOM = function(css) {
      var head, style;
      head = document.head || document.getElementsByTagName('head')[0];
      style = document.createElement('style');
      style.type = 'text/css';
      style.id = 'js-style';
      style.appendChild(document.createTextNode(css));
      head.appendChild(style);
      return $$('#js-style', true);
    };

    CSS_Handler.prototype.get_responsive = function() {
      return "#gallery {\n	height: " + App.win.height + "px;\n	width: " + App.win.width + "px;			\n}\n\n.js__stage_height {\n	height: " + App.win.height + "px;\n}\n";
    };

    CSS_Handler.prototype.get_regular = function() {
      return "body {\n	height: " + FRAME.height + "px;\n}\n\n.stage .hscol {\n	height: " + SIZE.entry.height + "px;\n}\n\n.hscol.js__service {\n	width: " + SIZE.entry.height + "px;\n	margin-top: " + SIZE.entry.margin + "px;\n}\n\n.stage, .js__winsize, .js__page {\n	height: " + SIZE.content.height + "px;\n	width: " + SIZE.content.width + "px;\n}\n\n.js__stage_height {\n	height: " + FRAME.height + "px;\n}\n\n.js__hscol {\n	margin-top: " + SIZE.entry.margin + "px;	\n}\n\n#gallery {\n	width: " + SIZE.container.width + "px;\n	height: " + SIZE.container.height + "px;\n}\n";
    };

    return CSS_Handler;

  })();

  Entry_Handler = (function() {
    function Entry_Handler() {
      this.update = bind(this.update, this);
      this.entries = {};
    }

    Entry_Handler.prototype.get = function() {
      return $$('.stage:not(.closing-stage)', true).find('.hscol').not('.service');
    };

    Entry_Handler.prototype.set_responsive = function() {
      return this.entries.css('width', '');
    };

    Entry_Handler.prototype.update = function() {
      this.entries = this.get();
      if (this.entries.length === 0) {
        return;
      }
      if (App.is.responsive) {
        return this.set_responsive();
      } else if (SIZE.short) {
        return this.scaleXY();
      } else {
        return this.scaleX();
      }
    };

    Entry_Handler.prototype.scaleX = function() {
      var $first;
      $$('html').removeClass('layout--short');
      $first = this.entries.first();
      if ($first.data('entryWidth') !== $first.width()) {
        return this.entries.each(function(key, el) {
          var $el, width;
          $el = $(el);
          width = $el.data('entryWidth');
          if (width > 0) {
            return $el.width(width);
          }
        });
      }
    };

    Entry_Handler.prototype.scaleXY = function() {
      $$('html').addClass('layout--short');
      return this.entries.each(function(key, el) {
        var $el, new_width, width;
        $el = $(el);
        width = $el.data('entryWidth');
        if (width > 0) {
          new_width = Math.ceil(width * SIZE.entry.ratio);
          return $el.width(new_width);
        }
      });
    };

    return Entry_Handler;

  })();

  Sizemaster = (function() {
    function Sizemaster() {}

    Sizemaster.prototype.setup_size = function() {
      var container, content, enough_space, height, margin, ratio, short, width;
      FRAME = _.clone(App.win);
      if (ADMIN_BAR === null) {
        ADMIN_BAR = (document.getElementById('wpadminbar') != null);
      }
      if (ADMIN_BAR) {
        FRAME.height -= 32;
      }
      margin = Math.floor((FRAME.height - CONFIG.image_height) / 2);
      height = Math.floor(FRAME.height - (margin * 2)) - 1;
      width = CONFIG.image_width;
      ratio = 0;
      short = margin < 10;
      if (short) {
        margin = Math.round(FRAME.height * 0.05);
        height = FRAME.height - (margin * 2);
      }
      ratio = height / CONFIG.image_height;
      if (short) {
        width = CONFIG.image_width * ratio;
      }
      enough_space = FRAME.width >= (CONFIG.image_width + CONFIG.header_width);
      container = {
        width: FRAME.width - CONFIG.header_toggle_width,
        height: FRAME.height
      };
      content = {
        width: enough_space ? FRAME.width - CONFIG.header_width : container.width,
        height: FRAME.height
      };
      SIZE = {
        container: container,
        content: content,
        entry: {
          width: width,
          height: height,
          margin: margin,
          ratio: ratio
        },
        short: short
      };
    };

    Sizemaster.prototype.set_layout_classes = function() {
      if (App.is.responsive) {
        return $$("html").removeClass("layout--regular").addClass("layout--responsive");
      } else {
        return $$("html").removeClass("layout--responsive").addClass("layout--regular");
      }
    };

    Sizemaster.prototype.change_app_state = function() {
      var enough_space;
      enough_space = FRAME.width >= (CONFIG.image_width + CONFIG.header_width);
      if (App.is.responsive === false && enough_space) {
        App.is.regular = true;
      } else {
        App.is.regular = false;
      }
      Hooks.doAction("theme.ready");
      App.Header.setup(!App.is.regular);
      if (App.is.regular && !App.Gallery) {
        return FRAME.width -= CONFIG.header_width;
      } else {
        return FRAME.width -= CONFIG.header_toggle_width;
      }
    };

    return Sizemaster;

  })();

  CSS = new CSS_Handler();

  Entries = new Entry_Handler();

  Master = new Sizemaster();

  on_content_load = function() {
    return Entries.update();
  };

  on_window_resize = function() {
    if (!App.is.responsive) {
      Master.setup_size();
    }
    CSS.update();
    Master.change_app_state();
    Entries.update();
    if (App.Gallery && App.Gallery.fotorama) {
      return App.Gallery.fotorama.resize(FRAME);
    }
  };

  Hooks.addAction("theme.resized", _.once(Master.set_layout_classes), 20);

  Hooks.addAction("theme.responsive", Master.set_layout_classes);

  Hooks.addAction("theme.resized", on_window_resize, 30);

  $(window).on("pronto.render", on_content_load);

}).call(this);

var Header_Class,
  bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

Header_Class = (function() {
  function Header_Class() {
    this.hide = bind(this.hide, this);
    this.show = bind(this.show, this);
    this.setup_sizes = bind(this.setup_sizes, this);
    this.toggle = bind(this.toggle, this);
    this.mode = null;
    this.is_setup = $.Deferred();
    this.is_setup.promise();
    this.state = {
      hiding: false,
      showing: false,
      visible: false
    };
    $.when(this.is_setup).then(function() {
      Hooks.addAction("theme.responsive", this.setup_mode, 5);
      return Hooks.addAction("theme.regular", this.setup_mode, 5);
    });
  }

  Header_Class.prototype.toggle = function() {
    if (this.state.hiding || this.state.showing) {
      return;
    }
    if (this.state.visible) {
      return this.hide();
    } else {
      return this.show();
    }
  };

  Header_Class.prototype.setup_mode = function(mode) {
    if (!_.isString(mode) || App.is.responsive) {
      if (App.is.responsive) {
        mode = 'mobile';
      } else if (App.is.regular) {
        mode = 'regular';
      } else {
        mode = 'responsive';
      }
    }
    mode = Hooks.applyFilters("header.mode", mode);
    if (this.mode !== mode) {
      this.setup_sizes(mode);
      if (this._set(mode)) {
        this.mode = mode;
      }
    }
  };

  Header_Class.prototype.setup_sizes = function(mode) {
    var c, header_width, previous_size, transform;
    c = App.config.size;
    previous_size = _.clone(this.size);
    if (mode === 'regular') {
      header_width = c.header_width;
      transform = c.header_width;
    }
    if (mode === 'responsive') {
      header_width = c.header_width + c.header_toggle_width;
      transform = c.header_width;
    }
    if (mode === 'mobile') {
      header_width = c.header_width;
      transform = c.header_width;
    }
    this.size = {
      toggle_width: c.header_toggle_width,
      width: header_width,
      transform: transform
    };
    if (!_.isEqual(this.size, previous_size)) {
      Hooks.doAction('header.resized');
    }
    return this.size;
  };

  Header_Class.prototype.maybe_transform = function(num) {
    if (this.state.visible || this.state.showing) {
      return 0;
    } else {
      return num;
    }
  };

  Header_Class.prototype.setup = function(responsive) {
    if (responsive == null) {
      responsive = false;
    }
    this.is_setup.resolve();
    this.setup_mode();
    return Hooks.doAction('header.ready');
  };

  Header_Class.prototype.is_visible = function() {
    return this.state.visible;
  };

  Header_Class.prototype.show = function() {
    if (this.state.showing || this.state.visible === true) {
      return;
    }
    this.state.showing = true;
    return $$('#header').velocity({
      properties: {
        translateX: 0
      },
      options: {
        easing: 'ease-out',
        duration: App.Ready ? 400 : 0,
        complete: (function(_this) {
          return function() {
            _this.state.visible = true;
            _this.state.showing = false;
          };
        })(this)
      }
    });
  };

  Header_Class.prototype.hide = function() {
    if (this.mode === 'regular') {
      return;
    }
    if (this.state.hiding === true || this.state.visible === false) {
      return;
    }
    this.state.hiding = true;
    return $$('#header').velocity({
      properties: {
        translateX: -this.size.transform
      },
      options: {
        easing: 'ease-out',
        duration: App.Ready ? 400 : 0,
        complete: (function(_this) {
          return function() {
            _this.state.visible = false;
            _this.state.hiding = false;
          };
        })(this)
      }
    });
  };

  Header_Class.prototype.reset = function() {
    if (this.mode === null) {
      return;
    }
    if (this.mode === 'mobile') {

    }
  };

  Header_Class.prototype._set = function(mode) {
    var duration;
    if (this.is_setup.state() !== 'resolved') {
      return false;
    }
    Hooks.doAction("header.set", mode);
    duration = App.Ready ? 200 : 0;
    if (mode === 'regular') {
      $$('.header__toggle').velocity({
        properties: 'fadeOut',
        options: {
          display: 'none',
          duration: duration
        }
      });
      $$('#header').velocity({
        properties: {
          width: this.size.width,
          translateX: 0
        },
        options: {
          duration: duration * 1.5
        }
      });
      $$("html").addClass('header--regular').removeClass('header--toggleable');
    }
    if (mode === 'responsive' || mode === 'mobile') {
      $$('.header__toggle').velocity({
        properties: 'fadeIn',
        options: {
          display: 'table',
          duration: duration
        }
      });
      $$('#header').velocity({
        properties: {
          width: this.size.width,
          translateX: this.maybe_transform(-this.size.transform)
        },
        options: {
          duration: duration * 1.5
        }
      });
      $$("html").removeClass('header--regular').addClass('header--toggleable');
    }
    return true;
  };

  Header_Class.prototype.set_to = function(mode, temporary) {
    if (mode == null) {
      mode = 'regular';
    }
    if (temporary == null) {
      temporary = false;
    }
    if (mode === this.mode) {
      return;
    }
    $.when(this.is_setup).then((function(_this) {
      return function() {
        return _this.setup_mode(mode);
      };
    })(this));
    return this;
  };

  return Header_Class;

})();

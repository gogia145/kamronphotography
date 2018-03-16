(function() {
  var MULTIPLIER, PARALLAX, STEP, arrows, get_navalax_pos, scroll_settings, scroll_with_arrows, setup_scroll,
    indexOf = [].indexOf || function(item) { for (var i = 0, l = this.length; i < l; i++) { if (i in this && this[i] === item) return i; } return -1; };

  PARALLAX = {
    x: App.config.on_scroll.x,
    y: App.config.on_scroll.y,
    speed_x: App.config.on_scroll.speed_x / 100,
    speed_y: App.config.on_scroll.speed_y / 100,
    container_x: App.config.size.header_width + App.config.size.header_toggle_width,
    height: $$('#header__background').data("height"),
    width: $$('#header__background').data("width")
  };

  get_navalax_pos = function(position, max_position, item_size, container_size, speed_modifier) {
    var ratio;
    ratio = (item_size - container_size) / max_position * speed_modifier;
    return position * -ratio;
  };

  scroll_settings = {
    enable_parallax: App.config.on_scroll.enable,
    force_parallax: App.config.on_scroll.force,
    callbacks: {
      y: function() {
        return $$('#header__background').css({
          "background-position-y": get_navalax_pos(this.y, this.maxScrollY, PARALLAX.height, App.win.height, PARALLAX.speed_y)
        });
      },
      x: function() {
        return $$('#header__background').css({
          "background-position-x": get_navalax_pos(this.x, this.maxScrollX, PARALLAX.width, PARALLAX.container_x, PARALLAX.speed_x)
        });
      }
    }
  };

  App.Scroll = new Scroll_Handle(scroll_settings);

  App.Header_Scroll = new Scroll_Handle({
    x: false,
    y: {
      keyBindings: false,
      snap: false
    }
  });

  $(window).on("load iscroll:refresh debouncedresize", function() {
    return App.Scroll.refresh();
  });

  setup_scroll = function() {
    if (App.is.responsive) {
      return;
    }
    App.Scroll.setup($$('#stage'));
    return $$('.js__scroll').scrollTop(0).scrollLeft(0);
  };

  Hooks.addAction("theme.ready", _.once(function() {
    if (App.is.responsive) {
      return;
    }
    setup_scroll();
    return $(window).on("load hashchange", function(e) {
      if (!window.location.hash) {
        return;
      }
      if (App.Scroll.active.y.length === 0) {
        return;
      }
      if ($$(window.location.hash).length) {
        App.Scroll.active.y[0].scrollToElement(window.location.hash, 0);
        return $$('.js__scroll').scrollTop(0).scrollLeft(0);
      }
    });
  }));

  Hooks.addAction("stage.complete", setup_scroll, 1001);

  Hooks.addAction("theme.responsive", function(responsive) {
    if (responsive === true) {
      App.Scroll.destroy();
    }
    if (responsive === false) {
      return App.Util.delay(100, function() {
        App.Scroll.setup($$('#stage'));
      });
    }
  });

  $(window).one("load", function() {
    return App.Header_Scroll.setup($$('#header'));
  });

  Hooks.addAction("theme.ready", function() {
    App.Scroll.refresh();
    return App.Header_Scroll.refresh();
  });

  if (App.config.scroll.keyboard_scroll && !App.config.scroll.snapping) {
    arrows = [37, 38, 39, 40];
    STEP = 1;
    MULTIPLIER = 1;
    $(document).on("keyup", function(e) {
      var ref;
      if (e.keyCode === 16) {
        MULTIPLIER = 1;
      }
      if (STEP === 1) {
        return;
      }
      if (ref = e.keyCode, indexOf.call(arrows, ref) >= 0) {
        STEP = 1;
      }
    });
    scroll_with_arrows = function(e) {
      var MAX, MOVE, X, Y, iS, key, ref;
      if (ref = e.keyCode, indexOf.call(arrows, ref) < 0) {
        return;
      }
      key = e.keyCode;
      if (key === 38 || key === 40) {
        if (!App.Scroll.active.y[0]) {
          return;
        }
        MOVE = App.config.scroll.speedY * MULTIPLIER * STEP;
        e.preventDefault();
        iS = App.Scroll.active.y[0];
        MAX = iS.maxScrollY;
        Y = iS.y;
        if (key === 40) {
          MOVE *= -1;
          if ((Y + MOVE) < MAX) {
            MOVE = MAX - Y;
          }
        } else {
          if (MOVE + Y >= 0) {
            MOVE = 0 - Y;
          }
        }
        iS.scrollBy(0, MOVE);
      }
      if (key === 37 || key === 39) {
        if (!App.Scroll.active.x[0]) {
          return;
        }
        MOVE = App.config.scroll.speedX * MULTIPLIER * STEP;
        e.preventDefault();
        iS = App.Scroll.active.x[0];
        MAX = iS.maxScrollX;
        X = iS.x;
        if (key === 39) {
          MOVE *= -1;
          if ((X + MOVE) < MAX) {
            MOVE = MAX - X;
          }
        } else {
          if (MOVE + X >= 0) {
            MOVE = 0 - X;
          }
        }
        iS.scrollBy(MOVE, 0);
      }
      if (STEP < 30) {
        STEP += 0.5;
      }
    };
    $(document).on("keydown", function(e) {
      if (App.is.gallery || App.is.responsive) {
        return;
      }
      if (e.keyCode === 16) {
        MULTIPLIER = 10;
      }
      return scroll_with_arrows(e);
    });
    $(document).on("keyup", function(e) {
      var AXIS, iS, key, ref;
      if (ref = e.keyCode, indexOf.call([33, 34, 35, 36], ref) < 0) {
        return;
      }
      if (App.Scroll.active.x[0]) {
        iS = App.Scroll.active.x[0];
        AXIS = "x";
      } else if (App.Scroll.active.y[0]) {
        iS = App.Scroll.active.y[0];
        AXIS = "y";
      }
      if (!iS) {
        return;
      }
      key = e.keyCode;
      if (key === 35) {
        iS.scrollTo(iS.maxScrollX, iS.maxScrollY, 200);
      }
      if (key === 36) {
        iS.scrollTo(0, 0, 200);
      }
      if (AXIS === "x") {
        return;
      }
      if (key === 34) {
        iS.scrollBy(0, App.win.height * -0.75, 200);
      }
      if (key === 33) {
        return iS.scrollBy(0, App.win.height * 0.75, 200);
      }
    });
  }

}).call(this);

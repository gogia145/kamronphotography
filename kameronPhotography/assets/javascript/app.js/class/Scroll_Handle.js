var Scroll_Handle,
  bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

Scroll_Handle = (function() {
  function Scroll_Handle(settings) {
    this.refresh = bind(this.refresh, this);
    var defaults;
    this.init = false;
    this.active = {
      x: [],
      y: []
    };
    defaults = {
      force_parallax: false,
      callbacks: {
        x: null,
        y: null
      },
      x: {
        interactiveScrollbars: true,
        mouseWheel: true,
        mouseWheelSpeed: App.config.scroll.speedX,
        scrollX: true,
        scrollY: false,
        tap: Modernizr.touch ? "click" : void 0,
        keyBindings: App.config.scroll.keyboard_scroll,
        snap: App.config.scroll.snapping,
        hasHorizontalScroll: true,
        hasVerticalScroll: false,
        scrollbars: App.config.scroll.hs_scrollbar && !Modernizr.touch ? 'custom' : false,
        shrinkScrollbars: Modernizr.touch ? 'clip' : 'scale',
        bounce: !Modernizr.touch
      },
      y: {
        interactiveScrollbars: true,
        mouseWheel: true,
        mouseWheelSpeed: App.config.scroll.speedY,
        scrollbars: 'custom',
        scrollX: false,
        tap: Modernizr.touch ? "click" : void 0,
        keyBindings: App.config.scroll.keyboard_scroll ? {
          up: 40,
          down: 38
        } : false,
        snap: App.config.scroll.snapping
      }
    };
    this.settings = $.extend(true, {}, defaults, settings);
  }

  Scroll_Handle.prototype.setup = function($page) {
    this.destroy();
    if (this.settings.x) {
      this.setup_horizontal($page);
    }
    if (this.settings.y) {
      this.setup_vertical($page);
    }
    this.maybe_hide_active_scrollbars();
    this.init = true;
  };

  Scroll_Handle.prototype.setup_vertical = function($page) {
    var $scroll, $scroller, enable_parallax, i, is_nested, key, len, results, scroller;
    $scroll = $page.find(".js__scroll");
    if ($scroll.length === 0) {
      return;
    }
    enable_parallax = this.settings.enable_parallax && ((this.settings.callbacks.y && !Modernizr.touch) || this.settings.force_parallax);
    results = [];
    for (key = i = 0, len = $scroll.length; i < len; key = ++i) {
      scroller = $scroll[key];
      $scroller = $(scroller);
      $scroller.children().wrapAll('<div class="js__scroll__canvas"/>');
      is_nested = $scroller.closest(".js__scroll--horizontal").length > 0;
      if (enable_parallax || is_nested === true) {
        this.settings.y.probeType = 3;
      }
      this.active.y[key] = new IScroll(scroller, this.settings.y);
      if (is_nested) {
        results.push(this.active.y[key].on("scroll", this.maybe_stop_propagation));
      } else if (enable_parallax) {
        results.push(this.active.y[key].on("scroll", this.settings.callbacks.y));
      } else {
        results.push(void 0);
      }
    }
    return results;
  };

  Scroll_Handle.prototype.setup_horizontal = function($page) {
    var $scroller, enable_parallax, i, key, len, ref, results, scroller, total_width;
    if ($page.find(".js__scroll--horizontal").length > 0) {
      enable_parallax = this.settings.enable_parallax && ((this.settings.callbacks.y && !Modernizr.touch) || this.settings.force_parallax);
      ref = $page.find(".js__scroll--horizontal");
      results = [];
      for (key = i = 0, len = ref.length; i < len; key = ++i) {
        scroller = ref[key];
        $scroller = $(scroller);
        $scroller.removeClass("scroll--horizontal");
        total_width = this.get_total_width($scroller.find(".hscol"));
        $scroller.children().wrapAll("<div class=\"js__scroll__canvas\" style=\"width: " + total_width + "px;\"/>");
        if (key === 0 && enable_parallax) {
          this.settings.x.probeType = 3;
        } else {
          this.settings.x.probeType = 0;
        }
        this.active.x[key] = new IScroll(scroller, this.settings.x);
        if (key === 0 && enable_parallax) {
          results.push(this.active.x[key].on("scroll", this.settings.callbacks.x));
        } else {
          results.push(void 0);
        }
      }
      return results;
    }
  };

  Scroll_Handle.prototype.maybe_stop_propagation = function() {
    if (this.y <= this.maxScrollY || this.y === 0) {
      this.options.stopPropagation = false;
    } else {
      this.options.stopPropagation = true;
    }
  };

  Scroll_Handle.prototype.destroy = function($page) {
    var i, iS, j, len, len1, ref, ref1;
    if (this.active.x.length === 0 && this.active.y.length === 0) {
      return;
    }
    ref = this.active.x;
    for (i = 0, len = ref.length; i < len; i++) {
      iS = ref[i];
      iS.destroy();
    }
    ref1 = this.active.y;
    for (j = 0, len1 = ref1.length; j < len1; j++) {
      iS = ref1[j];
      iS.destroy();
    }
    this.active = {
      x: [],
      y: []
    };
    if ($('.js__scroll__canvas').length > 0) {
      return $$('#stage').find(".js__scroll__canvas").children().unwrap();
    }
  };

  Scroll_Handle.prototype.refresh = function() {
    var $scroller, i, j, len, len1, ref, ref1, scroller, total_width;
    if (this.init === false) {
      return false;
    }
    ref = this.active.x;
    for (i = 0, len = ref.length; i < len; i++) {
      scroller = ref[i];
      $scroller = $(scroller.scroller);
      $(scroller.wrapper).removeClass("hide-scrollbar");
      total_width = this.get_total_width($scroller.find(".hscol"));
      $scroller.width(total_width);
      scroller.refresh();
    }
    ref1 = this.active.y;
    for (j = 0, len1 = ref1.length; j < len1; j++) {
      scroller = ref1[j];
      $(scroller.wrapper).removeClass("hide-scrollbar");
      scroller.refresh();
    }
    this.maybe_hide_active_scrollbars();
  };

  Scroll_Handle.prototype.maybe_hide_active_scrollbars = function() {
    var i, key, len, ref, results, scrollbar;
    ref = this.active.x.concat(this.active.y);
    results = [];
    for (key = i = 0, len = ref.length; i < len; key = ++i) {
      scrollbar = ref[key];
      results.push(this.maybe_hide_scrollbar(scrollbar));
    }
    return results;
  };

  Scroll_Handle.prototype.maybe_hide_scrollbar = function(IS) {
    var indicator, scrollbar_is_visible;
    if (!IS.indicators) {
      return;
    }
    indicator = IS.indicators[0];
    scrollbar_is_visible = (indicator.sizeRatioY < 0) || (indicator.sizeRatioX < 0);
    if (scrollbar_is_visible) {
      return $(IS.wrapper).removeClass("hide-scrollbar");
    } else {
      return $(IS.wrapper).addClass("hide-scrollbar");
    }
  };

  Scroll_Handle.prototype.get_total_width = function($elements) {
    var width;
    width = 0;
    $elements.each(function() {
      return width += $(this).outerWidth(true);
    });
    width += 80;
    return width;
  };

  return Scroll_Handle;

})();

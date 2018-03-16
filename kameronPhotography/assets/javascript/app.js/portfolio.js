(function() {
  var ENABLE_HOVER, fade_opacity, info_box;

  $$('#content').on('click', '.pfentry__image, .pfentry__info, .pfentry__info .js__link', function(e) {
    var $el, $this, url;
    $this = $(e.target);
    if (($this.is('a') || $this.is('i')) && !$this.is('.js__link')) {
      return;
    }
    $el = $this.closest('.js__hscol').find('.js__link');
    url = $el.attr('href');
    if (url != null) {
      if ($el.hasClass('js__direct')) {
        window.location = url;
      } else {
        App.Header.set_to('responsive');
        App.Header.hide();
        App.Load(url);
      }
    }
  });

  if (!App.config.portfolio.has_desc) {
    return;
  }

  ENABLE_HOVER = !Modernizr.touch && App.config.portfolio.detect_hover;

  fade_opacity = App.config.portfolio.fade_opacity / 100;

  info_box = {
    open: function($container) {
      if (App.is.responsive) {
        return;
      }
      if ($container.hasClass('no-thumbnail')) {
        return;
      }
      $container.find('.pfentry__info').velocity('stop').velocity({
        properties: {
          opacity: 1,
          scale: [1, 0.9]
        },
        options: {
          display: 'block',
          duration: 250
        }
      });
      if (fade_opacity < 1) {
        $container.find('.pfentry__image img').velocity('stop').velocity({
          properties: {
            opacity: [fade_opacity, 1]
          },
          options: {
            duration: 600
          }
        });
      }
      return $container.find('.js__open').velocity('stop').velocity('fadeOut', {
        duration: 250
      });
    },
    close: function($container) {
      if (App.is.responsive) {
        return;
      }
      if ($container.hasClass('no-thumbnail')) {
        return;
      }
      $container.find('.pfentry__info').velocity('stop').velocity({
        properties: "reverse",
        options: {
          display: 'none'
        }
      });
      if (fade_opacity < 1) {
        $container.find('.pfentry__image img').velocity('stop').velocity({
          properties: "reverse",
          options: {
            duration: 600
          }
        });
      }
      return $container.find('.js__open').velocity('stop').velocity('fadeIn');
    }
  };

  $$('#content').on('click', '.js__hscol .js__open, .js__hscol .js__close', function() {
    var $container, $el;
    if (App.is.responsive) {
      return;
    }
    $el = $(this);
    $container = $el.closest('.js__hscol');
    if ($el.hasClass('js__open')) {
      if (ENABLE_HOVER) {
        $container.addClass("js__clicklock");
      }
      info_box.open($container);
    }
    if ($el.hasClass('js__close')) {
      if (ENABLE_HOVER) {
        $container.removeClass("js__clicklock");
      }
      info_box.close($container);
    }
  });

  if (ENABLE_HOVER) {
    $$('#page').hoverIntent({
      selector: '.js__pfentry',
      timeout: 175,
      interval: 25,
      sensitivity: 6,
      over: function() {
        var $container;
        $container = $(this);
        return info_box.open($container);
      },
      out: function() {
        var $container;
        $container = $(this);
        if ($container.hasClass("js__clicklock")) {
          return;
        }
        return info_box.close($container);
      }
    });
  }

}).call(this);

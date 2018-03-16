(function() {
  var Header;

  Header = new Header_Class();

  Hooks.addAction("stage.complete", function() {
    return $$('#stage, .js__scroll--horizontal').css('overflow', '');
  });

  Hooks.addAction("header.set", function(mode) {
    if (mode === 'responsive') {
      return $$('#stage, .js__scroll--horizontal').css('overflow', 'visible');
    }
  });

  $$('#header-toggle').hoverIntent({
    over: Header.show,
    out: Header.hide,
    timeout: 200,
    interval: 50
  });

  $$('#header-toggle .js__toggle').click(Header.toggle);

  $(window).on("pronto.request", function() {
    if (Modernizr.touch) {
      Header.hide();
    }
    if (!App.is.responsive) {
      return $$('#header__background').velocity({
        properties: {
          'background-position-y': 0,
          'background-position-x': 0
        },
        options: {
          duration: 1200,
          easing: "ease-in"
        }
      });
    }
  });

  App.Header = Header;

}).call(this);

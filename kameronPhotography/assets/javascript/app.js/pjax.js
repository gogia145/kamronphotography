(function() {
  var $ajax, $render, R, Stage, _requested_url, clicked_url, decodeEntities, disable_pjax_on_selectors, load_url, on_render;

  if (!window.history.pushState) {
    return;
  }

  if (!App.config.enable_pjax) {
    return;
  }

  Stage = new Stage_Handler();

  $ajax = new $.Deferred();

  $render = new $.Deferred();

  clicked_url = false;

  decodeEntities = (function() {
    var decode_html, virtual_decoder;
    virtual_decoder = document.createElement('div');
    decode_html = function(str) {
      if (str && typeof str === 'string') {
        str = str.replace(/<script[^>]*>([\S\s]*?)<\/script>/gmi, '');
        str = str.replace(/<\/?\w(?:[^"'>]|"[^"]*"|'[^']*')*>/gmi, '');
        virtual_decoder.innerHTML = str;
        str = virtual_decoder.textContent;
        virtual_decoder.textContent = '';
      }
      return str;
    };
    return decode_html;
  })();

  _requested_url = false;

  load_url = function(url) {
    if (url === "#") {
      return false;
    }
    _requested_url = url;
    url = Hooks.applyFilters("stage.load_url", url);
    Hooks.doAction("stage.load", url);
    return $.pronto('load', url);
  };

  App.Load = _.debounce(load_url, 250, true);

  R = false;

  $(window).on("pronto.request", function() {
    if (R === true) {
      return;
    }
    R = true;
    $ajax = Stage.add_promise();
    Stage.close();
  });

  $(window).on("pronto.load", function() {
    $ajax.resolve();
    R = false;
  });

  $(window).on("pronto.error", function() {
    if (_requested_url === false) {
      _requested_url = App.config.gallery.root_url;
    }
    window.location.href = _requested_url;
  });

  $(window).on("pronto.render", function() {
    _requested_url = false;
    $render.resolve();
    $ajax.resolve();
  });

  on_render = function(data) {
    var $new_stage;
    $render = Stage.add_promise();
    $new_stage = Stage.add(data);
    $$('#content').prepend($new_stage);
    return $.when($new_stage).done(function() {
      data.body_classes.push("loaded");
      $$('body').attr('class', data.body_classes.join(" "));
      return $$('head title').text(decodeEntities(data.title));
    });
  };

  if (App.config.ignore_pjax.length > 0) {
    disable_pjax_on_selectors = function() {
      var selectors;
      selectors = App.config.ignore_pjax.join(', ').replace(/[^\s\w\d\.\-_#,]/g, '');
      return $$(selectors).addClass('no-pjax');
    };
    $(window).on("load", disable_pjax_on_selectors);
    Hooks.addAction("stage.complete", disable_pjax_on_selectors);
  }

  $(document).ready(function() {
    $$('#main-menu').find('.menu-item.no-pjax').removeClass('no-pjax').find('> a').addClass('no-pjax');
    return $.pronto({
      selector: '.js__pjax',
      requestKey: "pjax",
      force: true,
      target: {
        title: 'title',
        content: '#content'
      },
      render: on_render
    });
  });

  $(document).on('click', 'a:not(.no-pjax)', function(e) {
    var $el, href, url;
    $el = $(this);
    url = e.currentTarget;
    if (e.which > 1 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || (window.location.protocol !== url.protocol || window.location.host !== url.host) || url.target === "_blank") {
      return;
    }
    href = $el.attr('href');
    if ((href != null) && href !== "#") {
      e.preventDefault();
      return _.defer(function() {
        if (App._isLoadPrevented) {
          App._isLoadPrevented = false;
        } else {
          App.Load(href);
        }
      });
    }
  });

}).call(this);

(function() {
  var _timeout, _timeout_duration, cancel_content_refresh, content_refresh, stop_refresh;

  _timeout = false;

  _timeout_duration = App.config.refresh_timeout || 1500;

  cancel_content_refresh = function() {
    if (_timeout === false) {
      return;
    }
    clearTimeout(_timeout);
    _timeout = false;
  };

  content_refresh = function() {
    if (_timeout) {
      App.Scroll.refresh();
      cancel_content_refresh();
    }
    _timeout = setTimeout(content_refresh, _timeout_duration);
  };

  $(document).on("ready", content_refresh);

  Hooks.addAction("stage.complete", content_refresh);

  stop_refresh = function() {
    return $$('#content', true).imagesLoaded().always(cancel_content_refresh);
  };

  $(window).on("load", stop_refresh);

  Hooks.addAction("stage.complete", stop_refresh);

}).call(this);

(function() {
  var is_loading, start_loading, stop_loading;

  if (!App.config.loading_enabled) {
    return;
  }

  is_loading = false;

  start_loading = function() {
    if (is_loading) {
      return;
    }
    is_loading = true;
    return $$('#loading').velocity('stop').velocity('fadeIn');
  };

  stop_loading = function() {
    return $$('#loading').velocity('stop').velocity({
      properties: 'fadeOut',
      options: {
        complete: function() {
          is_loading = false;
        }
      }
    });
  };

  Hooks.addAction("stage.load", start_loading, 5);

  Hooks.addAction("stage.complete", stop_loading, 5001);

}).call(this);

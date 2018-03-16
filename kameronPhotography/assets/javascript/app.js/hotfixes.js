(function() {
  Hooks.addAction("stage.complete", function() {
    return $('form').each(function() {
      var $form, action;
      $form = $(this);
      action = $form.attr('action');
      if (!action) {
        return;
      }
      if (action.indexOf('pjax=true') > -1) {
        action = action.replace('pjax=true', '');
        return $form.attr('action', action);
      }
    });
  });

}).call(this);

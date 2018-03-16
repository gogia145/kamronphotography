(function() {
  var check_if_responsive, document_loaded, setup_fitvids;

  App.Util = new Village_Utilities();

  document_loaded = _.once(function() {
    $(document.body).addClass("loaded");
    return App.Util.delay(600, function() {
      App.Ready = true;
    });
  });

  Hooks.addAction("theme.ready", document_loaded, 5);

  check_if_responsive = function() {
    var is_responsive, was_responsive;
    is_responsive = App.win.width < 768 ? true : false;
    was_responsive = App.is.responsive === null ? is_responsive : App.is.responsive;
    if (was_responsive && !is_responsive) {
      App.is.responsive = false;
      Hooks.doAction("theme.responsive", false);
      return;
    }
    if (!was_responsive && is_responsive) {
      $(window).scrollTop(0).scrollLeft(0);
      App.is.responsive = true;
      Hooks.doAction("theme.responsive", true);
      return;
    }
    App.is.responsive = is_responsive;
  };

  Hooks.addAction("theme.resized", check_if_responsive, 5);

  setup_fitvids = function() {
    $$('#stage').fitVids();
  };

  $(window).load(function() {
    setup_fitvids();
    return $$('#main-menu').find('a').filter(function() {
      return !this.href || this.href === "#";
    }).addClass('no-link');
  });

}).call(this);

(function() {
  var close_icon, open_icon;

  $$('#main-menu').on("click", "a", function() {
    var $el, href;
    $el = $(this);
    href = $el.attr('href');
    if (!href || href === '#') {
      return;
    }
    $$('#main-menu .menu-item').removeClass('current-menu-item');
    return $(this).closest('.menu-item').addClass('current-menu-item');
  });

  Hooks.addAction("stage.complete", function() {
    var flat_href, href, rpat;
    href = window.location.href;
    if ($('#main-menu .current-menu-item a').attr('href') !== href) {
      rpat = /[^a-zA-Z0-9]/g;
      flat_href = href.replace(rpat, '');
      return $$('#main-menu .menu-item').removeClass('current-menu-item').find('a').filter(function() {
        return this.href.replace(rpat, '') === flat_href;
      }).closest('.menu-item').addClass('current-menu-item');
    }
  });

  close_icon = "ion-arrow-up-b";

  open_icon = "ion-arrow-down-b";

  $(document).ready(function() {
    var $menu_items;
    $('.menu-item-has-children:not(.no-dropdown) > a').addClass('no-pjax');
    $menu_items = $('#main-menu > .menu-item-has-children:not(.no-dropdown)').not('.no-link');
    $menu_items.find('> .sub-menu').hide();
    return $menu_items.find('> a').wrapInner('<span class="toggle-text"/>').append("<span class=\"toggle-icon\">\n	<i class=\"open icon " + open_icon + "\"></i>\n</span> ");
  });

  $('.menu-item-has-children:not(.no-dropdown) > a').on('click', function(e) {
    var $el, $icon, $sub;
    $el = $(this).closest('.menu-item-has-children');
    $sub = $el.find('.sub-menu').first();
    $icon = $el.find('.icon').first();
    e.preventDefault();
    if ($el.hasClass('is-open')) {
      if ($('#navigation .is-open').length === 1) {
        $('#navigation').removeClass('is-submenu-open');
      }
      $el.removeClass('is-open');
      $sub.velocity('slideUp', App.Header_Scroll.refresh);
      return $icon.velocity('fadeOut', 100, function() {
        return $icon.removeClass(close_icon).addClass(open_icon);
      }).velocity('fadeIn');
    } else {
      $('#navigation').addClass('is-submenu-open');
      $el.addClass('is-open');
      $sub.velocity('slideDown', App.Header_Scroll.refresh);
      return $icon.velocity('fadeOut', 100, function() {
        return $icon.removeClass(open_icon).addClass(close_icon);
      }).velocity('fadeIn');
    }
  });

}).call(this);

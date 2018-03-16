(function() {
  var defaults, fit_into_bounds, load_site, scale_into_bounds, setup_slides;

  if ($$('#fullscreen-gallery').length === 0) {
    return;
  }

  if ($$('#enter-site').length === 0) {
    return;
  }

  defaults = {
    play: App.config.slider.duration,
    animation: App.config.slider.animation,
    animation_speed: App.config.slider.animation_speed,
    animation_easing: App.config.slider.animation_easing,
    key_navigation: false,
    pagination: false
  };

  setup_slides = function(refresh) {
    var $slider, slide_config;
    if (refresh == null) {
      refresh = false;
    }
    $slider = $$('#fullscreen-gallery');
    slide_config = $.extend({}, defaults, $slider.data("slidesConfig"));
    $slider.superslides(slide_config);
    if ($slider.find('img').length === 1) {
      return $slider.superslides('stop');
    }
  };

  load_site = _.once(function() {
    if (!Modernizr.cssanimations) {
      window.location.href = $$('#enter-site').attr('href');
      return;
    }
    return $$('html').animo({
      animation: 'do-fadeOutUp',
      duration: 0.6,
      keep: true
    }, function() {
      window.location.href = $$('#enter-site').attr('href');
    });
  });

  $(document).ready(setup_slides);

  fit_into_bounds = function(rect, bounds) {
    var boundsRatio, output, rectRatio;
    rectRatio = rect.width / rect.height;
    boundsRatio = bounds.width / bounds.height;
    if (rectRatio > boundsRatio) {
      output = {
        width: bounds.width,
        height: rect.height * (bounds.width / rect.width)
      };
    } else {
      output = {
        width: rect.width * (bounds.height / rect.height),
        height: bounds.height
      };
    }
    return output;
  };

  scale_into_bounds = function(source, bounds) {
    var bounds_ratio, dimensions, rect;
    if (!source || !source.maxWidth || !source.maxHeight) {
      return {
        width: 0,
        height: 0
      };
    }
    rect = {
      width: source.maxWidth,
      height: source.maxHeight
    };
    bounds_ratio = App.win.height / App.win.width;
    bounds = {
      width: App.win.width * source.width,
      height: (App.win.width * source.width) * bounds_ratio
    };
    dimensions = fit_into_bounds(rect, bounds);
    if (dimensions.width > rect.width) {
      return rect;
    }
    return dimensions;
  };

  if ($$('#enter-site').length > 0) {
    (function() {
      var link, logo;
      logo = {};
      link = {};
      Hooks.addAction('theme.resized', _.once(function() {
        logo = $$('.fs__overlay__logo').data();
        if (logo) {
          logo.ratio = logo.maxWidth / logo.maxHeight;
          logo.height = logo.width / logo.ratio;
        } else {
          logo = false;
        }
        link = $$('.fs__overlay__link').data();
        if (link.maxWidth) {
          link.ratio = link.maxWidth / link.maxHeight;
          link.height = link.width / link.ratio;
        } else {
          link = false;
        }
      }));
      $(document).one('click mousewheel scroll', function(e) {
        e.preventDefault();
        return load_site();
      });
      return Hooks.addAction("theme.resized", function() {
        var link_dimensions, logo_dimensions;
        $$('#stage.fs').css({
          height: App.win.height,
          width: App.win.width
        });
        logo_dimensions = scale_into_bounds(logo, App.win);
        $$('.fs__overlay__logo img').css(logo_dimensions);
        link_dimensions = scale_into_bounds(link, App.win);
        $$('.fs__overlay__link img').css(link_dimensions);
        return $$('.fs__overlay').css('height', link_dimensions.height + logo_dimensions.height);
      });
    })();
  }

}).call(this);

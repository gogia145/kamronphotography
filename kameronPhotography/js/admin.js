(function() {
  var $, Image_Uploader, adjust_opacity, change_title, frame, init_options, options_change;

  $ = jQuery;

  Image_Uploader = (function() {
    function Image_Uploader($el, att) {
      this.check_height(att);
      this.add_image_data($el, att);
      this.add_preview($el, att);
    }

    Image_Uploader.prototype.check_height = function(att) {
      if (att.height < 560) {
        return alert("You have selected an image with less than 560px height. This may be an issue.");
      }
    };

    Image_Uploader.prototype.add_image_data = function($el, att) {
      var $input_id;
      $input_id = $el.find('.image_id');
      if ($input_id.length === 1) {
        return $input_id.attr('value', att.id);
      }
    };

    Image_Uploader.prototype.add_preview = function($el, att) {
      var $ref, $ref_image;
      $ref = $el.find('.js__ref');
      $ref_image = $ref.find('img');
      if ($ref_image.length === 0) {
        return $ref.append("<img src=\"" + att.url + "\"/>");
      } else {
        return $ref_image.attr('src', att.url);
      }
    };

    return Image_Uploader;

  })();

  $(document).on("village:media_selected", '.village-upload', function(e, attachment) {
    var $target, atts;
    $target = $(this).closest('.village-image-uploader');
    if ($target.length === 1) {
      atts = attachment.attributes;
      return new Image_Uploader($target, atts);
    }
  });

  $ = jQuery;

  frame = false;

  $(document).on("click", ".village-upload", function(e) {
    var $clicked;
    e.preventDefault();
    $clicked = $(this);
    if (frame) {
      frame.open();
      return;
    }
    frame = wp.media.frames.village_uploader = wp.media({
      library: {
        orderby: "date",
        type: "image"
      }
    });
    frame.on('select', function() {
      var attachment;
      attachment = frame.state().get('selection').first();
      if (attachment.attributes.type === "image") {
        return $clicked.trigger("village:media_selected", attachment, frame);
      }
    });
    frame.open();
  });

  $ = jQuery;

  options_change = function(e) {
    var $elem, $options, $parent;
    $elem = $(e.target);
    $parent = $elem.closest(".description");
    $options = $elem.closest(".block-options");
    if ($parent.hasClass("js__opacity")) {
      return adjust_opacity(e, $elem, $options);
    }
    if ($parent.hasClass("js__section_title")) {
      return change_title(e, $elem, $options);
    }
  };

  adjust_opacity = function(e, $elem, $options) {
    var $image, opacity, opacity_css;
    opacity = $elem.val();
    opacity_css = opacity / 100;
    if (!((100 >= opacity && opacity >= 0))) {
      $elem.css({
        "background-color": "#fc5c5c"
      });
      e.preventDefault();
      return false;
    } else {
      $elem.css({
        "background-color": ""
      });
    }
    $image = $options.find(".reference-image img");
    if ($image.length > 0) {
      return $image.stop().animate({
        opacity: opacity_css
      }, 100);
    }
  };

  change_title = function(e, $elem, $options) {
    var $title, title;
    title = $elem.val();
    $title = $options.find(" > .section__title");
    if ($title.length) {
      return $title.text(title);
    }
  };

  init_options = function() {
    var $options;
    $options = $(".js__options");
    $options.not("is-closed").addClass("is-closed").find(".column").slideUp();
    return $options.find(".reference-image img").not(".has-preview").each(function(key, val) {
      var $this;
      $this = $(val);
      $this.addClass("has-preview");
      return $this.clone().addClass("tiny-preview js__toggle_options").appendTo($this.closest(".js__options"));
    });
  };

  $(document).on("ready", init_options);

  $('#blocks-to-edit').on("click", '.js__toggle_options', function(e) {
    var $options, $this;
    e.preventDefault();
    $this = $(e.currentTarget);
    $options = $this.closest(".js__options");
    $options.toggleClass("is-closed");
    $options.toggleClass("is-open");
    $options.children(".column").slideToggle();
    return $options.children(".tiny-preview").fadeToggle();
  });

  $('#blocks-to-edit').on("change input", '.js__options', options_change);

  $('#blocks-to-edit').on("sortstop", function(e, UI) {
    return init_options();
  });

}).call(this);

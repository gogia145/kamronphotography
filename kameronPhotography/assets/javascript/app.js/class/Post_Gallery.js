var Post_Gallery;

Post_Gallery = (function() {
  function Post_Gallery(ID) {
    this._ID = ID;
    this._REOPEN = false;
    this.fotorama = false;
    if ($$('#popup__gallery', true).length === 0) {
      $("<div id=\"popup__gallery\" data-gallery-id=\"" + ID + "\">\n	<div id=\"popup__gallery__close\" class=\"gallery__button\">\n		<i class=\"icon ion-ios7-close-empty\"></i>\n	</div>\n	<div id=\"popup__stage\"></div>\n</div>").appendTo($$('body'));
      $$('#popup__gallery', true);
      $$('#popup__stage', true);
    }
    return;
  }

  Post_Gallery.prototype.destroy = function() {
    this.fotorama.destroy();
    $$('#popup__gallery').remove();
    $$('#popup__gallery', true);
    return $$('#popup__stage', true);
  };

  Post_Gallery.prototype.maybe_reopen = function(ID) {
    if (this._ID === ID) {
      this._REOPEN = true;
    } else {
      this._REOPEN = false;
    }
    return this._REOPEN;
  };

  Post_Gallery.prototype.reopen = function() {
    return this._REOPEN;
  };

  Post_Gallery.prototype.parse_data = function($links) {
    var $a, a, i, len, results;
    results = [];
    for (i = 0, len = $links.length; i < len; i++) {
      a = $links[i];
      $a = $(a);
      results.push({
        img: $a.attr('href'),
        thumb: $a.find('img').attr('src')
      });
    }
    return results;
  };

  Post_Gallery.prototype.open = function(index, data) {
    if (this._REOPEN) {
      this.fotorama = $$('#popup__stage').data('fotorama');
      this.fotorama.show({
        index: index,
        time: 0
      });
    } else {
      this.setup(index, data);
      this.fotorama = $$('#popup__stage').data('fotorama');
    }
    return $$('#popup__gallery').velocity({
      properties: 'fadeIn',
      options: {
        display: 'block'
      }
    });
  };

  Post_Gallery.prototype.close = function() {
    return $$('#popup__gallery').velocity({
      properties: 'fadeOut',
      options: {
        display: 'none'
      }
    });
  };

  Post_Gallery.prototype.setup = function(index, data) {
    return $$('#popup__stage', true).fotorama({
      data: data,
      fit: 'scaledown',
      height: App.win.height * 0.9,
      width: App.win.width * 0.9,
      shadows: false,
      startindex: index
    });
  };

  return Post_Gallery;

})();

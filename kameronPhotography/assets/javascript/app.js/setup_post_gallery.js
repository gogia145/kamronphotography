(function() {
  var Gallery;

  if (App.config.wp_galleries === false) {
    return;
  }

  Gallery = false;

  $(document).on('click', '.single-post .gallery a, .page .gallery a', function(e) {
    var $gallery, $images, $this, data, gallery_id, index;
    $this = $(this);
    if (!$this.is('a')) {
      return;
    }
    e.preventDefault();
    App.preventLoad();
    $gallery = $this.closest('.gallery');
    gallery_id = $gallery.attr('id') + $gallery.closest('.post').attr('id');
    if (!Gallery || Gallery.maybe_reopen(gallery_id) === false) {
      $('#popup__gallery').velocity('stop').hide();
      if (Gallery !== false) {
        Gallery.destroy();
      }
      Gallery = new Post_Gallery(gallery_id);
    }
    $images = $gallery.find('a');
    index = $images.index($this);
    if (!Gallery.reopen()) {
      data = Gallery.parse_data($images);
    }
    return Gallery.open(index, data);
  });

  $(document).on('click', '#popup__gallery, #popup__gallery__close .icon', function(e) {
    if (e.currentTarget !== e.target) {
      return;
    }
    if (Gallery) {
      return Gallery.close();
    }
  });

  $(document).on('keydown', function(e) {
    if (Gallery && e.keyCode === 27) {
      return Gallery.close();
    }
  });

}).call(this);

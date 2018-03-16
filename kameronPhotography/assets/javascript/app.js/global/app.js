"use strict";
var $, $$, App, Hooks, _WP_Global, __config_defaults, debounced_window_size, get_viewport_size;

$ = jQuery;

$$ = $.q;

$.fx.speeds._default = 700;

$.cookie.json = true;

window.blockFotoramaData = true;

if (!window.__VILLAGE_VARS) {
  window.__VILLAGE_VARS = {};
}

_WP_Global = __VILLAGE_VARS.wp;

__config_defaults = {
  enable_pjax: true,
  ignore_pjax: [],
  refresh_timeout: 1500,
  wp_gallery: true,
  loading_enabled: true,
  animations: {
    responsive_out: 'do-fadeOut',
    responsive_in: 'do-fadeIn',
    out: 'do-fadeOutUp',
    "in": 'do-fadeInUp'
  },
  portfolio: {
    has_desc: true,
    detect_hover: true,
    fade_opacity: 40
  },
  size: {
    header_toggle_width: 44,
    header_width: 220,
    image_height: 560,
    image_width: 900
  },
  on_scroll: {
    enable: true,
    force: false,
    x: true,
    y: true,
    speed_x: 100,
    speed_y: 100
  },
  scroll: {
    speedX: 30,
    speedY: 30,
    hs_scrollbar: true,
    keyboard_scroll: true,
    snapping: false
  },
  gallery: {
    enable: true,
    root_url: '/',
    fit: 'cover',
    transition: "slide",
    loop: false,
    thumbnails: true,
    thumbnails_overlay: true,
    thumbnails_height: 100,
    mouse_timeout: true,
    mouse_timeout_ms: 900,
    autoplay: false,
    autoplay_stop: true,
    autoplay_duration: 3000,
    force_thumbnails: false,
    force_sidebar: false,
    networks: []
  },
  slider: {
    duration: 3000,
    animation: "fade",
    animation_speed: 1300,
    animation_easing: "easeInOutQuad"
  },
  scroll_notification: {
    enable: true,
    enable_cookies: true,
    times: 2,
    expires: 7
  }
};

Hooks = window.wp.hooks;

App = {
  config: $.extend(true, {}, __config_defaults, __VILLAGE_VARS.config),
  is: {
    responsive: null,
    regular: null,
    gallery: false
  },
  sniff: {},
  win: {
    height: 0,
    width: 0
  },
  Page: false,
  Loading: false,
  Util: false,
  Location: false,
  Ready: false,
  Load: function() {},
  preventLoad: function() {
    App._isLoadPrevented = true;
  },
  _isLoadPrevented: false
};

get_viewport_size = function() {
  var a, e;
  e = window;
  a = "inner";
  if (!("innerWidth" in window)) {
    a = "client";
    e = document.documentElement || document.body;
  }
  return {
    width: e[a + "Width"],
    height: e[a + "Height"]
  };
};

App.set_window_size = function() {
  App.win = get_viewport_size();
  Hooks.doAction("theme.resized");
};

debounced_window_size = _.debounce(App.set_window_size, 300);

$(document).ready(App.set_window_size);

$(window).on("resize orientationchange", debounced_window_size);

window.Kameron_Theme = App;

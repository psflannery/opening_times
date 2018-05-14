// Avoid `console` errors in browsers that lack a console.
(function() {
    var method;
    var noop = function () {};
    var methods = [
        'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
        'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
        'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
        'timeline', 'timelineEnd', 'timeStamp', 'trace', 'warn'
    ];
    var length = methods.length;
    var console = (window.console = window.console || {});

    while (length--) {
        method = methods[length];

        // Only stub undefined methods.
        if (!console[method]) {
            console[method] = noop;
        }
    }
}());

// Gradient Text
(function($) {
  'use strict';

  //the plugin function
  $.gradienter = $.fn.gradienter = function(options) {
    if (this.length !== 0) {
     
      //initialize settings & vars
      var settings = $.extend({
            hueStart: 240,
            selector: '> *',
            saturation: 100,
            lightness: 50,
            rgb: true
          },
          options ),
        $gradientered = this,
        
        //ensure values are within proper range
        hstart = ensure(settings.hueStart, 0, Number.MAX_VALUE) % 360,
        sat = ensure(settings.saturation, 0, 100),
        light = ensure(settings.lightness, 0, 100);

      return $gradientered.each(function() {
        var $selected = $(this).find(settings.selector),
          hue = hstart,
          step = $selected.length === 0 ? 0 : 50 / ($selected.length);

        // iterate over selected elements to colorize
        $selected.each(function() {
          if ( ! settings.rgb ) {
            $(this).css({
              'color': 'hsl(' + hue + ', ' + sat + '%, ' + light + '%)'
            });
          } else {
            $(this).css({
              'color': hslToRgb(hue, sat, light)
            });
          }
          hue += step;
        });
      });
    }
  };

  function ensure(val, min, max) {
    val = parseInt(val, 10);
    if (isNaN(val) || val < min) return min;
    else if (val > max) return max;
    else return val;
  }

  function hslToRgb(h, s, l) {
    var r, g, b;
    h = h / 360;
    s = s / 100;
    l = l / 100;

    if (s === 0) r = g = b = l; // achromatic
    else {
      var q = l < 0.5 ? l * (1 + s) : l + s - l * s,
        p = 2 * l - q;
      r = hue2rgb(p, q, h + 1 / 3);
      g = hue2rgb(p, q, h);
      b = hue2rgb(p, q, h - 1 / 3);
    }

    return 'rgb(' + Math.round(r * 255) + ',' + Math.round(g * 255) + ',' + Math.round(b * 255) + ')';
  }

  function hue2rgb(p, q, t) {
    if (t < 0) t += 1;
    if (t > 1) t -= 1;
    if (t < 1 / 6) return p + (q - p) * 6 * t;
    if (t < 1 / 2) return q;
    if (t < 2 / 3) return p + (q - p) * (2 / 3 - t) * 6;
    return p;
  }

})(jQuery);

//@codekit-append "../bower_components/smoothstate/src/jquery.smoothState.js"
//@codekit-append "../bower_components/flickity/dist/flickity.pkgd.js"
//@codekit-append "../bower_components/infinite-scroll/dist/infinite-scroll.pkgd.js"
//@codekit-append "../bower_components/Scrollify/jquery.scrollify.js"
//@codekit-append "../bower_components/tether/dist/js/tether.js"
//@codekit-append "dist/js/bootstrap.js"

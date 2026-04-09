/* global jQuery */
(function ($) {
  'use strict';

  function initVideo(wrap) {
    if (!wrap) return;

    var src        = wrap.getAttribute('data-src');
    var type       = wrap.getAttribute('data-type');
    var useFacade  = wrap.getAttribute('data-facade') === 'yes';
    var facade     = wrap.querySelector('.pkae-video-facade');

    // ── Facade click → load iframe ────────────────────────────────────────
    if (useFacade && facade && src) {
      facade.addEventListener('click', function () {
        var iframe = document.createElement('iframe');
        iframe.className = 'pkae-video-iframe';
        iframe.setAttribute('frameborder', '0');
        iframe.setAttribute('allowfullscreen', '');
        iframe.setAttribute('allow', 'autoplay; encrypted-media');
        // Add autoplay to src when clicking
        var autoSrc = src.indexOf('?') !== -1 ? src + '&autoplay=1' : src + '?autoplay=1';
        if (type === 'vimeo') autoSrc += '&muted=1';
        iframe.src = autoSrc;
        facade.style.display = 'none';
        wrap.appendChild(iframe);
      });
    }

  }

  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/pkae-video.default', function ($scope) {
      initVideo($scope[0].querySelector('.pkae-video-wrap'));
    });
  });

  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.pkae-video-wrap').forEach(initVideo);
  });

})(jQuery);

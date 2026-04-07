/* global jQuery */
(function ($) {
  'use strict';

  function initGallery(root) {
    if (!root) return;

    var config = {};
    try { config = JSON.parse(root.getAttribute('data-config') || '{}'); } catch(e) {}

    var lightbox = config.lightbox;
    var lbCaption = config.lbCaption;

    // ── Filter ──────────────────────────────────────────────────────────────
    var filterBtns = root.querySelectorAll('.pkae-ig__filter-btn');
    var items      = root.querySelectorAll('.pkae-ig__item');

    filterBtns.forEach(function (btn) {
      btn.addEventListener('click', function () {
        filterBtns.forEach(function (b) { b.classList.remove('pkae-active'); });
        btn.classList.add('pkae-active');

        var filter = btn.getAttribute('data-filter');
        items.forEach(function (item) {
          if (filter === '*') {
            item.style.display = '';
          } else {
            item.style.display = item.classList.contains('pkae-filter-' + filter) ? '' : 'none';
          }
        });
      });
    });

    // ── Lightbox ─────────────────────────────────────────────────────────────
    if (!lightbox) return;

    var links = Array.prototype.slice.call( root.querySelectorAll('.pkae-ig__item-link') );
    var currentIdx = 0;

    function openLb(idx) {
      currentIdx = idx;
      var link    = links[idx];
      var src     = link.getAttribute('href');
      var caption = lbCaption ? (link.getAttribute('data-caption') || '') : '';
      var c       = config.lbClose || {};

      // Build close button inline styles
      var posH    = c.posH || 'right';
      var posV    = c.posV || 'top';
      var offH    = (c.offH || 16) + (c.offHU || 'px');
      var offV    = (c.offV || 16) + (c.offVU || 'px');
      var size    = (c.size || 36) + 'px';
      var iconSz  = (c.iconSz || 20) + 'px';
      var color   = c.color || '#fff';
      var bg      = c.bg || 'rgba(255,255,255,0.15)';
      var bgHover = c.bgHover || 'rgba(255,255,255,0.3)';
      var radius  = c.radius ? (c.radius.top||4)+(c.radius.unit||'px')+' '+(c.radius.right||4)+(c.radius.unit||'px')+' '+(c.radius.bottom||4)+(c.radius.unit||'px')+' '+(c.radius.left||4)+(c.radius.unit||'px') : '4px';
      var pad     = c.padding ? (c.padding.top||0)+(c.padding.unit||'px')+' '+(c.padding.right||0)+(c.padding.unit||'px')+' '+(c.padding.bottom||0)+(c.padding.unit||'px')+' '+(c.padding.left||0)+(c.padding.unit||'px') : '0';

      var closeStyle = 'position:fixed;' + posV + ':' + offV + ';' + posH + ':' + offH + ';' +
        'width:' + size + ';height:' + size + ';' +
        'font-size:' + iconSz + ';' +
        'color:' + color + ';background:' + bg + ';' +
        'border-radius:' + radius + ';padding:' + pad + ';' +
        'border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;z-index:100000;transition:background .2s;';

      var wid       = c.widgetId || '';
      var closeClass = 'pkae-ig-lb-close' + (wid ? ' pkae-ig-lb-close-' + wid : '');

      var overlay = document.createElement('div');
      overlay.className = 'pkae-ig-lb-overlay';
      overlay.innerHTML =
        '<div class="pkae-ig-lb-inner">' +
          '<img src="' + src + '" alt="' + caption + '">' +
          (caption ? '<p class="pkae-ig-lb-caption">' + caption + '</p>' : '') +
        '</div>' +
        '<button class="' + closeClass + '" style="' + closeStyle + '" aria-label="Close">&times;</button>' +
        (links.length > 1 ? '<button class="pkae-ig-lb-prev">&#8249;</button><button class="pkae-ig-lb-next">&#8250;</button>' : '');

      document.body.appendChild(overlay);
      document.body.style.overflow = 'hidden';

      var closeBtn = overlay.querySelector('.pkae-ig-lb-close');
      closeBtn.addEventListener('mouseenter', function () { closeBtn.style.background = bgHover; });
      closeBtn.addEventListener('mouseleave', function () { closeBtn.style.background = bg; });
      closeBtn.addEventListener('click', closeLb);
      overlay.addEventListener('click', function (e) { if (e.target === overlay) closeLb(); });

      var prev = overlay.querySelector('.pkae-ig-lb-prev');
      var next = overlay.querySelector('.pkae-ig-lb-next');
      if (prev) prev.addEventListener('click', function () { navigate(-1); });
      if (next) next.addEventListener('click', function () { navigate(1); });

      document.addEventListener('keydown', onKey);
    }

    function closeLb() {
      var overlay = document.querySelector('.pkae-ig-lb-overlay');
      if (overlay) overlay.remove();
      document.body.style.overflow = '';
      document.removeEventListener('keydown', onKey);
    }

    function navigate(dir) {
      closeLb();
      var next = (currentIdx + dir + links.length) % links.length;
      openLb(next);
    }

    function onKey(e) {
      if (e.key === 'Escape')      closeLb();
      if (e.key === 'ArrowRight')  navigate(1);
      if (e.key === 'ArrowLeft')   navigate(-1);
    }

    links.forEach(function (link, i) {
      link.addEventListener('click', function (e) {
        e.preventDefault();
        openLb(i);
      });
    });
  }

  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/pkae-image-gallery.default', function ($scope) {
      initGallery($scope[0].querySelector('.pkae-ig'));
    });
  });

  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.pkae-ig').forEach(initGallery);
  });

})(jQuery);

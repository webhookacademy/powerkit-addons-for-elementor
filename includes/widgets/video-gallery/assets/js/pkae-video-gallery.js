/* global jQuery */
(function ($) {
  'use strict';

  function initGallery(root) {
    if (!root) return;

    var filterBtns = root.querySelectorAll('.pkae-vg__filter-btn');

    // ── Filter ──────────────────────────────────────────────────────────────
    filterBtns.forEach(function (btn) {
      btn.addEventListener('click', function () {
        filterBtns.forEach(function (b) { b.classList.remove('pkae-active'); });
        btn.classList.add('pkae-active');
        var filter = btn.getAttribute('data-filter');
        root.querySelectorAll('.pkae-vg__item').forEach(function (item) {
          if (filter === '*') {
            item.classList.remove('pkae-hidden');
          } else {
            item.classList.toggle('pkae-hidden', !item.classList.contains('pkae-filter-' + filter));
          }
        });
      });
    });

    // ── Lightbox ─────────────────────────────────────────────────────────────
    var lbWidgetId = root.getAttribute('data-lb-widget-id') || '';
    var lbIconSize = root.getAttribute('data-lb-icon-size') || '20';
    var lbBtnSize  = root.getAttribute('data-lb-btn-size') || '40';
    var lbColor    = root.getAttribute('data-lb-color') || '#ffffff';
    var lbBg       = root.getAttribute('data-lb-bg') || 'rgba(0,0,0,0.5)';
    var lbBgHover  = root.getAttribute('data-lb-bg-hover') || 'rgba(0,0,0,0.8)';
    var lbRadius   = root.getAttribute('data-lb-radius') || '50%';
    var lbPosH     = root.getAttribute('data-lb-pos-h') || 'right';
    var lbPosV     = root.getAttribute('data-lb-pos-v') || 'top';
    var lbOffX     = root.getAttribute('data-lb-off-x') || '0px';
    var lbOffY     = root.getAttribute('data-lb-off-y') || '-50px';

    root.querySelectorAll('.pkae-vg__item').forEach(function (item) {
      item.addEventListener('click', function () {
        var embedUrl = item.getAttribute('data-embed');
        if (!embedUrl) return;

        // Read icon HTML and settings fresh each time for real-time editor updates
        lbIconSize = root.getAttribute('data-lb-icon-size') || '20';
        lbBtnSize  = root.getAttribute('data-lb-btn-size') || '40';
        lbColor    = root.getAttribute('data-lb-color') || '#ffffff';
        lbBg       = root.getAttribute('data-lb-bg') || 'rgba(0,0,0,0.5)';
        lbBgHover  = root.getAttribute('data-lb-bg-hover') || 'rgba(0,0,0,0.8)';
        lbRadius   = root.getAttribute('data-lb-radius') || '50%';
        lbPosH     = root.getAttribute('data-lb-pos-h') || 'right';
        lbPosV     = root.getAttribute('data-lb-pos-v') || 'top';
        lbOffX     = root.getAttribute('data-lb-off-x') || '0px';
        lbOffY     = root.getAttribute('data-lb-off-y') || '-50px';

        var iconDiv    = document.getElementById('pkae-vg-lb-icon-' + root.id);
        var lbIconHtml = iconDiv ? iconDiv.innerHTML : '&times;';

        var lb = document.createElement('div');
        lb.className = 'pkae-vg-lightbox';
        lb.innerHTML =
          '<div class="pkae-vg-lightbox__inner">' +
            '<button class="pkae-vg-lightbox__close" aria-label="Close">' + lbIconHtml + '</button>' +
            '<iframe src="' + embedUrl + '" allowfullscreen allow="autoplay; encrypted-media"></iframe>' +
          '</div>';

        document.body.appendChild(lb);
        document.body.style.overflow = 'hidden';

        var closeBtn = lb.querySelector('.pkae-vg-lightbox__close');
        
        // Apply inline styles for instant updates
        var oppH = lbPosH === 'right' ? 'left' : 'right';
        var oppV = lbPosV === 'top' ? 'bottom' : 'top';
        
        closeBtn.style[lbPosH] = lbOffX;
        closeBtn.style[lbPosV] = lbOffY;
        closeBtn.style[oppH] = 'auto';
        closeBtn.style[oppV] = 'auto';
        closeBtn.style.width = lbBtnSize + 'px';
        closeBtn.style.height = lbBtnSize + 'px';
        closeBtn.style.background = lbBg;
        closeBtn.style.borderRadius = lbRadius;
        closeBtn.style.color = lbColor;

        // Apply icon styles
        var icon = closeBtn.querySelector('i, svg');
        if (icon) {
          icon.style.fontSize = lbIconSize + 'px';
          icon.style.width = lbIconSize + 'px';
          icon.style.height = lbIconSize + 'px';
          if (icon.tagName === 'svg') {
            icon.style.fill = lbColor;
          }
        }

        closeBtn.addEventListener('mouseenter', function () { 
          closeBtn.style.background = lbBgHover; 
        });
        closeBtn.addEventListener('mouseleave', function () { 
          closeBtn.style.background = lbBg; 
        });
        closeBtn.addEventListener('click', closeLb);
        lb.addEventListener('click', function (e) { if (e.target === lb) closeLb(); });
        document.addEventListener('keydown', onKey);

        function closeLb() {
          lb.remove();
          document.body.style.overflow = '';
          document.removeEventListener('keydown', onKey);
        }
        function onKey(e) { if (e.key === 'Escape') closeLb(); }
      });
    });
  }

  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/pkae-video-gallery.default', function ($scope) {
      initGallery($scope[0].querySelector('.pkae-vg'));
    });
  });

  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.pkae-vg').forEach(initGallery);
  });

})(jQuery);

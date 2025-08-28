/* global jQuery */
(function ($) {
  'use strict';

  function initInstance(root) {
    if (!root) return;

    // Elements (scoped to this widget root)
    var track   = root.querySelector('.track');
    var wrap    = track ? track.parentElement : null; // scroll container (.slider)
    var cards   = track ? Array.prototype.slice.call(track.children) : [];
    var prevBtn = root.querySelector('[data-pkae-prev]');
    var nextBtn = root.querySelector('[data-pkae-next]');
    var dotsBox = root.querySelector('.dots');

    if (!wrap || !cards.length) return;

    var showDots   = root.getAttribute('data-show-dots') === 'yes';
    var showArrows = root.getAttribute('data-show-arrows') === 'yes';

    if (!showArrows) {
      if (prevBtn) prevBtn.style.display = 'none';
      if (nextBtn) nextBtn.style.display = 'none';
    }

    // Utilities
    var isMobile = function () { return window.matchMedia('(max-width:767px)').matches; };
    var supportsHover = function () { return window.matchMedia('(hover:hover)').matches; };

    // Build dots
    if (showDots && dotsBox) {
      dotsBox.innerHTML = '';
      cards.forEach(function (_, i) {
        var dot = document.createElement('span');
        dot.className = 'dot';
        dot.addEventListener('click', function () { activate(i, true); });
        dotsBox.appendChild(dot);
      });
    }
    var dots = dotsBox ? Array.prototype.slice.call(dotsBox.children) : [];

    var current = 0;

    function center(i) {
      var card = cards[i];
      if (!card) return;

      var axis = isMobile() ? 'top' : 'left';
      var size = isMobile() ? 'clientHeight' : 'clientWidth';
      var start = isMobile() ? card.offsetTop : card.offsetLeft;

      var scrollOptions = { behavior: 'smooth' };
      scrollOptions[axis] = start - (wrap[size] / 2 - card[size] / 2);
      wrap.scrollTo(scrollOptions);
    }

    function toggleUI(i) {
      cards.forEach(function (c, k) { c.toggleAttribute('active', k === i); });
      if (dots.length) dots.forEach(function (d, k) { d.classList.toggle('active', k === i); });
      if (prevBtn) prevBtn.disabled = (i === 0);
      if (nextBtn) nextBtn.disabled = (i === cards.length - 1);
    }

    function activate(i, scroll) {
      if (i === current || i < 0 || i > cards.length - 1) return;
      current = i;
      toggleUI(i);
      if (scroll) center(i);
    }

    function go(step) {
      var next = Math.min(Math.max(current + step, 0), cards.length - 1);
      activate(next, true);
    }

    // Buttons
    if (prevBtn) prevBtn.addEventListener('click', function () { go(-1); });
    if (nextBtn) nextBtn.addEventListener('click', function () { go(1); });

    // Keyboard (only when widget is hovered/focused)
    var hot = false;
    root.addEventListener('mouseenter', function () { hot = true; }, { passive: true });
    root.addEventListener('mouseleave', function () { hot = false; }, { passive: true });
    root.addEventListener('focusin',  function () { hot = true; });
    root.addEventListener('focusout', function () { hot = false; });

    document.addEventListener('keydown', function (e) {
      if (!hot) return;
      if (e.key === 'ArrowRight' || e.key === 'ArrowDown') go(1);
      if (e.key === 'ArrowLeft'  || e.key === 'ArrowUp')   go(-1);
    }, { passive: true });

    // Hover & click on cards
    cards.forEach(function (card, i) {
      card.addEventListener('mouseenter', function () {
        if (supportsHover()) activate(i, true);
      }, { passive: true });
      card.addEventListener('click', function () { activate(i, true); });
    });

    // Touch swipe
    var sx = 0, sy = 0;
    track.addEventListener('touchstart', function (e) {
      sx = e.touches[0].clientX;
      sy = e.touches[0].clientY;
    }, { passive: true });

    track.addEventListener('touchend', function (e) {
      var dx = e.changedTouches[0].clientX - sx;
      var dy = e.changedTouches[0].clientY - sy;
      var threshold = 60;
      if (isMobile() ? Math.abs(dy) > threshold : Math.abs(dx) > threshold) {
        go((isMobile() ? dy : dx) > 0 ? -1 : 1);
      }
    }, { passive: true });

    // Hide dots on mobile (match original)
    if (isMobile() && dotsBox) dotsBox.hidden = true;

    // Re-center on resize
    window.addEventListener('resize', function () { center(current); });

    // Init
    toggleUI(0);
    center(0);
  }

  // Elementor hook – init per widget
  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/pkae-accordion-carousel.default', function ($scope) {
      var root = $scope[0].querySelector('.pkae-accordion-carousel');
      initInstance(root);
    });
  });

  // Non-Elementor fallback (rare)
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.pkae-accordion-carousel').forEach(initInstance);
  });

})(jQuery);

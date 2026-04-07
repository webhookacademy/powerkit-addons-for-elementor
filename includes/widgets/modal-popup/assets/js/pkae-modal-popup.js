/* global jQuery */
(function ($) {
  'use strict';

  var modals = {};

  function initModal(cfg) {
    var id      = cfg.id;
    var overlay = document.getElementById(id + '-overlay');
    var modal   = document.getElementById(id + '-modal');
    if (!overlay || !modal) return;

    // Preview mode in editor
    if (cfg.preview) {
      overlay.classList.add('pkae-mp--preview', 'pkae-mp--open');
      return;
    }

    modals[id] = { overlay: overlay, modal: modal, cfg: cfg };

    function open() {
      // Cookie check
      if (cfg.cookies && getCookie('pkae_mp_' + id)) return;
      overlay.classList.add('pkae-mp--open');
      overlay.setAttribute('aria-hidden', 'false');
      document.body.style.overflow = 'hidden';
      // Load video iframes on open
      overlay.querySelectorAll('.pkae-mp__video-iframe').forEach(function (iframe) {
        var dataSrc = iframe.getAttribute('data-src');
        if (dataSrc && iframe.getAttribute('src') !== dataSrc) {
          iframe.setAttribute('src', dataSrc);
        }
      });
    }

    function close() {
      overlay.classList.remove('pkae-mp--open');
      overlay.setAttribute('aria-hidden', 'true');
      document.body.style.overflow = '';
      // Stop video by clearing src
      overlay.querySelectorAll('.pkae-mp__video-iframe').forEach(function (iframe) {
        iframe.removeAttribute('src');
      });
      // Set cookie
      if (cfg.cookies) setCookie('pkae_mp_' + id, '1', cfg.cookieDays);
    }

    // Trigger
    var trigger = cfg.trigger;

    if (trigger === 'on_load' || trigger === 'after_time') {
      var delay = (cfg.delay || 0) * 1000;
      setTimeout(open, delay);
    } else if (trigger === 'exit_intent') {
      document.addEventListener('mouseleave', function handler(e) {
        if (e.clientY <= 0) { open(); document.removeEventListener('mouseleave', handler); }
      });
    }

    // Click triggers
    document.querySelectorAll('[data-modal="' + id + '"]').forEach(function (el) {
      if (!el.classList.contains('pkae-mp__close')) {
        el.addEventListener('click', function (e) { e.preventDefault(); open(); });
      }
    });

    // Close button
    overlay.querySelectorAll('.pkae-mp__close').forEach(function (btn) {
      btn.addEventListener('click', close);
    });

    // Overlay click
    if (cfg.closeOnOver) {
      overlay.addEventListener('click', function (e) {
        if (e.target === overlay) close();
      });
    }

    // ESC key
    if (cfg.closeOnEsc) {
      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && overlay.classList.contains('pkae-mp--open')) close();
      });
    }
  }

  // Cookie helpers
  function setCookie(name, value, days) {
    var d = new Date();
    d.setTime(d.getTime() + days * 24 * 60 * 60 * 1000);
    document.cookie = name + '=' + value + ';expires=' + d.toUTCString() + ';path=/';
  }
  function getCookie(name) {
    var v = document.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)');
    return v ? v.pop() : '';
  }

  // Init all queued modals
  function initAll() {
    (window.pkaeModalQueue || []).forEach(initModal);
    window.pkaeModalQueue = { push: initModal }; // handle future pushes
  }

  // Elementor editor
  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/pkae-modal-popup.default', function () {
      initAll();
    });
  });

  document.addEventListener('DOMContentLoaded', initAll);

})(jQuery);

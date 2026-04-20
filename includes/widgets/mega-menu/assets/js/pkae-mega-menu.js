/* global elementorFrontend */
(function () {
  'use strict';

  function initMegaMenu(wrap) {
    if (!wrap || wrap.dataset.pkaeMMInit) return;
    wrap.dataset.pkaeMMInit = '1';

    var isVertical = wrap.classList.contains('pkae-mm-vertical');
    var trigger    = wrap.dataset.pkaeTrigger || 'hover';
    var bp         = parseInt(wrap.dataset.pkaeBp || '1024', 10);
    var nav        = wrap.querySelector('.pkae-mm-nav');
    var catHeader  = wrap.querySelector('.pkae-mm-cat-header');
    var hamburger  = wrap.querySelector('.pkae-mm-hamburger');

    console.log('[PKAE MegaMenu] init', {
      wrap: wrap,
      isVertical: isVertical,
      trigger: trigger,
      bp: bp,
      nav: nav,
      hamburger: hamburger,
      catHeader: catHeader,
      windowWidth: window.innerWidth
    });

    function isMobile() { return window.innerWidth <= bp; }

    /* ── Category header toggle (vertical) ──────────────────── */
    if (catHeader && nav) {
      catHeader.addEventListener('click', function (e) {
        e.stopPropagation();
        var open = nav.classList.toggle('pkae-mm-nav-open');
        catHeader.classList.toggle('pkae-mm-cat-open', open);
        catHeader.setAttribute('aria-expanded', open ? 'true' : 'false');
        console.log('[PKAE MegaMenu] catHeader clicked, open:', open);
      });
    }

    /* ── Hamburger toggle ────────────────────────────────────── */
    if (hamburger && nav) {
      console.log('[PKAE MegaMenu] hamburger found, attaching click');
      hamburger.addEventListener('click', function (e) {
        e.stopPropagation();
        var open = nav.classList.toggle('pkae-mm-nav-open');
        hamburger.classList.toggle('is-active', open);
        hamburger.setAttribute('aria-expanded', open ? 'true' : 'false');
        console.log('[PKAE MegaMenu] hamburger clicked, open:', open, 'nav classes:', nav.className, 'nav computed display:', window.getComputedStyle(nav).display);
      });
    } else {
      console.warn('[PKAE MegaMenu] hamburger or nav NOT found', { hamburger: hamburger, nav: nav });
    }

    /* ── Dropdown items ──────────────────────────────────────── */
    var items = Array.prototype.slice.call(
      wrap.querySelectorAll('.pkae-mm-nav > .pkae-mm-item.pkae-mm-has-drop')
    );
    console.log('[PKAE MegaMenu] dropdown items found:', items.length);

    function closeAll(except) {
      items.forEach(function (item) {
        if (item === except) return;
        item.classList.remove('pkae-mm-open');
        var lnk = item.querySelector('.pkae-mm-link');
        if (lnk) lnk.setAttribute('aria-expanded', 'false');
      });
    }

    items.forEach(function (item) {
      var link     = item.querySelector('.pkae-mm-link');
      var dropdown = item.querySelector('.pkae-mm-dropdown');
      if (!link || !dropdown) return;

      link.setAttribute('aria-haspopup', 'true');
      link.setAttribute('aria-expanded', 'false');

      function openItem()  { closeAll(item); item.classList.add('pkae-mm-open'); link.setAttribute('aria-expanded', 'true'); }
      function closeItem() { item.classList.remove('pkae-mm-open'); link.setAttribute('aria-expanded', 'false'); }
      function toggleItem(e) {
        e.preventDefault(); e.stopPropagation();
        console.log('[PKAE MegaMenu] item toggle', item.classList.contains('pkae-mm-open') ? 'closing' : 'opening');
        item.classList.contains('pkae-mm-open') ? closeItem() : openItem();
      }

      if (trigger === 'click') {
        link.addEventListener('click', toggleItem);
      } else {
        var timer;
        item.addEventListener('mouseenter', function () { if (isMobile()) return; clearTimeout(timer); openItem(); });
        item.addEventListener('mouseleave', function () { if (isMobile()) return; timer = setTimeout(closeItem, 150); });
        link.addEventListener('click', function (e) { if (!isMobile()) return; toggleItem(e); });
      }

      link.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); item.classList.contains('pkae-mm-open') ? closeItem() : openItem(); }
        if (e.key === 'Escape') { closeItem(); link.focus(); }
      });
    });

    /* ── Close on outside click ──────────────────────────────── */
    document.addEventListener('click', function (e) {
      if (wrap.contains(e.target)) return;
      closeAll(null);
      if (nav) nav.classList.remove('pkae-mm-nav-open');
      if (hamburger) { hamburger.classList.remove('is-active'); hamburger.setAttribute('aria-expanded', 'false'); }
      if (catHeader) { catHeader.classList.remove('pkae-mm-cat-open'); catHeader.setAttribute('aria-expanded', 'false'); }
    });

    /* ── Resize ──────────────────────────────────────────────── */
    window.addEventListener('resize', function () {
      if (!isMobile() && !isVertical) {
        if (nav) nav.classList.remove('pkae-mm-nav-open');
        if (hamburger) { hamburger.classList.remove('is-active'); hamburger.setAttribute('aria-expanded', 'false'); }
      }
    });
  }

  function bootAll() {
    var wraps = document.querySelectorAll('.pkae-mm-wrap');
    console.log('[PKAE MegaMenu] bootAll found wraps:', wraps.length);
    wraps.forEach(initMegaMenu);
  }

  if (typeof window.elementorFrontend !== 'undefined') {
    window.elementorFrontend.hooks.addAction(
      'frontend/element_ready/pkae-mega-menu.default',
      function ($el) {
        console.log('[PKAE MegaMenu] elementor frontend hook fired');
        var wrap = $el[0] && $el[0].querySelector('.pkae-mm-wrap');
        if (wrap) { delete wrap.dataset.pkaeMMInit; initMegaMenu(wrap); }
      }
    );
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bootAll);
  } else {
    bootAll();
  }

})();

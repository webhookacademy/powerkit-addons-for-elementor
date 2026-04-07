/* global jQuery */
(function ($) {
  'use strict';

  function initPosts(root) {
    if (!root) return;

    var pagination  = root.getAttribute('data-pagination');
    var widgetId    = root.getAttribute('data-widget-id');
    var maxPages    = parseInt(root.getAttribute('data-max-pages'), 10) || 1;
    var currentPage = 1;
    var loading     = false;

    // ── Filter ───────────────────────────────────────────────────────────────
    var filterBtns = root.querySelectorAll('.pkae-posts__filter-btn');

    filterBtns.forEach(function (btn) {
      btn.addEventListener('click', function () {
        filterBtns.forEach(function (b) { b.classList.remove('pkae-active'); });
        btn.classList.add('pkae-active');

        var filter = btn.getAttribute('data-filter');
        // Re-query each time to include dynamically loaded posts
        root.querySelectorAll('.pkae-posts__item').forEach(function (item) {
          if (filter === '*') {
            item.classList.remove('pkae-hidden');
          } else {
            item.classList.toggle('pkae-hidden', !item.classList.contains(filter));
          }
        });
      });
    });

    // ── Load More ─────────────────────────────────────────────────────────────
    var loadMoreBtn = root.querySelector('.pkae-posts__load-more');
    if (loadMoreBtn && pagination === 'load_more') {
      loadMoreBtn.addEventListener('click', function () {
        if (loading || currentPage >= maxPages) return;
        loadPosts(currentPage + 1);
      });
    }

    // ── Infinite Scroll ───────────────────────────────────────────────────────
    if (pagination === 'infinite') {
      window.addEventListener('scroll', function () {
        if (loading || currentPage >= maxPages) return;
        var rect = root.getBoundingClientRect();
        if (rect.bottom <= window.innerHeight + 300) {
          loadPosts(currentPage + 1);
        }
      }, { passive: true });
    }

    function loadPosts(page) {
      if (loading) return;
      loading = true;

      var origText = '';
      if (loadMoreBtn) {
        origText = loadMoreBtn.getAttribute('data-orig') || loadMoreBtn.textContent.trim();
        loadMoreBtn.setAttribute('data-orig', origText);
        loadMoreBtn.disabled = true;
        loadMoreBtn.textContent = origText + '...';
      }

      $.ajax({
        url:  (typeof pkaePostsAjax !== 'undefined' ? pkaePostsAjax.ajaxurl : '/wp-admin/admin-ajax.php'),
        type: 'POST',
        data: {
          action:    'pkae_load_posts',
          widget_id: widgetId,
          page:      page,
          nonce:     (typeof pkaePostsAjax !== 'undefined' ? pkaePostsAjax.nonce : ''),
        },
        success: function (res) {
          if (res.success && res.data.html) {
            var grid = root.querySelector('.pkae-posts__grid');
            grid.insertAdjacentHTML('beforeend', res.data.html);
            currentPage = page;
            root.setAttribute('data-page', page);
          }
        },
        complete: function () {
          loading = false;
          if (loadMoreBtn) {
            loadMoreBtn.textContent = origText;
            loadMoreBtn.disabled = (currentPage >= maxPages);
          }
        },
      });
    }
  }

  // Elementor editor
  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/pkae-posts.default', function ($scope) {
      initPosts($scope[0].querySelector('.pkae-posts'));
    });
  });

  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.pkae-posts').forEach(initPosts);
  });

})(jQuery);

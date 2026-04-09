/* global jQuery, pkaeSearchData */
(function ($) {
  'use strict';

  function initAdvancedSearch(wrapper) {
    if (!wrapper) return;

    var $wrapper = $(wrapper);
    var $input = $wrapper.find('.pkae-search-input');
    var $results = $wrapper.find('.pkae-search-results');
    var $searchIcon = $wrapper.find('.pkae-search-icon');
    var $closeIcon = $wrapper.find('.pkae-close-icon');
    
    var isAjax = $wrapper.data('ajax') === 'yes';
    var minChars = parseInt($wrapper.data('min-chars')) || 3;
    var delay = parseInt($wrapper.data('delay')) || 300;
    var count = parseInt($wrapper.data('count')) || 5;
    var postTypes = ($wrapper.data('post-types') || 'post,page').split(',');
    var showThumb = $wrapper.data('show-thumb') === 'yes';
    var showExcerpt = $wrapper.data('show-excerpt') === 'yes';
    var excerptLength = parseInt($wrapper.data('excerpt-length')) || 15;
    var showDate = $wrapper.data('show-date') === 'yes';
    var showType = $wrapper.data('show-type') === 'yes';
    var resultStyle = $wrapper.data('result-style') || 'list';

    var searchTimeout;
    var currentRequest;

    if (!isAjax) return;

    // Toggle icons based on input value
    function toggleIcons() {
      if ($input.val().trim().length > 0) {
        $searchIcon.hide();
        $closeIcon.show();
      } else {
        $searchIcon.show();
        $closeIcon.hide();
      }
    }

    // Initial state
    toggleIcons();

    // Input event handler
    $input.on('input', function() {
      var query = $(this).val().trim();
      
      toggleIcons();

      clearTimeout(searchTimeout);

      if (query.length < minChars) {
        $results.hide().empty();
        return;
      }

      searchTimeout = setTimeout(function() {
        performSearch(query);
      }, delay);
    });

    // Close icon click
    $closeIcon.on('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      $input.val('').focus();
      $results.hide().empty();
      toggleIcons();
    });

    // Click outside to close
    $(document).on('click', function(e) {
      if (!$wrapper.is(e.target) && $wrapper.has(e.target).length === 0) {
        $results.hide();
      }
    });

    // Focus to show results again
    $input.on('focus', function() {
      if ($results.children().length > 0) {
        $results.show();
      }
    });

    function performSearch(query) {
      // Cancel previous request
      if (currentRequest) {
        currentRequest.abort();
      }

      $results.html('<div class="pkae-search-loading"></div>').show();

      currentRequest = $.ajax({
        url: pkaeSearchData.ajaxUrl,
        type: 'POST',
        data: {
          action: 'pkae_advanced_search',
          nonce: pkaeSearchData.nonce,
          query: query,
          post_types: postTypes,
          count: count,
          show_thumb: showThumb ? 'yes' : 'no',
          show_excerpt: showExcerpt ? 'yes' : 'no',
          excerpt_length: excerptLength,
          show_date: showDate ? 'yes' : 'no',
          show_type: showType ? 'yes' : 'no',
          result_style: resultStyle
        },
        success: function(response) {
          if (response.success && response.data.html) {
            // Add style class to results container
            $results.removeClass('pkae-style-list pkae-style-grid').addClass('pkae-style-' + resultStyle);
            $results.html(response.data.html).show();
          } else {
            $results.html('<div class="pkae-no-results">No results found</div>').show();
          }
        },
        error: function(xhr) {
          if (xhr.statusText !== 'abort') {
            $results.html('<div class="pkae-no-results">Search error. Please try again.</div>').show();
          }
        },
        complete: function() {
          currentRequest = null;
        }
      });
    }
  }

  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/pkae-advanced-search.default', function ($scope) {
      initAdvancedSearch($scope[0].querySelector('.pkae-search-wrapper'));
    });
  });

  $(document).ready(function() {
    $('.pkae-search-wrapper').each(function() {
      initAdvancedSearch(this);
    });
  });

})(jQuery);

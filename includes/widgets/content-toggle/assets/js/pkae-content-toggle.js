/* global jQuery */
(function ($) {
  'use strict';

  function initToggle(root) {
    if (!root) return;

    var btn       = root.querySelector('.pkae-ct__switch');
    var panes     = root.querySelectorAll('.pkae-ct__pane');
    var labels    = root.querySelectorAll('.pkae-ct__label');
    var animation = root.getAttribute('data-animation') === 'yes';

    if (animation) root.classList.add('pkae-ct--animated');

    if (!btn) return;

    btn.addEventListener('click', function () {
      var isOn = btn.classList.contains('pkae-ct--active');

      // Toggle switch
      btn.classList.toggle('pkae-ct--active', !isOn);
      btn.setAttribute('aria-checked', (!isOn).toString());

      // Toggle panes
      panes.forEach(function (pane) {
        var isPrimary = pane.classList.contains('pkae-ct__pane--primary');
        pane.classList.toggle('pkae-ct--active', isOn ? isPrimary : !isPrimary);
      });

      // Toggle labels
      labels.forEach(function (label) {
        var isPrimary = label.classList.contains('pkae-ct__label--primary');
        label.classList.toggle('pkae-ct--active', isOn ? isPrimary : !isPrimary);
      });
    });

    // Labels clickable
    labels.forEach(function (label) {
      label.addEventListener('click', function () {
        var isPrimary = label.classList.contains('pkae-ct__label--primary');
        var isOn      = btn.classList.contains('pkae-ct--active');

        // Only toggle if clicking inactive label
        if ( (isPrimary && isOn) || (!isPrimary && !isOn) ) {
          btn.click();
        }
      });
    });
  }

  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/pkae-content-toggle.default', function ($scope) {
      initToggle($scope[0].querySelector('.pkae-ct'));
    });
  });

  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.pkae-ct').forEach(initToggle);
  });

})(jQuery);

/* global jQuery */
(function ($) {
  'use strict';

  function initTable(wrap) {
    if (!wrap) return;

    var table      = wrap.querySelector('.pkae-table');
    var searchInput = wrap.querySelector('.pkae-table-search');
    if (!table) return;

    var isSortable = table.classList.contains('pkae-table--sortable');
    var headers    = table.querySelectorAll('thead th');
    var tbody      = table.querySelector('tbody');

    // ── Sort ────────────────────────────────────────────────────────────────
    if (isSortable && headers.length) {
      var sortDir = {};

      headers.forEach(function (th, colIdx) {
        th.addEventListener('click', function () {
          var dir = sortDir[colIdx] === 'asc' ? 'desc' : 'asc';
          sortDir = {};
          sortDir[colIdx] = dir;

          // Reset all headers
          headers.forEach(function (h) {
            h.classList.remove('pkae-sort-asc', 'pkae-sort-desc');
          });
          th.classList.add('pkae-sort-' + dir);

          var rows = Array.prototype.slice.call(tbody.querySelectorAll('tr'));
          rows.sort(function (a, b) {
            var aText = (a.cells[colIdx] ? a.cells[colIdx].textContent : '').trim();
            var bText = (b.cells[colIdx] ? b.cells[colIdx].textContent : '').trim();
            var aNum  = parseFloat(aText.replace(/[^0-9.-]/g, ''));
            var bNum  = parseFloat(bText.replace(/[^0-9.-]/g, ''));

            if (!isNaN(aNum) && !isNaN(bNum)) {
              return dir === 'asc' ? aNum - bNum : bNum - aNum;
            }
            return dir === 'asc'
              ? aText.localeCompare(bText)
              : bText.localeCompare(aText);
          });

          rows.forEach(function (row) { tbody.appendChild(row); });
        });
      });
    }

    // ── Pagination ───────────────────────────────────────────────────────────
    var paginationWrap = wrap.querySelector('.pkae-table-pagination');
    var enablePagination = wrap.getAttribute('data-pagination') === 'yes';
    var rowsPerPage = parseInt(wrap.getAttribute('data-rows-per-page'), 10) || 5;
    var currentPage = 1;

    function getVisibleRows() {
      return Array.prototype.slice.call(tbody.querySelectorAll('tr:not(.pkae-hidden)'));
    }

    function renderPagination() {
      if (!enablePagination || !paginationWrap) return;
      var rows      = getVisibleRows();
      var totalPages = Math.ceil(rows.length / rowsPerPage);

      paginationWrap.innerHTML = '';
      if (totalPages <= 1) return;

      function makePage(label, page, disabled, active) {
        var btn = document.createElement('button');
        btn.className = 'pkae-table-page-btn' + (active ? ' pkae-active' : '') + (disabled ? ' pkae-disabled' : '');
        btn.textContent = label;
        btn.disabled = disabled;
        btn.addEventListener('click', function () {
          if (!disabled) { currentPage = page; applyPage(); }
        });
        paginationWrap.appendChild(btn);
      }

      makePage('«', 1, currentPage === 1, false);
      makePage('‹', currentPage - 1, currentPage === 1, false);
      for (var p = 1; p <= totalPages; p++) {
        makePage(p, p, false, p === currentPage);
      }
      makePage('›', currentPage + 1, currentPage === totalPages, false);
      makePage('»', totalPages, currentPage === totalPages, false);
    }

    function applyPage() {
      if (!enablePagination) return;
      var rows = getVisibleRows();
      var start = (currentPage - 1) * rowsPerPage;
      var end   = start + rowsPerPage;
      rows.forEach(function (row, i) {
        row.style.display = (i >= start && i < end) ? '' : 'none';
      });
      renderPagination();
    }

    if (enablePagination) {
      applyPage();
    }

    // Re-apply pagination after search
    if (searchInput) {
      searchInput.addEventListener('input', function () {
        currentPage = 1;
        setTimeout(applyPage, 0);
      });
    }
  }

  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/pkae-table.default', function ($scope) {
      initTable($scope[0].querySelector('.pkae-table-wrap'));
    });
  });

  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.pkae-table-wrap').forEach(initTable);
  });

})(jQuery);

    // ── Search (standalone, without pagination) ───────────────────────────────
    if (searchInput && !enablePagination) {
      searchInput.addEventListener('input', function () {
        var q = searchInput.value.toLowerCase().trim();
        var rows = tbody.querySelectorAll('tr');
        rows.forEach(function (row) {
          var text = row.textContent.toLowerCase();
          row.classList.toggle('pkae-hidden', q !== '' && text.indexOf(q) === -1);
        });
      });
    }

jQuery(document).ready(function($){
  $('.epka-accordion-carousel').each(function(){
    const $wrap = $(this);
    const $owl  = $wrap.find('.epka-owl-carousel');
    const showDots   = $wrap.data('show-dots') === 'yes';
    const showArrows = $wrap.data('show-arrows') === 'yes';

    if (typeof $owl.owlCarousel !== 'function') {
      // Owl missing — avoid breaking
      return;
    }

    $owl.owlCarousel({
      loop: true,
      items: 1,
      margin: 0,
      stagePadding: 0,
      autoplay: false,
      dots: showDots,
      nav: showArrows,
      navText: ['‹','›'],
      onInitialized: buildThumbs,
      onChanged: syncActive
    });

    function buildThumbs(event){
      // Add slidenumberN to non-cloned items
      let slidecount = 1;
      $wrap.find('.owl-item').not('.cloned').each(function(){
        $(this).addClass('epka-slidenumber' + slidecount);
        slidecount++;
      });

      // Add dotnumberN + data-info
      let dotcount = 1;
      $wrap.find('.epka-owl-dot, .owl-dot').each(function(){
        const $dot = $(this);
        $dot.addClass('epka-owl-dot epka-dotnumber' + dotcount)
            .attr('data-info', dotcount);
        dotcount++;
      });

      // Set background-images of dots from slide <img>
      $wrap.find('.epka-owl-dot').each(function(){
        const grab = $(this).data('info');
        const $slide = $wrap.find('.epka-slidenumber' + grab + ' img').first();
        const src = $slide.attr('src');
        if (src) {
          $(this).css('background-image', 'url(' + src + ')');
        }
      });

      // Equal height distribution (vertical rail)
      const amount = $wrap.find('.epka-owl-dot').length || 1;
      const gotowidth = 100 / amount;
      $wrap.find('.epka-owl-dot').css('height', gotowidth + '%');

      syncActive(event);
    }

    function syncActive(event){
      const idx = (event && typeof event.item !== 'undefined') ? event.item.index : 0;
      // owl gives absolute index including clones; map to current .owl-dot active class
      setTimeout(function(){
        // Owl adds/removes .active on dots automatically; ensure our class mirrors
        $wrap.find('.epka-owl-dot').removeClass('active');
        $wrap.find('.epka-owl-dots .active').addClass('active'); // keep if owl already did
      }, 0);
    }
  });
});

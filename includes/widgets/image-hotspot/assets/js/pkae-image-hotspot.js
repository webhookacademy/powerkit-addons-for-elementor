(function($) {
    'use strict';

    function initHotspot($scope) {
        var $markers = $scope.find('.pkae-hotspot-marker');

        $markers.each(function() {
            var $marker      = $(this);
            var trigger      = $marker.data('trigger') || 'hover';
            var tipStyle     = $marker.data('tooltip-style') || 'default';
            var $tooltip     = $marker.find('.pkae-hotspot-tooltip');
            var $lineTooltip = $marker.find('.pkae-line-tooltip');

            if ( tipStyle === 'line' ) {
                setupLineTooltip( $marker, $lineTooltip );
            }

            if ( trigger === 'hover' ) {
                // Stop click bubbling on hover-mode markers
                $marker.on('click', function(e) {
                    e.stopPropagation();
                });

                $marker.on('mouseenter', function() {
                    closeAllTooltips($scope);
                    if ( tipStyle === 'line' ) {
                        $lineTooltip.addClass('pkae-line-visible');
                    } else {
                        $tooltip.addClass('pkae-tooltip-visible');
                    }
                    $marker.addClass('pkae-active');
                });

                $marker.on('mouseleave', function() {
                    if ( tipStyle === 'line' ) {
                        $lineTooltip.removeClass('pkae-line-visible');
                    } else {
                        $tooltip.removeClass('pkae-tooltip-visible');
                    }
                    $marker.removeClass('pkae-active');
                });

            } else if ( trigger === 'click' ) {
                $marker.on('click', function(e) {
                    e.stopPropagation();
                    if ( tipStyle === 'line' ) {
                        var isOpen = $lineTooltip.hasClass('pkae-line-visible');
                        closeAllTooltips($scope);
                        if ( !isOpen ) {
                            $lineTooltip.addClass('pkae-line-visible');
                            $marker.addClass('pkae-active');
                        }
                    } else {
                        var isOpen = $tooltip.hasClass('pkae-tooltip-visible');
                        closeAllTooltips($scope);
                        if ( !isOpen ) {
                            $tooltip.addClass('pkae-tooltip-visible');
                            $marker.addClass('pkae-active');
                        }
                    }
                });
            }
        });

        // Always remove any previously attached listener for this scope first
        var scopeId = $scope.attr('data-id') || $scope.index();
        $(document).off('click.pkae_hotspot_' + scopeId);

        // Only re-attach if close-on-outside is explicitly 'yes'
        var closeOutside = $scope.find('.pkae-hotspot-wrapper').data('close-outside');
        if ( closeOutside === 'yes' ) {
            $(document).on('click.pkae_hotspot_' + scopeId, function() {
                closeAllTooltips($scope);
            });
        }

        // Prevent clicks inside tooltip from closing it
        $scope.find('.pkae-hotspot-tooltip, .pkae-line-tooltip').on('click', function(e) {
            e.stopPropagation();
        });
    }

    function setupLineTooltip( $marker, $lineTooltip ) {
        var dir     = $lineTooltip.data('dir') || 'right-down';
        var lineLen = parseInt( $marker.data('line-len') ) || 80;
        var $lineH  = $lineTooltip.find('.pkae-line-h');
        var $lineV  = $lineTooltip.find('.pkae-line-v');
        var $label  = $lineTooltip.find('.pkae-line-label');

        $lineTooltip[0].style.setProperty('--pkae-line-len', lineLen + 'px');
        $lineTooltip.css({ position: 'absolute', top: '50%', left: '50%' });

        if ( dir === 'right-down' ) {
            $lineH.css({ top: '-1px', left: '0', transformOrigin: 'left center' });
            $lineV.css({ top: '-1px', left: lineLen + 'px', transformOrigin: 'top center' });
            $label.css({ top: lineLen + 'px', left: lineLen + 'px', transform: 'translateX(-50%)' });
        } else if ( dir === 'right-up' ) {
            $lineH.css({ top: '-1px', left: '0', transformOrigin: 'left center' });
            $lineV.css({ bottom: '-1px', left: lineLen + 'px', top: 'auto', transformOrigin: 'bottom center' });
            $label.css({ bottom: lineLen + 'px', left: lineLen + 'px', top: 'auto', transform: 'translateX(-50%)' });
        } else if ( dir === 'left-down' ) {
            $lineH.css({ top: '-1px', right: '0', left: 'auto', transformOrigin: 'right center' });
            $lineV.css({ top: '-1px', right: lineLen + 'px', left: 'auto', transformOrigin: 'top center' });
            $label.css({ top: lineLen + 'px', right: lineLen + 'px', left: 'auto', transform: 'translateX(50%)' });
        } else if ( dir === 'left-up' ) {
            $lineH.css({ top: '-1px', right: '0', left: 'auto', transformOrigin: 'right center' });
            $lineV.css({ bottom: '-1px', right: lineLen + 'px', left: 'auto', top: 'auto', transformOrigin: 'bottom center' });
            $label.css({ bottom: lineLen + 'px', right: lineLen + 'px', left: 'auto', top: 'auto', transform: 'translateX(50%)' });
        }
    }

    function closeAllTooltips($scope) {
        $scope.find('.pkae-hotspot-tooltip').removeClass('pkae-tooltip-visible');
        $scope.find('.pkae-line-tooltip').removeClass('pkae-line-visible');
        $scope.find('.pkae-hotspot-marker').removeClass('pkae-active');
    }

    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/pkae-image-hotspot.default', function($scope) {
            initHotspot($scope);
        });
    });

})(jQuery);
/* global jQuery */
(function ($) {
  'use strict';

  function initSVGAnimator(element) {
    if (!element) return;

    var $el = $(element);
    var animationType = $el.data('animation-type') || 'draw';
    var trigger = $el.data('animation-trigger') || 'viewport';
    var threshold = parseFloat($el.data('viewport-threshold')) || 50;
    var duration = parseInt($el.data('duration')) || 1500;
    var delay = parseInt($el.data('delay')) || 0;
    var easing = $el.data('easing') || 'ease';
    var pathMode = $el.data('path-mode') || 'together';
    var staggerDelay = parseInt($el.data('stagger-delay')) || 100;
    var direction = $el.data('direction') || 'normal';
    var loop = $el.data('loop') === 'yes';
    var yoyo = $el.data('yoyo') === 'yes';
    var replayClick = $el.data('replay-click') === 'yes';
    var repeatScroll = $el.data('repeat-scroll') === 'yes';

    var $svg = $el.find('svg');
    var $paths = $svg.find('path, line, polyline, polygon, circle, ellipse, rect');
    var hasAnimated = false;

    // Calculate path lengths for draw animation
    if (animationType === 'draw') {
      $paths.each(function() {
        var path = this;
        var length = 0;
        
        if (path.getTotalLength) {
          length = path.getTotalLength();
        } else {
          length = 1000; // fallback
        }
        
        $(path).css({
          'stroke-dasharray': length,
          'stroke-dashoffset': length
        });
      });
    }

    function animate() {
      if (pathMode === 'sequential') {
        animateSequential();
      } else {
        animateTogether();
      }
      hasAnimated = true;
    }

    function animateTogether() {
      setTimeout(function() {
        $el.addClass('pkae-animated');
        
        if (animationType === 'draw') {
          $paths.css({
            'transition': 'stroke-dashoffset ' + duration + 'ms ' + easing,
            'stroke-dashoffset': direction === 'reverse' ? '2000' : '0'
          });
        } else if (animationType === 'fill') {
          $paths.css({
            'transition': 'fill-opacity ' + duration + 'ms ' + easing,
            'fill-opacity': '1'
          });
        } else if (animationType === 'scale') {
          $svg.css({
            'transition': 'transform ' + duration + 'ms ' + easing,
            'transform': 'scale(1)'
          });
        } else if (animationType === 'rotate') {
          $svg.css({
            'transition': 'transform ' + duration + 'ms ' + easing,
            'transform': 'rotate(' + (direction === 'reverse' ? '-360deg' : '360deg') + ')'
          });
        } else if (animationType === 'fade') {
          $svg.css({
            'transition': 'opacity ' + duration + 'ms ' + easing,
            'opacity': '1'
          });
        } else if (animationType === 'slide') {
          $svg.css({
            'transition': 'transform ' + duration + 'ms ' + easing + ', opacity ' + duration + 'ms ' + easing,
            'transform': 'translateY(0)',
            'opacity': '1'
          });
        }

        if (loop || yoyo) {
          setTimeout(function() {
            handleLoop();
          }, duration);
        }
      }, delay);
    }

    function animateSequential() {
      $el.addClass('pkae-animated');
      
      $paths.each(function(index) {
        var $path = $(this);
        var pathDelay = delay + (index * staggerDelay);
        
        setTimeout(function() {
          if (animationType === 'draw') {
            $path.css({
              'transition': 'stroke-dashoffset ' + duration + 'ms ' + easing,
              'stroke-dashoffset': direction === 'reverse' ? '2000' : '0'
            });
          } else if (animationType === 'fill') {
            $path.css({
              'transition': 'fill-opacity ' + duration + 'ms ' + easing,
              'fill-opacity': '1'
            });
          }
        }, pathDelay);
      });

      if (loop || yoyo) {
        var totalDuration = delay + ($paths.length * staggerDelay) + duration;
        setTimeout(function() {
          handleLoop();
        }, totalDuration);
      }
    }

    function handleLoop() {
      if (yoyo) {
        // Reverse animation
        if (animationType === 'draw') {
          $paths.css('stroke-dashoffset', direction === 'reverse' ? '0' : '2000');
        } else if (animationType === 'fill') {
          $paths.css('fill-opacity', '0');
        } else if (animationType === 'scale') {
          $svg.css('transform', 'scale(0)');
        } else if (animationType === 'rotate') {
          $svg.css('transform', 'rotate(0deg)');
        } else if (animationType === 'fade') {
          $svg.css('opacity', '0');
        } else if (animationType === 'slide') {
          $svg.css({'transform': 'translateY(50px)', 'opacity': '0'});
        }
        
        setTimeout(function() {
          animate();
        }, duration);
      } else if (loop) {
        // Reset and replay
        reset();
        setTimeout(function() {
          animate();
        }, 100);
      }
    }

    function reset() {
      $el.removeClass('pkae-animated');
      
      if (animationType === 'draw') {
        $paths.each(function() {
          var path = this;
          var length = path.getTotalLength ? path.getTotalLength() : 1000;
          $(path).css({
            'stroke-dashoffset': length,
            'transition': 'none'
          });
        });
      } else if (animationType === 'fill') {
        $paths.css({'fill-opacity': '0', 'transition': 'none'});
      } else if (animationType === 'scale') {
        $svg.css({'transform': 'scale(0)', 'transition': 'none'});
      } else if (animationType === 'rotate') {
        $svg.css({'transform': 'rotate(0deg)', 'transition': 'none'});
      } else if (animationType === 'fade') {
        $svg.css({'opacity': '0', 'transition': 'none'});
      } else if (animationType === 'slide') {
        $svg.css({'transform': 'translateY(50px)', 'opacity': '0', 'transition': 'none'});
      }
    }

    // Trigger handlers
    if (trigger === 'load') {
      animate();
    } else if (trigger === 'viewport') {
      var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
          if (entry.isIntersecting && entry.intersectionRatio >= (threshold / 100)) {
            if (!hasAnimated || repeatScroll) {
              animate();
            }
            if (!repeatScroll) {
              observer.unobserve(element);
            }
          }
        });
      }, { threshold: threshold / 100 });
      
      observer.observe(element);
    } else if (trigger === 'hover') {
      $el.on('mouseenter', function() {
        if (!hasAnimated || loop || yoyo) {
          reset();
          setTimeout(animate, 50);
        }
      });
    } else if (trigger === 'click') {
      $el.on('click', function() {
        reset();
        setTimeout(animate, 50);
      });
    }

    // Replay on click
    if (replayClick && trigger !== 'click') {
      $el.on('click', function() {
        reset();
        setTimeout(animate, 50);
      });
    }
  }

  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/pkae-svg-animator.default', function ($scope) {
      initSVGAnimator($scope[0].querySelector('.pkae-svg-animator'));
    });
  });

  $(document).ready(function() {
    $('.pkae-svg-animator').each(function() {
      initSVGAnimator(this);
    });
  });

})(jQuery);

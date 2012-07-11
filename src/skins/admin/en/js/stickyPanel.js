/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Sticky panel controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.10
 */
jQuery().ready(
  function () {
    jQuery('.sticky-panel').each(
      function () {

        var box = jQuery(this);

        // Options
        var options = box.data('options') || {};

        var defaultOptions = {
          bottomPadding:       25,
          parentContainerLock: true
        };

        jQuery.each(
          defaultOptions,
          function (key, value) {
            if ('undefined' == typeof(options[key])) {
              options[key] = value;
            }
          }
        );

        // Assemble variables
        var panel = box.find('.box').eq(0);
        box.height(panel.outerHeight());

        var timer;
        var doc = jQuery(window.document);
        var lastScrollTop = doc.scrollTop();
        var panelHeight = box.height();
        var parentContainerTop = box.parent().offset().top;

        var centeredBox = box.find('.centered-box').eq(0);
        if (centeredBox.length) {
          centeredBox.width(panel.outerWidth());
        }

        jQuery('button', this).each(function(ind, elem) {
          jQuery(elem).removeAttr('disabled');
        });

        // Check scroll state - run reposition or not
        function checkRepositionEvent()
        {
          clearTimeout(timer);
          timer = setTimeout(
            function()
            {
              var scrollTop = doc.scrollTop();
              if (Math.abs(scrollTop - lastScrollTop) > 0) {
                lastScrollTop = scrollTop;
                reposition();
              }
            },
            50
          );
        }

        // Reposition panel
        function reposition()
        {
          panel.stop();

          var boxScrollTop = box.offset().top;
          var docScrollTop = doc.scrollTop();
          var windowHeight = jQuery(window).height();
          var diff = windowHeight - boxScrollTop + docScrollTop - panelHeight - options.bottomPadding;

          if (0 > diff) {
            if (options.parentContainerLock && parentContainerTop > (boxScrollTop + diff)) {
              panel.css({position: 'absolute', top: parentContainerTop - boxScrollTop});

            } else if ('fixed' != panel.css('position')) {
              panel.css({position: 'fixed', top: windowHeight - panelHeight - options.bottomPadding});
            }

          } else if (panel.css('top') != '0px') {
            panel.css({position: 'absolute', top: 0});

          }
        }

        jQuery(window).scroll(checkRepositionEvent);
        jQuery(window).resize(checkRepositionEvent);
        core.bind('stickyPanelReposition', reposition);
        reposition();
      }
    );
  }
);



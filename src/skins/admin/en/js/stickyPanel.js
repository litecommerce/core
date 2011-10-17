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

        // Options
        var duration = 400;
        var easing = 'linear';
        var delay = 0;
        var bottomPadding = 25;
        var parentContainerLock = true;

        // Assemble variables
        var box = jQuery(this);
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

        // Check scroll stste - run reposition or not
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
          var diff = windowHeight - boxScrollTop + docScrollTop - panelHeight - bottomPadding;

          if (0 > diff) {
            if (parentContainerLock && parentContainerTop > (boxScrollTop + diff)) {
              diff = parentContainerTop - boxScrollTop;
            }

            panel.delay(delay).animate({top: diff}, duration, easing);

          } else if (panel.css('top') != '0px') {
            panel.delay(delay).animate({top: 0}, duration, easing);
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



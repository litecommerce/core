/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Product attachments page controller
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.10
 */

jQuery().ready(
  function () {

    jQuery('.product-attachments ul .name').click(
      function (event) {
        event.stopPropagation();
        self.location = this.href;

        return false;
      }
    );

    // Rollover
    jQuery('.product-attachments ul .switcher a,.product-attachments ul .row').click(
      function (event) {
        event.stopPropagation();
        var li = jQuery(this).parents('li').eq(0);
        var info = li.find('.info');
        if (0 < info.filter(':visible').length) {
          info.hide();
          li.removeClass('expanded');

        } else {
          info.show();
          li.addClass('expanded');
        }

        return false;
      }
    );

    // Remove
    jQuery('.product-attachments ul .remove').click(
      function(event) {
        event.stopPropagation();
        var o = jQuery(this).parents('form').eq(0);
        if (!o.hasClass('blocked')) {
          o.addClass('blocked');
          var row = jQuery(this).parents('li').eq(0);
          core.post(
            this.href,
            function(XMLHttpRequest, textStatus, data, valid) {
              o.removeClass('blocked');
              if (valid) {
                row.remove();
              }
            }
          );
        }

        return false;
      }
    );

    // Drag-and-drop-based sorting
    jQuery('.product-attachments ul .move').click(
      function (event) {
        event.stopPropagation();
        return false;
      }
    );
    jQuery('.product-attachments ul .move').mousedown(
      function () {
        jQuery(this).parents('li').removeClass('expanded');
        jQuery(this).parents('li').find('.info').hide();
      }
    );
    jQuery('.product-attachments ul').sortable(
      {
        axis: 'y',
        handle: '.move',
        update: function()
        {
          var i = 0;
          jQuery('.product-attachments form input.orderby').each(
            function () {
              this.value = i;
              i++;
            }
          );
        }
      }
    );
    jQuery('.product-attachments ul .move').disableSelection();
  }
);

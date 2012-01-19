/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Draggable rows JS controller
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.14
 */

jQuery().ready(
  function () {

    jQuery('ul.draggable-rows li.row div.container div.delete').click(
      function () {
        var box = jQuery(this).children('input[name*="toDelete"]');

        jQuery(this).closest('li.row').toggleClass('row-to-delete');
        box.val(true == box.val() ? 0 : 1);
      }
    );

    jQuery('ul.draggable-rows .move-cursor').click(
      function (event) {
        event.stopPropagation();
        return false;
      }
    );

    jQuery('ul.draggable-rows').sortable(
      {
        axis: 'y',
        handle: '.move-cursor',
        update: function()
        {
          var i = 0;
          jQuery('ul.draggable-rows input[id^=draggable_row_]').each(
            function () {
              i += 10;
              this.value = i;
            }
          );
        }
      }
    );
    jQuery('ul.draggable-rows .move-cursor').disableSelection();
  }
);

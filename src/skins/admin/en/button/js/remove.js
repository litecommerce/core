/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Remove button controller
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.15
 */

jQuery().ready(
  function () {
    jQuery('button.remove').click(
      function () {
        var btn = jQuery(this);
        var inp = btn.prev();
        var cell = btn.parents('.line').eq(0);

        var remove = !inp.attr('value');
        inp.val(remove ? '1' : '');

        if (remove) {
          btn.addClass('mark');
          cell.addClass('remove-mark');

        } else {
          btn.removeClass('mark');
          cell.removeClass('remove-mark');
        }
        cell.parents('form').change();
      }
    );
  }
);


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
        var inp = jQuery('input', this);
        var checked = inp.attr('checked');
        if (checked) {
          inp.removeAttr('checked');

        } else {
          inp.attr('checked', 'checked');
          inp.get(0).setAttribute('checked', 'checked');
        }
        inp.change();
      }
    );

    jQuery('button.remove input').change(
      function () {
        var btn = jQuery(this).parent();
        var cell = btn.parents('.line').eq(0);

        if (this.checked) {
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


/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Remove button controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

CommonForm.elementControllers.push(
  {
    pattern: '.line .actions .remove-wrapper',
    handler: function () {
      var inp = jQuery('input', this).eq(0);
      var btn = jQuery('button.remove', this);
      var cell = btn.parents('.line').eq(0);

      btn.click(
        function () {
          if (inp.attr('checked')) {
            inp.removeAttr('checked');
          } else {
            inp.attr('checked', 'checked');
          }

          inp.change();
        }
      );

      inp.change(
        function () {
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
  }
);


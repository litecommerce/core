/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Date picker controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 */
function datePickerPostprocess(input, elm)
{
}

jQuery().ready(
  function() {
    jQuery('.date-picker-widget').each(function (index, elem) {
      var options = core.getCommentedData(elem);

      jQuery('input', elem).datepicker(
        {
          dateFormat:        options.dateFormat,
          gotoCurrent:       true,
          yearRange:         options.highYear + '-' + options.lowYear,
          showButtonPanel:   false,
          beforeShow:        datePickerPostprocess,
          selectOtherMonths: true
        }
      );
    });
  }
);
/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Prodict options modify controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.5
 */

function lcChangeViewTypeOptionGroup () {
  var dataType = jQuery('#data_type').val();
  var dataViewTypeObj = jQuery('#data_view_type');
  var selected = dataViewTypeObj.val();

  if (typeof(lcViewTypeOptions[dataType]) != 'undefined') {

    dataViewTypeObj.empty();

    jQuery.each(
      lcViewTypeOptions[dataType],
      function(k, v) {
        dataViewTypeObj.append(new Option(v.name, k));
      }
    );

    dataViewTypeObj.val(selected);
  }
}

core.bind(
  'load',
  function () {
    lcChangeViewTypeOptionGroup();

    jQuery('#data_type').change(function () {lcChangeViewTypeOptionGroup()});
  }
);

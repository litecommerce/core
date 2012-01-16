/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * JS support for the attributes book
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.14
 */

jQuery().ready(
  function() {
    jQuery('div.group .delete.group').click(
      function () {
        var row = jQuery(this).closest('li.row');
        var box = jQuery(this).children('input[name*="toDelete"]');

        row.toggleClass('row-to-delete');
        row.find('div.group-attributes').toggleClass('row-to-delete');

        box.val(true == box.val() ? 0 : 1);
      }
    );

    jQuery('div.group .delete.attribute').click(
      function () {
        var row = jQuery(this).closest('li.row');
        var box = jQuery(this).children('input[name*="toDelete"]');

        row.toggleClass('row-to-delete');
        box.val(true == box.val() ? 0 : 1);
      }
    );
  }
);

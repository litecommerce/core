/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Product classes list JS
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.16
 */

jQuery().ready(
  function() {
    var oldValues = {};

    jQuery('div.product-class-name input[type="text"]').each(
      function () {
        oldValues[jQuery(this).attr('name')] = jQuery(this).val();
      }
    );

    jQuery('button#new_class_button').click(
      function () {
        jQuery('div.product-class.hidden').find('div.product-class-name').click();
      }
    );

    jQuery('div.product-class-name').click(
      function () {
        jQuery(this).children('input[type="text"]').show();
        jQuery(this).children('input[type="text"]').focus();
        jQuery(this).children('span').hide();
        jQuery(this).next('div.product-class-cancel-link').show();
      }
    );

    jQuery('div.product-class-cancel-link a').click(
      function () {
        var selfBox = jQuery(this).parent();
        var parentBox = selfBox.prev('div.product-class-name');
        var input = parentBox.children('input[type="text"]');
        var label = parentBox.children('span');

        selfBox.hide();
        input.hide();
        input.val(oldValues[input.attr('name')]);
        label.show();
      }
    );

    jQuery('div.product-class-name input[type="text"]').focusout(
      function () {
        if (jQuery(this).val() == oldValues[jQuery(this).attr('name')]) {
          jQuery(this).parent('div').next('div.product-class-cancel-link').children('a').click();
        }
      }
    );
  }
);

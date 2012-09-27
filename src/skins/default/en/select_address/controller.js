/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Pick address from address book controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */
core.trigger(
  'load',
  function() {
    var form = jQuery('form.select-address').eq(0);
    jQuery('.select-address li').click(
      function() {
        form.get(0).elements.namedItem('addressId').value = core.getValueFromClass(this, 'address')
        form.submit();
      }
    );
  }
);

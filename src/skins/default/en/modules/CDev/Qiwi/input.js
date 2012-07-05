/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Qiwi phone input controller
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.24
 */

function checkQiwiPhoneNumber(field, rules, i, options)
{
  var value = jQuery.trim(field.val()).replace(/[^0-9]/, '');

  if (-1 === value.search(/^[0-9]{10}$/)) {
    return core.t('Please enter 10-digit mobile phone number without country code (with no spaces or hyphens)');

  } else if (field.val() != value) {
    field.val(value);
  }
}

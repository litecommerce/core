/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Swicth button controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

function OrderInfoForm()
{
  jQuery(this.formContainer).get(0).commonController.submitOnlyChanged = true;
}

OrderInfoForm.prototype.formContainer = '.order-info form';

core.autoload(OrderInfoForm);
{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Payment methods list widget for popup
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<ul class="payments-list">
  {foreach:getPaymentMethods(),family,entries}
  <li IF="{entries[0]->getAdminIconURL()}" class="payment-method-icon"><img src="{entries[0]->getAdminIconURL()}" alt="{entries[0]->getTitle()}" /></li>
  <li FOREACH="entries,id,payment">{payment.getTitle()}</li>
  {end:}
</ul>
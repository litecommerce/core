{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Payment transactions summary
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="order.actions", weight="900")
 *}

<ul IF="hasPaymentTransactionSums()" class="payment-sums">
  <li FOREACH="getPaymentTransactionSums(),label,sum">
    {label}: {formatPrice(sum,order.getCurrency())}
  </li>
</ul>

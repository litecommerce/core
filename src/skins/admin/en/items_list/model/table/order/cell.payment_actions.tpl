{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Cell actions
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.24
 *
 * @ListChild (list="itemsList.orders.search.cell.status")
 *}

<div IF="hasPaymentActions(entity)" class="payment-actions">
  <widget class="\XLite\View\Order\Details\Admin\PaymentActions" order="{entity}" unitsFilter="{getTransactionsFilter()}" />
</div>


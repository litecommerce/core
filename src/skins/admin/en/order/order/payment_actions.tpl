{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Order transactions
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

{foreach:getTransactions(),index,transaction}
<div class="unit" FOREACH="getTransactionUnits(transaction),id,unit">
  <widget class="\XLite\View\Order\Details\Admin\PaymentActionsUnit" transaction="{transaction}" unit="{unit}" />
  <span IF="!isLastUnit(id)" class="payment-actions-separator">{t(#OR#)}</span>
</div>
{end:}

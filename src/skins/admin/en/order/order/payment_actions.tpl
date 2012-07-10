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

<tr>
  <td colspan="2">

    <ul class="payment-actions">
      <li FOREACH="getTransactions(),index,transaction">
        <div class="method-name">{transaction.getMethodLocalName()}</div>
        <ul>
          <li FOREACH="getBackendTransactions(transaction),bid,btransaction">
            <div>{formatTime(btransaction.getDate())}: {btransaction.getType()} - {btransaction.getStatus()}</div>
          </li>
        </ul>
        <div class="unit" FOREACH="getTransactionUnits(transaction),id,unit"><widget class="\XLite\View\Order\Details\Admin\PaymentActionsUnit" transaction="{transaction}" unit="{unit}" /></div>
      </li>
    </ul>

  </td>
</tr>

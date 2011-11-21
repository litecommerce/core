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
  <td>{t(#Payment transactions#)}:</td>
  <td>

<ul class="transactions">
  <li FOREACH="getTransactions(),index,transaction">
    <div class="id">#{transaction.getTransactionId()}{if:transaction.getPublicId()} / {transaction.getPublicId()}{end:}</div>
    <div class="status">{getTransactionStatus(transaction)}</div>
    <div class="value"><strong>{t(#Value#)}:</strong><span>{order.currency.formatValue(transaction.value)}</span></div>
    <ul class="data" IF="getTransactionData(transaction)">
      <li FOREACH="getTransactionData(transaction),cell">
        <strong>{cell.getLabel()}:</strong>
        <span>{cell.getValue()}</span>
      </li>
    </ul>
  </li>
</ul>

  </td>
</tr>

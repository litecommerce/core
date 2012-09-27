{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Payment transaction PNREF and PPREF (for PayPal transactions only)
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="order.payment", weight="110")
 *}

<div class="pp-transaction-ids" IF="order.getTransactionIds()">
  {foreach:order.getTransactionIds(),tid}
  <div>
    {tid.name}:
    {if:tid.url}
    <a href="{tid.url}">{tid.value}</a>
    {else:}
    {tid.value}
    {end:}
  </div>
  {end:}
</div>

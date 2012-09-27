{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<p align="justify">{t(#This section displays order processing statistics#)}</p>

<br /><br />

<table class="data-table order-statistics">

  <tr class="TableHead">
    <th class="title"><widget class="XLite\View\Order\Statistics\CurrencySelector" /></th>
    <th FOREACH="getStatsColumns(),c">{t(getColumnTitle(c))}</th>
  </tr>

  <tr FOREACH="getStats(),idx,row" class="dialog-box{if:isTotalsRow(idx)} totals{end:}">
    <td class="title">{t(getRowTitle(idx))}</td>
    <td FOREACH="row,idx1,val">
      {if:isTotalsRow(idx)}{formatPrice(val,getCurrency())}{else:}{val}{end:}
    </td>
  </tr>

</table>

<widget class="\XLite\View\Button\Regular" label="Perform order search" jsCode="self.location='{buildURL(#order_list#)}';" />

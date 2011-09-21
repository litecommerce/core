{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
<p align="justify">{t(#This section displays 10 top-selling products for today, this week and this month.#)}</p>

<h2>{t(#Top X products#,_ARRAY_(#count#^getRowsCount()))}</h2>

<table class="data-table top-sellers">
  <tr class="TableHead">
    <th class="title"><widget class="XLite\View\Order\Statistics\CurrencySelector" /></th>
    <th FOREACH="getStatsColumns(),c">{getColumnTitle(c)}</th>
  </tr>
  <tr FOREACH="getStats(),idx,row" class="dialog-box">
    <td class="title">{inc(idx)}.</td>
    <td FOREACH="row,idx1,val">
      {if:val}
        {if:val.product.product_id}
          <a href="{buildURL(#product#,##,_ARRAY_(#product_id#^val.product.product_id))}">{val.name}</a>
        {else:}
          <span>{val.name} ({t(#deleted#)})</span>
        {end:}
      {end:}
      <span IF="!val">&mdash;</span>
    </td>
  </tr>
</table>

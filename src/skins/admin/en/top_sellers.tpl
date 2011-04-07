{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<p align="justify">{t(#This section displays 10 top-selling products for today, this week and this month.#)}</p>

<h2>{t(#Top X products#,_ARRAY_(#count#^getRowsCount()))}</h2>

<table class="data-table">
  <tr class="TableHead">
    <th align="left">&nbsp;</th>
    <th FOREACH="getStatsColumns(),c" style="text-align:center;">{getColumnTitle(c)}</th>
  </tr>
  <tr FOREACH="getStats(),idx,row" class="dialog-box">
    <td>{inc(idx)}.</td>
    <td FOREACH="row,idx1,val" align="center">
      <a IF="val" href="{buildURL(#product#,##,_ARRAY_(#product_id#^val.product.product_id))}">{val.name}</a>
      <span IF="!val">&mdash;</span>
    </td>
  </tr>
</table>

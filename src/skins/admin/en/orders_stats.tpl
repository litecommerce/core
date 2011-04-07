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
<p align="justify">{t(#This section displays order placement statistics.#)}</p>

<br /><br />

<table class="data-table" width="80%">
  <tr class="TableHead" align="center">
    <th align="left">&nbsp;</th>
    <th FOREACH="getStatsColumns(),c" style="text-align:center;">{getColumnTitle(c)}</th>
  </tr>
  <tr FOREACH="getStats(),idx,row" class="dialog-box{if:isTotalsRow(idx)} totals{end:}">
    <td>{getRowTitle(idx)}</td>
    <td FOREACH="row,idx1,val" align="center">
      {if:isTotalsRow(idx)}{price_format(val)}{else:}{val}{end:}
    </td>
  </tr>
</table>

<br /><br />
<widget class="\XLite\View\Button\Regular" label="Perform order search" jsCode="self.location='admin.php?target=order_list';" />

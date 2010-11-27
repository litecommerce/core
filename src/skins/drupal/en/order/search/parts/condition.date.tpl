{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Orders search Order ID condition
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="orders.search.conditions", weight="20")
 *}
<tr class="date">
  <td class="title">{t(#Date range#)}:</td>
  <td>

    <widget class="\XLite\View\DatePicker" field="startDate" value="{getCondition(#startDate#)}" />
    &ndash;
    <widget class="\XLite\View\DatePicker" field="endDate" value="{getCondition(#endDate#)}" />

    <br />

{* TODO Restore

    <ul class="date-buttons">
      <li><a href="javascript:void(0);" onclick="javascript:">{t(#This week#)}</a></li>
      <li><a href="javascript:void(0);" onclick="javascript:">{t(#This month#)}</a></li>
      <li><a href="javascript:void(0);" onclick="javascript:">{t(#This year#)}</a></li>
    </ul>

*}

  </td>
</tr>

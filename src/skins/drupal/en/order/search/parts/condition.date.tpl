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
 * @ListChild (list="orders.search.conditions", weight="30")
 *}
<tr>
  <td>Date (range):</td>
  <td>
    <widget class="XLite_View_DatePicker" field="startDate" value="{getCondition(#startDate#)}" />
    &ndash;
    <widget class="XLite_View_DatePicker" field="endDate" value="{getCondition(#endDate#)}" />
  </td>
  <td class="button-cell"><widget class="XLite_View_Button_Submit" label="Search orders" /></td>
</tr>

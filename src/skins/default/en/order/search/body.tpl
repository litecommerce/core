{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Orders search widget
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<a IF="getTotalCount()" href="javascript:void(0);" class="dynamic search-orders dynamic-close"><span>Search orders</span><img src="images/spacer.gif" alt="" /></a>
<div class="orders-total">Total: <span>{getTotalCount()}</span> orders{if:getTotalCount()}, found: <span>{getCount()}</span> orders{end:}</div>

<widget class="\XLite\View\Form\Order\Search" name="order_search_form" IF="getTotalCount()" />

  <table cellspacing="0" class="form-table search-orders">
    <tr class="order-id">
      <td>Order id:</td>
      <td><input type="text" name="order_id" value="{getCondition(#order_id#)}" /></td>
      <td IF="!isDefaultConditions()" class="button-cell reset"><a href="{buildUrl(#order_list#,#reset#)}">See all orders</a></td>
    </tr>

    <tr class="status">
      <td>Status:</td>
      <td height="10">
        <widget class="\XLite\View\StatusSelect" field="status" value="{getCondition(#status#)}" allOption />
      </td>
    </tr>

    <tr>
      <td>Date (range):</td>
      <td>
        <widget class="\XLite\View\DatePicker" field="startDate" value="{getCondition(#startDate#)}" />
        &ndash;
        <widget class="\XLite\View\DatePicker" field="endDate" value="{getCondition(#endDate#)}" />
      </td>
      <td class="button-cell"><widget class="\XLite\View\Button\Submit" label="Search orders" /></td>
    </tr>

  </table>

<widget name="order_search_form" end />

<widget class="\XLite\View\OrderList\Search" />

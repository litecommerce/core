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
<widget template="order/invoice/page.tpl" />

<div class="invoice-service">
  <div>
    <a href="javascript:void(0);" onclick="javascript: window.print(); return false;">{t(#Print invoice#)}</a>
  </div>

  <form action="admin.php" method="post">
    <input type="hidden" name="target" value="order" />
    <input type="hidden" name="action" value="update" />
    <input type="hidden" name="order_id" value="{order.order_id}" />
    <input type="hidden" name="returnURL" value="{buildURL(#order#,##,_ARRAY_(#order_id#^order.order_id))}" />

    <table>
      <tr>
      	<td>{t(#Status#)}:</td>
      	<td><widget class="\XLite\View\FormField\Select\OrderStatus" fieldOnly="true" fieldName="status" value="{order.getStatus()}" orderId="{order.getOrderId()}" /></td>
      </tr>

      <tr FOREACH="order.getMeaningDetails(),d" valign="top">
      	<td>{d.getLabel()}:</td>
	      <td><input type="text" name="details[{d.getDetailId()}]" size="40" value="{d.getValue():r}" /></td>
      </tr>

      <tr valign="top">
	      <td>{t(#Notes#)}:</td>
	      <td><textarea name="notes" cols="60" rows="7">{order.notes:h}</textarea></td>
      </tr>

      {displayViewListContent(#order.details#,_ARRAY_(#order#^order))}

      <tr valign="top">
	      <td>&nbsp;</td>
  	    <td><widget class="\XLite\View\Button\Submit" label="Submit" /></td>
      </tr>
    </table>
  </form>

</div>

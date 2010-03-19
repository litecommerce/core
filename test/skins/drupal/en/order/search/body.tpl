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
<widget class="XLite_View_Form_Order_Search" name="order_search_form" />

  <table cellspacing="0">
    <tr>
      <td>Order id</td>
      <td>
        <input name="order_id1" value="{getCondition(#order_id1#)}" />
        -
        <input name="order_id2" value="{getCondition(#order_id2#)}" />
      </td>
    </tr>

    <tr>
      <td>Order status:</td>
      <td height="10">
        <widget class="XLite_View_StatusSelect" field="status"  value="{getCondition(#status1#)}" allOption />
      </td>
    </tr>

    <tr>
      <td>Order date from:</td>
      <td>
        <widget class="XLite_View_Date" field="startDate" value="{getCondition(#startDate#)}" />
      </td>
    </tr>

    <tr>
      <td>Order date through:</td>
      <td>
        <widget class="XLite_View_Date" field="endDate" value="{getCondition(#endDate#)}"/>
      </td>
    </tr>

    <tr>
      <td>&nbsp;</td>
      <td><widget class="XLite_View_Button_Submit" label="Search" /></td>
    </tr>

  </table>

<widget name="order_search_form" end />

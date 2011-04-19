{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Order search form
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<widget class="\XLite\View\Form\Order\Search" name="search_form" />

  <table>

    <tbody>

      <tr>
        <td class="table-label">Order id</td>
        <td style="height:10px;">
          <input type="text" size="6" name="orderId" value="{getCondition(#orderId#)}" />
        </td>
      </tr>

      <tr>
  	    <td class="table-label">E-mail:</td>
      	<td><input type="text" name="login" value="{getCondition(#login#):r}" /></td>
      </tr>

      <tr>
        <td class="table-label">Order status:</td>
        <td style="height:10px;">
          <widget class="\XLite\View\FormField\Select\OrderStatus" fieldOnly fieldName="status" value="{getCondition(#status#)}" allOption />
        </td>
      </tr>

      <widget module="CDev\AntiFraud" template="modules/CDev/AntiFraud/orders/search_form.tpl" />

      <tr>
        <td class="table-label">Order date from:</td>
        <td style="height:10px;">
          <widget class="\XLite\View\Date" field="startDate" value="{getDateCondition()}" />
        </td>
      </tr>

      <tr>
        <td class="table-label">Order date through:</td>
        <td style="height:10px;">
          <widget class="\XLite\View\Date" field="endDate" value="{getDateCondition(false)}" />
        </td>
      </tr>

      {displayViewListContent(#orders.search.children#)}

      <tr>
        <td style="width:78;">&nbsp;</td>
        <td style="height:30;">
          <widget class="\XLite\View\Button\Submit" label=" Search " style="main-button" />

          {* TODO - restore and uncomment *}

          {*
          &nbsp;&nbsp;&nbsp;
          <widget class="\XLite\View\Button\Regular" label=" Export to.. " jsCode="document.order_search_form.action.value=document.order_search_form.export_format.value;document.order_search_form.submit()" />
          &nbsp;
          <select name="export_format">
            <option value="default" selected>- select export format -</option>
            <option FOREACH="exportFormats,format,description" value="{format}">{description}</option>
          </select>
          *}

        </td>
      </tr>

    </tbody>

  </table>

<widget name="search_form" end />

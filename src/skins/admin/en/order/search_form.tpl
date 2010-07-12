{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Order search form
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<form action="admin.php" method="GET" name="order_search_form">

  <input type="hidden" name="target" value="order_list" />
  <input type="hidden" name="mode" value="search" />
  <input type="hidden" name="action" value="" />

  <table>

    <tbody>

      <tr>
        <td class=FormButton noWrap height=10>Order id</td>
        <td height=10>
          <input size=6 name=order_id value="{order_id}" />
        </td>
      </tr>

      <tr>
  	    <td class=FormButton noWrap height=10>E-mail:</td>
      	<td><input type="text" name="login" value="{login:r}" /></td>
      </tr>

      <tr>
        <td class=FormButton noWrap height=10>Order status:</td>
        <td height=10>
          <widget class="\XLite\View\StatusSelect" field="status" allOption />
        </td>
      </tr>

      <widget module="AntiFraud" template="modules/AntiFraud/orders/search_form.tpl" />

      <tr>
        <td class=FormButton noWrap height=10>Order date from:</td>
        <td height=10>
          <widget class="\XLite\View\Date" field="startDate" value="{startDate}" />
        </td>
      </tr>

      <tr>
        <td class=FormButton noWrap height=10>Order date through:</td>
        <td height=10>
          <widget class="\XLite\View\Date" field="endDate" value="{endDate}" />
        </td>
      </tr>

      {displayViewListContent(#orders.search.childs#)}

      <tr>
        <td class=FormButton width=78>&nbsp;</td>
        <td height=30>
          <input type="button" value=" Search " class="DialogMainButton" onClick="document.order_search_form.mode.value='search'; document.order_search_form.action.value=''; document.order_search_form.submit()" />
          &nbsp;&nbsp;&nbsp;
          <input type="button" value=" Export to.. " onclick="document.order_search_form.action.value=document.order_search_form.export_format.value;document.order_search_form.submit()" />
          &nbsp;
          <select name="export_format">
            <option value="default" selected>- select export format -</option>
            <option FOREACH="exportFormats,format,description" value="{format}">{description}</option>
          </select>
        </td>
      </tr>

    </tbody>

  </table>

</form>


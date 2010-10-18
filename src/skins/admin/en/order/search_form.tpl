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

<widget class="\XLite\View\Form\Order\Search" name="search_form" />

  <table>

    <tbody>

      <tr>
        <td class="FormButton" nowrap="nowrap" height="10">Order id</td>
        <td height="10">
          <input size="6" name="orderId" value="{getCondition(#orderId#)}" />
        </td>
      </tr>

      <tr>
  	    <td class="FormButton" nowrap="nowrap" height="10">E-mail:</td>
      	<td><input type="text" name="login" value="{getCondition(#login#):r}" /></td>
      </tr>

      <tr>
        <td class="FormButton" nowrap="nowrap" height="10">Order status:</td>
        <td height="10">
          <widget class="\XLite\View\StatusSelect" field="status" allOption value="{getCondition(#status#)}" />
        </td>
      </tr>

      <widget module="AntiFraud" template="modules/AntiFraud/orders/search_form.tpl" />

      <tr>
        <td class="FormButton" nowrap="nowrap" height="10">Order date from:</td>
        <td height="10">
          <widget class="\XLite\View\Date" field="startDate" value="{getDateCondition()}" />
        </td>
      </tr>

      <tr>
        <td class="FormButton" nowrap="nowrap" height="10">Order date through:</td>
        <td height="10">
          <widget class="\XLite\View\Date" field="endDate" value="{getDateCondition(false)}" />
        </td>
      </tr>

      {displayViewListContent(#orders.search.childs#)}

      <tr>
        <td class="FormButton" width="78">&nbsp;</td>
        <td height="30">
          <input type="submit" value=" Search " class="DialogMainButton" />
          {* TODO - restore and uncomment *}
          {*&nbsp;&nbsp;&nbsp;
          <input type="button" value=" Export to.. " onclick="document.order_search_form.action.value=document.order_search_form.export_format.value;document.order_search_form.submit()" />
          &nbsp;
          <select name="export_format">
            <option value="default" selected>- select export format -</option>
            <option FOREACH="exportFormats,format,description" value="{format}">{description}</option>
          </select>*}
        </td>
      </tr>

    </tbody>

  </table>

<widget name="search_form" end />

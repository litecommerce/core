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

<div IF="hasShippingMarkups()">

<script type="text/javascript" language="JavaScript 1.2">

checkboxes_form = 'shippingratesform';
checkboxes = new Array(
{foreach:getShippingMarkups(),k,markup}{if:!k=0},{end:}'sm_{markup.zone.getZoneId()}_{markup.shipping_method.getMethodId()}','to_delete[{markup.getMarkupId()}]'{end:}
);
var lbl_no_items_have_been_selected = 'There are no markups selected';

function submitForm(formName, action)
{
  document.forms[formName].elements['action'].value = action;
  document.forms[formName].submit();
}

</script> 

<br /><br />

<table cellpadding="0" cellspacing="0" width="100%">
<tr>
  <td><div style="line-height:170%"><b><a href="javascript:change_all(true);">Check all</a> / <a href="javascript:change_all(false);">Uncheck all</a></b></div></td>
  <td align="right"><a href="#addmarkup"}><b>Add markup</b></a></td>
</tr>
</table>

<form action="admin.php" method="post" name="shippingratesform">

  <input type="hidden" name="target" value="shipping_rates" />
  <input type="hidden" name="action" value="update" />
  <input type="hidden" name="zoneid" value="{zoneid}" />
  <input type="hidden" name="methodid" value="{methodid}" />
  <input type="hidden" name="deleted_markup" />

  <table class="data-table">

  {foreach:getPreparedShippingMarkups(),zid,zn}

    <tr>
      <td>
        <h3>{zn.zone.getZoneName()}</h3>
      </td>
    </tr>

    <tbody FOREACH="zn.methods,sid,method">

      <tr>
        <td>

          <table cellpadding="2" cellspacing="0" width="100%">

            <script type="text/javascript" language="JavaScript 1.2">
              checkboxes{zn.zone.getZoneId()}_{method.method.getMethodId()} = new Array({foreach:method.markups,k,markup}{if:!k=0},{end:}'to_delete[{markup.getMarkupId()}]'{end:});
            </script> 

            <tr>
              <th><input type="checkbox" id="sm_{zn.zone.getZoneId()}_{method.method.getMethodId()}" name="sm_{zn.zone.getZoneId()}_{method.method.getMethodId()}" onclick="javascript:change_all(this.checked, checkboxes_form, checkboxes{zn.zone.getZoneId()}_{method.method.getMethodId()});" /></th>
              <th width="100%"><label for="sm_{zn.zone.getZoneId()}_{method.method.getMethodId()}">{method.method.getName()}</label></th>
            </tr>

          </table>

        </td>
      </tr>

      <tr>
        <td>

          <table cellpadding="0" cellspacing="3" width="100%">

            <tbody FOREACH="method.markups,mid,markup">

              <tr>
                <td rowspan="3" class="table-label"><img src="images/spacer.gif" width="10" height="1" alt="" /><input type="checkbox" name="to_delete[{markup.getMarkupId()}]" /></td>
                <td>Weight range:</td>
                <td class="table-label">
                  <input type="text" name="posted_data[{markup.getMarkupId()}][min_weight]" size="9" value="{markup.getMinWeight()}" />
                  -
                  <input type="text" name="posted_data[{markup.getMarkupId()}][max_weight]" size="9" value="{markup.getMaxWeight()}" />
                </td>
                <td>Flat markup ($):</td>
                <td class="table-label"><input type="text" name="posted_data[{markup.getMarkupId()}][markup_flat]" size="5" value="{markup.getMarkupFlat()}" /></td>
                <td>Percent markup:</td>
                <td><input type="text" name="posted_data[{markup.getMarkupId()}][markup_percent]" size="5" value="{markup.getMarkupPercent()}" /></td>
              </tr>

              <tr>
                <td>Subtotal range:</td>
                <td class="table-label">
                  <input type="text" name="posted_data[{markup.getMarkupId()}][min_total]" size="9" value="{markup.getMinTotal()}" />
                  -
                  <input type="text" name="posted_data[{markup.getMarkupId()}][max_total]" size="9" value="{markup.getMaxTotal()}" />
                </td>
                <td>Per item markup ($):</td>
                <td class="table-label"><input type="text" name="posted_data[{markup.getMarkupId()}][markup_per_item]" size="5" value="{markup.getMarkupPerItem()}" /></td>
                <td>Per weight unit markup ($):</td>
                <td class="table-label"><input type="text" name="posted_data[{markup.getMarkupId()}][markup_per_weight]" size="5" value="{markup.getMarkupPerWeight()}" /></td>
              </tr>

              <tr>
                <td>Items range:</td>
                <td class="table-label">
                  <input type="text" name="posted_data[{markup.getMarkupId()}][min_items]" size="9" value="{markup.getMinItems()}" />
                  -
                  <input type="text" name="posted_data[{markup.getMarkupId()}][max_items]" size="9" value="{markup.getMaxItems()}" />
                </td>
                <td colspan="4">&nbsp;</td>
              </tr>

              <tr IF="isShowMarkupsSeparator(mid,method.markups)">
                <td colspan="7" class="separator">&nbsp;</td>
              </tr>

            </tbody>  {* FOREACH="methods.markups,markup" *}

          </table>

        </td>
      </tr>

    </tbody> {* FOREACH="zn.methods,method" *}

    {end:}  {* foreach:getPreparedShippingMarkups(),zn *}

    <tr>
      <td>
        <widget class="\XLite\View\Button\Regular" label="Delete selected" jsCode="javascript: if (checkMarks(this.form, new RegExp('to_delete\\[[0-9]+\\]', 'gi'))) submitForm('shippingratesform', 'delete');" />
        &nbsp;&nbsp;&nbsp;&nbsp;
        <widget class="\XLite\View\Button\Submit" label="Update" />
      </td>
    </tr>

  </table>

</form>

</div>

<br /><br /><br />

<form name="addshippingrate" action="admin.php" method="post" IF="getShippingMethods()">

  <input type="hidden" name="target" value="shipping_rates" />
  <input type="hidden" name="action" value="add" />
  <input type="hidden" name="zoneid" value="{zoneid}" />
  <input type="hidden" name="methodid" value="{methodid}" />


  <a name="addmarkup"></a>

  <div class="admin-title">Add markup</div>

  <br /><br />

  <table class="data-table">

    <tr>
      <td>Shipping method:</td>
      <td>&nbsp;</td>
      <td>
        <select name="new[method_id]">
          <option value="">Please select one</option>
          <option FOREACH="getShippingMethods(),m" value="{m.getMethodId()}">{m.getName()}</option>
        </select>
      </td>
    </tr>

    <tr>
      <td>Destination zone:</td>
      <td>&nbsp;</td>
      <td>
        <select name="new[zone_id]">
          <option FOREACH="getShippingZones(),zn" value="{zn.getZoneId()}">{zn.getZoneName()}</option>
        </select>
      </td>
    </tr>

  </table>

  <table class="data-table">

    <tr>
      <td>Weight range:</td>
      <td class="table-label">
        <input type="text" name="new[min_weight]" size="9" value="0" />
        -
        <input type="text" name="new[max_weight]" size="9" value="9999999" />
      </td>
      <td>Flat markup ($):</td>
      <td class="table-label"><input type="text" name="new[markup_flat]" size="5" value="0" /></td>
      <td>Percent markup:</td>
      <td><input type="text" name="new[markup_percent]" size="5" value="0" /></td>
    </tr>

    <tr>
      <td>Subtotal range:</td>
      <td class="table-label">
        <input type="text" name="new[min_total]" size="9" value="0" />
        -
        <input type="text" name="new[max_total]" size="9" value="9999999" />
      </td>
      <td>Markup per item ($):</td>
      <td class="table-label"><input type="text" name="new[markup_per_item]" size="5" value="0" /></td>
      <td>Markup per weight unit ($):</td>
      <td class="table-label"><input type="text" name="new[markup_per_weight]" size="5" value="0" /></td>
    </tr>

    <tr>
      <td>Items range:</td>
      <td class="table-label">
        <input type="text" name="new[min_items]" size="9" value="0" />
        -
        <input type="text" name="new[max_items]" size="9" value="9999999" />
      </td>
      <td colspan="4">&nbsp;</td>
    </tr>

  </table>

  <br />
  <widget class="\XLite\View\Button\Submit" label="Add" />

</form>


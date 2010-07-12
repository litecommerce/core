{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<script language="Javascript">
<!-- 

var CheckBoxes = new Array();

function populateChecked(class_name, check_id)
{
	var CheckBoxesArray = CheckBoxes[class_name];
	CheckBoxesArray[CheckBoxesArray.length] = check_id;
}

function setChecked(class_name, check)
{
	var CheckBoxesArray = CheckBoxes[class_name];

	if (CheckBoxesArray) {
        for (var i = 0; i < CheckBoxesArray.length; i++) {
        	var Element = document.getElementById(CheckBoxesArray[i]);
            if (Element) {
            	Element.checked = check;
            }
        }
	}
}

function setHeaderChecked(class_name)
{
	var Element = document.getElementById("enable_method_" + class_name);
    if (Element && !Element.checked) {
    	Element.checked = true;
    }
}

// -->
</script>
Use this section to define your store's shipping methods.
<hr>
<p>
{foreach:xlite.factory.\XLite\Model\Shipping.getModules(),module}
<script language="Javascript">CheckBoxes["{module.class}"] = new Array();</script>
<table cellpadding="0" cellspacing="0" border="0">
<form action="admin.php" name="shipping_method_{module.class}" method="POST">
	<tr><td>&nbsp;</td></tr>
	<tr class="DialogBox">
		<td class="AdminHead" colspan=5>{module.getModuleName()}</td>
	</tr>
	<tr><td align="right">&nbsp;<widget module="UPSOnlineTools" template="modules/UPSOnlineTools/settings_link.tpl" IF="module.class=#ups#"/></td></tr>
	<tr>
		<td class="CenterBorder">
			<table cellspacing="1" cellpadding="2" border="0">
				<tr class="TableHead">
				    <th class="TableHead">Shipping method</th>
				    <th class="TableHead">Destination</th>
				    <th class="TableHead">Pos.</th>
				    <th class="TableHead">
    Active<br>
    	<input id="enable_method_{module.class}" type="checkbox" onClick="this.blur();setChecked('{module.class}',this.checked);">
				    </th>
				
				    <th valign="top">&nbsp;</th>
				</tr>
				<tr FOREACH="module.shippingMethods,shipping_idx,shipping" class="{getRowClass(shipping_idx,#DialogBox#,#TableRow#)}">
				    <td>{shipping.name:h}</td>
				    <td>{if:shipping.isSelected(#destination#,#L#)}National{else:}International{end:}</td>
					<td><input type=text name="order_by[{shipping.shipping_id}]" size=4 value="{shipping.order_by}"></td>
				    <td align=center>
				        <input id="shipping_enabled_{shipping.shipping_id}" type=checkbox name="enabled[{shipping.shipping_id}]" checked="{shipping.enabled}" onClick="this.blur();">
				        <script language="Javascript">populateChecked("{module.class}", "shipping_enabled_{shipping.shipping_id}");</script -->
				        <script language="Javascript" IF="shipping.enabled">setHeaderChecked("{module.class}");</script>
				    </td>
				    <td>
				        <input type="button" name="delete" value="Delete" onclick="document.shipping_method_{module.class}.shipping_id.value='{shipping.shipping_id}'; document.shipping_method_{module.class}.action.value='delete'; document.shipping_method_{module.class}.submit();">
				    </td>
				</tr>
                <widget module="UPSOnlineTools" template="modules/UPSOnlineTools/settings_disclaimer.tpl" IF="module.class=#ups#"/>
			</table>
		</td>
	</tr>

	<tr>
	    <td colspan=5>
        <br>
        <input type="hidden" name="shipping_id" value="">
        <input type="hidden" name="target" value="shipping_methods">
        <input type="hidden" name="action" value="update">
        <input type=submit value=" Update " class="DialogMainButton">
    </td>
</tr>
</form>
</table>

<form action="admin.php" method="POST">
<input type="hidden" name="target" value="shipping_methods">
<input type="hidden" name="action" value="add">
<input type="hidden" name="class" value="{module.class}">
<table cellpadding="0" cellspacing="0" border="0">
	<tr><td>&nbsp;</td></tr>
	<tr class="DialogBox">
		<td class="AdminTitle">Add shipping method</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td class="CenterBorder">
			<table cellspacing="1" cellpadding="2" border="0">
				<tr>
					<th class="TableHead">Shipping method</th>
					<th class="TableHead">Destination</th>
					<th class="TableHead">Pos.</th>
				</tr>
				<tr class="DialogBox">
				    <td>
				    	<input type=text name="name" size=32 value="{name}">
				    	<widget class="\XLite\Validator\ShippingMethodValidator" field="name">
				    </td>
				    <td>
				        <select name="destination">
				            <option value="I" selected="destination=#I#">International</option>
				            <option value="L" selected="destination=#L#">National</option>
				        </select>
				    </td>
				    <td><input type=text name="order_by" size=4 value="{order_by}"></td>
				</tr>
			</table>
		</td>
	</tr>

<tr>
    <td colspan=5><br><input type=submit value=" Add "></td>
</tr>
<tr IF="!moduleArrayPointer=moduleArraySize">
	<td colspan='5'><br><hr style="background-color: #E5EBEF; height: 2px; border: 0"><br></td>
</tr>
</form>
</table>
{end:}

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

function visibleBox(id, status)
{
	var Element = document.getElementById(id);
    if (Element) {
    	Element.style.display = ((status) ? "" : "none");
    }
}

function ShowNotes()
{
	visibleBox("notes_url", false);
    visibleBox("notes_body", true);
}

var CheckBoxes = new Array();

function setChecked(check)
{
    for (var i = 0; i < CheckBoxes.length; i++) {
    	var Element = document.getElementById(CheckBoxes[i]);
        if (Element) {
        	Element.checked = check;
        }
    }
}

function setHeaderChecked()
{
	var Element = document.getElementById("activate_payments");
    if (Element && !Element.checked) {
    	Element.checked = true;
    }
}

// -->
</script>

Use this section to define payment methods accepted by your store.
<span id="notes_url" style="display:"><a href="javascript:ShowNotes();" class="NavigationPath" onClick="this.blur()"><b>How to use this section &gt;&gt;&gt;</b></a></span>
<span id="notes_body" style="display: none">
<br><br>
Optional 'special instructions' field can be used to provide your customers with
instructions on using corresponding payment methods.<br>
</span>
<hr><br>

<widget module="AdvancedSecurity" template="modules/AdvancedSecurity/payment_methods_note.tpl">

<table cellpadding="0" cellspacing="0" border="0">
<form action="admin.php" method="POST" name="payment_methods">
<input type="hidden" name="target" value="payment_methods">
<input type="hidden" name="action" value="update">
	<tr>
		<td class="CenterBorder">
			<table cellspacing="1" cellpadding="2" border="0">
				<tr>
				    <th width="20%" class="TableHead">ID</th>
				    <th width="20%" class="TableHead">Method</th>
				    <th width="60%" class="TableHead">Special instructions</th>
				    <th width="5%" class="TableHead">Pos.</th>
				    <th class="TableHead">Active<br><input id="activate_payments" type="checkbox" onClick="this.blur();setChecked(this.checked);"></th>
					<th class="TableHead" IF="hasConfigurableMethods()">Configure</th>
				</tr>
				<tr FOREACH="xlite.factory.XLite_Model_PaymentMethod.readAll(),pm_idx,payment_method" class="{getRowClass(pm_idx,#DialogBox#,#TableRow#)}">
					<input type="hidden" name="data[{payment_method.payment_method}][payment_method]" value = "{payment_method.payment_method:r}">
					<td>
						{payment_method.payment_method}
					</td>
				    <td width="20%">
			    	    <input type="text" name="data[{payment_method.payment_method}][name]" value="{payment_method.name:r}" size="20%" maxlength="128">
				    </td>
			    	<td width="60%">
			        	<input type="text" name="data[{payment_method.payment_method}][details]" value="{payment_method.details:r}" size="60%" maxlength="255">
				    </td>
			    	<td width="5%">
			        	<input type="text" name="data[{payment_method.payment_method}][orderby]" value="{payment_method.orderby}" size="5%" maxlength="5">
				    </td>
			    	<td align="center">
			        	<input id="payment_{pm_idx}" type="checkbox" name="data[{payment_method.payment_method}][enabled]" checked="{payment_method.enabled}" onClick="this.blur()">
				        <script language="Javascript">CheckBoxes[CheckBoxes.length]="payment_{pm_idx}";</script>
			    	    <script language="Javascript" IF="payment_method.enabled">setHeaderChecked();</script>
				    </td>
					<td align="center" IF="hasConfigurableMethods()" nowrap>
					    <span  IF="payment_method.configurationTemplate">
						<input type="button" value="Configure" onClick="javascript: window.location='admin.php?target=payment_method&payment_method={payment_method.payment_method}'">
					    </span>
					    <span  IF="!payment_method.configurationTemplate">n/a</span>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
    	<td colspan="5">
        	<br>
	        <input type="submit" name="update" value="Update" class="DialogMainButton">
    	</td>
	</tr>
</form>
</table>

<hr>
<table cellpadding="3" border="0">
<form action="admin.php" method="POST" name="payment_methods">
<input type="hidden" name="target" value="payment_methods">
<input type="hidden" name="action" value="default_payment">
<tr>
    <td>Default payment method:&nbsp;</td>
    <td>
    <select name="default_select_payment">
    <option value="" selected="config.Payments.default_select_payment=##">-None-</option>
    {foreach:xlite.factory.XLite_Model_PaymentMethod.readAll(),payment_method}
    {if:payment_method.enabled&!payment_method.payment_method=#google_checkout#}
    <option value="{payment_method.payment_method}" selected="config.Payments.default_select_payment=payment_method.payment_method">{payment_method.name}</option>
    {end:}
    {end:}
    </select>
    </td>
</tr>
<tr>
    <td>Payment method to be assumed<br>in case of zero order totals:&nbsp;</td>
    <td>
    <select name="default_payment">
    <option value="" selected="config.Payments.default_offline_payment=##">-None-</option>
	{foreach:xlite.factory.XLite_Model_PaymentMethod.readAll(),payment_method}
	{if:payment_method.class=#offline#}
    <option value="{payment_method.payment_method}" selected="config.Payments.default_offline_payment=payment_method.payment_method">{payment_method.name}</option>
	{end:}
	{end:}
    </select>
    </td>
</tr>
<tr>
    <td colspan="2">
        <input type="submit" name="update" value="Update">
    </td>
</tr>
</form>
</table>

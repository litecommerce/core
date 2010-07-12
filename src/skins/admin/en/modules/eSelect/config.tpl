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
Use this page to configure your store to communicate with your Payment Processing Gateway.<br>
Complete the required fields below and press the "Update" button.<hr>

<p>
<span class="SuccessMessage" IF="updated">eSelect parameters were successfully changed. Please make sure that the eSelect payment method is enabled on the <a href="admin.php?target=payment_methods"><u>Payment methods</u></a> page before you can start using it.</span>
<form action="admin.php" method="POST">
<input type="hidden" name="target" value="payment_method">
<input type="hidden" name="action" value="update">
<input type="hidden" name="payment_method" value="{pm.get(#payment_method#)}">

<table border=0 cellspacing=2 cellpadding=1 width="100%">
	<tr>
		<td align="center"><img src="images/modules/eSelect/eselect_logo.gif" alt=""></td>
		<td width="20">&nbsp;</td>
		<td>

<table border=0 cellspacing=5 cellpadding=2>
<tr>
	<td colspan="3"><widget template="modules/eSelect/separator.tpl" caption="Account settings"></td>
</tr>
<tr>
	<td align="right">Store ID:</td>
	<td>&nbsp;</td>
	<td><input type=text name="params[store_id]" size=32 value="{pm.params.store_id}"></td>
</tr>

<tr>
	<td align="right">API Token:</td>
	<td>&nbsp;</td>
	<td><input type=text name="params[api_token]" size=32 value="{pm.params.api_token}"></td>
</tr>

<tr>
	<td align="right">Account type:</td>
	<td>&nbsp;</td>
	<td>
		<select name="params[account_type]">
			<option value="US" selected="{IsSelected(pm.params.account_type,#US#)}">US</option>
			<option value="CA" selected="{IsSelected(pm.params.account_type,#CA#)}">Canada</option>
		</select>
	</td>
</tr>

<tr>
	<td colspan="3"><widget template="modules/eSelect/separator.tpl" caption="Transaction settings"></td>
</tr>

<tr>
	<td align="right">Transaction type:</td>
	<td>&nbsp;</td>
	<td>
		<select name="params[trans_type]">
			<option value="auth" selected="{IsSelected(pm.params.trans_type,#auth#)}">Authorization</option>
			<option value="purch" selected="{IsSelected(pm.params.trans_type,#purch#)}">Purchase</option>
		</select>
	</td>
</tr>

<tr>
	<td align="right">Test/Live mode:</td>
	<td>&nbsp;</td>
	<td>
		<select name="params[testmode]">
			<option value=Y selected="{IsSelected(pm.params.testmode,#Y#)}">Test</option>
			<option value=N selected="{IsSelected(pm.params.testmode,#N#)}">Live</option>
		</select>
	</td>
</tr>

<tr>
	<td align="right">Order prefix:</td>
	<td>&nbsp;</td>
	<td><input type=text name="params[order_prefix]" size=32 value="{pm.params.order_prefix}"></td>
</tr>

<tr>
	<td align="right">Success order status:</td>
	<td>&nbsp;</td>
	<td><widget class="\XLite\View\StatusSelect" field="params[status_success]" value="{pm.orderSuccessStatus}"></td>
</tr>

<tr>
	<td align="right">Failed order status:</td>
	<td>&nbsp;</td>
	<td><widget class="\XLite\View\StatusSelect" field="params[status_fail]" value="{pm.orderFailStatus}"></td>
</tr>

<tr>
	<td colspan="3"><widget template="modules/eSelect/separator.tpl" caption="Authentication settings"></td>
</tr>

<tr>
	<td align="right">Apply 3DSecure  checks:</td>
	<td>&nbsp;</td>
	<td><input type="checkbox" name="params[vbv_check]" value="1" checked="pm.params.vbv_check"></td>
</tr>

<tr>
	<td align="right">Apply AVS/CVD checks:</td>
	<td>&nbsp;</td>
	<td><input type="checkbox" name="params[avs_cvd_check]" value="1" checked="pm.params.avs_cvd_check"></td>
</tr>

<tr>
	<td colspan="3" align="middle"><input type=submit value=" Update "></td>
</tr>
</table>

		</td>
	</tr>
</table>

</form>

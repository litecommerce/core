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

function HideActionStatus()
{
	visibleBox("action_status", false);
}

function HandleTestLiveMode(mode)
{
	visibleBox("test_mode_status", (mode != "Y") ? false : true);
	visibleBox("live_mode_status", (mode != "Y") ? true : false);
}

-->
</script>

<span IF="updated" id="action_status" style="display: ;">
<span class="SuccessMessage">GoogleCheckout parameters were successfully changed.</span><br>
<span IF="!pm.enabled"><b>Note:</b> Please make sure that the <b>GoogleCheckout payment method</b> is enabled on the <a href="admin.php?target=payment_methods"><u>Payment methods</u></a> page before you can start using it.</span>
<br><br>

<script language="Javascript">
<!--
setTimeout("HideActionStatus()", 10000);
-->
</script>
</span>

<style>
    .adminParagraph {
		text-align  : justify;
    }
</style>

<form action="admin.php" method="POST">
<input type="hidden" name="target" value="payment_method">
<input type="hidden" name="action" value="update">
<input type="hidden" name="payment_method" value="{pm.payment_method}">

<table width="100%" border=0 cellspacing="0" cellpadding="0">
    <tr>
        <td  align="right">
            <a href="http://checkout.google.com/sell?promo=sequaliteamsoftware" title="Sign up for Google Checkout seller account"><img src="skins/admin/en/images/go.gif" border=0 width=13 align=absmiddle alt="Sign up for Google Checkout seller account"></a>
            <a href="http://checkout.google.com/sell?promo=sequaliteamsoftware" title="Sign up for Google Checkout seller account"><b>Sign up for Google Checkout seller account</b></a>
        </td>
    </tr>
</table>

<table border=0 cellspacing=5 cellspacing=5>
<tr>
    <td align="center"><img src="images/modules/GoogleCheckout/google_checkout.gif" border="0"></td>   
    <td>
Use this page to configure your store to communicate with your Payment System Processing Gateway. <br>
Complete the required fields below and click the "Update" button.
<span id="notes_url" style="display:"><a href="javascript:ShowNotes();" class="NavigationPath" onClick="this.blur()"><b>Additional information &gt;&gt;&gt;</b></a></span>
<span id="notes_body" style="display: none">
<p class="adminParagraph">
<table border=0 cellpadding=5 cellspacing=0>
    <tr>
        <td>&nbsp;&nbsp;</td>
        <td class="TableHead">&nbsp;&nbsp;</td>
        <td>&nbsp;</td>
        <td>
You should obtain your Merchant ID and Merchant key values from your Google Checkout account. Choose 'Test mode' if you are going to use a Sandbox account. Choose 'Live mode' if you are going to use your production account.<br>
<br>
This URL should be used as an 'API callback URL' in your Google Checkout account:<br>
<b>{pm.callbackURL}</b>
<br>
(Log in to your Merchant Center account, click on the 'Settings' tab, then click on the 'Integration' link in the menu on the left side of the page. Enter this URL into the field 'API callback URL')<br>
<br>
<table width=100%>
<tr>
<td><b>Note:</b> Make sure this callback URL<br>is secured by <a href="http://code.google.com/apis/checkout/developer/index.html#security_precautions"><u>HTTP Basic Authentication</u></a>.</td>
<td>&nbsp;</td>
<td><input type="button" name="test_callback_url" value="Test Callback URL" onclick="javascript: self.location='admin.php?target={target}&payment_method={payment_method}&mode=test_callback'" /></td>
</tr>
<tr IF="mode=#test_callback#">
<td colspan=3>
<script language="Javascript" IF="mode=#test_callback#">
<!--
ShowNotes();
-->
</script>
Trying to access the callback URL ...
{if:checkCallbackConnection(pm)}
<span class="SuccessMessage">SUCCESS</span>
{else:}
<span class="ErrorMessage">FAILED</span> <i>(Connection cannot be established)</i>
{end:}
</td>
</tr>
</table>
<br>
Please note that, in Live mode, Google Checkout only communicates with servers that have SSL certificates installed. Make sure your server has a valid SSL certificate, otherwise the module will not be able to function correctly, as your store will not be able to receive any messages or notifications from Google Checkout.<br>
<br>
In Test mode, an http connection can be used.<br>
<br>
Please be aware that Google Checkout cannot be used for certain kinds of products (see <a href="http://checkout.google.com/seller/content_policies.html"><u>Google Checkout: Content policies</u></a> for details). If your store sells products that do not comply with Google Checkout content policies, you should disable Google Checkout for these products by deselecting the check box 'Enable Google Checkout for this product' on their details pages.<br>
<br>
Visit <a href="http://code.google.com/apis/checkout/"><u>this page</u></a> to learn more about Google Checkout API. 
        </td>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
    </tr>
</table>
</p>
</span>
    </td>
</tr>
</table>

<br>

<table cellSpacing=2 cellpadding=2 border=0 width="100%">
<tr>
    <td colspan=3>
	<table cellspacing=0 cellpadding=0 border=0 width="100%">
	<tr>
		<td class="SidebarTitle" align=center nowrap>&nbsp;&nbsp;&nbsp;Account options&nbsp;&nbsp;&nbsp;</td>
		<td width=100%>&nbsp;</td>
	</tr>
	<tr>
		<td class="SidebarTitle" align=center colspan=2 height=3></td>
	</tr>
	</table>
    </td>
</tr>
<tr>
    <td nowrap align="right" width="50%">Merchant ID:</td>
    <td>&nbsp;</td>
    <td width="50%"><input type=text name="params[merchant_id]" size=24 value="{pm.params.merchant_id:r}"></td>
</tr>
<tr>
    <td nowrap align="right">Merchant key:</td>
    <td>&nbsp;</td>
    <td><input type=text name="params[merchant_key]" size=24 value="{pm.params.merchant_key:r}"></td>
</tr>
<tr>
	<td nowrap align="right" valign="top">Test/Live mode:</td>
    <td>&nbsp;</td>
	<td>
		<select name=params[testmode] onChange="HandleTestLiveMode(this.value)">
			<option value="Y" selected="{IsSelected(pm.params.testmode,#Y#)}">test</option>
			<option value="N" selected="{IsSelected(pm.params.testmode,#N#)}">live</option>
		</select>
		<span id="test_mode_status" style="display:none;">
		<br><input type="checkbox" name="params[secure_testmode]" checked="pm.params.secure_testmode" onClick="this.blur()"> Use non-secure HTTP protocol.
		</span>
		<span id="live_mode_status" style="display:none;">
		<br><b>Note:</b> Please make sure LIVE mode is set up in your merchant account settings.
		</span>
        <script language="Javascript">
        HandleTestLiveMode('{pm.params.testmode}');
        </script>
	</td>
</tr>
<tr>
	<td nowrap align="right" valign="top">Seller account currency:</td>
    <td>&nbsp;</td>
	<td>
		<select name=params[currency]>
			<option value="USD" selected="{IsSelected(pm.params.currency,#USD#)}">USD</option>
			<option value="GBP" selected="{IsSelected(pm.params.currency,#GBP#)}">GBP</option>
		</select>
	</td>
</tr>

<tr>
    <td colspan=3>
	<table cellspacing=0 cellpadding=0 border=0 width="100%">
	<tr>
		<td colspan=2>&nbsp;</td>
	</tr>
	<tr>
		<td class="SidebarTitle" align=center nowrap>&nbsp;&nbsp;&nbsp;Order specific options&nbsp;&nbsp;&nbsp;</td>
		<td width=100%>&nbsp;</td>
	</tr>
	<tr>
		<td class="SidebarTitle" align=center colspan=2 height=3></td>
	</tr>
	</table>
    </td>
</tr>
<tr>
	<td nowrap align="right">Order prefix:</td>
    <td>&nbsp;</td>
	<td><input type=text name="params[order_prefix]" size=24 value="{pm.params.order_prefix:h}"></td>
</tr>
<tr>
	<td nowrap align="right">'CHARGEABLE' order status:</td>
    <td>&nbsp;</td>
	<td><widget class="\XLite\View\StatusSelect" field="params[status_chargeable]" value="{pm.chargeableStatus}"></td>
</tr>
<tr>
	<td nowrap align="right">'CHARGED' order status:</td>
    <td>&nbsp;</td>
	<td><widget class="\XLite\View\StatusSelect" field="params[status_charged]" value="{pm.chargedStatus}"></td>
</tr>
<tr>
	<td nowrap align="right">Declined/Canceled/Failed order status:</td>
    <td>&nbsp;</td>
	<td><widget class="\XLite\View\StatusSelect" field="params[status_failed]" value="{pm.failedStatus}"></td>
</tr>

<tr>
    <td colspan=3>
	<table cellspacing=0 cellpadding=0 border=0 width="100%">
	<tr>
		<td colspan=2>&nbsp;</td>
	</tr>
	<tr>
		<td class="SidebarTitle" align=center nowrap>&nbsp;&nbsp;&nbsp;Conditions on which orders will be charged&nbsp;&nbsp;&nbsp;</td>
		<td width=100%>&nbsp;</td>
	</tr>
	<tr>
		<td class="SidebarTitle" align=center colspan=2 height=3></td>
	</tr>
	</table>
    </td>
</tr>
<tr>
	<td nowrap align="right">Charge order if the result of AVS check-up is:</td>
    <td>&nbsp;</td>
	<td>
    <select name="params[check_avs][]" multiple="multiple" size="5">
    	<option value="Y" selected="{pm.isCheckAvs(#Y#)}">Full AVS match (address and postal code)</option>
    	<option value="P" selected="{pm.isCheckAvs(#P#)}">Partial AVS match (postal code only)</option>
    	<option value="A" selected="{pm.isCheckAvs(#A#)}">Partial AVS match (address only)</option>
    	<option value="N" selected="{pm.isCheckAvs(#N#)}">No AVS match</option>
    	<option value="U" selected="{pm.isCheckAvs(#U#)}">AVS not supported by issuer</option>
    </select>
	</td>
</tr>
<tr>
	<td nowrap align="right">Charge order if the result of CVN check-up is:</td>
    <td>&nbsp;</td>
	<td>
    <select name="params[check_cvn][]" multiple="multiple" size="4">
    	<option value="M" selected="{pm.isCheckCvn(#M#)}">CVN match</option>
    	<option value="N" selected="{pm.isCheckCvn(#N#)}">No CVN match</option>
    	<option value="U" selected="{pm.isCheckCvn(#U#)}">CVN not available</option>
    	<option value="E" selected="{pm.isCheckCvn(#E#)}">CVN error</option>
    </select>
	</td>
</tr>
<tr>
	<td nowrap align="right">Charge order only if it is eligible for Google Checkout's<br>payment guarantee policy:</td>
    <td>&nbsp;</td>
	<td><input type="checkbox" name="params[check_prot]" checked="pm.params.check_prot" onClick="this.blur()"></td>
</tr>

<tr>
    <td colspan=3>
	<table cellspacing=0 cellpadding=0 border=0 width="100%">
	<tr>
		<td colspan=2>&nbsp;</td>
	</tr>
	<tr>
		<td class="SidebarTitle" align=center nowrap>&nbsp;&nbsp;&nbsp;Other options&nbsp;&nbsp;&nbsp;</td>
		<td width=100%>&nbsp;</td>
	</tr>
	<tr>
		<td class="SidebarTitle" align=center colspan=2 height=3></td>
	</tr>
	</table>
    </td>
</tr>
<tr>
	<td nowrap align="right">Cancel order if merchant calculations fail:</td>
    <td>&nbsp;</td>
	<td><input type="checkbox" name="params[merchant_calc]" checked="pm.params.merchant_calc" onClick="this.blur()"></td>
</tr>
<tr>
	<td nowrap align="right">Send order notification emails from:</td>
    <td>&nbsp;</td>
	<td>
		<select name=params[disable_customer_notif]>
			<option value="1" selected="{checkDisableCustomerNotif(pm.params.disable_customer_notif)}">Google Checkout only</option>
			<option value="0" selected="{!checkDisableCustomerNotif(pm.params.disable_customer_notif)}">Google Checkout and LC store</option>
		</select>
	</td>
</tr>
<tr>
	<td align="right">Default shipping cost (will be used if Google Checkout does not receive a correct XML response from your store):</td>
	<td>&nbsp;</td>
	<td><input type="text" name="params[default_shipping_cost]" value="{pm.params.default_shipping_cost}"></td>
</tr>
<tr>
	<td nowrap align="right">Display a note about products not available<br>through Google Checkout in the cart:</td>
    <td>&nbsp;</td>
	<td><input type="checkbox" name="params[display_product_note]" value="1" checked="pm.params.display_product_note" onClick="this.blur()"></td>
</tr>
<tr>
	<td nowrap align="right">Remove discounts and gift certificates from order:</td>
	<td>&nbsp;</td>
	<td><input type="checkbox" name="params[remove_discounts]" value="1" checked="pm.params.remove_discounts" onClick="this.blur()"></td>
</tr>

</table>
<p>
<center>
<input type=submit value=" Update ">
</center>
</form>

<br>
<div IF="pm.onlineShippingsActive">
<b>Note:</b> Using real-time shipping rates calculation in LiteCommerce increases the risk of LC's not being able to provide a merchant-calculation-results response to Google's merchant-calculation-callback within the allowed period of three seconds. If Google does not receive a response within three seconds, it will use the default shipping cost it received in the Checkout API request. If this represents a problem, disable real-time shipping rates calculation at your store.
</font>
</div>

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
<script>
	var hwnd_Window; 
	function Window() 
	{
		if (hwnd_Window) hwnd_Window.close(); 
		hwnd_Window = window.open('admin.php?target=fraud&mode=track&order_id={order.order_id}','Track','width=600,height=400,scrollbars=yes');  
	}

	function visibleBox(id, skipOpenClose)
    {
        elm1 = document.getElementById("open" + id);
        elm2 = document.getElementById("close" + id);
        if (elm1.style.display == "") {
            elm1.style.display = "none";
            elm2.style.display = "";
        } else {
            elm1.style.display = "";
            elm2.style.display = "none";
        }
    }
</script>
<table IF="order.details.af_result" cellspacing="0" cellpadding="5" border="0">
<tr>
    <td>Fraud Risk Factor: </td>
	<td align="right"><b>{if:compare(order.totalTrustScore,config.CDev.AntiFraud.antifraud_risk_factor)}<font class="Star">{else:}<font class="SuccessMessage">{end:}{order.totalTrustScore}</font></b></td>
</tr>
</table>
<table IF="order.details.af_result" cellspacing="5" cellpadding="0" border="0">
<tbody id="close1" style="cursor: hand;" onClick="javascript: visibleBox(1);">
<tr>
    <td colspan="3"><input type="image" src="images/go.gif" width="13" height="13" border="0" align="absmiddle"/> See details</td>
</tr>
</tbody>
<tbody id="open1" style="display: none; cursor: hand;" onClick="javascript: visibleBox(1);">
<tr>
	<td colspan="3"><input type="image" src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Fraud risk details</td>
</tr>
<tr class="TableRow">
	<td> Order total: </td>
    <td align="right"><b>{if:compare(order.total,config.CDev.AntiFraud.antifraud_order_total)}<font class="Star">{else:}<font class="SuccessMessage">{end:}{price_format(order.total)}</font></b></td>
	<td>{if:compare(order.total,config.CDev.AntiFraud.antifraud_order_total)} * Order total exceeded order total threshold{else:}&nbsp;{end:}</td>
</tr>
<tr>
	<td> Billing country: </td>
	<td align="right" nowrap><b>{if:country.riskCountry}<font class="Star">{else:}<font class="SuccessMessage">{end:}{country.country:h}</font></b></td>
    <td>
    {if:country.riskCountry=#0#}&nbsp;{end:}
    {if:country.isRiskCountry(#1#)} * Country is in the list of risk countries<br>{end:}
    {if:country.isRiskCountry(#2#)} * Differs from the country of the customer's IP address<br>{end:}
    {if:country.isRiskCountry(#4#)} * The distance is greater than the safe distance<br>{end:}
    </td>
</tr>
<tr class="TableRow">
    <td> Customer IP: </td>
    <td align="right"><b>{if:order.details.af_data.check_error=#IP_NOT_FOUND#}<font class="Star">{else:}<font class="SuccessMessage">{end:}{if:order.address}{order.address}{else:}N/A{end:}</font></b></td>
    <td>&nbsp;</td>
</tr>
<tr IF="{order.details.processed_orders}">
    <td nowrap>Previous successful orders: </td>
    <td align="right"><b><font class="SuccessMessage">{order.details.processed_orders:h}</font></b></td>
	<td>&nbsp;</td>
</tr>
<tr class="TableRow" IF="{order.details.declined_orders}">
    <td nowrap>Previous declined orders: </td>
    <td align="right"><b><font class="Star">{order.details.declined_orders:h}</font></b></td>
	<td>{if:order.details.declined_orders} * This customer had previous declined orders{else:}&nbsp;{end:}</td>
</tr>
<tr IF="order.details.af_result.error">
    <td> Error: </td>
    <td align="right"> {order.details.af_result.error}</td>
    <td>&nbsp;</td>
</tr>   
<tr>
    <td colspan="3"><hr></td>
</tr>
<tr IF="order.details.af_data">
    <td colspan="3" class="DialogTitleComment">Additional Antifraud Service information :</td>
</tr>   
<span IF="order.details.af_data">
<tr FOREACH="order.details.af_data,key,value">
    <td>{key}:</td>
	<td>&nbsp;</td>
	<td IF="!isAFServiceValue(value)">
	{value}
    <span IF="key=#CHECK_IP_COUNTRY#&country.isRiskCountry(#2#)"><font class="Star">(*)</font></span>
    <span IF="key=#CHECK_IP_DISTANCE#&country.isRiskCountry(#4#)"><font class="Star">(*)</font></span>
	</td>
	<td IF="isAFServiceValue(value)">
    <span IF="value=#IP_NOT_FOUND#">IP address has not been found</span>
    <span IF="value=#POSTAL_CODE_NOT_FOUND#">Zipcode is missing in request</span>
    <span IF="value=#COUNTRY_NOT_FOUND#">Country is missing in request</span>
    <span IF="value=#CITY_NOT_FOUND#">City is missing in request</span>
    <span IF="value=#IP_REQUIRED#">Please fill in IP address</span>
    <span IF="value=#DOMAIN_REQUIRED#">Domain name is invalid</span>
    <span IF="value=#EMPTY_SERVICE_KEY#">AntiFraud Service License key is empty</span>
    <span IF="value=#NOT_AVAILABLE_SERVICE#">Service is not available</span>
    <span IF="value=#NO_ACTIVE_LICENSES#">You don't have active licenses</span>
    <span IF="value=#NOT_ALLOWED_SHOP_IP#">The shop has unallowed IP address</span>
    </td>
</tr>
</span>	
<tr>
    <td colspan="3"><hr></td>
</tr>
</tbody>
</table>
<table IF="order.details.af_result" cellspacing="5" cellpadding="0" border="0">
<tr>
    <td colspan="3"><a href="javascript: Window();"><input type="image" src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Track IP address </a></td>
</tr>
</table>
<form action="admin.php" method="POST" name="send_form">
<input type="hidden" name="target" value="order">
<input type="hidden" name="action" value="fraud_notify">
<input type="hidden" name="order_id" value="{order.order_id}">
<table IF="order.details.af_result" cellspacing="5" cellpadding="0" border="0">
<tbody id="close2" style="cursor: hand;" onClick="javascript: visibleBox(2);">
<tr>
    <td><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Notify Antifraud Service about fraudulent IP</td>
</tr>
</tbody>
<tbody id="open2" style="display: none; cursor: hand;">	
<tr>
    <td onClick="javascript: visibleBox(2)"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Notify Antifraud Service about fraudulent IP</td>
</tr>
<tr>
    <td><p align="justify">Send the IP address from which this order originated to the Antifraud Service server to inform that this IP was used by a fraudster. This will help to avoid further fraudulent orders coming from this IP.Please describe the reason why you report this IP as a source of fraudulent orders:</td>
</tr>
<tr>
    <td><textarea name="fraud_comment" cols="45" rows="6"></textarea></td>
</tr>
<tr>
    <td align="center"><input type="submit" value="Send"></td>
</tr>
</tbody>
</table>
</form>

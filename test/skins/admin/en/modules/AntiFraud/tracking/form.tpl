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
<form action="admin.php" method="GET" name="fraud_check">
<input type="hidden" name="target" value="fraud">
<input type="hidden" name="mode" value="track">
<input type="hidden" name="distance" value="0">
<input type="hidden" name="order_id" value="{order_id}">
<table width="100%" cellpadding="0" cellspacing="4" border="0">
	<tr>
		<td>IP:</td>
		<td align="right"><input  size="22" type="text" name="ip" value="{ip:h}"></td>
		<td></td>
		<td></td>
	</tr>
	<tr>
        <td>IP City:</td>
        <td>{if:response.data.city}{response.data.city}{else:}n/a{end:}</td>
		<td>City:</td>
		<td align="right"><input  size="22" type="text" name="city" value="{order.profile.billing_city:h}"></td>
	</tr>	
    <tr>
        <td>IP State:</td>
        <td>{if:response.data.state}{response.data.state}{else:}n/a{end:}</td>
        <td>State:</td>
        <td align="right"><widget class="XLite_View_StateSelect" field="state" value="{order.profile.billing_state:h}"></td>
    </tr>   	
    <tr>
        <td>IP Country:</td>
        <td>{if:response.data.country}{response.data.country}{else:}n/a{end:}</td>
        <td>Country:</td>
        <td align="right"><widget class="XLite_View_CountrySelect" field="country" value="{order.profile.billing_country:h}"></td>
    </tr>   
    <tr>
        <td>IP Zipcode:</td>
        <td>{if:response.data.zipcode}{response.data.zipcode}{else:}n/a{end:}</td>
        <td>Zipcode:</td>
        <td align="right"><input size="22" type="text" name="zipcode" value="{order.profile.billing_zipcode:h}"></td>
    </tr>   
	<tr>
		<td></td>
		<td></td>
		<td>{if:response.data.distance}Distance: {else:}&nbsp;{end:}</td>
        <td>{if:response.data.distance}{response.data.distance} km.{else:}&nbsp;{end:}</td>
	</tr>
    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>
	<tr>
		<td colspan="2" align="center"><input type="button" value="Lookup IP" onClick="javascript: document.fraud_check.distance.value = '0'; document.fraud_check.submit();"></td>
		<td colspan="2" align="center"><input type="button" value="Measure distance" onClick="javascript:  document.fraud_check.distance.value = '1'; document.fraud_check.submit();"></td>
	</tr>
</table>

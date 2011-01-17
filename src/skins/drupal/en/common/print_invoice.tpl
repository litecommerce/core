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
<HTML>
<HEAD>
<title>{config.Company.company_name:h}: INVOICE</title>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<STYLE type="text/css">

BODY,P,DIV,TH,TD,P,INPUT,SELECT,TEXTAREA {
        FONT-FAMILY: Verdana, Arial, Helvetica, Sans-serif;
        COLOR: #000000; FONT-SIZE: 12px;
}
BODY {
        MARGIN-TOP: 5 px; MARGIN-BOTTOM: 5 px; MARGIN-LEFT: 5 px; MARGIN-RIGHT: 5 px;
        BACKGROUND-COLOR: #FFFFFF;
}
A:link {
        COLOR: #000000; TEXT-DECORATION: none;
}
A:visited {
        COLOR: #000000; TEXT-DECORATION: none;
}
A:hover {
        COLOR: #000000; TEXT-DECORATION: underline;
}
A:active  {
        COLOR: #000000; TEXT-DECORATION: none;
}
</STYLE>

</HEAD>
<BODY LEFTMARGIN="5" TOPMARGIN="5" RIGHTMARGIN="5" BOTTOMMARGIN="5" MARGINWIDTH="5" MARGINHEIGHT="5" onLoad="window.print()">

<table border="0" cellpadding="3" cellspacing="0" width="600">
<tr>
<td valign="top" width="300">
<FONT color="#B2B2B3" style="font-size: 30px;"><b>INVOICE</b></FONT><BR>
<table border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td nowrap><b>Order id</b></td>
		<td>#{order.order_id:h}</td>
	</tr>
	<tr>
		<td nowrap><b>Order date</b></td>
		<td>{formatTime(order.date)}</td>
	</tr>

	<tr>
		<td nowrap><b>Order Status<b></td>
		<td><widget template="common/order_status.tpl"></td>
	</tr>
	<tr>
		<td nowrap><b>E-mail</b></td>
		<td>{order.profile.login:h}</td>
	</tr>
</table>

</td>

<td>
<table border="0" cellpadding="3" cellspacing="0">
<tr>
	<td nowrap colspan="2">
	<FONT style="font-size: 14px;"><b>{config.Company.company_name}</b></FONT>
	<br><br></td>
</tr>
<tr>
	<td valign="top">Address</td>
	<td>{config.Company.location_address} <br>
	{config.Company.location_city} {config.Company.locationState.state} {config.Company.location_zipcode} <br>
	{config.Company.location_country}
	</td>
</tr>
<tr>
	<td nowrap>Web Site</td>
	<td>{config.Company.company_website}</td>
</tr>
<tr>
	<td>Phone</td>
	<td>{config.Company.company_phone}</td>
</tr>
<tr>
	<td>Fax</td>
	<td>{config.Company.company_fax}</td>
</tr>
</table>
</td>

</tr>
</table>
<br>
<table border="0" cellpadding="3" cellspacing="0">
<tr>
<td>
<table border="0" cellpadding="3" cellspacing="0" width="300">
	<tr>
		<td  colspan="2" nowrap><b>Billing Info</b></td>
	</tr>
    <tr><td colspan="2"><hr width="80%" align=left></td></tr>
	<tr>
		<td nowrap>Name</td>
		<td>{order.profile.billing_address.title} {order.profile.billing_address.firstname:h} {order.profile.billing_address.lastname:h}</td>
	</tr>
	<tr>
		<td nowrap>Phone</td>
		<td>{order.profile.billing_address.phone:h}</td>
	</tr>
	<tr>
		<td nowrap>Address</td>
		<td>{order.profile.billing_address.street:h}</td>
	</tr>
	<tr>
		<td nowrap>City</td>
		<td>{order.profile.billing_address.city:h}</td>
	</tr>
	<tr>
		<td nowrap>State</td>
		<td>{order.profile.billing_address.state.state:h}</td>
	</tr>
	<tr>
		<td nowrap>Country</td>
		<td>{order.profile.billing_address.country.country:h}</td>
	</tr>
	<tr>
		<td nowrap>Zip code</td>
		<td>{order.profile.billing_address.zipcode:h}</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
</table>
</td>
<td>
<table border="0" cellpadding="3" cellspacing="0" width="300">
	<tr>
		<td  colspan="2" nowrap><b>Shipping Info</b></td>
	</tr>
    <tr><td colspan="2"><hr width="80%" align=left></td></tr>
	<tr>
		<td nowrap>Name</td>
		<td>{order.profile.shipping_address.title} {order.profile.shipping_address.firstname:h} {order.profile.shipping_address.lastname:h}</td>
	</tr>
	<tr>
		<td nowrap>Phone</td>
		<td>{order.profile.shipping_address.phone:h}</td>
	</tr>
	<tr>
		<td nowrap>Address</td>
		<td>{order.profile.shipping_address.street:h}</td>
	</tr>
	<tr>
		<td nowrap>City</td>
		<td>{order.profile.shipping_address.city:h}</td>
	</tr>
	<tr>
		<td nowrap>State</td>
		<td>{order.profile.shipping_address.state.state:h}</td>
	</tr>
	<tr>
		<td nowrap>Country</td>
		<td>{order.profile.shipping_address.country.country:h}</td>
	</tr>
	<tr>
		<td nowrap>Zip code</td>
		<td>{order.profile.shipping_address.zipcode:h}</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
</table>
</td>
</tr>
</table>
<table border="0" cellpadding="3" cellspacing="0">
<tr>
	<td><b>Payment method</b></td>
	<td>{order.paymentMethod.name:h}</td>
</tr>
<tr>
	<td><b>Delivery</b></td>
	<td>{if:order.shippingMethod}{order.shippingMethod.name:h}{else:}N/A{end:}</td>
</tr>
</table>
<br>
<br>
<table border="1" cellpadding="3" cellspacing="0" width="600">
	<tr>
		<td nowrap colspan=5 align="center"><b>Products Ordered</b></td>
	</tr>
		<tr>
			<td nowrap align="center"><b>Description</b></td>
			<td nowrap align="center"><b>SKU</b></td>
			<td nowrap align="center"><b>QTY</b></td>
			<td nowrap align="center"><b>Item Price</b></td>
		        <td nowrap align="center"><b>Total Price</b></td>
		</tr>
	<tbody FOREACH="order.items,item">
		<tr>
			<td><b>{item.name:h}</b><BR>
				<table>
<widget module="CDev\Egoods" template="modules/CDev/Egoods/invoice.tpl"></table></td>
			<td align="center">{item.sku:h}&nbsp;</td>
			<td align="center">{item.amount:h}</td>
			<td align="right">{price_format(item,#price#):h}</td>
 	                <td align="right">{price_format(item,#total#):h}</td>
 		</tr>
	</tbody>
		<tr>
			<td nowrap colspan="4" align="right">
			<widget module="CDev\WholesaleTrading" template="modules/CDev/WholesaleTrading/print_invoice_discount_label.tpl" ignoreErrors>
			<b>Subtotal</b><br>
            <widget module="CDev\Promotion" template="modules/CDev/Promotion/print_invoice_discount_label.tpl">
            <b>Shipping cost</b><br>
            <span FOREACH="order.getDisplayTaxes(),tax_name,tax">
    		<b>{order.getTaxLabel(tax_name)}</b><br></span>
            <widget module="CDev\Promotion" template="modules/CDev/Promotion/print_invoice_label.tpl">
            <widget module="CDev\GiftCertificates" template="modules/CDev/GiftCertificates/print_invoice_label.tpl">
			<b>TOTAL</b>

			</td>
			<td align="right">
			<widget module="CDev\WholesaleTrading" template="modules/CDev/WholesaleTrading/print_invoice_discount.tpl" ignoreErrors>
			{price_format(order,#subtotal#):h}<br>
            <widget module="CDev\Promotion" template="modules/CDev/Promotion/print_invoice_discount.tpl">
			{price_format(order,#shipping_cost#):h}<br>
		        <span FOREACH="order.getDisplayTaxes(),tax_name,tax">
			{price_format(tax):h}<br></span>
            <widget module="CDev\Promotion" template="modules/CDev/Promotion/print_invoice_total.tpl">
            <widget module="CDev\GiftCertificates" template="modules/CDev/GiftCertificates/print_invoice.tpl">
			<b>{price_format(order,#total#):h}</b>

			</td>
		</tr>
	</table>
<FONT style="font-size: 10px;">If you have any questions about this invoice, please contact us at the following address {config.Company.orders_department:h}</FONT>
<br><br>
<table border="0" cellpadding="0" cellspacing="0" width="600">
<tr>
<td align="center">
<FONT style="font-size: 12px;"><b>Thank you for your business.</b></FONT>
</td>
</tr>
</table>
<br><br>
</BODY>
</HTML>

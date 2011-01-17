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
{if:mode=#invoice#}
{if:mailMode=##}
<html>
<head>
    <title>LiteCommerce. Powerful PHP shopping cart software</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta name="ROBOTS" content="NOINDEX">
    <meta name="ROBOTS" content="NOFOLLOW">
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
    <LINK href="skins/admin/en/style.css"  rel=stylesheet type=text/css>
</head>
<body LEFTMARGIN=0 TOPMARGIN=0 RIGHTMARGIN=0 BOTTOMMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0 onLoad="window.print()">
{end:}
{end:}
<table width="90%" border="0" cellpadding="0" cellspacing="0" align="center">
	<tr valign="top">
		<td IF="!mode=#invoice#" align="left" class="OrderTitle" style="font-size: 20px">Order #{order.order_id:h}</td>
		<td IF="mode=#invoice#"><img src="images/logo.gif"></td>
		<td width="33%">
			<table>
				<tr IF="mode=#invoice#">
					<td colspan="3" align="left" class="OrderTitle" style="font-size: 20px">INVOICE #{order.order_id}</td>
				</tr>
				<tr>
			        <td nowrap class="ProductDetailsTitle">Date:</td>
					<td>&nbsp;</td>	
			        <td>{formatTime(order.date)}</td>
				</tr>
			    <tr>
			        <td nowrap class="ProductDetailsTitle">Status:</td>
					<td>&nbsp;</td> 
			        <td>{if:order.orderStatus.name}{order.orderStatus.name:h}{else:}N/A{end:}</td>
			    </tr>
                <tr>
                    <td nowrap class="ProductDetailsTitle">E-mail:</td>
                    <td>&nbsp;</td>
                    <td>{if:order.profile.login}{order.profile.login:h}{else:}N/A{end:}</td>
                </tr>
			</table>
		</td>
		<td width="34%">
            <table>
                <tr>
                    <td nowrap class="ProductDetailsTitle">Payment method:</td>
                    <td>&nbsp;</td> 
                    <td>{if:order.paymentMethod}{order.paymentMethod.name:h}{else:}N/A{end:}</td>
                </tr>
                <tr> 
                    <td nowrap class="ProductDetailsTitle">Delivery:</td>
                    <td>&nbsp;</td> 
                    <td nowrap>
                    {if:order.shippingMethod}
                        {order.shippingMethod.name:h}
                    {else:}
                        {if:order.shipping_id=#0#}
                            Free
                        {else:}
                            N/A
                        {end:}
                    {end:}
                    </td>
                </tr>
            </table>
		</td>
	</tr>
	<tr>
		<td colspan="3"><hr style="background-color: #516176; height: 2px; border: 0"></td>
	</tr>
</table>
<table width="90%" border="0" cellpadding="0" cellspacing="0" align="center">
    <tr valign="top">
		<td>
			<table>
				<tr IF="config.Company.company_name">
					<td class="ProductDetailsTitle">Company:</td>
                	<td>{config.Company.company_name:h}</td>
				</tr>
                <tr>
                    <td class="ProductDetailsTitle">Address:</td>
                    <td>{config.Company.location_address:h}, {config.Company.location_city:h}</td>
                </tr>
                <tr>
                    <td class="ProductDetailsTitle">&nbsp;</td>
                    <td>{order.locationState.state:h}, {config.Company.location_zipcode:h}, {order.locationCountry.country:h}</td>
                </tr>
                <tr IF="config.Company.company_phone">
                    <td class="ProductDetailsTitle">Phone:</td>
                    <td>{config.Company.company_phone:h}</td>
                </tr>
                <tr IF="config.Company.company_fax">
                    <td class="ProductDetailsTitle">Fax:</td>
                    <td>{config.Company.company_fax:h}</td>
                </tr>
                <tr IF="config.Company.company_fax">
                    <td class="ProductDetailsTitle">E-mail:</td>
                    <td>{config.Company.orders_department:h}</td>
                </tr>
                <tr IF="config.Company.company_website">
                    <td class="ProductDetailsTitle">Website:</td>
                    <td>{config.Company.company_website:r}</td>
                </tr>
			</table>
		</td>	
	</tr>
</table>	
<table width="90%" border="0" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td colspan="3">&nbsp;</td>
	</tr>
    <tr>
		<td class="OrderTitle" style="font-size: 14px">Billing info</td>
		<td>&nbsp;</td>
		<td class="OrderTitle" style="font-size: 14px">Shipping info</td>
    </tr>	
    <tr>
        <td><hr style="background-color: #516176; height: 2px; border: 0"></td>
		<td>&nbsp;</td>
        <td><hr style="background-color: #516176; height: 2px; border: 0"></td>
    </tr>
    <tr>
		<td>
			<table>
				<tr valign="top">
					<td nowrap class="ProductDetailsTitle">Name:</td>
			        <td>{if:order.profile.billing_firstname}{order.profile.billing_title} {order.profile.billing_firstname:h} {order.profile.billing_lastname:h}{else:}N/A{end:}</td>
			    </tr>
			    <tr valign="top">
			        <td nowrap class="ProductDetailsTitle">Phone:</td>
			        <td>{if:order.profile.billing_phone}{order.profile.billing_phone:h}{else:}N/A{end:}</td>
			    </tr>
			    <tr valign="top">
			        <td nowrap class="ProductDetailsTitle">Fax:</td>
					<td>{if:order.profile.billing_fax}{order.profile.billing_fax:h}{else:}N/A{end:}</td>
			    </tr>
			    <tr valign="top">
					<td nowrap class="ProductDetailsTitle">Company:</td>
			        <td>{if:order.profile.billing_company}{order.profile.billing_company:h}{else:}N/A{end:}</td>
			    </tr>
			    <tr valign="top">
			        <td nowrap class="ProductDetailsTitle">Address:</td>
			        <td>{if:order.profile.billing_address}{order.profile.billing_address:h}{else:}N/A{end:}</td>
			    </tr>
			    <tr valign="top">
			        <td nowrap class="ProductDetailsTitle">City:</td>
			        <td>{if:order.profile.billing_city}{order.profile.billing_city:h}{else:}N/A{end:}</td>
			    </tr>
			    <tr valign="top">
			        <td nowrap class="ProductDetailsTitle">State:</td>
			        <td>{if:order.profile.billingState}{order.profile.billingState.state:h}{else:}N/A{end:}</td>
			    </tr>
				<tr valign="top">
			        <td nowrap class="ProductDetailsTitle">Country:</td>
					<td>{if:order.profile.billingCountry}{order.profile.billingCountry.country:h}{else:}N/A{end:}</td>
				</tr>
			    <tr valign="top">
					<td nowrap class="ProductDetailsTitle">Zip/Postal code:</td>
			        <td>{if:order.profile.billing_zipcode}{order.profile.billing_zipcode:h}{else:}N/A{end:}</td>
			    </tr>
			</table>
		</td>
		<td>&nbsp;</td>
		<td>
			<table>
			    <tr valign="top">
			        <td nowrap class="ProductDetailsTitle">Name:</td>
			        <td>{if:order.profile.shipping_firstname}{order.profile.shipping_title} {order.profile.shipping_firstname:h} {order.profile.shipping_lastname:h}{else:}N/A{end:}</td>
			    </tr>
			    <tr valign="top">
			        <td nowrap class="ProductDetailsTitle">Phone:</td>
			        <td>{if:order.profile.shipping_phone}{order.profile.shipping_phone:h}{else:}N/A{end:}</td>
			    </tr>
			    <tr valign="top">
			        <td nowrap class="ProductDetailsTitle">Fax:</td>
			        <td>{if:order.profile.shipping_fax}{order.profile.shipping_fax:h}{else:}N/A{end:}</td>
			    </tr>
			    <tr valign="top">
			        <td nowrap class="ProductDetailsTitle">Company:</td>
			        <td>{if:order.profile.shipping_company}{order.profile.shipping_company:h}{else:}N/A{end:}</td>
			    </tr>
			    <tr valign="top">
			        <td nowrap class="ProductDetailsTitle">Address:</td>
			        <td>{if:order.profile.shipping_address}{order.profile.shipping_address:h}{else:}N/A{end:}</td>
			    </tr>
			    <tr valign="top">
			        <td nowrap class="ProductDetailsTitle">City:</td>
			        <td>{if:order.profile.shipping_city}{order.profile.shipping_city:h}{else:}N/A{end:}</td>
			    </tr>
			    <tr valign="top">
			        <td nowrap class="ProductDetailsTitle">State:</td>
			        <td>{if:order.profile.shippingState}{order.profile.shippingState.state:h}{else:}N/A{end:}</td>
			    </tr>
			    <tr valign="top">
			        <td nowrap class="ProductDetailsTitle">Country:</td>
			        <td>{if:order.profile.shippingCountry}{order.profile.shippingCountry.country:h}{else:}N/A{end:}</td>
			    </tr>
			    <tr valign="top">
			        <td nowrap class="ProductDetailsTitle">Zip/Postal code:</td>
			        <td>{if:order.profile.shipping_zipcode}{order.profile.shipping_zipcode:h}{else:}N/A{end:}</td>
			    </tr>
			</table>
		</td>
	</tr>	
</table>
<table width="90%" border="0" cellpadding="0" cellspacing="0" align="center" IF="order.showCCInfo&adminMail">
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2" class="OrderTitle" style="font-size: 14px">Credit card information:</td>
    </tr>
    <tr>
        <td colspan="2"><hr style="background-color: #516176; height: 2px; border: 0"></td>
    </tr>
    <tr>
        <td nowrap class="AomProductDetailsTitle">{order.detail_labels.cc_number:h}:</td>
        <td>{order.details.cc_number:h}</td>
    </tr>
    <tr>
        <td nowrap class="AomProductDetailsTitle">{order.detail_labels.cc_type:h}:</td>
        <td>{order.details.cc_type:h}</td>
    </tr>
    <tr>
        <td nowrap class="AomProductDetailsTitle">{order.detail_labels.cc_name:h}</td>
        <td>{order.details.cc_name:h}</td>
    </tr>
    <tr>
        <td nowrap class="AomProductDetailsTitle">{order.detail_labels.cc_date:h}</td>
        <td>{order.details.cc_date:h}</td>
    </tr>
    <tr>
        <td nowrap class="AomProductDetailsTitle">{order.detail_labels.cc_cvv2:h}</td>
        <td>{order.details.cc_cvv2:h}</td>
    </tr>
</table>

<table width="90%" border="0" cellpadding="0" cellspacing="0" align="center" IF="order.showECheckInfo&adminMail">
<tr>
    <td colspan="2">&nbsp;</td>
</tr>
<tr>
    <td colspan="2" class="OrderTitle" style="font-size: 14px">eCheck information:</td>
</tr>
<tr>
    <td colspan="2"><hr style="background-color: #516176; height: 2px; border: 0"></td>
</tr>
<tr>
    <td nowrap class="AomProductDetailsTitle">{order.detail_labels.ch_routing_number:h}:</td>
    <td>{order.details.ch_routing_number:h}</td>
</tr>
<tr>
    <td nowrap class="AomProductDetailsTitle">{order.detail_labels.ch_acct_number:h}:</td>
    <td>{order.details.ch_acct_number:h}</td>
</tr>
<tr>
    <td nowrap class="AomProductDetailsTitle">{order.detail_labels.ch_type:h}:</td>
    <td>{order.details.ch_type:h}</td>
</tr>
<tr>
    <td nowrap class="AomProductDetailsTitle">{order.detail_labels.ch_bank_name:h}:</td>
    <td>{order.details.ch_bank_name:h}</td>
</tr>
<tr>
    <td nowrap class="AomProductDetailsTitle">{order.detail_labels.ch_acct_name:h}:</td>
    <td>{order.details.ch_acct_name:h}</td>
</tr>
<tr IF="order.details.ch_number">
    <td nowrap class="AomProductDetailsTitle">{order.detail_labels.ch_number:h}:</td>
    <td>{order.details.ch_number:h}</td>
</tr>
</table>

<table width="90%" border="0" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td class="OrderTitle" style="font-size: 14px">Additional info</td>
    </tr>
    <tr>
        <td><hr style="background-color: #516176; height: 2px; border: 0"></td>
    </tr>
</table>
<table width="90%" border="0" cellpadding="3" cellspacing="0" align="center" IF="xlite.WholesaleTradingEnabled&order.profile.isShowWholesalerFields()">
	<tr>
		<td colspan="2"><b>Wholesaler details</b></td>
	</tr>
	<tr  IF="xlite.config.WholesaleTrading.WholesalerFieldsTaxId">
		<td nowrap width="10%">Sales Permit/Tax ID#</td>
		<td align="left">{if:order.profile.tax_id}{order.profile.tax_id:h}{else:}N/A{end:}</td>
	</tr>
	<tr IF="xlite.config.WholesaleTrading.WholesalerFieldsVat">
		<td nowrap width="10%">VAT Registration number</td>
		<td align="left">{if:order.profile.vat_number}{order.profile.vat_number:h}{else:}N/A{end:}</td>
	</tr>
	<tr IF="xlite.config.WholesaleTrading.WholesalerFieldsGst">
		<td nowrap width="10%">GST Registration number</td>
		<td align="left">{if:order.profile.gst_number}{order.profile.gst_number:h}{else:}N/A{end:}</td>
	</tr>
	<tr IF="xlite.config.WholesaleTrading.WholesalerFieldsPst">
		<td nowrap width="10%">PST Registration number</td>
		<td align="left">{if:order.profile.pst_number}{order.profile.pst_number:h}{else:}N/A{end:}</td>
	</tr>
</table>
<table width="90%" border="0" cellpadding="3" cellspacing="0" align="center" IF="order.isTaxRegistered()">
    <tr>
        <td colspan="2"><b>Tax registration numbers:</b></td>
    </tr>
    <tr FOREACH="order.getDisplayTaxes(),tax_name,tax">
        <td width="10%">&nbsp;{order.getTaxLabel(tax_name)}:</td>
        <td align="left">{order.getRegistration(tax_name)}</td>
    </tr>
</table>
<table width="90%" border="0" cellpadding="3" cellspacing="0" align="center" IF="order.discountCoupon">
    <tr>
        <td><b>Discount coupon:</b></td>
    </tr>
    <tr>
        <td>&nbsp;{order.DC.coupon:h}</td>
    </tr>
</table>
<widget template="modules/CDev/AOM/invoice_so.tpl">
<table width="90%" border="0" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td colspan="5"><br><br></td>
	</tr>	
    <tr>
        <td colspan="5" class="TopLabel" align="center">Products Ordered</td>
    </tr>
	<tr>
		<td colspan="5">	
			<table width="100%" cellpadding="2" cellspacing="2">
				<tr>
					<th height="25" class="AomTableHead">Product</th>
			    	<th height="25" class="AomTableHead">SKU</th>
					<th height="25" class="AomTableHead">Quantity</th>
					<th height="25" class="AomTableHead">Item price</th>
					<th height="25" class="AomTableHead" width="100">Total</th>
				</tr>
				<tr FOREACH="order.items,item" valign="top">
				   	<td class="ProductDetailsTitle">{item.product_name}<br>	
						<table cellspacing="5">	
					    	<widget module="CDev\ProductOptions" template="modules/CDev/AOM/invoice_options.tpl" item="{item}" IF="{item.hasOptions()}">
			    		    <widget module="CDev\Egoods" template="modules/CDev/Egoods/invoice.tpl">
						</table>
					</td>
		        	<td>{item.product_sku}</td>
				    <td align="center">{item.amount}</td>
			        <td align="right">{price_format(item.price):h}</td>
			        <td align="right">{price_format(item.total):h}</td>
				</tr>
				<tr IF="!order.items">
					<td colspan="5" class="ProductDetailsTitle" align="center">No products</td>
				</tr>
				<tr>
					<td colspan="5" valign="top"><hr style="background-color: #cdd9e1; border: 0"></td>
				</tr>
				<widget module="CDev\WholesaleTrading" template="modules/CDev/AOM/invoice_wsale.tpl">
			    <tr>
			        <td colspan="4" align="right" class="ProductDetailsTitle">Subtotal:</td>
			        <td align="right">{price_format(order,#subtotal#):h}</td>
			    </tr>
				<widget module="CDev\Promotion" template="modules/CDev/AOM/invoice_promotion.tpl">   
			    <tr>
			        <td colspan="4" align="right" class="ProductDetailsTitle">Shipping cost:</td>
			        <td align="right">{price_format(order.shipping_cost):h}</td>
			    </tr>
				<tr FOREACH="order.getDisplayTaxes(),tax_name,tax">
			        <td colspan="4" align="right" class="ProductDetailsTitle">{order.getTaxLabel(tax_name)}:</td>
			        <td align="right">{price_format(tax):h}</td>
			    </tr>
				<widget module="CDev\Promotion" template="modules/CDev/AOM/invoice_bonus_points.tpl">   
				<widget module="CDev\GiftCertificates" template="modules/CDev/AOM/invoice_gc.tpl">
				</table>
			</td>
		</tr>
		<tr>
		<td>
		<table width="100%" cellpadding="3" cellspacing="0">
			<tr>	
		        <td class="AomTableHead" style="border-color: #516176; border-style: solid none none none; border-width: 2px" align="right"><b>Total:</b></td>
		        <td class="AomTableHead" style="border-color: #516176; border-style: solid none none none; border-width: 2px" width="100px" align="right"><b>{price_format(order,#total#):h}</b></td>
			</tr>
		</table>
		</td>
	</tr>
</table>
{if:mode=#invoice#}
{if:mailMode=##}
</body>
</html>
{end:}
{end:}

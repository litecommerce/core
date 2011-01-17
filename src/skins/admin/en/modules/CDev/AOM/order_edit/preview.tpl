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
<form name="under_order_form" action="admin.php" method="POST">
<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val:r}"/>
<input type="hidden" name="action" value="undo_changes">
<table width="90%" border="0" cellpadding="0" cellspacing="0" align="center">
	<tr valign="top">
		<td align="left" class="OrderTitle" style="font-size: 20px">Order #{order.order_id:h}</td>
		<td IF="mode=#invoice#"><img src="images/logo.gif"></td>
		<td width="33%">
			<table>
				<tr>
			        <td nowrap class="ProductDetailsTitle">Date:</td>
					<td>&nbsp;</td>	
			        <td>{formatTime(cloneOrder.date)}</td>
				</tr>
			    <tr>
			        <td nowrap class="ProductDetailsTitle">Status:</td>
					<td>&nbsp;</td> 
			        <td IF="!target=#create_order#">{order.orderStatus.name}</td>
                    <td IF="target=#create_order#"><widget class="\XLite\View\StatusSelect" field="substatus" value="{order.orderStatus.status}" style="width: 150px; border-width: 1px; border-color: #516176; border-style: solid;"></td>
			    </tr>
                <tr>
                    <td nowrap class="ProductDetailsTitle">E-mail:</td>
                    <td>&nbsp;</td>
                    <td>{if:cloneOrder.profile.login}{cloneOrder.profile.login:h}{else:}<font class="Star">N/A (*)</font>{end:}</td>
                </tr>
			</table>
		</td>
		<td width="34%">
            <table>
                <tr>
                    <td nowrap class="ProductDetailsTitle">Payment method:</td>
                    <td>&nbsp;</td> 
                    <td>{if:cloneOrder.paymentMethod}{cloneOrder.paymentMethod.name:h}{else:}<font class="Star">N/A (*)</font>{end:}{if:unsecureCC}<font class="Star">&nbsp;(*)</font>{end:}</td>
                </tr>
                <tr> 
                    <td nowrap class="ProductDetailsTitle">Delivery:</td>
                    <td>&nbsp;</td> 
                    <td nowrap>
                    {if:!cloneOrder.shipping_id=#-1#}
                        {if:cloneOrder.shippingMethod}
                            {cloneOrder.shippingMethod.name:h}
                        {else:}
                            {if:cloneOrder.shipping_id=#0#}
                                Free
                            {else:}
                                N/A
                            {end:}
                        {end:}
                    {else:}
                        <font class="Star">N/A (*)</font>
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
                    <td>{cloneOrder.locationState.state:h}, {config.Company.location_zipcode:h}, {cloneOrder.locationCountry.country:h}</td>
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
			        <td>{if:cloneProfile.billing_firstname}{cloneProfile.billing_title} {cloneProfile.billing_firstname:h} {cloneProfile.billing_lastname:h}{else:}<font class="Star">N/A</font>{end:}</td>
			    </tr>
			    <tr valign="top">
			        <td nowrap class="ProductDetailsTitle">Phone:</td>
			        <td>{if:cloneProfile.billing_phone}{cloneProfile.billing_phone:h}{else:}<font class="Star">N/A</font>{end:}</td>
			    </tr>
			    <tr valign="top">
			        <td nowrap class="ProductDetailsTitle">Fax:</td>
					<td>{if:cloneProfile.billing_fax}{cloneProfile.billing_fax:h}{else:}<font class="Star">N/A</font>{end:}</td>
			    </tr>
			    <tr valign="top">
					<td nowrap class="ProductDetailsTitle">Company:</td>
			        <td>{if:cloneProfile.billing_company}{cloneProfile.billing_company:h}{else:}<font class="Star">N/A</font>{end:}</td>
			    </tr>
			    <tr valign="top">
			        <td nowrap class="ProductDetailsTitle">Address:</td>
			        <td>{if:cloneProfile.billing_address}{cloneProfile.billing_address:h}{else:}<font class="Star">N/A</font>{end:}</td>
			    </tr>
			    <tr valign="top">
			        <td nowrap class="ProductDetailsTitle">City:</td>
			        <td>{if:cloneProfile.billing_city}{cloneProfile.billing_city:h}{else:}<font class="Star">N/A</font>{end:}</td>
			    </tr>
			    <tr valign="top">
			        <td nowrap class="ProductDetailsTitle">State:</td>
			        <td>{if:cloneProfile.billingState}{cloneProfile.billingState.state:h}{else:}<font class="Star">N/A</font>{end:}</td>
			    </tr>
				<tr valign="top">
			        <td nowrap class="ProductDetailsTitle">Country:</td>
					<td>{if:cloneProfile.billingCountry}{cloneProfile.billingCountry.country:h}{else:}<font class="Star">N/A</font>{end:}</td>
				</tr>
			    <tr valign="top">
					<td nowrap class="ProductDetailsTitle">Zip/Postal code:</td>
			        <td>{if:cloneProfile.billing_zipcode}{cloneProfile.billing_zipcode:h}{else:}<font class="Star">N/A</font>{end:}</td>
			    </tr>
			</table>
		</td>
		<td>&nbsp;</td>
		<td>
			<table>
			    <tr valign="top">
			        <td nowrap class="ProductDetailsTitle">Name:</td>
			        <td>{if:cloneProfile.shipping_firstname}{cloneProfile.shipping_title} {cloneProfile.shipping_firstname:h} {cloneProfile.shipping_lastname:h}{else:}<font class="Star">N/A</font>{end:}</td>
			    </tr>
			    <tr valign="top">
			        <td nowrap class="ProductDetailsTitle">Phone:</td>
			        <td>{if:cloneProfile.shipping_phone}{cloneProfile.shipping_phone:h}{else:}<font class="Star">N/A</font>{end:}</td>
			    </tr>
			    <tr valign="top">
			        <td nowrap class="ProductDetailsTitle">Fax:</td>
			        <td>{if:cloneProfile.shipping_fax}{cloneProfile.shipping_fax:h}{else:}<font class="Star">N/A</font>{end:}</td>
			    </tr>
			    <tr valign="top">
			        <td nowrap class="ProductDetailsTitle">Company:</td>
			        <td>{if:cloneProfile.shipping_company}{cloneProfile.shipping_company:h}{else:}<font class="Star">N/A</font>{end:}</td>
			    </tr>
			    <tr valign="top">
			        <td nowrap class="ProductDetailsTitle">Address:</td>
			        <td>{if:cloneProfile.shipping_address}{cloneProfile.shipping_address:h}{else:}<font class="Star">N/A</font>{end:}</td>
			    </tr>
			    <tr valign="top">
			        <td nowrap class="ProductDetailsTitle">City:</td>
			        <td>{if:cloneProfile.shipping_city}{cloneProfile.shipping_city:h}{else:}<font class="Star">N/A</font>{end:}</td>
			    </tr>
			    <tr valign="top">
			        <td nowrap class="ProductDetailsTitle">State:</td>
			        <td>{if:cloneProfile.shippingState}{cloneProfile.shippingState.state:h}{else:}<font class="Star">N/A</font>{end:}</td>
			    </tr>
			    <tr valign="top">
			        <td nowrap class="ProductDetailsTitle">Country:</td>
			        <td>{if:cloneProfile.shippingCountry}{cloneProfile.shippingCountry.country:h}{else:}<font class="Star">N/A</font>{end:}</td>
			    </tr>
			    <tr valign="top">
			        <td nowrap class="ProductDetailsTitle">Zip/Postal code:</td>
			        <td>{if:cloneProfile.shipping_zipcode}{cloneProfile.shipping_zipcode:h}{else:}<font class="Star">N/A</font>{end:}</td>
			    </tr>
			</table>
		</td>
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
<table width="90%" border="0" cellpadding="3" cellspacing="0" align="center" IF="xlite.WholesaleTradingEnabled&isShowWholesalerFields()">
    <tr>
        <td colspan="2"><b>Wholesaler details</b></td>
    </tr>
    <tr  IF="xlite.config.WholesaleTrading.WholesalerFieldsTaxId">
        <td nowrap width="10%">Sales Permit/Tax ID#</td>
        <td align="left">{if:cloneOrder.profile.tax_id}{cloneOrder.profile.tax_id:h}{else:}N/A{end:}</td>
    </tr>
    <tr IF="xlite.config.WholesaleTrading.WholesalerFieldsVat">
        <td nowrap width="10%">VAT Registration number</td>
        <td align="left">{if:cloneOrder.profile.vat_number}{cloneOrder.profile.vat_number:h}{else:}N/A{end:}</td>
    </tr>
    <tr IF="xlite.config.WholesaleTrading.WholesalerFieldsGst">
        <td nowrap width="10%">GST Registration number</td>
        <td align="left">{if:cloneOrder.profile.gst_number}{cloneOrder.profile.gst_number:h}{else:}N/A{end:}</td>
    </tr>
    <tr IF="xlite.config.WholesaleTrading.WholesalerFieldsPst">
        <td nowrap width="10%">PST Registration number</td>
        <td align="left">{if:cloneOrder.profile.pst_number}{cloneOrder.profile.pst_number:h}{else:}N/A{end:}</td>
    </tr>
</table>
<table width="90%" border="0" cellpadding="3" cellspacing="0" align="center" IF="cloneOrder.isTaxRegistered()">
    <tr>
        <td colspan="2"><b>Tax registration numbers:</b></td>
    </tr>
    <tr FOREACH="cloneOrder.getDisplayTaxes(),tax_name,tax">
        <td width="10%">&nbsp;{cloneOrder.getTaxLabel(tax_name)}:</td>
        <td align="left">{cloneOrder.getRegistration(tax_name)}</td>
    </tr>
</table>
<table width="90%" border="0" cellpadding="3" cellspacing="0" align="center" IF="cloneOrder.discountCoupon">
    <tr>
        <td><b>Discount coupon:</b></td>
    </tr>
    <tr>
        <td>&nbsp;{cloneOrder.DC.coupon:h}</td>
    </tr>
</table>
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
				<tr class="AomTableHead">
					<th height="25">Product</th>
			    	<th height="25">SKU</th>
					<th height="25">Quantity</th>
					<th height="25">Item price</th>
					<th height="25" width="100">Total</th>
				</tr>
				<tr FOREACH="cloneOrder.items,item" valign="top">
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
				<tr IF="!cloneOrder.items">
					<td colspan="5" class="ProductDetailsTitle" align="center">No products</td>
				</tr>
				<tr>
					<td colspan="5" valign="top"><hr style="background-color: #cdd9e1; border: 0"></td>
				</tr>
				<widget module="CDev\WholesaleTrading" clone="1" template="modules/CDev/AOM/invoice_wsale.tpl">
			    <tr>
			        <td colspan="4" align="right" class="ProductDetailsTitle">Subtotal:</td>
			        <td align="right">{price_format(cloneOrder,#subtotal#):h}</td>
			    </tr>
				<widget module="CDev\Promotion" clone="1" template="modules/CDev/AOM/invoice_promotion.tpl">   
			    <tr>
			        <td colspan="4" align="right" class="ProductDetailsTitle">Shipping cost:</td>
			        <td align="right">{price_format(cloneOrder.shipping_cost):h}</td>
			    </tr>
				<tr FOREACH="cloneOrder.getDisplayTaxes(),tax_name,tax">
			        <td colspan="4" align="right" class="ProductDetailsTitle">{cloneOrder.getTaxLabel(tax_name)}:</td>
			        <td align="right">{price_format(tax):h}</td>
			    </tr>
				<widget module="CDev\Promotion" clone="1" template="modules/CDev/AOM/invoice_bonus_points.tpl">   
				<widget module="CDev\GiftCertificates" clone="1" template="modules/CDev/AOM/invoice_gc.tpl">
				</table>
			</td>
		</tr>
		<tr>
		<td>
		<table width="100%" cellpadding="3" cellspacing="0">
			<tr>	
		        <td class="AomTableHead" style="border-color: #516176; border-style: solid none none none; border-width: 2px" align="right"><b>Total:</b></td>
		        <td class="AomTableHead" style="border-color: #516176; border-style: solid none none none; border-width: 2px" width="100px" align="right"><b>{price_format(cloneOrder,#total#):h}</b></td>
			</tr>
		</table>
		</td>
	</tr>
</table>
<br>
<table width="90%" border="0" cellpadding="0" cellspacing="0" align="center" IF="cloneOrder.isTaxRegistered()">
    <tr>
    	<td>
        	<table cellpadding=0 cellspacing=0 border=0 width="100%">
    	    	<tr>
        	    	<td colspan="2"><b>Tax registration numbers:</b></td>
	       		</tr>
	    	    <tr FOREACH="cloneOrder.getDisplayTaxes(),tax_name,tax">
    	   			<td width="10%">{cloneOrder.getTaxLabel(tax_name)}:</td>
        		    <td align="left">{cloneOrder.getRegistration(tax_name)}</td>
		        </tr>
    	    </table>
    	</td>
	</tr>
</table>
<script>
function saveChanges()
{
	if (parseInt("{cloneOrder.productItemsCount}") == 0) {
		alert("The order cannot be saved because it contains no products.")
		return false;
	} 	
	document.under_order_form.action.value = 'save_changes'; 
	document.under_order_form.submit();
	
}
</script>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
    <td width="50%">&nbsp;</td> 
    <td align="right">
    <table border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td><input type="button" value=" Undo changes " onClick="javascript: document.under_order_form.action.value = 'undo_changes'; document.under_order_form.submit();">&nbsp;&nbsp;</td>
            <td IF="updateAvailable"><input type="button" class="UpdateButton" value=" Save changes " onClick="saveChanges();"></td>
            <td IF="!updateAvailable"><font class="Star">(*)</font> <a class="AomMenu" href="admin.php?target={target}&order_id={order.order_id}&page=order_edit">{if:!unsecureCC}Edit details{else:}Master password not entered{end:}&nbsp;<img src="images/go.gif" width="13" height="13" border="0" align="middle"></a></td>
        </tr>
    </table>    
    </td>
</tr>   
</table>
</form>

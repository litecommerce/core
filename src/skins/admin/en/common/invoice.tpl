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
<table border="0" cellpadding="3" cellspacing="0" width=350>
	<tr>
		<td nowrap><b>Order id</b></td>
		<td>#{order.order_id:h}</td>
	</tr>
	<tr>
		<td nowrap><b>Order date</b></td>
		<td>{time_format(order.date)}</td>
	</tr>

	<tr>
		<td nowrap><b>Order Status</b></td>
		<td>
      <table border="0" cellpadding="0" cellspacing="0" width="100%">
      <tr><widget class="\XLite\View\FormField\Label\OrderStatus" value="{order.getStatus()}" /></tr>
      </table>
    </td>
	</tr>
	<tr>
		<td nowrap><b>E-mail</b></td>
		<td>{order.profile.login:h}</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>


	<tr>
		<td  colspan="2" nowrap bgcolor="#DDDDDD"><b>Billing Info</b></td>
	</tr>
	<tr>
		<td nowrap>Name</td>
		<td>{order.profile.billing_title} {order.profile.billing_firstname:h} {order.profile.billing_lastname:h}</td>
	</tr>
	<tr>
		<td nowrap>Phone</td>
		<td>{order.profile.billing_phone:h}</td>
	</tr>
	<tr>
		<td nowrap>Fax</td>
		<td>{order.profile.billing_fax:h}</td>
	</tr>
	<tr>
		<td nowrap>Company</td>
		<td>{order.profile.billing_company:h}</td>
	</tr>
	<tr>
		<td nowrap>Address</td>
		<td>{order.profile.billing_address:h}</td>
	</tr>
	<tr>
		<td nowrap>City</td>
		<td>{order.profile.billing_city:h}</td>
	</tr>
	<tr>
		<td nowrap>State</td>
		<td>{order.profile.billingState.state:h}</td>
	</tr>
	<tr>
		<td nowrap>Country</td>
		<td>{order.profile.billingCountry.country:h}</td>
	</tr>
	<tr>
		<td nowrap>Zip code</td>
		<td>{order.profile.billing_zipcode:h}</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td  colspan="2" nowrap bgcolor="#DDDDDD"><b>Shipping Info</b></td>
	</tr>
	<tr>
		<td nowrap>Name</td>
		<td>{order.profile.shipping_title} {order.profile.shipping_firstname:h} {order.profile.shipping_lastname:h}</td>
	</tr>
	<tr>
		<td nowrap>Phone</td>
		<td>{order.profile.shipping_phone:h}</td>
	</tr>
	<tr>
		<td nowrap>Fax</td>
		<td>{order.profile.shipping_fax:h}</td>
	</tr>
	<tr>
		<td nowrap>Company</td>
		<td>{order.profile.shipping_company:h}</td>
	</tr>
	<tr>
		<td nowrap>Address</td>
		<td>{order.profile.shipping_address:h}</td>
	</tr>
	<tr>
		<td nowrap>City</td>
		<td>{order.profile.shipping_city:h}</td>
	</tr>
	<tr>
		<td nowrap>State</td>
		<td>{order.profile.shippingState.state:h}</td>
	</tr>
	<tr>
		<td nowrap>Country</td>
		<td>{order.profile.shippingCountry.country:h}</td>
	</tr>
	<tr>
		<td nowrap>Zip code</td>
		<td>{order.profile.shipping_zipcode:h}</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<widget module=WholesaleTrading template="modules/WholesaleTrading/wholesaler_details.tpl" profile="{order.profile}">
	<tr>
		<td  colspan="2" nowrap bgcolor="#DDDDDD"><b>Products Ordered</b></td>
	</tr>
	<tbody FOREACH="order.items,item">
		<tr>
			<td nowrap>SKU</td>
			<td>{item.sku}</td>
		</tr>
		<tr>
			<td nowrap>Product</td>
			<td>{item.name}</td>
		</tr>
		<tr>
			<td nowrap>Quantity</td>
			<td>{item.amount}</td>
		</tr>

    {displayViewListContent(#admin.invoice.item#,_ARRAY_(#item#^item))}
    <widget module="GiftCertificates" template="modules/GiftCertificates/invoice_item.tpl">
		<widget module="Egoods" template="modules/Egoods/invoice.tpl">

		<tr>
			<td nowrap>Item price</td>
			<td>{price_format(item,#price#):h}</td>
		</tr>
      <tr>
            <td nowrap><b>Total</b></td>
            <td>{price_format(item,#total#):h}</td>
      </tr>
      <tr>
	    <td colspan="2" height="1"><table bgcolor="#DDDDDD" width="100%" height="1" border="0" cellspacing="0" cellpadding="0"><td></td></table></td>
      </tr>
	</tbody>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td nowrap bgcolor="#DDDDDD"><b>Payment method</b></td>
		<td bgcolor="#DDDDDD">{order.paymentMethod.name:h}</td>
	<tr>

    {* TODO: check if it's needed *}
    {* <tbody IF="order.showCCInfo&adminMail">
    <tr>
        <td nowrap><b>Credit card information:</b></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td nowrap>{order.detail_labels.cc_number:h}</td>
        <td>{order.details.cc_number:h}</td>
    </tr>
    <tr>
        <td nowrap>{order.detail_labels.cc_type:h}</td>
        <td>{order.details.cc_type:h}</td>
    </tr>
    <tr>
        <td nowrap>{order.detail_labels.cc_name:h}</td>
        <td>{order.details.cc_name:h}</td>
    </tr>
    <tr>
        <td nowrap>{order.detail_labels.cc_date:h}</td>
        <td>{order.details.cc_date:h}</td>
    </tr>
    <tr>
        <td nowrap>{order.detail_labels.cc_cvv2:h}</td>
        <td>{order.details.cc_cvv2:h}</td>
    </tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
    </tbody>

    <tbody IF="order.showECheckInfo&adminMail">
    <tr>
        <td nowrap><b>eCheck information:</b></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td nowrap>{order.detail_labels.ch_routing_number:h}</td>
        <td>{order.details.ch_routing_number:h}</td>
    </tr>
    <tr>
        <td nowrap>{order.detail_labels.ch_acct_number:h}</td>
        <td>{order.details.ch_acct_number:h}</td>
    </tr>
    <tr>
        <td nowrap>{order.detail_labels.ch_type:h}</td>
        <td>{order.details.ch_type:h}</td>
    </tr>
    <tr>
        <td nowrap>{order.detail_labels.ch_bank_name:h}</td>
        <td>{order.details.ch_bank_name:h}</td>
    </tr>
    <tr>
        <td nowrap>{order.detail_labels.ch_acct_name:h}</td>
        <td>{order.details.ch_acct_name:h}</td>
    </tr>
    <tr IF="order.details.ch_number">
        <td nowrap>{order.detail_labels.ch_number:h}</td>
        <td>{order.details.ch_number:h}</td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    </tbody>*}

	<tr>
		<td nowrap>Delivery</td>
		<td>{if:order.shippingMethod}{order.shippingMethod.name:h}{else:}N/A{end:}</td>
	</tr>
	<widget module="WholesaleTrading" template="modules/WholesaleTrading/invoice.tpl">
	<tr>
		<td nowrap>Subtotal</td>
		<td>{price_format(order,#subtotal#):h}</td>
	</tr>
    <widget module="Promotion" template="modules/Promotion/invoice_discount.tpl">
	<tr>
		<td nowrap>Shipping cost</td>
		<td>{price_format(order,#shipping_cost#):h}</td>
	</tr>
	<tr FOREACH="order.getDisplayTaxes(),tax_name,tax">
   		<td nowrap>{order.getTaxLabel(tax_name)}</td>
		<td>{price_format(tax):h}</td>
	</tr>
	<tr if="order.isTaxRegistered()">
    <td colspan="2">
        <table cellpadding=0 cellspacing=0 border=0 width="100%">
        <tr>
            <td colspan="2">Tax registration numbers:</td>
        </tr>
        <tr FOREACH="order.getDisplayTaxes(),tax_name,tax">
            <td>{order.getTaxLabel(tax_name)}</td>
            <td align="left">{order.getRegistration(tax_name)}</td>
        </tr>
        </table>
    </td>
</tr>

        <widget module="Promotion" template="modules/Promotion/invoice.tpl">
        <widget module="GiftCertificates" template="modules/GiftCertificates/invoice.tpl">
	<tr>

		<td nowrap><b>Total</b></td>
		<td>{price_format(order,#total#):h}</td>
	</tr>
	<widget module=Promotion template="modules/Promotion/order_offers.tpl">
</table>

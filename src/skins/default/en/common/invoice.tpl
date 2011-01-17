{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Order invoice
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<table cellspacing="0" class="invoice-header">
  <tr>
    <td class="left"><img src="images/invoice_logo.png" alt="{config.Company.company_name}" /></td>
    <td class="right">
      <strong>{config.Company.company_name}</strong>
      <p>
        {config.Company.location_address}<br />
        {config.Company.location_city}, {config.Company.locationState.state}, {config.Company.location_zipcode}<br />
        {config.Company.location_country}
      </p>
      <p IF="config.Company.company_phone|config.Company.company_fax">
        {if:config.Company.company_phone}Phone: {config.Company.company_phone}<br />{end:}
        {if:config.Company.company_fax}Fax: {config.Company.company_fax}{end:}
      </p>
      <p IF="config.Company.company_website">
        <a href="{config.Company.company_website}">{config.Company.company_website}</a>
      </p>
    </td>
  </tr>
</table>

<h2 class="invoice">Invoice #{order.order_id}</h2>
<div class="invoice-title">
{formatTime(order.date)}
<span>Grand total: {price_format(order,#total#):h}</span>
</div>

<table cellspacing="1" class="invoice-items">

  <tr>
    <th class="name">Product</th>
    <th class="sku">SKU</th>
    <th class="price">Price</th>
    <th class="qty">Qty</th>
    <th class="total">Total</th>
  </tr>

  <tr FOREACH="order.getItems(),item">
    <td class="name"><a href="{item.getUrl()}">{item.name}</a></td>
    <td class="sku">{item.sku}</td>
    <td class="price">{price_format(item,#price#):h}</td>
    <td class="qty">{item.amount}</td>
    <td class="total">{price_format(item,#total#):h}</td>
  </tr>

</table>

<table cellspacing="1" class="invoice-totals">

  <tr>
    <td>Subtotal:</td>
    <td class="total">{price_format(order,#subtotal#):h}</td>
  </tr>

  <tr>
    <td>Shipping cost:</td>
    <td class="total">{price_format(order,#shipping_cost#):h}</td>
  </tr>

  <tr>
    <td>Tax cost:</td>
    <td class="total">{price_format(order,#tax_cost#):h}</td>
  </tr>

  <tr class="grand-total">
    <td>Grand total:</td>
    <td class="total">{price_format(order,#total#):h}</td>
  </tr>

</table>

<table cellspacing="0" class="invoice-address">
  <tr>
    <td class="bill">
      <strong>Billing address</strong>
      <p>
        {order.profile.billing_title} {order.profile.billing_firstname:h} {order.profile.billing_lastname:h}
      </p>

      <p>
        {order.profile.billing_address:h}<br />
        {order.profile.billing_city:h}, {order.profile.billingState.state:h}, {order.profile.billing_zipcode:h}<br />
        {order.profile.billing_company:h}
      </p>

      <p IF="order.profile.billing_phone|order.profile.billing_fax">
        {if:order.profile.billing_phone}Phone: {order.profile.billing_phone:h}<br />{end:}
        {if:order.profile.billing_fax}Fax: {order.profile.billing_fax:h}{end:}
      </p>

      <p class="last">
        E-mail: <a href="mailto:{order.profile.login:h}">{order.profile.login:h}</a>
      </p>

    </td>
    <td class="ship">
      <strong>Shipping address</strong>
      <p>
        {order.profile.shipping_title} {order.profile.shipping_firstname:h} {order.profile.shipping_lastname:h}
      </p>

      <p>
        {order.profile.shipping_address:h}<br />
        {order.profile.shipping_city:h}, {order.profile.shippingState.state:h}, {order.profile.shipping_zipcode:h}<br />
        {order.profile.shipping_company:h}
      </p>

      <p IF="order.profile.shipping_phone|order.profile.shipping_fax" class="last">
        {if:order.profile.shipping_phone}Phone: {order.profile.shipping_phone:h}<br />{end:}
        {if:order.profile.shipping_fax}Fax: {order.profile.shipping_fax:h}{end:}
      </p>
    </td>
  </tr>

  <tr>
    <td class="payment">
      <strong>Payment method:</strong>
      {order.paymentMethod.name:h}
    </td>
    <td class="shipping">
      <strong>Shipping method:</strong>
      {if:order.shippingMethod}{order.shippingMethod.name:h}{else:}n/a{end:}
    </td>
  </tr>

</table>

<div class="invoice-thank">Thank you for your order</div>

<hr class="tiny" />

{*
<widget module="CDev\GiftCertificates" template="modules/CDev/GiftCertificates/invoice_item.tpl">
<widget module="CDev\ProductOptions" template="modules/CDev/ProductOptions/invoice_options.tpl" IF="{item.hasOptions()}">
<widget module="CDev\Egoods" template="modules/CDev/Egoods/invoice.tpl">
<widget module="CDev\WholesaleTrading" template="modules/CDev/WholesaleTrading/wholesaler_details.tpl" profile={order.profile}>
<widget module="CDev\WholesaleTrading" template="modules/CDev/WholesaleTrading/invoice.tpl">
<widget module="CDev\Promotion" template="modules/CDev/Promotion/invoice_discount.tpl">
<widget module="CDev\Promotion" template="modules/CDev/Promotion/invoice.tpl">
<widget module="CDev\GiftCertificates" template="modules/CDev/GiftCertificates/invoice.tpl">
<widget module="CDev\Promotion" template="modules/CDev/Promotion/order_offers.tpl">
*}

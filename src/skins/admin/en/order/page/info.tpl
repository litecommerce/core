{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Order info
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}
<div class="order-info">

  <h1>{t(#Order X#,_ARRAY_(#id#^order.getOrderId()))}</h1>

  <p class="title">
    {if:hasProfilePage()}
      {t(#Placed on X by Y link#,_ARRAY_(#date#^getOrderDate(),#url#^getProfileURL(),#name#^getProfileName())):h}
    {else:}
      {t(#Placed on X by Y#,_ARRAY_(#date#^getOrderDate(),#name#^getProfileName())):h}
    {end:}
    {if:getMembership()}
      <span class="membership">({membership.getName()})</span>
    {end:}
  </p>

  <p class="total">{t(#Order Total X#,_ARRAY_(#total#^getorderTotal())):h}</p>

  <widget class="XLite\View\Form\Order\Operations" name="operations" />

    <div class="line-1">

        <div class="actions">
          <list name="order.actions" />
        </div>

        <div class="note">
          <list name="order.note" />
        </div>

        <div class="clear"></div>
    </div>

    <div class="line-2">

      <div class="payment" IF="order.getActivePaymentTransactions()">
        <h2>{t(#Payment info#)}</h2>
        <div class="box"><list name="order.payment" /></div>
      </div>

      <div class="shipping" IF="getShippingModifier()&shippingModifier.getMethod()">
        <h2>{t(#Shipping info#)}</h2>
        <div class="box"><list name="order.shipping" /></div>
      </div>

        <div class="clear"></div>
    </div>

    <widget class="XLite\View\StickyPanel\Order\Admin\Info" />

  <widget name="operations" end />

</div>

<!--
      <tr FOREACH="order.getMeaningDetails(),d" valign="top">
      	<td>{d.getLabel()}:</td>
	      <td><input type="text" name="details[{d.getDetailId()}]" size="40" value="{d.getValue():r}" /></td>
      </tr>

      <list name="order.details" order="{order}" />
-->

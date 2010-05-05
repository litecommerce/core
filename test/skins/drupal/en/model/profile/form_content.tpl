{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Register form
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div class="user-addresses">

  <div class="billing-address-form">

    <h2>Billing address</h2>

    <table class="user-address-form" cellspacing="0">
      <tr FOREACH="getBillingAddressFields(),field">{field.display()}</tr>
    </table>

  </div>

  <div class="shipping-address-form">

    <h2>Shipping address</h2>

    <div class="same-address"><input type="checkbox" id="ship_as_bill" name="postedData[ship_as_bill]" value="Y" checked="{isSameAddress()}" onclick="javascript: this.checked ? $('.shipping-address-form .user-address-form').hide() : $('.shipping-address-form .user-address-form').show();" /><label for="ship_as_bill">The same as billing</label></div>

    <table class="user-address-form" cellspacing="0" id="shipping_address_block">
      <tr FOREACH="getShippingAddressFields(),field">{field.display()}</tr>
    </table>

  </div>

</div>
<div class="clear">&nbsp;</div>
{if:isSameAddress()}
<script type="text/javascript">
<!--
document.getElementById('shipping_address_block').style.display = 'none';
-->
</script>
{end:}

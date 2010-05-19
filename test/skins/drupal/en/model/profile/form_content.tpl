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
      {foreach:getBillingAddressFields(),field}
      <tr{if:field.getName()=#postedData[billing_custom_state]#} style="display: none;"{end:}>{field.display()}</tr>
      {end:}
    </table>

  </div>

  <div class="shipping-address-form">

    <h2>Shipping address</h2>

    <div class="same-address"><input type="checkbox" id="ship_as_bill" name="postedData[ship_as_bill]" value="Y" checked="{isSameAddress()}" onclick="javascript: addressBlocksController();" /><label for="ship_as_bill">The same as billing</label></div>

    <table class="user-address-form" cellspacing="0" id="shipping_address_block">
      {foreach:getShippingAddressFields(),field}
      <tr{if:field.getName()=#postedData[shipping_custom_state]#} style="display: none;"{end:}>{field.display()}</tr>
      {end:}
    </table>

  </div>

</div>
<div class="clear">&nbsp;</div>
<script type="text/javascript">
<!--
function addressBlocksController()
{
  var inp = document.getElementById('ship_as_bill');
  var box = document.getElementById('shipping_address_block');

  if (inp && box) {

    var list = [
      box.getElementsByTagName('input'),
      box.getElementsByTagName('select'),
      box.getElementsByTagName('textarea')
    ];

    for (var i = 0; i < list.length; i++) {
      for (var j = 0; j < list[i].length; j++) {
        list[i].item(j).disabled = inp.checked;
      }
    }
  }
}
addressBlocksController();
-->
</script>

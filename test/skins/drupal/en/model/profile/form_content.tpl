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
    <h2>Billing address</h3>
    <table class="user-address-form">
      <tr FOREACH="getBillingAddressFields(),field">{field.display()}</tr>
    </table>
  </div>
  <div class="shipping-address-form">
    <h3>Shipping address</h3>
    <table class="user-address-form">
      <tr FOREACH="getShippingAddressFields(),field">{field.display()}</tr>
    </table>
  </div>
</div>

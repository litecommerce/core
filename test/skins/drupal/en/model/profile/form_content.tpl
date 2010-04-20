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

<div class="register-form">
  <div class="address billing">
    <h4>Billing address</h4>
    <div class="details">
      <table cellspacing="0" class="form-table">
        <tr FOREACH="getBillingAddressFields(),field">{field.display()}</tr>
      </table>
    </div>
  </div>

  <div class="address shipping">
    <h4>Shipping address</h4>
    <div class="details">
      <table cellspacing="0" class="form-table">
        <tr FOREACH="getShippingAddressFields(),field">{field.display()}</tr>
      </table>
    </div>
  </div>
</div>
<div class="clear">&nbsp;</div>

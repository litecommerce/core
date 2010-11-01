{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Billing adddress
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<widget class="\XLite\View\Form\Checkout\UpdateProfile" name="billingAddress" className="address billing-address" />

  <div class="same-address">
    <input type="hidden" name="same_address" value="0" />
    <input id="same_address" type="checkbox" name="same_address" value="1" checked="{isSameAddress()}" />
    <label for="same_address">{t(#The same as shipping#)}</label>
  </div>

  {if:isSameAddress()}
    <widget template="checkout/parts/address.plain.tpl" address="{getSameAddress()}" />

  {else:}
    <ul class="form">
      {displayViewListContent(#checkout.payment.address#)}
    </ul>

    {if:!isAnonymous()}
      <hr />

      <div class="save">
        <input type="checkbox" id="save_billing_address" name="billingAddress[save_as_new]" value="1" />
        <label for="save_billing_address">{t(#Save as new#)}</label>
      </div>
    {end:}

  {end:}

<widget name="billingAddress" end />

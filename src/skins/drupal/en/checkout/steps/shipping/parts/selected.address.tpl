{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Checkout : shipping step : selected state : address
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="checkout.shipping.selected", weight="10")
 *}
<h3>{t(#Enter shipping address#)}</h3>
<widget IF="!isAnonymous()" class="\XLite\View\Button\Link" label="Address book" location="{buildURL(#select_address#,##,_ARRAY_(#atype#^#s#))}" style="address-book" />

<widget class="\XLite\View\Form\Checkout\UpdateProfile" name="shippingAddress" className="address shipping-address" />

  <widget class="\XLite\View\Checkout\ShippingAddress" />

  {if:!isAnonymous()}
    <hr />

    <div class="save">
      <input type="checkbox" id="save_shipping_address" name="shippingAddress[save_as_new]" value="1" />
      <label for="save_shipping_address">{t(#Save as new#)}</label>
    </div>
  {end:}

<widget name="shippingAddress" end />

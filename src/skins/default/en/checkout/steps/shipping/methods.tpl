{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shipping methods list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<widget class="\XLite\View\Form\Checkout\ShippingMethod" name="shippingMethod" className="shipping-methods" />

  {if:isShippingAvailable()}

    <widget class="\XLite\View\ShippingList" />

  {else:}

    {if:isAddressCompleted()}
      <p class="error">{t(#There are no shipping methods available#)}</p>
    {else:}
      <p class="address-not-defined">{t(#Shipping address is not defined yet#)}</p>
    {end:}

  {end:}

<widget name="shippingMethod" end />

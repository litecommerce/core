{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Checkout : order review step : selected state : button
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="checkout.review.selected", weight="20")
 *}
<widget class="\XLite\View\Form\Checkout\Place" name="placeOrder" className="place"/>

  {if:getPaymentTemplate()}
    <widget template="{getPaymentTemplate()}" />
  {end:}

  <div class="notes">
    <label for="place_order_note">{t(#Customer note#)}:</label>
    <textarea name="notes"></textarea>
  </div>

  <div class="terms">
    <div class="terms-box">
      <label for="place_order_agree">
        <input type="checkbox" name="agree" value="1" id="place_order_agree" />
        {t(#I accept Terms and Conditions#,_ARRAY_(#URL#^getTermsURL())):h}
      </label>
    </div>
    <div class="mark"></div>
  </div>

  <div class="button-row">
    <widget class="\XLite\View\Button\Submit" label="{getPlaceTitle()}" style="bright" />
  </div>

  <p class="agree-note">{t(#To place the order please accept Terms and Conditions#)}</p>
<widget name="placeOrder" end />

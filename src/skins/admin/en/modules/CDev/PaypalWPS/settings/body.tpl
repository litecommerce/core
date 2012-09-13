{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * PayPal Payments Standard settings
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}


<div class="payment-settings {paymentMethod.getServiceName()}">

  <div class="middle">

    <div class="settings">

        <ul class="options">

          <li>
            <span><label for="pp_account">{t(#PayPal ID / Email#)}:</label></span>
            <input type="text" id="pp_account" name="settings[account]" value="{paymentMethod.getSetting(#account#)}" class="field-required" />
            <widget
              class="\XLite\View\Tooltip"
              id="pp-account-help"
              text="{t(#Enter the email address associated with your PayPal account.#)}"
              caption=""
              isImageTag="true"
              className="help-icon" />
          </li>

          <li>
            <span><label for="pp_description">{t(#Purchase description#)}:</label></span>
            <input type="text" id="pp_description" name="settings[description]" value="{paymentMethod.getSetting(#description#)}" />
            <widget
              class="\XLite\View\Tooltip"
              id="pp-vendor-help"
              text="{t(#Enter description of the purchase that will be displayed on PayPal payment page.#)}"
              caption=""
              isImageTag="true"
              className="help-icon" />
          </li>

          <li>
            <span><label for="pp_test">{t(#Test/Live mode#)}:</label></span>
            <select id="pp_test" name="settings[mode]">
              <option value="live" selected="{isSelected(#live#,paymentMethod.getSetting(#mode#))}">{t(#Live mode#)}</option>
              <option value="test" selected="{isSelected(#test#,paymentMethod.getSetting(#mode#))}">{t(#Test mode#)}</option>
            </select>
          </li>

          <li>
            <span><label for="pp_order_prefix">{t(#Order id prefix#)}:</label></span>
            <input type="text" id="pp_order_prefix" name="settings[prefix]" value="{paymentMethod.getSetting(#prefix#)}" />
            <widget
              class="\XLite\View\Tooltip"
              id="pp-vendor-help"
              text="{t(#You can define an order id prefix, which would precede each order number in your shop, to make it unique (each transaction id must be unique for a PayPal account). This options is relevant only if you share your PayPal account with other online shops#)}"
              caption=""
              isImageTag="true"
              className="help-icon" />
          </li>

        </ul>
 
      <div class="buttons">
        <widget class="\XLite\View\Button\Submit" label="{t(#Save changes#)}" style="main-button" />
      </div>

    </div>

    <div class="help">
      <div class="logo-ppa"></div>

      <div class="help-title">Accept PayPal and Credit Cards Securely</div>

      <div class="help-text">
        Add a PayPal payment button to your site to accept Visa, MasterCard&reg;, American Express, Discover and PayPal payments securely. When your customers check out, they are redirected to PayPal to pay, then return to your site after they are finished.
      </div>


      <div class="help-link">Don't have an account? <a href="{paymentMethod.getReferralPageURL()}">Sign Up Now</a></div>

  </div>

</div>


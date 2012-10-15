{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * PayPal Express Checkout settings
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}


<div class="payment-settings {paymentMethod.getServiceName()}">

  <div class="middle">

    <div class="settings">

      <h2>{t(#Your account settings#)}</h2>

        <ul class="options">

          <li>
            <span><label for="pp_partner">{t(#Partner name#)}:</label></span>
            <input type="text" id="pp_partner" name="settings[partner]" value="{paymentMethod.getSetting(#partner#)}" class="field-required" />
            <widget
              class="\XLite\View\Tooltip"
              id="pp-partner-help"
              text="{t(#Your partner name is PayPal#)}"
              caption=""
              isImageTag="true"
              className="help-icon" />
          </li>

          <li>
            <span><label for="pp_vendor">{t(#Merchant login#)}:</label></span>
            <input type="text" id="pp_vendor" name="settings[vendor]" value="{paymentMethod.getSetting(#vendor#)}" class="field-required" />
            <widget
              class="\XLite\View\Tooltip"
              id="pp-vendor-help"
              text="{t(#This is the login name you created when signing up for PayPal Payments Advanced.#)}"
              caption=""
              isImageTag="true"
              className="help-icon" />
          </li>

          <li>
            <span><label for="pp_user">{t(#User#)}:</label></span>
            <input type="text" id="pp_user" name="settings[user]" value="{paymentMethod.getSetting(#user#)}" class="field-required" />
            <widget
              class="\XLite\View\Tooltip"
              id="pp-vendor-help"
              text="{t(#PayPal recommends entering a User Login here instead of your Merchant Login. You can set up a User profile in <a href="https://manager.paypal.com">PayPal Manager</a>. This will enhance security and prevent service interruption should you change your Merchant Login password.#)}"
              caption=""
              isImageTag="true"
              className="help-icon" />
          </li>

          <li>
            <span><label for="pp_pwd">{t(#Password#)}:</label></span>
            <input type="text" id="pp_pwd" name="settings[pwd]" value="{paymentMethod.getSetting(#pwd#)}" class="field-required" />
            <widget
              class="\XLite\View\Tooltip"
              id="pp-vendor-help"
              text="{t(#This is the password you created when signing up for PayPal Payments Advanced or the password you created for API calls.#)}"
              caption=""
              isImageTag="true"
              className="help-icon" />
          </li>

        </ul>

      <h2>{t(#Additional settings#)}</h2>

        <ul class="options ">

          <li>
            <span><label for="pp_transaction_type">{t(#Transaction type#)}:</label></span>
            <select id="pp_transaction_type" name="settings[transaction_type]">
              <option value="S" selected="{isSelected(#S#,paymentMethod.getSetting(#transaction_type#))}">{t(#Auth and Capture#)}</option>
              <option value="A" selected="{isSelected(#A#,paymentMethod.getSetting(#transaction_type#))}">{t(#Auth only#)}</option>
            </select>
          </li>

          <li>
            <span><label for="pp_test">{t(#Test mode#)}:</label></span>
            <select id="pp_test" name="settings[test]">
              <option value="N" selected="{isSelected(#N#,paymentMethod.getSetting(#test#))}">{t(#Live mode#)}</option>
              <option value="Y" selected="{isSelected(#Y#,paymentMethod.getSetting(#test#))}">{t(#Test mode#)}</option>
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
      <div class="logo-pec"></div>

      <div class="help-title">Let Your Customers Pay with PayPal</div>

      <div class="help-text">
        Add PayPal as a payment method to your checkout page or use it as a stand-alone solution. You'll open the door to over 100 million active PayPal customers who look for and use this fast, easy, and secure way to pay.
      </div>


      <div class="help-link">Don't have an account? <a href="{paymentMethod.getReferralPageURL()}">Sign Up Now</a></div>

      <div class="help-text"><a href="{paymentMethod.getPartnerPageURL()}">Get more information</a></div>

  </div>

  <div IF="!{paymentMethod.getSetting(#hide_instruction#)}" class="footer">

    <h2>Configure PayPal - Instructions</h2>

    <h3>Enabling the Secure Token setting:</h3>

    <div>Enabling this setting helps to secure your PayPal Payments Advanced account.</div>

    <div>
      <ul>
        <li>1. Log in to your <a href="https://manager.paypal.com">PayPal Manager account</a>.</li>
        <li>2. Click Service Settings.</li>
        <li>3. Under Hosted Checkout Pages, click Set Up.</li>
        <li>4. Set Enable Secure Token to Yes.</li>
        <li>5. Click Save Changes.</li>
      </ul>
    </div>

    <h3>Creating your custom PayPal payment page</h3>

    <div>Creating a custom payment page allows you to co-brand the PayPal checkout pages with your logo and colors.</div>

    <div>
      <ul>
        <li>1. Log in to your <a href="https://www.paypal.com">PayPal account</a>.</li>
        <li>2. Underneath the My Account tab, click Profile.</li>
        <li>3. Click My selling tools.</li>
        <li>4. Locate Custom payment pages, and click the Update link next to it.</li>
        <li>5. Click Add.</li>
        <li>6. In the Page Style Name box, give a name to your custom page style. (It doesn't matter what you put here, as long as it isn't "PayPal".)</li>
        <li>7. In the Logo Image URL box, enter the URL of your logo image.  Your logo image should be 190x60px, and should be hosted on an SSL-secured (https://) site.  If you do not have an SSL-secured site available to you, free alternatives (such as sslpic.com) are available.</li>
        <li>8. In the Cart Area Gradient Color box, enter an HTML hex code that represents the gradient color you want to use around the shopping cart section of the checkout page.</li>
        <li>9. Click Save.</li>
        <li>10. Click the radio button next to the new payment page style you just created, and click Make Primary. Your custom payment page style will now be used whenever buyers elect to pay with PayPal.</li>
      </ul>
    </div>

    <div><a href="{buildURL(#payment_method#,#hide_instruction#,_ARRAY_(#method_id#^paymentMethod.getMethodId()))}">I've done this, dismiss the instruction</a></div>

  </div>

  <div IF="{paymentMethod.getSetting(#hide_instruction#)}" class="footer">

    <div class="pp-token-enabled">The PayPal Secure Token is enabled</div>

    <div class="pp-token-enabled-link"><a href="{buildURL(#payment_method#,#show_instruction#,_ARRAY_(#method_id#^paymentMethod.getMethodId()))}">Show the instruction</a></div>

  </div>

</div>



{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * PayPal Payflow Link settings
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
              text="{t(#This should be the same partner name that is used when logging into your PayPal Payflow account.#)}"
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
              text="{t(#This is the login name you created when signing up for Payflow.#)}"
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
              text="{t(#This is the password you created when signing up for PayPal Payflow or the password you created for API calls.#)}"
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
      <div class="logo-pfl"></div>

      <div class="help-title">Accept Payments with Your Merchant Account</div>

      <div class="help-text">Accept Visa, MasterCard&reg;, American Express, Discover and PayPal payments on your site by connecting a PayPal-hosted checkout page with your own internet merchant account. Customize the page to look like the rest of your site and make sure customer transactions are secure.
      </div>


      <div class="help-link">Don't have an account? <a href="{paymentMethod.getReferralPageURL()}">Sign Up Now</a></div>

      <div class="help-text"><a href="{paymentMethod.getPartnerPageURL()}">Get more information</a></div>

  </div>

  <div IF="!{paymentMethod.getSetting(#hide_instruction#)}" class="footer">

    <h2>Configure PayPal - Instructions</h2>

    <div>In order to accept payments via PayPal Payflow, you must complete the following steps.</div>

    <div>Please do not change any other values, as the system will pass these values on your behalf for the ease of configuration.</div>

    <h3>Enabling the Secure Token setting:</h3>
    <div class="left-part">
      <ul>
        <li>1. Log in to <a href="https://manager.paypal.com">PayPal Manager</a>.</li>
        <li>2. Select Hosted Checkout Pages then Set Up.</li>
        <li>3. Under Security Options, set the Secure Token to Yes.</li>
      </ul>
    </div>

    <div class="right-part">
      <a href="http://youtu.be/y9IGQpJCJeE">View a tutorial</a> | <a href="https://www.paypal.com/us/cgi-bin/webscr?cmd=_help">PayPal Help</a>
    </div>

    <div class="clear"></div>

    <h3>Creating your custom PayPal payment page (for Express Checkout)</h3>

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

    <h3>Setting up a test account</h3>

    <div>To test Payflow Link, you will need to set up two PayPal Sandbox accounts (one to simulate you, as the merchant, and one to simulate a buyer) and a test Payflow Link account. To set up these accounts, follow these instructions:</div>

    <div>
      <ul>
        <li>1. Go to <a href="https://developer.paypal.com/">PayPal Sandbox</a> and sign in. If you do not have an account, click "Sign Up", and follow the instructions given.</li>
        <li>2. Click <b>Create a preconfigured account</b>.</li>
        <li>3. Set <b>Account Type</b> to <b>Seller</b>.</li>
        <li>4. Make a note of the password that is generated for you in the <b>Password</b> box, or replace it with a password of your choice.</li>
        <li>5. Click <b>Create Account</b>. A new Sandbox account will be created, and will be shown on the <b>Test Accounts</b> page. Make a note of the email address generated. This will be your test merchant account.</li>
        <li>6. Click <b>Home</b>, then repeat steps 2-5. On step 3, set <b>Account Type</b> to <b>Buyer</b>. This will be your test buyer account.</li>
        <li>7. Create a test Payflow Link account by going to <a href="https://registration.paypal.com/welcomePage.do?producttype=C1&country=US&mode=try">the link</a> and following the registration process. When you reach the "Next Steps" page, your account is created.</li>
        <li>8. Log in to your new account at <a href="https://manager.paypal.com">PayPal Manager</a>. Log in with the following credentials:
          <div style="margin-left: 20px; font-size: 0.9em;">
            <ul>
              <li>Partner: <b>PayPal</b></li>
              <li>Merchant Login: The Merchant Login that you chose in step 7</li>
              <li>User: Leave blank</li>
              <li>Password: The password that you chose in step 7</li>
            </ul>
          </div>
        </li>
        <li>9. Click <b>Service Settings</b>.</li>
        <li>10. Under <b>Hosted Checkout Pages</b>, click <b>Set Up</b>.</li>
        <li>11. In the <b>PayPal Sandbox email address</b> box, enter the email address of your Sandbox merchant account (from step 5).</li>
        <li>12. Click <b>Save Changes</b>.</li>
      </ul>
    </div>

    <div><a href="{buildURL(#payment_method#,#hide_instruction#,_ARRAY_(#method_id#^paymentMethod.getMethodId()))}">I've done this, dismiss the instruction</a></div>

  </div>

  <div IF="{paymentMethod.getSetting(#hide_instruction#)}" class="footer">

    <div class="pp-token-enabled">The PayPal Secure Token is enabled</div>

    <div class="pp-token-enabled-link"><a href="{buildURL(#payment_method#,#show_instruction#,_ARRAY_(#method_id#^paymentMethod.getMethodId()))}">Show the instruction</a></div>

  </div>

</div>


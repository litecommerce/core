{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Moneybookers settings
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<p class="pp-common-note">{t(#Use this page to configure your store to communicate with your Payment Processing Gateway. Complete the required fields below and press the "Update" button.#)}</p>

<p class="pp-register-note">{t(#To have access to Paypal services please register here for an account if you don't have one yet#)}: <a href="{getRegisterURL()}">{getRegisterURL()}</a></p>

<hr class="pp-line" />

<form action="admin.php" method="post" class="pp-options">
  <input type="hidden" name="target" value="{target}" />
  <input type="hidden" name="action" value="save_options" />
  <input type="hidden" name="options[test]" value="1" />

  <p class="pp-account-section">{t(#Account settings#)}</p>

  <ul>

    <li>
      <span><label for="pp_vendor">{t(#Vendor#)}:</label></span>
      <input type="text" id="pp_vendor" name="options[vendor]" value="{config.CDev.Paypal.vendor}" />
      <div>{t(#Your merchant login ID that you created when you registered for the account.#)}</div>
    </li>

    <li>
      <span><label for="pp_user">{t(#User#)}:</label></span>
      <input type="text" id="pp_user" name="options[user]" value="{config.CDev.Paypal.user}" />
      <div>{t(#If you set up one or more additional users on the account, this value is the ID of the user authorized to process transactions. If, however, you have not set up additional users on the account, USER has the same as VENDOR.#)}</div>
    </li>

    <li>
      <span><label for="pp_pwd">{t(#Password#)}:</label></span>
      <input type="text" id="pp_pwd" name="options[pwd]" value="{config.CDev.Paypal.pwd}" />
      <div>{t(#The password that you defined while registering for the account.#)}</div>
    </li>

    <li>
      <span><label for="pp_partner">{t(#Partner#)}:</label></span>
      <input type="text" id="pp_partner" name="options[partner]" value="{config.CDev.Paypal.partner}" />
      <div>{t(#The ID provided to you by the authorized PayPal Reseller who registered you for the Gateway gateway. If you purchased your account directly from PayPal, use PayPal.#)}</div>
    </li>

  </ul>

  <p class="pp-account-section">{t(#Operation settings#)}</p>

  <ul>

    <li>
      <span><label for="pp_transaction_type">{t(#Action to be performed on order placement#)}:</label></span>
      <select id="pp_transaction_type" name="options[transaction_type]">
        <option value="S" selected="{isSelected(#S#,config.CDev.Paypal.transaction_type)}">{t(#Auth and Capture#)}</option>
        <option value="A" selected="{isSelected(#A#,config.CDev.Paypal.transaction_type)}">{t(#Auth only#)}</option>
      </select>
    </li>

    <li>
      <span><label for="pp_test">{t(#Test mode#)}:</label></span>
      <input type="checkbox" id="pp_test" name="options[test]" checked="{isSelected("1",config.CDev.Paypal.test)}" />
    </li>


  </ul>

  <p class="pp-order-prefix-note">{t(#You can define an order id prefix, which would precede each order number in your shop, to make it unique (each transaction id must be unique for a Paypal account)#)}</p>

  <ul>

    <li>
      <span><label for="pp_order_prefix">{t(#Order id prefix#)}:</label></span>
      <input type="text" id="pp_order_prefix" name="options[prefix]" value="{config.CDev.Paypal.prefix}" />
      <div>{t(#(optional: This options is relevant only if you share your Paypal account with other online shops)#)}</div>
    </li>

  </ul>

  <hr class="pp-line-last" />

  <widget class="\XLite\View\Button\Submit" label="Update" />

</form>


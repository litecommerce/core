{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * 2Checkout.com configuration page
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.11
 *}

<table cellspacing="1" cellpadding="5" class="settings-table">

  <tr>
    <td class="setting-name">
    <label for="settings_login">{t(#2Checkout.com account number#)}</label>
    </td>
    <td>
    <input type="text" id="settings_account" name="settings[account]" value="{paymentMethod.getSetting(#account#)}" class="field-required" />
    </td>
  </tr>

  <tr>
    <td class="setting-name">
    <label for="settings_key">{t(#Secret word#)}</label>
    </td>
    <td>
    <input type="text" id="settings_secret" name="settings[secret]" value="{paymentMethod.getSetting(#secret#)}" class="field-required" />
    </td>
  </tr>

  <tr>
    <td class="setting-name">
      <label for="settings_language">{t(#Language#)}</label>
    </td>
    <td>
      <select id="settings_language" name='settings[language]'>
        <option value="en">English</option>
        <option value="zh">Chinese</option>
        <option value="da">Danish</option>
        <option value="nl">Dutch</option>
        <option value="fr">French</option>
        <option value="gr">German</option>
        <option value="el">Greek</option>
        <option value="it">Italian</option>
        <option value="jp">Japanese</option>
        <option value="no">Norwegian</option>
        <option value="pt">Portuguese</option>
        <option value="sl">Slovenian</option>
        <option value="es_ib">European Spanish</option>
        <option value="es_la">Latin Spanish</option>
        <option value="sv">Swedish</option>
      </select>
    </td>
  </tr>

  <tr>
    <td class="setting-name">
    <label for="settings_mode">{t(#Test/Live mode#)}</label>
    </td>
    <td>
    <widget
      class="\XLite\View\FormField\Select\TestLiveMode"
      fieldId="settings_mode"
      fieldName="settings[mode]"
      fieldOnly=true
      value="{paymentMethod.getSetting(#mode#)}" />
    </td>
  </tr>

  <tr>
    <td class="setting-name">
    <label for="settings_prefix">{t(#Order prefix#)}</label>
    </td>
    <td>
    <input type="text" id="settings_prefix" value="{paymentMethod.getSetting(#prefix#)}" name="settings[prefix]" />
    </td>
  </tr>

  <tr>
    <td class="setting-name">
      <label for="settings_currency">{t(#Pricing currency#)}</label>
    </td>
    <td>
      <select id="settings_currency" name='settings[currency]'>
        <option value="ARS">Argentina Peso</option>
        <option value="AUD">Australian Dollars</option>
        <option value="BRL">Brazilian Real</option>
        <option value="GBP">British Pounds Sterling</option>
        <option value="BGN">Bulgarian Lev</option>
        <option value="CAD">Canadian Dollars</option>
        <option value="CLP">Chilean Peso</option>
        <option value="DKK">Danish Kroner</option>
        <option value="EUR">Euros</option>
        <option value="HKD">Hong Kong Dollars</option>
        <option value="INR">Indian Rupee</option>
        <option value="IDR">Indonesian Rupiah</option>
        <option value="ILS">Israeli New Shekel</option>
        <option value="JPY">Japanese Yen</option>
        <option value="LTL">Lithuanian Litas</option>
        <option value="MYR">Malaysian Ringgit</option>
        <option value="MXN">Mexican Peso</option>
        <option value="NZD">New Zealand Dollars</option>
        <option value="NOK">Norwegian Kroner</option>
        <option value="PHP">Philippine Peso</option>
        <option value="RON">Romanian New Leu</option>
        <option value="RUB">Russian Ruble</option>
        <option value="SGD">Singapore Dollar</option>
        <option value="ZAR">South African Rand</option>
        <option value="SEK">Swedish Kronor</option>
        <option value="CHF">Swiss Francs</option>
        <option value="TRY">Turkish Lira</option>
        <option value="UAH">Ukrainian Hryvnia</option>
        <option value="AED">United Arab Emirates Dirham</option>
        <option value="USD">US Dollars</option>
      </select>
    </td>
  </tr>
  <tr>
    <td colspan="2" class="note">
      {t(#To set up the integration you should follow #)}
      <a href="https://www.2checkout.com/va/acct/detail_company_info" target="_blank">{t(#"Site management"#)}</a>
      {t(# page and make sure that:#)}<br /><br />
      <ol>
        <li>
          {t(#The "Pricing currency" value must be exactly the same as on it.#)}
        </li>
        <li>
          {t(#The "Approved URL" value must be exact to "Customer front-end URL" with target = "payment_return". #)}<br />
          {t(#For example#)}:<br />
          <strong>"https://www.your-company.com/cms/lc/cart.php?target=payment_return"</strong> in the standalone LC installation.<br />
          <strong>"https://www.your-company.com/cms/store/payment_return"</strong> in the case of Drupal CMS connections.
          ("https://www.your-company.com/cms/" - your Drupal installation URL)<br />
        </li>
      </ul>
    </td>
  </tr>

</table>

<script type="text/javascript">
  jQuery("#settings_currency").val("{paymentMethod.getSetting(#currency#)}");
  jQuery("#settings_language").val("{paymentMethod.getSetting(#language#)}");
</script>

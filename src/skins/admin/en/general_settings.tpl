{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * General settings
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
<form action="admin.php" name="options_form" method="post" IF="!page=#Environment#">
  <input type="hidden" name="target" value="{target}" />
  <input type="hidden" name="action" value="update" />
  <input type="hidden" name="page" value="{page}" />
  <input type="hidden" name="moduleId" value="{moduleId}" IF="moduleId" />

  <table cellspacing="1" cellpadding="5" class="settings-table">
    {foreach:getOptions(),option}
      <tr>
        {if:!option.type=#separator#}
          {if:!option.type=#serialized#}
            <td class="setting-name" width="30%">{option.option_name:h}: </td>
          {end:}
          <td>

            {if:option.type=#checkbox#}
              <input id="{option.name}" type="checkbox" name="{option.name}" checked="{option.value=#Y#}" />
            {end:}

            {if:option.type=#text#}
              <input id="{option.name}" type="text" name="{option.name}" value="{option.value}" size="30" />
            {end:}

            {if:option.type=#country#"}
              <widget class="\XLite\View\CountrySelect" field="{option.name}" country="{option.value}" fieldId="{option.name}_select" />
            {end:}

            {if:option.type=#state#"}
              <widget class="\XLite\View\StateSelect" field="{option.name}" state="{getStateById(option.value)}" fieldId="{option.name}_select" isLinked=1 />
            {end:}

            {if:option.name=#mail_backend#}
              <select name="{option.name}">
                <option value="mail" selected="{option.value=#mail#}">mail</option>
                <option value="sendmail" selected="{option.value=#sendmail#}">sendmail</option>
                <option value="smtp" selected="{option.value=#smtp#}">smtp</option>
              </select>
            {end:}

            {if:option.type=#textarea#}
              <textarea id="{option.name}" name="{option.name}" rows=5>{option.value}</textarea>
            {end:}

            {if:option.name=#weight_unit#}
<script type="text/javascript">
<!--
function setUnitSymbol(symbol) {
  if (document.getElementById('weight_symbol') != null) {
    document.options_form.weight_symbol.value = symbol;
  }
}
-->
</script>
              <select name="{option.name}" onchange="setUnitSymbol(this.value)">
                <option value="lbs" selected="{option.value=#lbs#}">LB</option>
                <option value="oz" selected="{option.value=#oz#}">OZ</option>
                <option value="kg" selected="{option.value=#kg#}">KG</option>
                <option value="g" selected="{option.value=#g#}">G</option>
              </select>
            {end:}

            {if:option.name=#subcategories_look#}
              <select name="{option.name}">
                <option value="list" selected="{option.value=#list#}">List</option>
                <option value="icons" selected="{option.value=#icons#}">Icons</option>
              </select>
            {end:}

            {if:option.name=#thousand_delim#}
              <select name="{option.name}">
                <option value="" selected="{option.value=##}">No delimiter</option>
                <option value="," selected="{option.value=#,#}">,</option>
                <option value=" " selected="{option.value=# #}">Space</option>
              </select>
            {end:}

            {if:option.name=#decimal_delim#}
              <select name="{option.name}">
                <option value="" selected="{option.value=##}">No fractional part</option>
                <option value="." selected="{option.value=#.#}">.</option>
                <option value="," selected="{option.value=#,#}">,</option>
              </select>
            {end:}

            {if:option.name=#date_format#}
              <select name="{option.name}">
                <option value="%m/%d/%Y" selected="{option.value=#%m/%d/%Y#}">{formatDate(null,null,#%m/%d/%Y#)}</option>
                <option value="%b %e, %Y" selected="{option.value=#%b %e, %Y#}">{formatDate(null,null,#%b %e, %Y#)}</option>
                <option value="%d.%m.%Y" selected="{option.value=#%d.%m.%Y#}">{formatDate(null,null,#%d.%m.%Y#)}</option>
              </select>
            {end:}

            {if:option.name=#time_format#}
              <select name="{option.name}">
                <option value="%T" selected="{option.value=#%T#}">{formatTime(null,null,#%T#)}</option>
                <option value="%H:%M" selected="{option.value=#%H:%M#}">{formatTime(null,null,#%H:%M#)}</option>
                <option value="%I:%M %p" selected="{option.value=#%I:%M %p#}">{formatTime(null,null,#%I:%M %p#)}</option>
                <option value="%r" selected="{option.value=#%r#}">{formatTime(null,null,#%r#)}</option>
              </select>
            {end:}

            {if:option.name=#you_save#}
              <select name="{option.name}">
                <option value="N" selected="{option.value=#N#}">No</option>
                <option value="YP" selected="{option.value=#YP#}">Yes (percents)</option>
                <option value="YD" selected="{option.value=#YD#}">Yes (difference)</option>
              </select>
            {end:}

            {if:option.name=#add_on_mode_page#}
              <select name="{option.name}">
                <option value="cart.php?target=cart" selected="{option.value=#cart.php?target=cart#}">Shopping cart</option>
                <option value="cart.php" selected="{option.value=#cart.php#}">Main page</option>
              </select>
            {end:}

            {if:option.name=#clear_cc_info#}
              <select name="{option.name}">
                <option value="N" selected="{option.value=#N#}">No</option>
                <option value="P" selected="{option.value=#P#}">to Processed</option>
                <option value="C" selected="{option.value=#C#}">to Complete</option>
              </select>
            {end:}

            {if:option.name=#smtp_security#}
              <select name="{option.name}">
                <option value="ssl" selected="{option.value=#ssl#}">SSL</option>
                <option value="tls" selected="{option.value=#tls#}">TLS</option>
              </select>
            {end:}

            {if:option.name=#time_zone#}
              <select name="{option.name}">
                {foreach:timezoneslist,tz}
                  {if:option.value=##}
                    <option value="{tz}" selected="{tz=currenttimezone}">{tz}</option>
                  {else:}
                    <option value="{tz}" selected="{option.value=tz}">{tz}</option>
                  {end:}
                {end:}
              </select>
            {end:}

            <widget class="\XLite\View\ModulesManager\Settings" section="{page}" option="{option}" />

            {displayViewListContent(#general_settings.general.parts#,_ARRAY_(#page#^page,#option#^option))}

            <widget IF="option.optionComment" class="\XLite\View\Tooltip" text="{option.optionComment}" isImage="true" className="help-icon" />

          </td>

        {else:}
          <td colspan="3">
            <h2>{option.option_name:h}</h2>
          </td>
        {end:}

      </tr>

    {end:}

    {if:!page=#Environment#}
        <tr>
          <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
          <td><widget class="\XLite\View\Button\Submit" label="Submit" /></td>
          <td colspan="2">&nbsp;</td>
        </tr>
    {end:}
  </table>

</form>

{if:page=#Security#}

<h2>{t(#Safe mode#)}</h2>

<table cellspacing="1" cellpadding="5" class="settings-table">
<tr>
  <td>{t(#Safe mode access key#)}:</td>
  <td><strong>{getSafeModeKey()}</strong></td>
</tr>
<tr>
  <td>{t(#Hard reset URL (disables all modules and runs application)#)}:</td>
  <td>{getHardResetURL()}</td>
</tr>
<tr>
  <td>{t(#Soft reset URL (disables only unsafe modules and runs application)#)}:</td>
  <td>{getSoftResetURL()}</td>
</tr>
</table>
<widget class="\XLite\View\Button\Regular" label="Re-generate access key" jsCode="self.location='{buildURL(#settings#,#safe_mode_key_regen#)}'" />
<p>{t(#New access key will be also sent to the Site administrator email address#)}</p>

<h2>{t(#HTTPS check#)}</h2>

<script type="text/javascript">
<!--
/* uncheck & disable checkboxes */
var customer_security_value = document.options_form.customer_security.checked;
var full_customer_security_value = document.options_form.full_customer_security.checked;
var admin_security_value = document.options_form.admin_security.checked;
var httpsEnabled = false;

function https_checkbox_click()
{
    if (!httpsEnabled) {
        document.options_form.customer_security.checked = false;
        document.options_form.full_customer_security.disabled = true;
        document.options_form.admin_security.checked = false;
        document.getElementById("httpserror-message").style.cssText = "";
        alert("No HTTPS is available. See the red message below.");
    }
    if (document.options_form.customer_security.checked == false) {
        document.options_form.full_customer_security.checked = false;
        document.options_form.full_customer_security.disabled = true;
    }
    if (document.options_form.customer_security.checked == true)
        document.options_form.full_customer_security.disabled = false;

}

function enableHTTPS()
{
    httpsEnabled = true;

    document.options_form.customer_security.checked = customer_security_value;
    if (customer_security_value)
        document.options_form.full_customer_security.disabled = false;
    else
        document.options_form.full_customer_security.disabled = true;
    document.options_form.full_customer_security.checked = full_customer_security_value;
    document.options_form.admin_security.checked = admin_security_value;

    document.getElementById("httpserror-message").style.cssText = "";
    document.getElementById("httpserror-message").innerHTML = "<span class='success-message'>Success</span>";
}

document.options_form.customer_security.checked = false;
document.options_form.full_customer_security.checked = false;
document.options_form.full_customer_security.disabled = true;
document.options_form.admin_security.checked = false;
document.options_form.customer_security.onclick = https_checkbox_click;
document.options_form.full_customer_security.onclick = https_checkbox_click;
document.options_form.admin_security.onclick = https_checkbox_click;
-->
</script>

  {* Check if https is available *}
  Trying to access the shop at <a href="{getShopURL(#cart.php#,#1#)}">{getShopURL(#cart.php#,#1#)}</a> ...
  <span id="httpserror-message" style="visibility:hidden">
    <p class="error-message"><b>FAILED.</b> Secure connection cannot be established.</p>
    To fix this problem, do the following:
    <ul>
      <li> make sure that your hosting service provider has HTTPS protocol enabled;
      <li> verify your HTTPS settings ("https_host" parameter in the "etc/config.php" file must be valid);
      <li> reload this page.
    </ul>
  </span>

  <script type="text/javascript" src="{getShopURL(#https_check.php#,#1#)}"></script>
<script>
<!--
if (!httpsEnabled) {
    document.getElementById("httpserror-message").style.cssText = "";
}
-->
</script>

{end:}

{if:page=#Environment#}
  <widget page="Environment" template="summary.tpl" />
{end:}

<widget class="\XLite\View\ModulesManager\SettingsFooter" section="{page}" />

{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * General settings
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<form action="admin.php" name="options_form" method="POST" IF="!page=#Environment#">
  <input type="hidden" name="target" value="{target}">
  <input type="hidden" name="action" value="update">
  <input type="hidden" name="page" value="{page}">

  <table cellSpacing="2" cellpadding="2" width="100%">
    {foreach:getOptions(),option}
      <tr>
        {if:!option.type=#separator#}
          {if:!option.type=#serialized#}<td align="right" width="50%">{option.getOptionName():h}: </td>{end:}
          <td width="50%">

            {if:option.type=#checkbox#}
              {if:option.name=#captcha_protection_system#}
                {if:isGDLibLoaded()}
                  <input id="{option.name}" type="checkbox" name="{option.name}" checked="{option.value=#Y#}" />
                {else:}
                  <input id="{option.name}" type="checkbox" name="{option.name}" checked="checked" disabled="disabled" />&nbsp;<font class="ErrorMessage">GDLib isn't detected</font>
                {end:}
              {else:}
                <input id="{option.name}" type="checkbox" name="{option.name}" checked="{option.value=#Y#}" />
              {end:}
            {end:}

            {if:option.type=#text#}
              {if:option.name=#captcha_length#}
                <input id="{option.name}" type="text" name="{option.name}" value="{option.value}" size="5" />&nbsp; (more than 1 and less than 10)
              {else:}
                <input id="{option.name}" type="text" name="{option.name}" value="{option.value}" size="30" />
              {end:}
            {end:}

            {if:option.type=#country#"}
              <widget class="XLite_View_CountrySelect" field="{option.name}" country="{option.value}" fieldId="{option.name}_select" />
            {end:}

            {if:option.type=#state#"}
              <widget class="XLite_View_StateSelect" field="{option.name}" state="{option.value}" fieldId="{option.name}_select" isLinked=1 />
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

            {if:option.name=#httpsClient#}
              <select name="{option.name}">
                <option value="autodetect" selected="{option.value=#autodetect#}">Autodetect</option>
                <option value="libcurl" selected="{option.value=#libcurl#}">CURL PHP extension</option>
                <option value="curl" selected="{option.value=#curl#}">Curl external application</option>
                <option value="openssl" selected="{option.value=#openssl#}">OpenSSL external application</option>
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
                <option value="&amp;nbsp;" selected="{option.value=#&nbsp;#}">Space</option>
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
                <option value="%m/%d/%Y" selected="{option.value=#%m/%d/%Y#}">mm/dd/yyyy</option>
                <option value="%b %e, %Y" selected="{option.value=#%b %e, %Y#}">Mmm dd, yyyy</option>
                <option value="%d.%m.%Y" selected="{option.value=#%d.%m.%Y#}">dd.mm.yyyy</option>
              </select>
            {end:}

            {if:option.name=#time_format#}
              <select name="{option.name}">
                <option value="%T" selected="{option.value=#%T#}">{strftime(#%T#)}</option>
                <option value="%H:%M" selected="{option.value=#%H:%M#}">{strftime(#%H:%M#)}</option>
                <option value="%I:%M %p" selected="{option.value=#%I:%M %p#}">{strftime(#%I:%M %p#)}</option>
                <option value="%r" selected="{option.value=#%r#}">{strftime(#%r#)}</option>
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

            {if:option.name=#captcha_type#}
              <select name="{option.name}">
                <option value="numbers" selected="{option.value=#numbers#}">Numbers only</option>
                <option value="letters" selected="{option.value=#letters#}">Letters only</option>
                <option value="all" selected="{option.value=#all#}">Numbers and letters</option>
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
              {if:!timezone_changable}
                This option is not available in your PHP version.
              {else:}
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
            {end:}

            <widget class="XLite_View_ModuleSettings" section="{page}" option="{option}" />

          </td>
        {else:}
          <td colspan="2">

            <table cellspacing="0" cellpadding="0" width="100%">
              <tr>
                <td colspan=2>&nbsp;</td>
              </tr>
              <tr>
                <td class="SidebarTitle" align="center" nowrap="nowrap">&nbsp;&nbsp;&nbsp;{option.option_name:h}&nbsp;&nbsp;&nbsp;</td>
                <td width="100%">&nbsp;</td>
              </tr>
              <tr>
                <td class="SidebarTitle" align="center" colspan="2" height="3"></td>
              </tr>
            </table>

          </td>
        {end:}

      </tr>

    {end:}

    {if:page=#Captcha#}
      <tr FOREACH="enabledCaptchaPages,widget_id,v">
        {if:widget_id=#on_register#}<td align=right width="50%">On Registration page: </td>{end:}
        {if:widget_id=#on_contactus#}<td align=right width="50%">On Contact us page: </td>{end:}
        {if:widget_id=#on_add_giftcert#}<td align=right width="50%">On Add Gift Certificate page: </td>{end:}
        {if:widget_id=#on_partner_register#}<td align=right width="50%">On Registration partner page: </td>{end:}
        <td width="50%"><input type="checkbox" name="active_captcha_pages[{widget_id}]" {if:isActiveCaptchaPage(widget_id)}checked="1"{end:} /></td>
      </tr>
    {end:}

    {if:!page=#Environment#}
      {if:!page=#AdminIP#}
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td align="right"><input type="submit" value="Submit" /></td>
          <td>&nbsp;</td>
        </tr>
      {end:}
    {end:}
  </table>

</form>

{if:page=#Security#}

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
        document.getElementById("httpsErrorMessage").style.cssText = "";
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

    document.getElementById("httpsErrorMessage").style.cssText = "";
    document.getElementById("httpsErrorMessage").innerHTML = "<font class='SuccessMessage'>Success</font>";
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
  Trying to access the shop at <a href="{getShopUrl(#cart.php#,#1#)}">{getShopUrl(#cart.php#,#1#)}</a> ...
  <span id="httpsErrorMessage" style="visibility:hidden">
    <p class="ErrorMessage"><b>FAILED.</b> Secure connection cannot be established.</p>
    To fix this problem, do the following:
    <ul>
      <li> make sure that your hosting service provider has HTTPS protocol enabled;
      <li> verify your HTTPS settings ("https_host" parameter in the "etc/config.php" file must be valid);
      <li> reload this page.
    </ul>
  </span>

  <script type="text/javascript" src="{getShopUrl(#https_check.php#,#1#)}"></script>
<script>
<!--
if (!httpsEnabled) {
    document.getElementById("httpsErrorMessage").style.cssText = "";
}
-->
</script>

  <br />
  <br />
  <p>Trying to perform a background HTTPS request ...</p>
  {if:check_https(config.Security.httpsClient)=#1#}
    <p class="ErrorMessage"><b>FAILED.</b> Secure connection cannot be established.</p>
    To fix this problem, do the following:</p>
    <ul>
      <li> make sure that your hosting service provider has the HTTPS client installed and configured;
      <li> select this HTTPS client in the "HTTPS client to use" drop-down box above;
      <li> click the "Submit" button.
      <li IF="openBasedirRestriction">Curl or OpenSSl executable path: LiteCommerce attempted to find Curl or OpenSSL executable in your system automatically. Your hosting provider might need to remove the open_basedir restriction for this directory path.</li>
    </ul>
  {else:}
    <font class='SuccessMessage'>Success</font>
  {end:}

{end:}

{if:page=#Environment#}
  <widget page="Environment" template="summary.tpl" />
{end:}

{if:page=#AdminIP#}
  <widget page="AdminIP" template="waiting_ips.tpl" />
{end:}

<widget class="XLite_View_ModuleSettingsFooter" section="{page}" />

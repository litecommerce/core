{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Environment footer
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.13
 *
 * @ListChild (list="crud.settings.footer", zone="admin", weight="100")
 *}
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
    document.getElementById('httpserror-message').style.cssText = '';
}
-->
</script>

{end:}

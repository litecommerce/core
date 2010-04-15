{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
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

<widget template="js/select_states_begin_js.tpl">

<table cellSpacing=2 cellpadding=2 border=0 width="100%">
{foreach:options,option}
{if:option.name=#custom_location_state#}<tbody id="custom_location_state_body">{end:}
<TR>
{if:!option.isSeparator()}
    {if:!option.type=#serialized#}<TD align=right width="50%">{option.comment:h}: </TD>{end:}
    <TD width="50%">
    {if:option.isCheckbox()}
    {if:option.name=#captcha_protection_system#}
        {if:isGDLibLoaded()}
            <input id="{option.name}" type="checkbox" name="{option.name}" checked="{option.isChecked()}" />
        {else:}
            <input id="{option.name}" type="checkbox" name="{option.name}" checked="0" disabled="1" />&nbsp;<font class="ErrorMessage">GDLib isn't detected</font>
        {end:}
    {else:}
    <input id="{option.name}" type="checkbox" name="{option.name}" checked="{option.isChecked()}">
    {end:}
    {end:}

    {if:option.isText()}
    {if:option.name=#captcha_length#}
    <input id="{option.name}" type="text" name="{option.name}" value="{option.value}" size="5" />&nbsp; (more than 1 and less than 10)
    {else:}
    <input id="{option.name}" type="text" name="{option.name}" value="{option.value}" size=30>
    {end:}
    {end:}
    {if:option.isCountry()}
    <widget class="XLite_View_CountrySelect" field="{option.name}" value="{option.value}" onChange="javascript: populateStates(this,'location_state');" fieldId="{option.name}_select">
    {end:}
    {if:option.isState()}
		{if:option.name=#location_state#}
		<widget class="XLite_View_StateSelect" field="{option.name}" value="{option.value}" onChange="changeCompanyState(this, 'custom_location_state_body');" fieldId="{option.name}_select">
		{else:}
		<widget class="XLite_View_StateSelect" field="{option.name}" value="{option.value}" fieldId="{option.name}_select">
		{end:}
    {end:}
    {if:option.isName(#mail_backend#)}
    <select name="{option.name}">
        <option value="mail" selected="{option.isSelected(#mail#)}">mail</option>
        <option value="sendmail" selected="{option.isSelected(#sendmail#)}">sendmail</option>
        <option value="smtp" selected="{option.isSelected(#smtp#)}">smtp</option>
    </select>
    {end:}
    {if:option.isTextArea()}
    <textarea id="{option.name}" name="{option.name}" rows=5>{option.value}</textarea>
    {end:}
    {if:option.isName(#weight_unit#)}
    <script language="Javascript">
    <!--
    function setUnitSymbol(symbol) {
        if (document.getElementById('weight_symbol') != null) {
            document.options_form.weight_symbol.value = symbol;
        }
    }
    // -->
    </script>
    <select name="{option.name}" onchange="setUnitSymbol(this.value)">
        <option value="lbs" selected="{option.isSelected(#lbs#)}">LB</option>
        <option value="oz" selected="{option.isSelected(#oz#)}">OZ</option>
        <option value="kg" selected="{option.isSelected(#kg#)}">KG</option>
        <option value="g" selected="{option.isSelected(#g#)}">G</option>
    </select>
    {end:}
    {if:option.isName(#httpsClient#)}
    <select name="{option.name}">
        <option value="autodetect" selected="{option.isSelected(#autodetect#)}">Autodetect</option>
        <option value="libcurl" selected="{option.isSelected(#libcurl#)}">CURL PHP extension</option>
        <option value="curl" selected="{option.isSelected(#curl#)}">Curl external application</option>
        <option value="openssl" selected="{option.isSelected(#openssl#)}">OpenSSL external application</option>
    </select>
    {end:}
    {if:option.isName(#subcategories_look#)}
    <select name="{option.name}">
        <option value="list" selected="{option.isSelected(#list#)}">List</option>
        <option value="icons" selected="{option.isSelected(#icons#)}">Icons</option>
    </select>
    {end:}
    {if:option.isName(#thousand_delim#)}
    <select name="{option.name}">
        <option value="" selected="{option.isSelected(##)}">No delimiter</option>
        <option value="," selected="{option.isSelected(#,#)}">,</option>
        <option value="&amp;nbsp;" selected="{option.isSelected(#&nbsp;#)}">Space</option>
    </select>
    {end:}
    {if:option.isName(#decimal_delim#)}
    <select name="{option.name}">
        <option value="" selected="{option.isSelected(##)}">No fractional part</option>
        <option value="." selected="{option.isSelected(#.#)}">.</option>
        <option value="," selected="{option.isSelected(#,#)}">,</option>
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
            {if:option.value=##}<option value="{tz}" selected="{tz=currenttimezone}">{tz}</option>{else:}<option value="{tz}" selected="{option.value=tz}">{tz}</option>{end:}
            {end:}
        </select>
    {end:}
    {end:}

    <widget target="module" template="modules/{page}/settings.tpl" ignoreErrors>

    </TD>
{else:}
    <TD colspan=2>
	<table cellspacing=0 cellpadding=0 border=0 width="100%">
	<tr>
		<td colspan=2>&nbsp;</td>
	</tr>
	<tr>
		<td class="SidebarTitle" align=center nowrap>&nbsp;&nbsp;&nbsp;{option.comment:h}&nbsp;&nbsp;&nbsp;</td>
		<td width=100%>&nbsp;</td>
	</tr>
	<tr>
		<td class="SidebarTitle" align=center colspan=2 height=3></td>
	</tr>
	</table>
    </TD>
{end:}
</TR>

{if:option.name=#custom_location_state#}</tbody>{end:}
{end:}

{if:page=#Captcha#}
<tr FOREACH="enabledCaptchaPages,widget_id,v">
    {if:widget_id=#on_register#}<TD align=right width="50%">On Registration page: </TD>{end:}
    {if:widget_id=#on_contactus#}<TD align=right width="50%">On Contact us page: </TD>{end:}
    {if:widget_id=#on_add_giftcert#}<TD align=right width="50%">On Add Gift Certificate page: </TD>{end:}
    {if:widget_id=#on_partner_register#}<TD align=right width="50%">On Registration partner page: </TD>{end:}
    <TD width="50%"><input type="checkbox" name="active_captcha_pages[{widget_id}]" {if:isActiveCaptchaPage(widget_id)}checked="1"{end:} /></TD>
</tr>
{end:}

{if:!page=#Environment#}
{if:!page=#AdminIP#}
<TR><TD colspan=2>&nbsp;</TD></TR>
<TR><TD align="right"><input type="submit" value="Submit"></TD>
<TD>&nbsp;</TD></TR>
{end:}
{end:}
</table>

<widget template="js/select_states_end_js.tpl">

</form>

{if:page=#Security#}
<script>
{* uncheck & disable checkboxes *}
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
<script src="{getShopUrl(#https_check.php#,#1#)}"></script>
<script>
if (!httpsEnabled) {
    document.getElementById("httpsErrorMessage").style.cssText = "";
}
</script>

<br><br>
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
<widget page="Environment" template="summary.tpl">
{end:}
{if:page=#AdminIP#}
<widget page="AdminIP" template="waiting_ips.tpl">
{end:}

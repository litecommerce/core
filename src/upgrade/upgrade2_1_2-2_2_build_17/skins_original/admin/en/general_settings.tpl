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
    <TD align=right width="50%">{option.comment:h}: </TD>
    <TD width="50%">
    {if:option.isCheckbox()}
    <input id="{option.name}" type="checkbox" name="{option.name}" checked="{option.isChecked()}">
    {end:}
    {if:option.isText()}
    <input id="{option.name}" type="text" name="{option.name}" value="{option.value}" size=30>
    {end:}
    {if:option.isCountry()}
    <widget class="CCountrySelect" field="{option.name}" value="{option.value}" onChange="javascript: populateStates(this,'location_state');" fieldId="{option.name}_select">
    {end:}
    {if:option.isState()}
		{if:option.name=#location_state#}
		<widget class="CStateSelect" field="{option.name}" value="{option.value}" onChange="changeCompanyState(this, 'custom_location_state_body');" fieldId="{option.name}_select">
		{else:}
		<widget class="CStateSelect" field="{option.name}" value="{option.value}" fieldId="{option.name}_select">
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
        <option value="category_subcategories_list.tpl" selected="{option.isSelected(#category_subcategories_list.tpl#)}">List</option>
        <option value="category_subcategories.tpl" selected="{option.isSelected(#category_subcategories.tpl#)}">Icons</option>
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
{if:!page=#Environment#}
<TR><TD colspan=2>&nbsp;</TD></TR>
<TR><TD align="right"><input type="submit" value="Submit"></TD>
<TD>&nbsp;</TD></TR>
{end:}
</table>

<widget template="js/select_states_end_js.tpl">

</form>

{if:page=#Security#}
<script>
{* uncheck & disable checkboxes *}
var customer_security_value = document.options_form.customer_security.checked;
var admin_security_value = document.options_form.admin_security.checked;
var httpsEnabled = false;

function https_checkbox_click()
{
    if (!httpsEnabled) {
        document.options_form.customer_security.checked = false;
        document.options_form.admin_security.checked = false;
        document.getElementById("httpsErrorMessage").style.cssText = "";
        alert("No HTTPS is available. See the red message below.");
    }
}

function enableHTTPS()
{
    httpsEnabled = true;

    document.options_form.customer_security.checked = customer_security_value;
    document.options_form.admin_security.checked = admin_security_value;

    document.getElementById("httpsErrorMessage").style.cssText = "";
    document.getElementById("httpsErrorMessage").innerHTML = "<p class='SuccessMessage'>Success";
}

document.options_form.customer_security.checked = false;
document.options_form.admin_security.checked = false;
document.options_form.customer_security.onclick = https_checkbox_click;
document.options_form.admin_security.onclick = https_checkbox_click;
</script>
{* Check if https is available *}
Trying to access the shop at <a href="{shopUrl(#cart.php#,#1#)}">{shopUrl(#cart.php#,#1#)}</a> ...
<div id="httpsErrorMessage" style="visibility:hidden">
<p class="ErrorMessage">
<b>FAILED.</b> Secure connection cannot be established.<br><br>
To fix this problem, do the following:
<ul class="ErrorMessage">
<li> make sure that your hosting service provider has HTTPS protocol enabled;
<li> verify your HTTPS settings ("https_host" parameter in the "etc/config.php" file must be valid);
<li> reload this page.
</ul>
</div>
<script src="{shopUrl(#https_check.php#,#1#)}"></script>
<script>
if (!httpsEnabled) {
    document.getElementById("httpsErrorMessage").style.cssText = "";
}
</script>
{end:}
{if:page=#Environment#}
<widget page="Environment" template="summary.tpl">
{end:}


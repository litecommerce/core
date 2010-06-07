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
<script type="text/javascript">
<!-- 

function visibleBox(id, status)
{
	var Element = document.getElementById(id);
    if (Element) {
    	Element.style.display = ((status) ? "" : "none");
    }
}

function ShowNotes()
{
	visibleBox("notes_url", false);
    visibleBox("notes_body", true);
}

function setChecked(form, input, check, key)
{
    var elements = document.forms[form].elements[input];

	if ( elements.length > 0 ) {
	    for (var i = 0; i < elements.length; i++) {
    	    elements[i].checked = check;
	    }
	} else {
		elements.checked = check;
	}
	if (key) {
		checkUpdated(key);
	}
}

function checkUpdated(key)
{
	var Element = document.getElementById("update_button_"+key);
    if (Element) {
    	Element.className = "DialogMainButton";
    }
}

function setHeaderChecked(key)
{
	var Element = document.getElementById("activate_modules_"+key);
    if (Element && !Element.checked) {
    	Element.checked = true;
    }
}

// -->
</script>

Use this section to manage add-on components of your online store.
<span id="notes_url" style="display:"><a href="javascript:ShowNotes();" class="NavigationPath" onClick="this.blur()"><b>How to use this section &gt;&gt;&gt;</b></a></span>
<span id="notes_body" style="display: none"><p align="justify">Activate a module and click on its title or <img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> icon to configure it</p>
</span>
<hr>
<p class="adminParagraph"><b class="Star">Warning:</b> It is strongly recommended that you close the shop for maintenance on the <a href="admin.php?target=settings"><u>General settings</u></a> page before performing any operations on this page!</p>

<span class="ErrorMessage" IF="xlite.mm.error">{xlite.mm.error:h}<br></span>

<span IF="xlite.mm.errorBrokenDependencies">
<p class="ErrorMessage">&gt;&gt; Unable to {action} module {xlite.mm.moduleName} &lt;&lt;</p>
<p>There are depending modules found</p>
<li FOREACH="xlite.mm.errorDependencies,dep">{dep:h}</li>
<p>Please {action} the depending modules first</p>
<br>
</span>

<span IF="xlite.mm.brokenDependencies">
<p class="ErrorMessage">&gt;&gt; Cannot initialize some module(s): dependency modules are not available &lt;&lt;</p>
</span>

{if:xlite.mm.safeMode}
<p>
<font class="ErrorMessage">&gt;&gt; Modules information is not available in safe mode &lt;&lt;</font>
<br><br>
<a IF="xlite.session.safe_mode&!config.General.safe_mode" href="{url}&safe_mode=off&auth_code={xlite.options.installer_details.auth_code}"><u><b>Turn OFF temporarily enabled safe mode</b></u></a>
</p>
{else:}
<p />You have <b>{xlite.mm.getActiveModulesNumber()}</b> module{if:!xlite.mm.getActiveModulesNumber()=#1#}s{end:} activated.</p>
{end:}

<table cellpadding="0" cellspacing="0" border="0" width="100%">

{* Display payment modules *}
<widget template="modules_body.tpl" caption="Payment modules" key="1" IF="getModules(#1#)" />

{* Display shipping modules *}
<widget template="modules_body.tpl" caption="Shipping modules" key="2" IF="getModules(#2#)" />

{* Display connector modules *}
<widget template="modules_body.tpl" caption="Connectors" key="4" IF="getModules(#4#)" />

{* Display regular modules *}
<widget template="modules_body.tpl" caption="Add-ons" key="5" IF="getModules(#5#)" />

{* Display 3rd party modules *}
<widget template="modules_body.tpl" caption="3rd party modules" key="6" IF="getModules(#6#)" />

</table>

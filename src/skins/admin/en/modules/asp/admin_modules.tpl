<script language="Javascript">
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

<p IF="!xlite.mm.modules"><b>&gt;&gt;&nbsp;You have no modules installed&nbsp;&lt;&lt;</b></p>
<p IF="xlite.mm.modules">You have <b>{xlite.mm.modulesNumber}</b> module{if:!xlite.mm.modulesNumber=#1#}s{end:} installed and <b>{xlite.mm.activeModulesNumber}</b> module{if:!xlite.mm.activeModulesNumber=#1#}s{end:} activated.</p>

<table cellpadding="0" cellspacing="0" border="0" width="100%">

{* Display payment modules *}

<tbody IF="getSortModules(#8#)">
<widget template="modules_body.tpl" caption="Commercial payment modules" key="8">
</tbody>


{* Display shipping modules *}

<tbody IF="getSortModules(#4#)">
<widget template="modules_body.tpl" caption="Commercial shipping modules" key="4">
</tbody>


{* Display commercial modules *}

<tbody IF="getSortModules(#2#)">
{if:getSortModules(#8#)|getSortModules(#4#)}
<widget template="modules_body.tpl" caption="Other commercial modules" key="2">
{else:}
<widget template="modules_body.tpl" caption="Commercial modules" key="2">
{end:}
</tbody>


{* Display commercial skin modules *}

<tbody IF="getSortModules(#16#)">
<widget template="modules_body.tpl" caption="Commercial skin modules" key="16">
</tbody>


{* Display free modules *}

<tbody IF="getSortModules(#1#)">
<widget template="modules_body.tpl" caption="Free modules" key="1">
</tbody>


{* Display 3rd party modules *}

<tbody IF="getSortModules(#4096#)">
<widget template="modules_body.tpl" caption="3rd party modules" key="4096">
</tbody>

</table>

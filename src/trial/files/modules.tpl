This section is used to enable/disable functional parts of your store. Click on <img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> icon below to configure the corresponding add-on.
<hr>
<span class="ErrorMessage">{error}<br></span>

<span IF="xlite.mm.errorBrokenDependencies">
<p class="ErrorMessage">&gt;&gt; Unable to {action} module {xlite.mm.moduleName} &lt;&lt;</p>
<p>There are depending modules found</p>
<li FOREACH="xlite.mm.errorDependencies,dep">{dep:h}</li>
<p>Please {action} the depending modules first</p>
<br>
</span>

{if:xlite.mm.safeMode}
<p>
<font class="ErrorMessage">&gt;&gt; Modules information is not available in safe mode &lt;&lt;</font>
<br><br>
<a IF="xlite.session.safe_mode" href="{url}&safe_mode=off"><u><b>Turn OFF safe mode</b></u></a>
</p>
{else:}
<p IF="!xlite.mm.modules"><b>&gt;&gt;&nbsp;You have no modules installed&nbsp;&lt;&lt;</b></p>
<p IF="xlite.mm.modules">You have <b>{xlite.mm.modulesNumber}</b> module{if:!xlite.mm.modulesNumber=#1#}s{end:} installed and <b>{xlite.mm.activeModulesNumber}</b> module{if:!xlite.mm.activeModulesNumber=#1#}s{end:} activated.</p>
{end:}

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

</table>

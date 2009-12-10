{if:dialog.modulesUpdatesNumber}
<script language="Javascript">
<!-- 

function ShowUpdNotes()
{
	visibleBox("updnotes_url", false);
    visibleBox("updnotes_body", true);
}

// -->
</script>

Update Manager has found <b>{dialog.modulesUpdatesNumber}</b> add-on module{if:!dialog.modulesUpdatesNumber=#1#}s{end:} which ha{if:dialog.modulesUpdatesNumber=#1#}s{else:}ve{end:} {if:dialog.modulesUpdatesNumber=#1#}a {end:} newer version{if:!dialog.modulesUpdatesNumber=#1#}s{end:} available.
<span id="updnotes_url" style="display:"><a href="javascript:ShowUpdNotes();" class="NavigationPath" onClick="this.blur()"><b>More details &gt;&gt;&gt;</b></a></span>
<span id="updnotes_body" style="display: none"><br>
A module which has a newer version available is marked by the <span class="NavigationPath"><b>(!)</b></span> sign.<br>
Click on that sign to log in to your <a href="http://litecommerce.com/fwd.html?url=https://secure.qualiteam.biz" target="_blank" onClick="this.blur()" class="NavigationPath"><b>Support helpdesk</b></a> account and download and install a newer version of a module.</p>
</span>
<p>
{end:}

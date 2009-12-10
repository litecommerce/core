<p class="TabHeader">License</p>
<p>This section provides the details of your LiteCommerce ASPE Control Center license certificate.

<br><br><br>

<!-- install/update diagnostig -->
<span IF="error" class="ErrorMessage">Unable to update License Certificate! </span>
<span IF="error=#invalid_license#" class="ErrorMessage">License Certificate is invalid.</span>
<span IF="error=#permission_denied#" class="ErrorMessage">Can not open file LICENSE for writing.</span>
<span class="SuccessMessage" IF="success">The license was installed successfully</span>

<p>

<font IF="!xlite.license.license_no" class="ErrorMessage">&gt;&gt; You have no License Certificate installed &lt;&lt;</font>

<table border="0" cellpadding=0 cellspacing=0>
<tr><td class="TableHead">
<table id="license_data" IF="xlite.license.license_no" border="0"  cellpadding=3 cellspacing=1>
<tr class="Center"><td nowrap>License ID</td><td>{xlite.license.license_no}</td></tr>
<tr class="Center" IF="name"><td>Issued for</td><td>{xlite.license.name}</td></tr>
<tr class="Center"><td>Domain<span IF="multiDomains">s</span></td><td>{domainString}</td></tr>
<tr class="Center"><td>Version</td><td>{xlite.license.version}</td></tr>
<tr class="Center"><td>Expires</td><td>{if:xlite.license.expire}{date_format(xlite.license.expire)}{else:}Never{end:}</td></tr>
<tr class="Center"><td>Type</td><td>{xlite.license.type}</td></tr>
<tr class="Center"><td>Issue date</td><td>{date_format(xlite.license.issue_date)}</td></tr>
<tr class="Center"><td>Maximum number of shops</td><td>{xlite.license.N}</td></tr>
<tr IF="xlite.license.modules" class="Center">
    <td valign="top"><br>Modules</td><td>
    <table border="0">
	<tr>
		<th align="left">Name</th>
		<th align="left">Expiration</th>
	</tr>
    <tr FOREACH="xlite.license.modules,module">
        <td>{module.name}</td>
        <td align="center">{if:module.expiration}{date_format(module.expiration)}{else:}Never{end:}</td>
    </tr>
    </table>
    </td>
</tr>
</table>
</td></tr>
</table>

<p>
<br>
<p  class="AdminTitle">Install / Update license</p>

<form action="cpanel.php" method="POST" enctype="multipart/form-data">
<input type="hidden" name="target" value="license">
<input type="hidden" name="action" value="setup">
<p>
License file: 
<input type="file" name="license_file">
<p>
License text:<br>
<textarea name="license" cols="82" rows="20" style="FONT-FAMILY: 'Courier New', Courier; FONT-SIZE: 12px;">
</textarea>
<br><br>
<input type="submit" value=" Update license " class="DialogMainButton" onClick="this.blur();">
</form>


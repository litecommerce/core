Use this section to review your LiteCommerce software license and update it if necessary.<hr>

<!-- install/update diagnostig -->
<span IF="error" class="ErrorMessage">Unable to install/update License Certificate! </span>
<span IF="error=#invalid_license#" class="ErrorMessage">License Certificate is invalid.</span>
<span IF="error=#permission_denied#" class="ErrorMessage">Can not open file LICENSE for writing.</span>
<span class="SuccessMessage" IF="success">The license was installed successfully</span>

<p>

<font IF="!xlite.license.license_no" class="ErrorMessage">&gt;&gt; You have no License Certificate installed &lt;&lt;</font>

<table border="0" cellpadding=0 cellspacing=0>
<tr><td class="TableHead">
<table id="license_data" IF="xlite.license.license_no" border="0"  cellpadding=3 cellspacing=1>
<tr class="Center"><td style="font-weight: bold;" nowrap>License #</td><td>{xlite.license.license_no}</td></tr>
<tr class="Center" IF="name"><td style="font-weight: bold;">Issued for</td><td>{xlite.license.name}</td></tr>
<tr class="Center"><td style="font-weight: bold;">Domain</td><td>{xlite.license.domain}</td></tr>
<tr class="Center"><td style="font-weight: bold;">Version</td><td>{xlite.license.version}</td></tr>
<tr class="Center"><td style="font-weight: bold;">Expires</td><td>{if:xlite.license.expire}{date_format(xlite.license.expire)}{else:}Never{end:}</td></tr>
<tr class="Center"><td style="font-weight: bold;">Type</td><td>{xlite.license.type}</td></tr>
<tr class="Center"><td style="font-weight: bold;" nowrap>Issue date</td><td>{date_format(xlite.license.issue_date)}</td></tr>
<tr IF="xlite.license.modules" class="Center">
    <td valign="top" style="font-weight: bold;"><br>Modules</td><td>
    <table border="0"><tr class="TableHead"><th>Name</th><th>Expiration</th></tr>
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
<p class="AdminTitle">Install / Update license</p>

<form action="admin.php" method="POST" enctype="multipart/form-data">
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
<input type="submit" value=" Install/Update license ">
</form>


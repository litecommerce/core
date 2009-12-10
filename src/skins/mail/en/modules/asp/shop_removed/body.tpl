<p><b>LiteCommerce shop has been removed/uninstalled from the following URL:<br>
{shop.url}</b>
<span IF="showInfo">
<p>Shop info:
<p>
<table border=0 cellpadding=4>
<tr><td>Version:</td><td>{config.Version.version}</td></tr>
<tr><td>Installation path:</td><td>{shop.path}</td></tr>
<tr><td>Mysql host:</td><td>{shop.localConfig.database_details.hostspec}</td></tr>
<tr><td>Mysql database:</td><td>{shop.localConfig.database_details.database}</td></tr>
<tr><td>Mysql user:</td><td>{shop.localConfig.database_details.username}</td></tr>
<tr><td>Mysql password:</td><td>{shop.localConfig.database_details.password}</td></tr>
</table>
</span>
<p>{signature:h}

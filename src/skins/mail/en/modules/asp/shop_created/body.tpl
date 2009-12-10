<p><b>New LiteCommerce shop has been installed successfully at the following URL:<br>
{shop.url}</b>
<p>Shop info:
<p>
<table border=0 cellpadding=4>
<tr><td>Version:</td><td>{config.Version.version}</td></tr>
<tr><td>Admin zone URL:</td><td><a href="{shop.url:h}/admin.php">{shop.url:h}/admin.php</a></td></tr>
<tr><td>Customer zone URL:</td><td><a href="{shop.url:h}/cart.php">{shop.url:h}/cart.php</a></td></tr>
<tr><td>Admin login:</td><td>{adminUser}</td></tr>
<tr><td>Admin password:</td><td>{adminPassword}</td></tr>
<tr><td>Installation path:</td><td>{shop.path}</td></tr>
<tr><td>Mysql host:</td><td>{shop.localConfig.database_details.hostspec}</td></tr>
<tr><td>Mysql database:</td><td>{shop.localConfig.database_details.database}</td></tr>
<tr><td>Mysql user:</td><td>{shop.localConfig.database_details.username}</td></tr>
<tr><td>Mysql password:</td><td>{shop.localConfig.database_details.password}</td></tr>
</table>

<p>{signature:h}

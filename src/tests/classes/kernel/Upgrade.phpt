<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";
require_once "kernel/Upgrade.php";

class UpgradeTest extends PHPUnit_TestCase
{
    function UpgradeTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

	function test_upgrade()
	{
		ini_set("include_path", "tests/classes/kernel/Upgrade" . PATH_SEPARATOR . ini_get("include_path"));
		$u = new Upgrade;
		$u->set("from_ver", "100.2.3");
		$u->set("to_ver", "100.3.4");
		global $config;
		$ver = $config->Version->version;
		$config->Version->version = "100.2.3";
		$fd = fopen("tests/classes/kernel/Upgrade/a", "w");
		fclose($fd);
		@rmdir("tests/classes/kernel/Upgrade/b");
		$u->connection->query("DROP TABLE IF EXISTS xlite_upgrade_test_table");
		$u->delete();
		$u->setInteractive(false);
		$u->doUpgrade();
		$this->assertFalse($u->failed);
		$this->assertTrue(is_dir("tests/classes/kernel/Upgrade/b"));
		$this->assertEquals("asd", trim(file_get_contents("tests/classes/kernel/Upgrade/a")));
		$connection = Database::getConnection();
		$this->assertTrue($connection->isTableExists("xlite_upgrade_test_table"));
		$c = new Config;
		$c->set("category", "Version");
		$c->set("name", "version");
		$this->assertEquals("100.3.4", $c->get("value"));
		$c->set("value", $ver);
		$c->update();
		$u->connection->query("DROP TABLE IF EXISTS xlite_upgrade_test_table");
		@rmdir("tests/classes/kernel/Upgrade/b");
		@unlink("tests/classes/kernel/Upgrade/a");
		$u->delete();
	}

	function test_patchFile()
	{
		$u = new Upgrade;
		$file = "tests/classes/kernel/Upgrade/f";
		$fd = fopen($file, "w");
		fclose($fd);
		$u->patchFile($file, array());
		$this->assertEquals("", trim(file_get_contents($file)));
		$u->patchFile($file, array(
			array("insert start", "First Line\nNextLine"),
			array("insert end", "   Last line")));
		$this->assertEquals("First Line\nNextLine\n   Last line", trim(file_get_contents($file)));
		$u->patchFile($file, array(
			array("remove", "First Line"),
			array("replace", "Last line", "Last\nline")));
		$this->assertEquals("NextLine\nLast\nline", trim(file_get_contents($file)));
		@unlink($file);
	}
}


$suite = new PHPUnit_TestSuite("UpgradeTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>

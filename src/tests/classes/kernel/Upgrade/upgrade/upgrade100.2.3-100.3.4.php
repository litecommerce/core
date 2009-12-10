<?
	$this->copyFile("tests/classes/kernel/Upgrade/c", "tests/classes/kernel/Upgrade/a");
	$this->createDir("tests/classes/kernel/Upgrade/b");
	$this->createDir("tests/classes/kernel/Upgrade/b"); // 'already exists' message
	$this->patchSQL("create table xlite_upgrade_test_table(a int)");
?>

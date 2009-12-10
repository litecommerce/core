<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";

class DatabaseTest extends PHPUnit_TestCase
{
    var $db;

    function DatabaseTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
        global $xlite;
        $this->db =& $xlite->get('db');
    }

    function tearDown()
    {
    }

    function testGetConnection()
    {
        $c =& $this->db;
		$count = $c->getOne("select count(*) from xlite_countries");
        $this->assertTrue($count>50);
    }

    function testgetTableByAlias()
    {
        $table = "xlite_countries";
        $result = $this->db->getTableByAlias("countries");
        $this->assertEquals($table, $result); 
    }

    function testCreateTable()
    {
        $name = "xlite_test_table";
        $drop = "DROP TABLE IF EXISTS $name";
        $sql = "CREATE table $name (id int(1), name varchar(10))";
        $c =& $this->db;
        $c->query($drop);
        $this->assertTrue($c->createTable($name, $sql, false));
    }

    function testCreateIndex()
    {
        $name = "xlite_test_table";
        $drop = "ALTER TABLE $name DROP INDEX id";
        $sql = "ALTER TABLE $name ADD INDEX (id)";
        $c =& $this->db;
        if ($c->isIndexExists("id", $name)) {
            $c->query($drop);
        }    
        $this->assertTrue($c->createIndex("id", $name, $sql, false));
    }

    function testQuery()
    {
        $name = "xlite_test_table";
        $sql = "INSERT INTO $name VALUES (1, 'John')";
        $c =& $this->db;
        $this->assertTrue($c->query($sql));
    }

    function testgetOne()
    {
        $name = "xlite_test_table";
        $sql = "SELECT id FROM $name WHERE name='John'";
        $c =& $this->db;
        $this->assertTrue($c->getOne($sql) == 1);
    }

    function testGetRow()
    {
        $name = "xlite_test_table";
        $sql = "SELECT * FROM $name WHERE id = 1";
        $c =& $this->db;
        $this->assertTrue(count($c->getRow($sql)) == 2);
    }

    function testgetAll()
    {
        $name = "xlite_test_table";
        $sql = "SELECT * FROM $name";
        $c =& $this->db;
        $this->assertTrue(count($c->getAll($sql)));
    }

    function testDropTable()
    {
        $name = "xlite_test_table"; 
        $c =& $this->db;
        $c->dropTable($name, false);
        $this->assertTrue(true);
    }
}

$suite = new PHPUnit_TestSuite("DatabaseTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>

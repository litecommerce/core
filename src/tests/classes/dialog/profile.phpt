<?
require_once "tests/classes/config.php";
require_once "PHPUnit.php";
require_once "base/Dialog.php";
require_once "dialog/profile.php";
require_once "view/Main.php";

class Dialog_profileTest extends PHPUnit_TestCase
{
	var $dialog;

	function Dialog_profileTest($name)
	{
		$this->PHPUnit_TestCase($name);
		new View_Main;
	}
	function setUp()
	{
		$this->dialog =& new Dialog_profile;
	}
	function tearDown()
	{
		unset($this->dialog);
	}	
	function testValidate()
	{
    $this->dialog->login = "ndv@rrf.ru";
    $this->dialog->password = "123";
    $this->dialog->confirm_password = "123";
    $this->dialog->password_hint ="";
    $this->dialog->password_hint_answer ="";
    $this->dialog->billing_title = "Mr.";
    $this->dialog->billing_firstname = "123";
    $this->dialog->billing_lastname = "123";
    $this->dialog->billing_company ="";
    $this->dialog->billing_phone = "123";
    $this->dialog->billing_fax ="";
    $this->dialog->billing_address = "123";
    $this->dialog->billing_city = "123";
    $this->dialog->billing_state = "DE";
    $this->dialog->billing_country = "AW";
    $this->dialog->billing_zipcode = "123";
    $this->dialog->shipping_title = "Mr.";
    $this->dialog->shipping_firstname = "123";
    $this->dialog->shipping_lastname = "123";
    $this->dialog->shipping_company ="";
    $this->dialog->shipping_phone = "123";
    $this->dialog->shipping_fax ="";
    $this->dialog->shipping_address = "123";
    $this->dialog->shipping_city = "123";
    $this->dialog->shipping_state = "DE";
    $this->dialog->shipping_country = "AI";
    $this->dialog->shipping_zipcode = "123";
		$this->assertTrue($this->dialog->isValid("Register"));
		if (isset($this->invalidFieldName)) {
			print "invalid field name ".$this->dialog->invalidFieldName;
		}	
	}
}

$suite = new PHPUnit_TestSuite("Dialog_profileTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>

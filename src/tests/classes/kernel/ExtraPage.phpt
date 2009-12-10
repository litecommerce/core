<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";
require_once "kernel/ExtraPage.php";

class ExtraPageTest extends PHPUnit_TestCase
{
    function ExtraPagesTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
		$this->ep = new ExtraPage;
		$this->ep->pagesTemplate = "pages.tpl";
		$this->ep->locationTemplate = "location.tpl";
		$this->ep->menuTemplate = "help.tpl";
		$this->ep->templatePrefix = "";
		// empty the files
		$this->ep->createFile("pages.tpl");
		$this->ep->createFile("location.tpl");
		$this->ep->createFile("help.tpl");
    }

    function tearDown()
    {
		@unlink('this_is_a_test_page__.tpl');
		@unlink('pages.tpl');
		@unlink('location.tpl');
		@unlink('help.tpl');
    }

	function test_getPages()
	{
		$pages =<<<EOT
{page1.display(#Title1#)}
{page2.display(#Title2#)}

EOT;
		$this->ep->createFile("pages.tpl", $pages);

		$pages = $this->ep->getPages();
		$tst = array();
		foreach ($pages as $p) {
			$tst[] = $p->page . "-" . $p->title;
		}
		$this->assertEquals(array("page1-Title1", "page2-Title2"), $tst);
	}

	function test_add()
	{
		// add a page
		$this->ep->title = "This is a Test Page !";
		$this->ep->content = "A content of a page test<p>";
		$this->ep->add();
		$this->assertFile($this->ep->content, "this_is_a_test_page__.tpl");
		$this->assertFile("{this_is_a_test_page__.display(#This is a Test Page !#)}", "pages.tpl");
		$this->assertFile('<span IF="this_is_a_test_page__" class="NavigationPath">&nbsp;::&nbsp;This is a Test Page !</span>', "location.tpl");
		$this->assertFile('<FONT class="SidebarItems"><a href="cart.php?page=this_is_a_test_page__" class="SidebarItems">This is a Test Page !</a></FONT><br>', "help.tpl");
	}

	function test_replaceLine()
	{
		$test =<<<EOT
 line 1
line 222


EOT;
		$this->ep->createFile($testfile = "TEST", $test);
		$this->ep->replaceLine("/line.*1/", "1", $testfile);
		$this->ep->replaceLine("/^line/", "2", $testfile);
		$this->ep->replaceLine("/^\$/", "3", $testfile);
		$this->assertFile("1\n2\n3", $testfile);
	}
	
	function test_modify()
	{
		$this->test_add();
		$this->ep->title = "Changed Title";
		$this->ep->content = "Changed Content";
		$this->ep->modify();
		$this->assertFile($this->ep->content, "this_is_a_test_page__.tpl");
		$this->assertFile("{this_is_a_test_page__.display(#".$this->ep->title."#)}", "pages.tpl");
		$this->assertFile('<span IF="this_is_a_test_page__" class="NavigationPath">&nbsp;::&nbsp;'.$this->ep->title.'</span>', "location.tpl");
		$this->assertFile('<FONT class="SidebarItems"><a href="cart.php?page=this_is_a_test_page__" class="SidebarItems">'.$this->ep->title.'</a></FONT><br>', "help.tpl");
	}

	function test_remove()
	{
		$this->test_add();
		$this->ep->remove();
		$this->assertFile("", "pages.tpl");
		$this->assertFile("", "location.tpl");
		$this->assertFile("", "help.tpl");
	}

	function assertFile($content, $file)
	{
		$content1 = trim(file_get_contents($file));
		$this->assertEquals($content, $content1);
	}

}


$suite = new PHPUnit_TestSuite("ExtraPageTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>

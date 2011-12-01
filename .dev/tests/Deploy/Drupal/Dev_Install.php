<?php

require_once realpath(__DIR__ . "/../../") . DIRECTORY_SEPARATOR . "local.php";

set_include_path(get_include_path() . PATH_SEPARATOR . XLITE_DEV_LIB_DIR . DIRECTORY_SEPARATOR . "phpunit-selenium/");

require_once "PHPUnit/Extensions/SeleniumTestCase.php";
require_once "PHPUnit/Extensions/SeleniumTestCase/Driver.php";

class Dev_Install extends PHPUnit_Extensions_SeleniumTestCase{

    public function setUp(){
        $this->setBrowser("*firefox");
        $this->setBrowserUrl(SELENIUM_SOURCE_URL . "/");
        $this->setHost(SELENIUM_SERVER);
        $this->setPort(4444);
    }

    public function testInstall(){
        $this->open("dev_install.php");
        sleep(30);
        while (true){
            if($this->isTextPresent("Congratulations, you installed Ecommerce CMS!"))
                break;
            sleep(10);
        }
        //$this->waitForElementPresent("//a[text()='Visit your new site']");
    }

}
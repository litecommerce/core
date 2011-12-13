<?php

require_once "local.php";

set_include_path(get_include_path() . PATH_SEPARATOR . XLITE_DEV_LIB_DIR . DIRECTORY_SEPARATOR . "phpunit-selenium/");

require_once "PHPUnit/Extensions/SeleniumTestCase.php";
require_once "PHPUnit/Extensions/SeleniumTestCase/Driver.php";

class Dev_Install extends PHPUnit_Extensions_SeleniumTestCase{

    static $isCacheRebuilt;

    public function setUp()
        {
            $this->setBrowser("*firefox");
            if (self::$isCacheRebuilt)
                $this->setBrowserUrl(SELENIUM_SOURCE_URL . "/");
            else
                $this->setBrowserUrl(SELENIUM_SOURCE_URL_ADMIN . "/");
            $this->setHost(SELENIUM_SERVER);
            $this->setPort(4444);
        }

        public function testCacheRebuild()
        {

            $this->open('admin.php');

            //sleep(30);
            for($i = 0; $i < 1000; $i++) {
                if ($this->isTextPresent("Administration Zone"))
                    break;
                sleep(10);
            }
            self::$isCacheRebuilt = true;

        }

        public function testInstall()
        {
            $this->open("dev_install.php");
            if ($this->isTextPresent("LiteCommerce software is not installed"))
                return;
            sleep(30);
            for ($i = 0; $i < 200; $i++) {
                if ($this->isTextPresent("Congratulations, you installed Ecommerce CMS!"))
                    break;
                sleep(10);
            }
            //$this->waitForElementPresent("//a[text()='Visit your new site']");
        }

}
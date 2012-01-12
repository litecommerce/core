<?php

require_once "local.php";

set_include_path(get_include_path() . PATH_SEPARATOR . XLITE_DEV_LIB_DIR . DIRECTORY_SEPARATOR . "phpunit-selenium/");

require_once "PHPUnit/Extensions/SeleniumTestCase.php";
require_once "PHPUnit/Extensions/SeleniumTestCase/Driver.php";
/**
 * LiteCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 *
 * @category   LiteCommerce
 * @package    Tests
 * @subpackage Web
 * @author     Creative Development LLC <info@cdev.ru>
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 *
 */
class Dev_Install extends PHPUnit_Extensions_SeleniumTestCase{



    public function setUp()
        {
            $this->setBrowser("*firefox");
            $this->setBrowserUrl(SELENIUM_SOURCE_URL . "/");
            $this->setHost(SELENIUM_SERVER);
            $this->setPort(4444);
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
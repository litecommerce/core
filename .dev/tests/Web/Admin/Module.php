<?php
// vim: set ts=4 sw=4 sts=4 et:

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
 * @version    GIT: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

require_once __DIR__ . '/AAdmin.php';

class XLite_Web_Admin_Module extends XLite_Web_Admin_AAdmin
{
    const BUTTON_ENTER_LICENSE_KEY = '//button[@class="popup-button"]/span[text()="Enter license key"]';

    const INPUT_KEY = '//div[@class="addon-key"]/input[@type="text" and @name="key"]';

    const BUTTON_KEY = '//button[@type="submit"]/span[text()="Validate key"]';


    public function testEnterLicenseKeyBlock()
    {
        $this->logIn();

        $this->open('admin.php?target=addons_list');

        $this->assertElementPresent(
            self::BUTTON_ENTER_LICENSE_KEY,
            'No enter license key'
        );

        $this->click(self::BUTTON_ENTER_LICENSE_KEY);

        $this->waitForAJAXProgress();        

        $this->assertElementPresent(
            self::INPUT_KEY,
            'No input for license key'
        );

        $this->assertElementPresent(
            self::BUTTON_KEY,
            'No validate key button'
        );


    }

    public function testEnterLicenseKeyBlockOpen()
    {

    }

}

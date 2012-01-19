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
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 *
 * @block_all
 */

require_once __DIR__ . '/AAdmin.php';

/**
 * XLite_Web_Admin_Module 
 *
 * @see   ____class_see____
 * @since 1.0.15
 */
class XLite_Web_Admin_Module extends XLite_Web_Admin_AAdmin
{
/*    const BUTTON_ENTER_LICENSE_KEY = '//button[@class="popup-button"]/span[text()="Enter license key"]';
    const INPUT_KEY = '//div[@class="addon-key"]/input[@type="text" and @name="key"]';
    const BUTTON_KEY = '//button[@type="submit"]/span[text()="Validate key"]';*/

    /**
     * setUp
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function setUp()
    {
        parent::setUp();

        $this->markTestSkipped('Awaiting for...');

        /*\Includes\Utils\FileManager::copyRecursive(
            dirname(__FILE__) . LC_DS . '..' . LC_DS . '..' . LC_DS . '..' . LC_DS . 'test_modules' . LC_DS . 'Test',
            LC_DIR_MODULES . 'Test'
        );*/
    }

    /**
     * tearDown
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function tearDown()
    {
        // \Includes\Utils\FileManager::unlinkRecursive(LC_DIR_MODULES . 'Test');

        parent::tearDown();
    }

    /**
     * testModulesManage
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.15
     */
    public function testModulesManage()
    {
        $this->skipCoverage();
        $this->logIn();
        $this->open('admin.php?target=addons_list_installed');

        /*$this->assertElementPresent(
            '//tr[@class="module-11 disabled"]/td[@class="module-main-section"]/div[@class="actions"]/span[@class="disabled" and text()="Enable"]',
            'Enable is not a text label'
        );

        $this->assertElementPresent(
            '//tr[@class="module-11 disabled"]/td[@class="module-main-section"]/div/div[@class="note version error"]/button/span[text()="Upgrade"]',
            'Upgrade button error'
        );

        $this->assertElementPresent(
            '//tr[@class="module-12 disabled"]/td[@class="module-main-section"]/div/div[@class="note version error"]/button/span[text()="Upgrade core"]',
            'Upgrade core button error'
        );

        $this->assertElementPresent(
            '//tr[@class="module-13 disabled"]/td[@class="module-main-section"]/div/div[@class="note dependencies"]/ul/li/a[text()="Test module 1 (by Test)"]',
            'test module 1 dependency is absent'
        );

        $this->assertElementPresent(
            '//tr[@class="module-13 disabled"]/td[@class="module-main-section"]/div/div[@class="note dependencies"]/ul/li/a[text()="Test module 2 (by Test)"]',
            'test module 2 dependency is absent'
        );*/

    }


    /**
     * testEnterLicenseKeyBlock
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.15
     */
    /*public function testEnterLicenseKeyBlock()
    {
        $this->logIn();

        $this->open('admin.php?target=addons_list_marketplace');

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

        $this->type(self::INPUT_KEY, 'test1');

        // Register License key
        $this->click(self::BUTTON_KEY);

        $this->waitForPageToLoad(60000);

        $moduleId = \XLite\Core\Database::getRepo('\XLite\Model\Module')->findOneBy(
            array(
                'name'   => 'MegaModule48',
                'author' => 'Igoryan',
            )
        )->getModuleId();

        // Check license key registration
        $this->assertElementPresent('//div[@id="status-messages"]/ul/li[@class="info" and text()="License key has been successfully verified for MegaModule48 module by Igoryan author"]');

        $buttonInstall = '//tr[@class="module-' . $moduleId . '"]/td/div[@class="price-info"]/form[@action="admin.php" and @method="post"]/div[@class="install"]/button[@class="popup-button" and @type="button"]';

        $this->assertElementPresent($buttonInstall, 'Install button is absent');

        // Try to install (upload) paid module (Megamodule 48)
        $this->click($buttonInstall);

        $this->waitForAJAXProgress();

        // Check License agreement block
        $this->assertElementPresent('//span[text()="SuperModule 48 license agreement" and @id="ui-dialog-title-2"]', 'No title for module License');

        $this->assertElementPresent('//div[@class="module-license"]', 'No License block');

        $this->assertElementPresent('//div[@class="module-license"]/div[@class="form"]/form[@name="getAddonForm" and @method="post" and @action="admin.php"]', 'No form inside the License block');

        $this->assertElementPresent('//div[@class="license-block"]/table/tbody/tr/td[@class="license-text"]/textarea[@id="license-area" and @class="license-area" and @readonly="readonly"]', 'No License textarea element');

        $agreeCheckbox = '//div[@class="module-license"]/div[@class="form"]/form[@name="getAddonForm" and @method="post" and @action="admin.php"]/table[@class="agree"]/tbody/tr/td/input[@type="checkbox" and @id="agree" and @name="agree" and @value="Y"]';

        $this->assertElementPresent($agreeCheckbox, 'No agree checkbox');

        $this->click($agreeCheckbox);

        $installAddonButton = '//div[@class="module-license"]/div[@class="form"]/form[@name="getAddonForm" and @method="post" and @action="admin.php"]/table[@class="install-addon"]/tbody/tr/td/button[@type="submit"]';

        $this->assertElementPresent($installAddonButton . '/span[text()="Install add-on"]', 'No install addon button');

        // Install click
        $this->click($installAddonButton);

        $this->waitForPageToLoad(6000);

        // Check module installation
        $moduleBlock = '//table[@class="data-table items-list modules-list"]/tbody/tr[@class="module-' . $moduleId . ' disabled"]/td/div[@class="name" and text()="MegaModule 48"]';

        $this->assertElementPresent($moduleBlock, 'MegaModule48 module was not uploaded');

        // Try to uninstall
        $uninstall = '//table[@class="data-table items-list modules-list"]/tbody/tr[@class="module-' . $moduleId . ' disabled"]/td/div[@class="actions"]/a[@class="uninstall" and text()="Uninstall"]';

        $this->assertElementPresent($uninstall, 'No uninstall link!');

        $this->click($uninstall);

        $this->assertEquals($this->getConfirmation(), 'Are you sure you want to uninstall this add-on?', 'Wrong confirmation dialog');

        $this->waitForPageToLoad(6000);

        $this->assertElementNotPresent($moduleBlock, 'MegaModule48 module was not uninstalled');
    }*/
}

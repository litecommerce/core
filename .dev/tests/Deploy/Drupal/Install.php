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
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

require_once __DIR__ . '/../ADeploy.php';

class XLite_Deploy_Drupal_Install extends XLite_Deploy_ADeploy
{
    protected $buildDir = null;
    
    public function testInstall()
    {
        // Calculate the build directory path
        $currentPath = dirname(realpath(__FILE__));
        $this->buildDir = realpath($currentPath . '/../../../build');

        $this->open('install.php');

        $this->deleteCookie('lcl', 'domain=.crtdev.local');
        $this->deleteCookie('lcl2', 'domain=.crtdev.local');

        // First step page: License agreement
        $this->stepOne();

        // Second step: checking requirements
        $this->stepTwo();

        // Third step: database configuration
        $this->stepThree();

        // Fourth step: database an modules installation
        $this->stepFour();

        $this->assertTrue(false, 'Drupal has been successfully deployed');
    }

    /**
     * stepOne: License agreement page
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function stepOne($pass = 1)
    {
         // Check page title
        $this->assertTitleEquals('License agreement |', 'Checking the page title');

        // Check logo
        $this->assertElementPresent(
            '//div[@id="header"]/descendant::img[contains(@src,"profiles/litecommerce/lc_logo.png") and @id="logo"]',
            'Check if LC logo is presented in the header'
        );

        // Verify that LA text is presented (by part of it)
        $this->assertTextPresent(
            'This package contains the following parts distributed under the', 
            'Check that part of license text is presented'
        );

        // Check the checkbox availability
        $this->assertElementPresent(
            '//form[@id="-litecommerce-license-form"]/descendant::input[@type="checkbox" and @id="edit-license-confirmed" and @value="1"]',
            'Check if checkbox "I understand and accept..." is presented'
        );

        // Check the lcl2 hidden field availability
        $this->assertElementPresent(
            '//form[@id="-litecommerce-license-form"]/descendant::input[@type="hidden" and @id="edit-lcl2" and @value="1"]',
            'Check if license flag (hidden input) is presented'
        );

        // Check the submit button availability
        $this->assertElementPresent(
            '//form[@id="-litecommerce-license-form"]/descendant::input[@type="submit" and @id="edit-submit" and @value="Continue"]',
            'Check if "Continue" button is presented'
        );


        // Additional checkings on second pass
        if (2 == $pass) {
            // Check that error message appears
            $this->assertElementPresent(
                '//form[@id="-litecommerce-license-form"]/descendant::div[@id="lcl-error" and text()="You should confirm the license agreement before proceeding"]',
                'Check if License error message is presented'
            );

            // Mark checkbox
            $this->check('css=#edit-license-confirmed');
        }

        // Submit
        $this->clickAndWait('css=#edit-submit');

        // Go to second pass
        if (1 == $pass) {
            $this->stepOne(2);
        }
    }

    /**
     * stepTwo: check requirements
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function stepTwo()
    {
        // Check page title
        $this->assertTitleEquals('Requirements problem |', 'Checking the page title');

        // Check page header
        $this->assertElementPresent(
            '//h2[text()="Requirements problem"]',
            'Check that page header equals to text "Requirements problem"'
        );

        // Check that error message about settings.php os presented
        $this->assertElementPresent(
            '//div[@class="messages error"]//descendant::em[text()="./sites/default/default.settings.php"]',
            'Check that message about settings.php file is presented'
        );

        // Copy file default.settings.php to settings.php
        if (defined('DRUPAL_SITE_PATH') && file_exists(constant('DRUPAL_SITE_PATH'))) {
            $srcFile = constant('DRUPAL_SITE_PATH') . '/src/sites/default/default.settings.php';
            $dstFile = constant('DRUPAL_SITE_PATH') . '/src/sites/default/settings.php';
            copy($srcFile, $dstFile);
        
        } else {
            // Fail test if DRUPAL_SITE_PATH constant is undefined or wrong
            $this->assertTrue(false, 'DRUPAL_SITE_PATH constant is undefined or has a wrong value. Define it in .dev/tests/local.php');
        }

        // Reload page
        $this->refreshAndWait();
    }

    /**
     * stepThree: database configuration
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function stepThree($pass = 1)
    {
        // Check page title
        $this->assertTitleEquals('Database configuration |', 'Checking the page title');

        // Check page header
        $this->assertElementPresent(
            '//h2[text()="Database configuration"]',
            sprintf('Check that page header equals to text "Database configuration" (pass %d)', $pass)
        );

        // Check that "Database name" field is presented within section "Basic options"
        $this->assertElementPresent(
            '//form[@id="install-settings-form"]/descendant::fieldset[not(@*)]/descendant::input[@id="edit-db-path" and @type="text" and @name="db_path"]',
            sprintf('Check that "Database name" field is presented (pass %d)', $pass)
        );

        // Check that "Database username" field is presented within section "Basic options"
        $this->assertElementPresent(
            '//form[@id="install-settings-form"]/descendant::fieldset[not(@*)]/descendant::input[@id="edit-db-user" and @type="text" and @name="db_user"]',
            sprintf('Check that "Database username" field is presented (pass %d)', $pass)
        );

        // Check that "Database password" field is presented within section "Basic options"
        $this->assertElementPresent(
            '//form[@id="install-settings-form"]/descendant::fieldset[not(@*)]/descendant::input[@id="edit-db-pass" and @type="password" and @name="db_pass"]',
            sprintf('Check that "Database password" field is presented (pass %d)', $pass)
        );

        // Check that "Database host" field is presented within section "Advanced options"
        $this->assertElementPresent(
            '//form[@id="install-settings-form"]/descendant::fieldset/descendant::input[@id="edit-db-host" and @type="text" and @name="db_host"]',
            sprintf('Check that "Database host" field is presented (pass %d)', $pass)
        );

        // Check that "Database port" field is presented within section "Advanced options"
        $this->assertElementPresent(
            '//form[@id="install-settings-form"]/descendant::fieldset/descendant::input[@id="edit-db-port" and @type="text" and @name="db_port"]',
            sprintf('Check that "Database port" field is presented (pass %d)', $pass)
        );

        // Check that "Table prefix" field is presented within section "Advanced options"
        $this->assertElementPresent(
            '//form[@id="install-settings-form"]/descendant::fieldset/descendant::input[@id="edit-db-prefix" and @type="text" and @name="db_prefix"]',
            sprintf('Check that "Table prefix" field is presented (pass %d)', $pass)
        );

        // Check that submit button is presented
        $this->assertElementPresent(
            '//form[@id="install-settings-form"]/descendant::input[@id="edit-save" and @type="submit" and @value="Save and continue"]',
            sprintf('Check that "Save and continue" submit button is presented on "Database configuration" step (pass %d)', $pass)
        );

        if (2 == $pass) {

            // Check that error message is presented
            $this->assertElementPresent(
                '//h3[contains(text(),"must be resolved before you can continue the installation process")]',
                'Check that error message is presented (Database configuration step)'
            );

            // Check that error message 'Failed to select your database...' is presented
            $this->assertElementPresent(
                '//div[contains(text(),"Failed to select your database on your MySQL database server")]',
                'Check that error message "Failed to select your database..." is presented (Database configuration step)'
            );
        }

        $configFile = $this->buildDir . '/config.local.php';

        if (!file_exists($configFile)) {
            $this->assertTrue(false, 'File .dev/build/config.local.php not found');
        }

        $options = parse_ini_file($configFile, true);

        $checkDbOptions = true;

        foreach (array('hostspec', 'database', 'username', 'password') as $field) {
            $checkDbOptions = $checkDbOptions && !empty($options['database_details'][$field]);
        }

        $this->assertTrue($checkDbOptions, 'Database options are wrong in .dev/build/config.local.php');

        $this->type('css=#edit-db-user', $options['database_details']['username']);
        $this->type('css=#edit-db-pass', $options['database_details']['password']);
        $this->type('css=#edit-db-host', $options['database_details']['hostspec']);
        $this->type('css=#edit-db-port', $options['database_details']['port']);
        $this->type('css=#edit-db-prefix', 'drupal_');

        if (1 == $pass) {
            $this->type('css=#edit-db-path', 'wrong_value');

        } else {
            $this->type('css=#edit-db-path', $options['database_details']['database']);
        }

        // Submit
        $this->clickAndWait('css=#edit-save');

        if (1 == $pass) {
            // Go through pass 2 of database configuration step
            $this->stepThree(2);
        }
    }

    /**
     * stepFour: installing database and modules
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function stepFour()
    {

    }


    
    protected function checkHeader()
    {

    }

}

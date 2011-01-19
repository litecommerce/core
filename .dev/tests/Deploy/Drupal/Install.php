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
 * @subpackage Deploy
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
    /**
     * Maximum iterations number for 'Database and modules installation' step
     */
    const MAX_ITERATIONS_COUNT = 10;

    /**
     * Test email
     */
    const TESTER_EMAIL = 'rnd_tester@cdev.ru';

    /**
     * Product name (must equals to the name defined in litecommerce.profile)
     */
    const PRODUCT_NAME = 'Ecommerce CMS';

    /**
     * buildDir 
     * 
     * @var    mixed
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $buildDir = null;

    /**
     * testInstall 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testInstall()
    {
        // Drop/create database
        $this->emptyDatabase();

        // Add RewriteBase value to drupal's .htaccess
        $this->prepareHtaccess();

        // Start installation process
        $this->open('install.php');

        // First step page: License agreement
        $this->stepOne();

        // Second step: checking requirements
        // TODO: emulate problem and uncomment this step
        // $this->stepTwo();

        // Third step: database configuration
        $this->stepThree();

        // Fourth step: database an modules installation
        $this->stepFour();

        // Fifth step: site configuration
        $this->stepFive();

        // Sixth step: confirmation page
        $this->stepSix();

        // Seventh step: re-building cache and checking of the frontpage
        $this->stepSeven();

        $this->stepEight();

        $this->stepNine();
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
        $this->assertTitleEquals('License agreement | Drupal', 'Checking the page title');

        // Check page header
        $this->assertElementPresent(
            '//h1[@class="page-title" and text()="License agreement"]',
            'Check that page header equals to text "License agreement"'
        );

        // Check logo
        $this->assertElementPresent(
            '//div[@id="page"]/div[@id="sidebar-first"]/descendant::img[contains(@src,"profiles/litecommerce/lc_logo.png") and @id="logo"]',
            'Check if LC logo is presented in the header'
        );

        // Verify that LA text is presented (by part of it)
        $this->assertTextPresent(
            'This package contains the following parts distributed under the', 
            'Check that part of license text is presented'
        );

        // Check the checkbox availability
        $this->assertElementPresent(
            '//form[@id="litecommerce-license-form"]/descendant::input[@type="checkbox" and @id="edit-license-confirmed" and @value="1"]',
            'Check if checkbox "I understand and accept..." is presented'
        );

        // Check the submit button availability
        $this->assertElementPresent(
            '//form[@id="litecommerce-license-form"]/descendant::input[@type="submit" and @id="edit-submit" and @value="Save and continue"]',
            'Check if "Continue" button is presented'
        );


        // Additional checkings on second pass
        if (2 == $pass) {
            // Check that error message appears
            $this->assertElementPresent(
                '//div[@id="page"]/div[@id="content"]/div[@id="console"]/descendant::h2[text()="Error message"]',
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
        $this->assertTitleEquals('Requirements problem | Drupal', 'Checking the page title');

        // Check page header
        $this->assertElementPresent(
            '//h1[@class="page-title" and text()="Requirements problem"]',
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
        $this->assertTitleEquals('Database configuration | Drupal', 'Checking the page title');

        // Check page header
        $this->assertElementPresent(
            '//h1[@class="page-title" and text()="Database configuration"]',
            sprintf('Check that page header equals to text "Database configuration" (pass %d)', $pass)
        );

        // Check that "Database name" field is presented within section "Basic options"
        $this->assertElementPresent(
            '//form[@id="install-settings-form"]/descendant::input[@id="edit-mysql-database" and @type="text" and @name="mysql[database]"]',
            sprintf('Check that "Database name" field is presented (pass %d)', $pass)
        );

        // Check that "Database username" field is presented within section "Basic options"
        $this->assertElementPresent(
            '//form[@id="install-settings-form"]/descendant::input[@id="edit-mysql-username" and @type="text" and @name="mysql[username]"]',
            sprintf('Check that "Database username" field is presented (pass %d)', $pass)
        );

        // Check that "Database password" field is presented within section "Basic options"
        $this->assertElementPresent(
            '//form[@id="install-settings-form"]/descendant::input[@id="edit-mysql-password" and @type="password" and @name="mysql[password]"]',
            sprintf('Check that "Database password" field is presented (pass %d)', $pass)
        );

        $this->click('css=a[class="fieldset-title"]');

        // Check that "Database host" field is presented within section "Advanced options"
        $this->assertElementPresent(
            '//form[@id="install-settings-form"]/descendant::input[@id="edit-mysql-host" and @type="text" and @name="mysql[host]"]',
            sprintf('Check that "Database host" field is presented (pass %d)', $pass)
        );

        // Check that "Database port" field is presented within section "Advanced options"
        $this->assertElementPresent(
            '//form[@id="install-settings-form"]/descendant::input[@id="edit-mysql-port" and @type="text" and @name="mysql[port]"]',
            sprintf('Check that "Database port" field is presented (pass %d)', $pass)
        );

        // Check that "Database socket" field is presented within section "Advanced options"
        $this->assertElementPresent(
            '//form[@id="install-settings-form"]/descendant::input[@id="edit-mysql-unix-socket" and @type="text" and @name="mysql[unix_socket]"]',
            sprintf('Check that "Database socket" field is presented (pass %d)', $pass)
        );

        // Check that "Table prefix" field is presented within section "Advanced options"
        $this->assertElementPresent(
            '//form[@id="install-settings-form"]/descendant::input[@id="edit-mysql-db-prefix" and @type="text" and @name="mysql[db_prefix]"]',
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
                '//div[@id="console"]/div[@class="messages error"]/p[@class="error" and contains(text(),"Failed to connect to your database server")]',
                'Check that error message is presented (Database configuration step)'
            );
        }

        $options = $this->getConfigOptions();

        $this->type('css=#edit-mysql-username', $options['database_details']['username']);
        $this->type('css=#edit-mysql-password', $options['database_details']['password']);
        $this->type('css=#edit-mysql-host', $options['database_details']['hostspec']);
        $this->type('css=#edit-mysql-port', $options['database_details']['port']);
        $this->type('css=#edit-mysql-db-prefix', 'drupal_');

        if (isset($options['database_details']['unix_socket'])) {
            $this->type('css=#edit-mysql-port', $options['database_details']['unix_socket']);
        }

        if (1 == $pass) {
            $this->type('css=#edit-mysql-database', 'wrong_value');

        } else {
            $this->type('css=#edit-mysql-database', $options['database_details']['database']);
        }

        // Submit
        $this->clickAndWait('css=#edit-save');

        if (1 == $pass) {
            // Go through pass 2 of database configuration step
            $this->stepThree(2);
        }
    }

    /**
     * stepFour: Set up LiteCommerce
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function stepFour($pass = 1)
    {
        // Check page title
        $this->assertTitleEquals('Install LiteCommerce | Drupal', 'Checking the page title');

        // Check page header
        $this->assertElementPresent(
            '//h1[@class="page-title" and text()="Install LiteCommerce"]',
            sprintf('Check that page header equals to text "Install LiteCommerce" (pass %d)', $pass)
        );

        // Check that "Database name" field is presented within section "Basic options"
        $this->assertElementPresent(
            '//form[@id="litecommerce-setup-form"]/descendant::fieldset[@id="edit-litecommerce-settings"]//descendant::input[@id="edit-lc-install-demo" and @type="checkbox" and @name="lc_install_demo" and @value="1"]',
            sprintf('Check that "Install sample catalog" checkbox is presented (pass %d)', $pass)
        );

        // Check the submit button availability
        $this->assertElementPresent(
            '//form[@id="litecommerce-setup-form"]/descendant::input[@type="submit" and @id="edit-save" and @value="Save and continue"]',
            'Check if "Continue" button is presented'
        );

        // Mark checkbox
        $this->check('css=#edit-lc-install-demo');

        // Submit
        $this->clickAndWait('css=#edit-save');
    }

    /**
     * stepFive: installing LiteCommerce
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function stepFive($pass = 1)
    {
        // Check page title
        $this->assertTitleEquals('Installing LiteCommerce | Drupal', sprintf('Checking the page title (pass %d)', $pass));

        // Check page header
        $this->assertElementPresent(
            '//h1[@class="page-title" and text()="Installing LiteCommerce"]',
            sprintf('Check that page header equals to text "Installing LiteCommerce" (pass %d)', $pass)
        );

        $this->waitForCondition(
            'selenium.browserbot.getCurrentWindow().document.getElementsByClassName("percentage").length > 0 && selenium.browserbot.getCurrentWindow().document.getElementsByClassName("percentage")[0].innerHTML == "100%"',
            350000
        );

        $this->waitForPageToLoad(10000);
    }

    /**
     * step Six: installing Drupal modules
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function stepSix($pass = 1)
    {
        // Check page title
        $this->assertTitleEquals('Installing ' . self::PRODUCT_NAME . ' | Drupal', sprintf('Checking the page title (pass %d)', $pass));

        // Check page header
        $this->assertElementPresent(
            '//h1[@class="page-title" and text()="Installing ' . self::PRODUCT_NAME . '"]',
            sprintf('Check that page header equals to text "Installing %s" (pass %d)', self::PRODUCT_NAME, $pass)
        );

        $this->waitForLocalCondition(
            'jQuery(".percentage").html() == "100%"',
            250000,
            'Waiting for Drupal module installing'
        );

        if ($this->isElementPresent('//div[@class="percentage"]')) {
            $percentage = $this->getText('//div[@class="percentage"]');
        }

        if ('100%' != $percentage) {
            
            if ($pass < self::MAX_ITERATIONS_COUNT) {
                $this->stepFour($pass + 1);
            
            } else {
                $this->assertTrue(false, sprintf('Maximum iterations count value was exceeded (%d)', $pass));
            }
        }

        $this->waitForCondition(
            'selenium.browserbot.getCurrentWindow().document.getElementsByTagName("h1").length > 0 && selenium.browserbot.getCurrentWindow().document.getElementsByTagName("h1")[0].innerHTML == "Configure site"',
            50000
        );
    }

    /**
     * stepSeven: Configure site
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function stepSeven($pass = 1)
    {
        // Check page title
        $this->assertTitleEquals('Configure site | Drupal', 'Checking the page title');

        // Check page header
        $this->assertElementPresent(
            '//h1[@class="page-title" and text()="Configure site"]',
            'Check that page header equals to text "Configure site"'
        );

        // Check that "Site name" field is presented
        $this->assertElementPresent(
            '//form[@id="install-configure-form"]/descendant::input[@id="edit-site-name" and @type="text" and @name="site_name"]',
            sprintf('Check that "Site name" field is presented (pass %d)', $pass)
        );

        // Check that "Site e-mail address" field is presented
        $this->assertElementPresent(
            '//form[@id="install-configure-form"]/descendant::input[@id="edit-site-mail" and @type="text" and @name="site_mail"]',
            sprintf('Check that "Site e-mail address" field is presented (pass %d)', $pass)
        );

        // Check that "Username" field is presented
        $this->assertElementPresent(
            '//form[@id="install-configure-form"]/descendant::input[@id="edit-account-name" and @type="text" and @name="account[name]"]',
            sprintf('Check that "Username" field is presented (pass %d)', $pass)
        );

        // Check that "E-mail" field is presented
        $this->assertElementPresent(
            '//form[@id="install-configure-form"]/descendant::input[@id="edit-account-mail" and @type="text" and @name="account[mail]"]',
            sprintf('Check that "E-mail address" field is presented (pass %d)', $pass)
        );

        // Check that "Password" field is presented
        $this->assertElementPresent(
            '//form[@id="install-configure-form"]/descendant::input[@id="edit-account-pass-pass1" and @type="password" and @name="account[pass][pass1]"]',
            sprintf('Check that "Password" field is presented (pass %d)', $pass)
        );

        // Check that "Confirm password" field is presented
        $this->assertElementPresent(
            '//form[@id="install-configure-form"]/descendant::input[@id="edit-account-pass-pass2" and @type="password" and @name="account[pass][pass2]"]',
            sprintf('Check that "Confirm password" field is presented (pass %d)', $pass)
        );

        // Check that "Default country" field is presented
        $this->assertElementPresent(
            '//form[@id="install-configure-form"]/descendant::select[@id="edit-site-default-country" and @name="site_default_country"]',
            sprintf('Check that "Default country" field is presented (pass %d)', $pass)
        );

        // Check that "Default timezone" field is presented
        $this->assertElementPresent(
            '//form[@id="install-configure-form"]/descendant::select[@id="edit-date-default-timezone" and @name="date_default_timezone"]',
            sprintf('Check that "Default timezone" field is presented (pass %d)', $pass)
        );

        // Check that "Check for updates automatically" field is presented
        $this->assertElementPresent(
            '//form[@id="install-configure-form"]/descendant::input[@id="edit-update-status-module-1" and @name="update_status_module[1]"]',
            sprintf('Check that "Check for updates automatically" checkbox is presented (pass %d)', $pass)
        );

        // Check that "Receive e-mail notifications" field is presented
        $this->assertElementPresent(
            '//form[@id="install-configure-form"]/descendant::input[@id="edit-update-status-module-1" and @name="update_status_module[1]"]',
            sprintf('Check that "Receive e-mail notifications" checkbox is presented (pass %d)', $pass)
        );

        // Check that "Save and continue" button is presented
        $this->assertElementPresent(
            '//form[@id="install-configure-form"]/descendant::input[@id="edit-submit" and @name="op" and @value="Save and continue"]',
            sprintf('Check that "Save and Continue" button is presented on "Configure site" step (pass %d)', $pass)
        );
       
        // Fill the form fields
        $this->type('css=#edit-site-name', 'Test ' . self::PRODUCT_NAME);
        $this->type('css=#edit-site-mail', self::TESTER_EMAIL);
        $this->type('css=#edit-account-name', 'master');
        $this->type('css=#edit-account-mail', self::TESTER_EMAIL);
        $this->type('css=#edit-account-pass-pass1', 'master');
        $this->type('css=#edit-account-pass-pass2', 'master');

        // Select to install all states
        $this->select('css=#edit-site-default-country', 'value=US');

        // Mark checkboxes
        $this->uncheck('css=#edit-update-status-module-2');
        $this->uncheck('css=#edit-update-status-module-1');

        $this->waitForLocalCondition(
            'jQuery("#edit-clean-url").attr("value") == "1"',
            30000,
            'Waiting for clean URLs testing'
        );

        // Submit
        $this->clickAndWait('css=#edit-submit');
    }

    /**
     * stepEight: Confirmation page
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function stepEight()
    {
         // Check page title
        $this->assertTitleEquals(self::PRODUCT_NAME . ' installation complete | Test ' . self::PRODUCT_NAME, 'Checking the page title');

        // Check page header
        $this->assertElementPresent(
            '//h1[@class="page-title" and text()="' . self::PRODUCT_NAME . ' installation complete"]',
            'Check that page header equals to text "' . self::PRODUCT_NAME . ' installation complete"'
        );

         // Check that link to the installed store is presented
        $this->assertElementPresent(
            '//div[@id="content"]/p[contains(text(),"Congratulations, you installed Ecommerce CMS!")]',
            'Check that congratulations text is presented'
        );

         // Check that link to the installed store is presented
        $this->assertElementPresent(
            '//div[@id="content"]/p/a[text()="your new site"]',
            'Check that "your new site" text is presented'
        );

        // Click link to the frontend
        $this->clickAndWait('//a[text()="your new site"]');
    }

    /**
     * stepNine: Checking the frontend page
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function stepNine()
    {
        // Check page title
        $this->assertTitleEquals('Test ' . self::PRODUCT_NAME, 'Checking the page title');

        // Check if minicart is presented and has correct items value
        $this->assertElementPresent(
            '//div[@id="lc-minicart-horizontal"]/div[@class="minicart-items-number" and text()="0"]',
            'Check for minicart presence'
        );

        // Check that "Powered by" element is presented
        $this->assertElementPresent(
            '//div[@class="powered-by"]/p[contains(text(),"Powered by")]',
            'Check that "Powered by" element is presented'
        );
    }

    /**
     * getBuildDir 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getBuildDir()
    {
        if (!isset($this->buildDir)) {
            // Calculate the build directory path
            $currentPath = dirname(realpath(__FILE__));
            $this->buildDir = realpath($currentPath . '/../../../build');
       }

        return $this->buildDir;
    }

    /**
     * getConfigOptions 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getConfigOptions()
    {

        $configFile = $this->getBuildDir() . '/config.local.php';

        if (!file_exists($configFile)) {
            $this->assertTrue(false, 'File .dev/build/config.local.php not found');
        }

        $options = parse_ini_file($configFile, true);

        $checkDbOptions = true;

        foreach (array('hostspec', 'database', 'username', 'password') as $field) {
            $checkDbOptions = $checkDbOptions && !empty($options['database_details'][$field]);
        }

        $this->assertTrue($checkDbOptions, 'Database options are wrong in .dev/build/config.local.php');

        return $options;
    }

    /**
     * emptyDatabase 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function emptyDatabase()
    {
        $options = $this->getConfigOptions();

        // Prepare db host
        $dbhost = $options['database_details']['hostspec'] 
            . (!empty($options['database_details']['socket']) ? ':' . $options['database_details']['socket']
                : (!empty($options['database_details']['port']) ? ':' . $options['database_details']['port'] : '')
            );
            
        $connect = @mysql_connect(
            $dbhost,
            $options['database_details']['username'],
            $options['database_details']['password']
        );

        if ($connect) {

            // Drop / create database
            @mysql_query(sprintf('DROP DATABASE %s', $options['database_details']['database']));
            @mysql_query(sprintf('CREATE DATABASE %s', $options['database_details']['database']));

            // Try to select database
            $dbSelected = @mysql_select_db($options['database_details']['database']);

            if ($dbSelected) {
                // Check that database is empty
                $res = @mysql_query('SHOW TABLES');
                if ($res) {
                    $row = @mysql_result($res, 0);
                    $this->assertTrue(empty($row), sprintf('Cannot empty database %s', $options['database_details']['database']));
                }

            } else {
                $this->assertTrue(false, sprintf('Cannot select database %s', $options['database_details']['database']));
            }
        
        } else {
            $this->assertTrue(false, 'Wrong database connection parameters!');
        }
    }

    /**
     * prepareHtaccess: add RewriteBase value for clean URL feature
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareHtaccess()
    {
        if (defined('DRUPAL_SITE_PATH')) {

            $fileName = DRUPAL_SITE_PATH . '/src/.htaccess';

            $uri = preg_replace('/\/$/', '', parse_url($this->baseURL, PHP_URL_PATH));

            if (file_exists($fileName)) {
                $content = file_get_contents($fileName);
                $content = preg_replace('/(.*RewriteEngine +on)/Ssi', "\\1\n  RewriteBase " . preg_quote($uri), $content);
                file_put_contents($fileName, $content);

            } else {
                $this->assertTrue(false, $fileName . ' file not found');
            }

        } else {
            $this->assertTrue(false, 'DRUPAL_SITE_PATH constant undefined');
        }
    }

}

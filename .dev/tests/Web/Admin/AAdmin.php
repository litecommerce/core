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
 */

require_once __DIR__ . '/../AWeb.php';

abstract class XLite_Web_Admin_AAdmin extends XLite_Web_AWeb
{

    /**
     * Login procedure
     *
     * @param string $user     user name
     * @param string $password user password
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function logIn($user = 'rnd_tester@cdev.ru', $password = 'master')
    {
        $this->open('admin.php');

        if ($this->isLoggedIn()) {
            $this->logOut(true);
        }

        $this->type("//input[@name='login' and @type='text']", $user);
        $this->type("//input[@name='password' and @type='password']", $password);

        $this->click("//button[@class='main-button' and @type='submit']");

        $this->waitForPageToLoad(30000);
    }

    /**
     * Log out procedure
     *
     * @param boolean $pageIsOpened Flag to open page
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function logOut($pageIsOpened = false)
    {
        if (!$pageIsOpened) {
            $this->open('admin.php');
        }

        if ($this->isLoggedIn()) {

            $this->open('admin.php?target=login&action=logoff');

            $this->waitForPageToLoad(30000);
        }
    }

    /**
     *  Checks if the user is logged in
     *
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isLoggedIn()
    {
        return $this->isElementPresent("//a[text()='Sign out']");
    }

    /**
     * Executes an SQL query
     *
     * @param string $query SQL query to execute
     *
     * @return Doctrine\DBAL\Driver\PDOStatement
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function query($query)
    {
        return \XLite\Core\Database::getEM()->getConnection()->executeQuery($query, array());
    }

    /**
     * Resets the browser and instantiates a new browser session
     *
     * @return void
     * @access protected
     * @since  1.0.0
     */
    protected function resetBrowser()
    {
        $this->stop();
        $this->start();
    }

    /**
     * Admin pages initialisation
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function setUp()
    {
        parent::setUp();

        $this->baseURL = rtrim(SELENIUM_SOURCE_URL_ADMIN, '/') . '/';

        $this->setBrowserUrl($this->baseURL);
    }

    protected function waitForAJAXProgress()
    {
        $this->waitForLocalCondition(
            'jQuery(".popup .ui-dialog-titlebar").length > 0',
            300000
        );
    }

}

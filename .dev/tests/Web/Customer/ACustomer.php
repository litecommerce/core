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

require_once __DIR__ . '/../AWeb.php';

class XLite_Web_Customer_ACustomer extends XLite_Web_AWeb
{

    protected function logIn($user = 'master', $password = 'master')
    {
        $this->open('user');

        if ($this->isLoggedIn()) {
            $this->logOut(true);
        }

        $this->type("//input[@name='name' and @type='text']", $user);
        $this->type("//input[@name='password' and @type='password']", $password);

        $this->click("//input[@id='edit-submit']");

        $this->waitForPageToLoad(3000);
    }

    protected function logOut($pageIsOpened = false)
    {
        if (!$pageIsOpened) {
            $this->open('user');   
        }

        if ($this->isLoggedIn()) {
            $this->click("//a[text()='Log out']");

            $this->waitForPageToLoad(3000);
        }
    }

    protected function isLoggedIn()
    {
        return $this->isElementPresent("//a[text()='Log out']");
    }

    protected function getActiveProduct()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Product')
            ->findOneByEnabled(true);
    }

    protected function getActiveProducts()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Product')
            ->findByEnabled(true);
    }

}

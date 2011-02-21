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

/**
 * Substutional skin
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Web_Customer_SubstutionalSkin extends XLite_Web_Customer_ACustomer
{
    /**
     * PHPUnit default function.
     * Redefine this method only if you really need to do so.
     * In any other cases redefine the getRequest() one
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function setUp()
    {
        parent::setUp();

        \Includes\Utils\FileManager::copyRecursive(
            dirname(__FILE__) . LC_DS . 'SubstutionalSkin' . LC_DS . 'scripts' . LC_DS . 'CDev',
            LC_MODULES_DIR . 'CDev'
        );
        \Includes\Utils\FileManager::copyRecursive(
            dirname(__FILE__) . LC_DS . 'SubstutionalSkin' . LC_DS . 'scripts' . LC_DS . 'CDev',
            LC_CLASSES_CACHE_DIR . 'XLite' . LC_DS . 'Module' . LC_DS . 'CDev'
        );

        \Includes\Utils\FileManager::copyRecursive(
            dirname(__FILE__) . LC_DS . 'SubstutionalSkin' . LC_DS . 'skins',
            LC_SKINS_DIR
        );

        $skin = \XLite\Core\Database::getRepo('XLite\Model\Module')->findByActualName('TestSkin', 'CDev');
        if (!$skin) {
            $skin = new \XLite\Model\Module;
            $skin->setAuthor('CDev');
            $skin->setName('TestSkin');

            \XLite\Core\Database::getEM()->persist($skin);
        }

        $skin->setInstalled(true);
        $skin->setEnabled(true);

        \XLite\Core\Database::getEM()->flush();

        \XLite\Core\Database::getCacheDriver()->deleteAll();
    }

    /**
     * PHPUnit default function.
     * It's not recommended to redefine this method
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function tearDown()
    {
        \Includes\Utils\FileManager::unlinkRecursive(
            LC_MODULES_DIR . 'CDev' . LC_DS . 'TestSkin'
        );
        \Includes\Utils\FileManager::unlinkRecursive(
            LC_CLASSES_CACHE_DIR . 'XLite' . LC_DS . 'Module' . LC_DS . 'CDev' . LC_DS . 'TestSkin'
        );
        \Includes\Utils\FileManager::unlinkRecursive(
            LC_SKINS_DIR . 'test'
        );

        $skin = \XLite\Core\Database::getRepo('XLite\Model\Module')->findByActualName('TestSkin', 'CDev');
        if ($skin) {
            \XLite\Core\Database::getEM()->remove($skin);
            \XLite\Core\Database::getEM()->flush();
        }

        \XLite\Core\Database::getCacheDriver()->deleteAll();

        parent::tearDown();
    }

    public function testSubstitute()
    {
        $this->openAndWait('');

        $this->assertElementPresent(
            "//h1[@class='substitutional-test-skin' and text()='WELCOME PAGE']",
            'test substutional template'
        );
    }

    public function testInheritance()
    {
        copy(
            LC_SKINS_DIR . 'test' . LC_DS . 'en' . LC_DS . 'welcome.i.tpl',
            LC_SKINS_DIR . 'test' . LC_DS . 'en' . LC_DS . 'welcome.tpl'
        );
        $this->openAndWait('');

        $this->assertElementPresent(
            "//h1[@class='substitutional-test-skin' and text()='WELCOME PAGE']",
            'test substutional template'
        );

        $this->assertElementPresent(
            "//h3[text()='Access denied!']",
            'test inherited template'
        );
    }

    public function testDirectCall()
    {
        copy(
            LC_SKINS_DIR . 'test' . LC_DS . 'en' . LC_DS . 'welcome.d.tpl',
            LC_SKINS_DIR . 'test' . LC_DS . 'en' . LC_DS . 'welcome.tpl'
        );
        $this->openAndWait('');

        $this->assertElementPresent(
            "//h1[@class='substitutional-test-skin' and text()='WELCOME PAGE']",
            'test substutional template'
        );

        $this->assertElementPresent(
            "//h3[text()='Access denied!']",
            'test direct template'
        );
    }

}

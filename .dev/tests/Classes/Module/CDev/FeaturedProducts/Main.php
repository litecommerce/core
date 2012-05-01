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
 * @subpackage Classes
 * @author     Creative Development LLC <info@cdev.ru>
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

/**
 * XLite_Tests_Module_CDev_FeaturedProducts_Main 
 *
 * @see   ____class_see____
 * @since 1.0.22
 */
class XLite_Tests_Module_CDev_FeaturedProducts_Main extends XLite_Tests_TestCase
{
    /**
     * testGetModuleName
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.22
     */
    public function testGetModuleName()
    {
        $main = $this->getMain();
        $this->assertEquals('Featured Products', $main::getModuleName(), 'Wrong module name');
    }

    /**
     * testGetDescription
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.22
     */
    public function testGetDescription()
    {
        $main = $this->getMain();
        $this->assertEquals('Shows your best and most profitable products to customers.', $main::getDescription(), 'Wrong description');
    }

    /**
     * testGetVersion
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.22
     */
    public function testGetVersion()
    {
        $main = $this->getMain();
        $this->assertEquals('1.0.12', $main::getVersion(), 'Wrong version');
    }

    /**
     * testShowSettingsForm
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.22
     */
    public function testShowSettingsForm()
    {
        $main = $this->getMain();
        $this->assertTrue($main::showSettingsForm(), 'Wrong flag to show settings form');
    }

    /**
     * getMain
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.22
     */
    protected function getMain()
    {
        return 'XLite\Module\CDev\FeaturedProducts\Main';
    }
}

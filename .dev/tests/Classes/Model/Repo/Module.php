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
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

class XLite_Tests_Model_Repo_Module extends XLite_Tests_Model_ModuleAbstract
{
    /**
     * getDefaultCnd
     *
     * @return \XLite\Core\CommonCell
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDefaultCnd()
    {
        return new \XLite\Core\CommonCell(
            array(
                \XLite\Model\Repo\Module::P_SUBSTRING => '',
                \XLite\Model\Repo\Module::P_ORDER_BY => array(
                    \XLite\View\ItemsList\Module\AModule::SORT_BY_MODE_NAME,
                    \XLite\View\ItemsList\Module\AModule::SORT_ORDER_ASC
                ),
                \XLite\Model\Repo\Module::P_LIMIT => array(0, 100),
            )
        );
    }

    /**
     * testSearchAll
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testSearch()
    {
        $cnd = $this->getDefaultCnd();

        $result = \XLite\Core\Database::getRepo('\XLite\Model\Module')->search($cnd);

        // If all products selected
        $this->assertEquals(44, count($result), 'Number of found modules does not match');

        // If the first selected (SORT_BY_MODE_NAME, SORT_ORDER_ASC) is the "AustraliaPost" one
        $this->assertEquals(1, $result[0]->getModuleId(), 'ID of the first found module does not match');
        $this->assertEquals(41, $result[count($result)-1]->getModuleId(), 'ID of the last found module does not match');
    }

    /**
     * testFindAllModules
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testFindAllModules()
    {
        $result = \XLite\Core\Database::getRepo('\XLite\Model\Module')->findAllModules();

        $this->assertEquals(44, count($result), 'Number of found modules does not match');
        $this->assertEquals(1, $result[0]->getModuleId(), 'ID of the first found module does not match');
        $this->assertEquals(41, $result[count($result)-1]->getModuleId(), 'ID of the last found module does not match');
    }

    /**
     * testFindInactiveModules
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testFindInactiveModules()
    {
        $result = \XLite\Core\Database::getRepo('\XLite\Model\Module')->findInactiveModules();

        $this->assertEquals(37, count($result), 'Number of found inactive modules does not match');
        $this->assertEquals(8, $result[0]->getModuleId(), 'ID of the first found module does not match');
        $this->assertEquals(44, $result[count($result)-1]->getModuleId(), 'ID of the last found module does not match');
    }

    /**
     * testFindUpgradableModules
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testFindUpgradableModules()
    {
        $result = \XLite\Core\Database::getRepo('\XLite\Model\Module')->findUpgradableModules();

        $this->assertEquals(6, count($result), 'Number of found upgradable modules does not match');
        $this->assertEquals(10, $result[0]->getModuleId(), 'ID of the first found module does not match');
        $this->assertEquals(15, $result[count($result)-1]->getModuleId(), 'ID of the last found module does not match');
    }

    /**
     * testFindAllNames
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testFindAllNames()
    {
        $result = \XLite\Core\Database::getRepo('\XLite\Model\Module')->findAllNames();

        $this->assertEquals(44, count($result), 'Number of found modules does not match');

        $this->assertEquals('CDev\AustraliaPost', $result[0], 'First found module name does not match');
        $this->assertEquals('CDev\XPaymentsConnector', $result[count($result)-1], 'Last found module name does not match');
    }

    /**
     * testFindAllByModuleIds
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testFindAllByModuleIds()
    {
        $ids = array(1,3,5);
        $result = \XLite\Core\Database::getRepo('\XLite\Model\Module')->findAllByModuleIds($ids);

        $this->assertEquals(3, count($result), 'Number of found modules does not match');

        $this->assertEquals(1, $result[0]->getModuleId(), 'First found module name does not match');
        $this->assertEquals(5, $result[count($result)-1]->getModuleId(), 'Last found module name does not match');
    }

    /**
     * testFindAllByModuleIds
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testFindAllEnabled()
    {
        $result = \XLite\Core\Database::getRepo('\XLite\Model\Module')->findAllEnabled();

        $this->assertEquals(7, count($result), 'Number of found active modules does not match');
        $this->assertEquals(1, $result[0]->getModuleId(), 'ID of the first found module does not match');
        $this->assertEquals(7, $result[count($result)-1]->getModuleId(), 'ID of the last found module does not match');
    }

    /**
     * testFindAllByModuleIds
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testFindByActualName()
    {
        $name = 'FeaturedProducts';
        $author = 'CDev';
        
        $result = \XLite\Core\Database::getRepo('\XLite\Model\Module')->findByActualName($name, $author);
        $this->assertEquals('FeaturedProducts', $result->getName(), 'Did not find module by actual name');
        $this->assertEquals('CDev', $result->getAuthor(), 'Did not find module by actual name');
    }

    /**
     * testGetActiveModules
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetActiveModules()
    {
        $result = \XLite\Core\Database::getRepo('\XLite\Model\Module')->getActiveModules();

        $checkModule = 'CDev\AustraliaPost';

        $this->assertEquals(7, count($result), 'Number of found active modules does not match');
        $this->assertEquals($checkModule, $result[$checkModule]->getActualName(), 'Check found active modules format');
    }

    /**
     * testIsModuleActive
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testIsModuleActive()
    {
        $nameA = 'CDev\FeaturedProducts';
        $nameD = 'CDev\AOM';
        
        $this->assertTrue(\XLite\Core\Database::getRepo('\XLite\Model\Module')->isModuleActive($nameA), 'active module check failed');
        $this->assertFalse(\XLite\Core\Database::getRepo('\XLite\Model\Module')->isModuleActive($nameD), 'inactive module check failed');
    }

    /**
     * testGetActiveModules
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testInitialize()
    {
        // TODO: check after tests on modules installation and depack are created
    }

    /**
     * testGetActiveModules
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testCheckModules()
    {
        // TODO: check after tests on modules installation and depack are created
    }

}

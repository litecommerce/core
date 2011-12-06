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
 * XLite_Tests_Model_Repo_Module 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class XLite_Tests_Model_Repo_Module extends XLite_Tests_TestCase
{
    /**
     * setUp 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function setUp()
    {
        parent::setUp();

        $this->doRestoreDb(__DIR__ . '/sql/module/setup.sql', false);
    }

    protected function tearDown(){
        parent::tearDown();
        $this->doRestoreDb();
    }

    /**
     * testSearchAll 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testSearchAll()
    {
        $this->searchTest(null, null, 19, 'CDev\TinyMCE');
    }

    /**
     * testSearchSubstring 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testSearchSubstring()
    {
        $this->searchTest('P_SUBSTRING', 'f', 9, 'CDev\ProductOptions');
    }

    /**
     * testSearchPriceFilter 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testSearchPriceFilterFree()
    {
        $this->searchTest('P_PRICE_FILTER', \XLite\Model\Repo\Module::PRICE_FREE, 16, 'CDev\TinyMCE');
    }

    /**
     * testSearchPriceFilterPaid 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testSearchPriceFilterPaid()
    {
        $this->searchTest('P_PRICE_FILTER', \XLite\Model\Repo\Module::PRICE_PAID, 4, 'Test\Module7');
    }

    /**
     * testSearchInstalled 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testSearchInstalled()
    {
        $this->searchTest('P_INSTALLED', true, 12, 'CDev\TinyMCE');
    }

    /**
     * testSearchNotInstalled 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testSearchNotInstalled()
    {
        $this->searchTest('P_INSTALLED', false, 10, 'Test\Module7');
    }

    /**
     * testSearchInactive 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testSearchInactive()
    {
        $this->searchTest('P_INACTIVE', true, 12, 'Test\Module7');
    }

    /**
     * testSearchCoreVersion1
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testSearchCoreVersion1()
    {
        $this->searchTest('P_CORE_VERSION', '1.0', 17, 'CDev\TinyMCE');
    }

    /**
     * testSearchCoreVersion2
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testSearchCoreVersion2()
    {
        $this->searchTest('P_CORE_VERSION', '1.0', 17, 'CDev\TinyMCE');
    }

    /**
     * testSearchFromMarketplace 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testSearchFromMarketplace()
    {
        $this->searchTest('P_FROM_MARKETPLACE', true, 10, 'Test\Module7');
    }

    /**
     * testSearchOrderByAsc 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testSearchOrderByAsc()
    {
        $this->searchTest('P_ORDER_BY', array('m.name', 'ASC'), 19, 'CDev\TinyMCE');
    }

    /**
     * testSearchOrderByDesc
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testSearchOrderByDesc()
    {
        $this->searchTest('P_ORDER_BY', array('m.name', 'DESC'), 19, 'CDev\AustraliaPost');
    }

    /**
     * testSearchLimit 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testSearchLimit()
    {
        $this->searchTest('P_LIMIT', array(2, 7), 7, 'Test\Module1');
    }

    /**
     * testSearchTag 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testSearchTag()
    {
        $this->searchTest('P_TAG', 'Test', 19, 'CDev\TinyMCE');
    }

    /**
     * testpUdateMarketplaceModules 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testUpdateMarketplaceModules()
    {
        $data = array(
            array(
                'name'          => 'Module1',
                'author'        => 'Test',
                'enabled'       => 1,
                'installed'     => 1,
                'date'          => time(),
                'majorVersion'  => '1.0',
                'minorVersion'  => '1',
                'moduleName'    => 'Module2',
                'authorName'    => 'Test',
                'description'   => 'Description',
                'iconURL'       => '',
                'pageURL'       => '',
                'authorPageURL' => '',
                'dependencies'  => array(),
                'rating'        => 0,
                'votes'         => 0,
                'downloads'     => 0,
                'price'         => 0.00,
                'currency'      => 'USD',
                'revisionDate'  => 0,
                'packSize'      => 0,
            ),
            array(
                'name'          => 'Module2',
                'author'        => 'Test',
                'enabled'       => 1,
                'installed'     => 1,
                'date'          => time(),
                'majorVersion'  => '1.0',
                'minorVersion'  => '1',
                'moduleName'    => 'Module2',
                'authorName'    => 'Test',
                'description'   => 'Description',
                'iconURL'       => '',
                'pageURL'       => '',
                'authorPageURL' => '',
                'dependencies'  => array(),
                'rating'        => 0,
                'votes'         => 0,
                'downloads'     => 0,
                'price'         => 0.00,
                'currency'      => 'USD',
                'revisionDate'  => 0,
                'packSize'      => 0,
            ),
        );

        $this->getRepo()->updateMarketplaceModules($data);

        $this->searchTest(null, null, 14, 'CDev\TinyMCE');
    }

    /**
     * testGetModuleForUpdate 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetModuleForUpdate()
    {
        $installed = $this->getModuleByAuthorAndName('CDev', 'DrupalConnector', '1.0', '0');
        $forUpdate = $this->getRepo()->getModuleForUpdate($installed);

        $this->assertNotNull($forUpdate, 'check if module is found');
        $this->assertNotEquals($installed->getModuleID(), $forUpdate->getModuleID(), 'check module IDs');

        $installed = $this->getModuleByAuthorAndName('CDev', 'Bestsellers', '1.0', '0');
        $forUpdate = $this->getRepo()->getModuleForUpdate($installed);

        $this->assertNull($forUpdate, 'check if module is found');
    }

    /**
     * testGetModuleFromMarketplace 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetModuleFromMarketplace()
    {
        $installed = $this->getModuleByAuthorAndName('CDev', 'DrupalConnector', '1.0', '0');
        $fromMarketplace = $this->getRepo()->getModuleFromMarketplace($installed);

        $this->assertNotNull($fromMarketplace, 'check if module is found [1]');
        $this->assertNotEquals($installed->getModuleID(), $fromMarketplace->getModuleID(), 'check module IDs [1]');
        $this->assertNotEmpty($fromMarketplace->getMarketplaceID(), 'check marketplace ID [1]');

        $installed = $this->getModuleByAuthorAndName('CDev', 'Bestsellers', '1.0', '0');
        $fromMarketplace = $this->getRepo()->getModuleFromMarketplace($installed);

        $this->assertNotNull($fromMarketplace, 'check if module is found [2]');
        $this->assertNotEquals($installed->getModuleID(), $fromMarketplace->getModuleID(), 'check module IDs [2]');
        $this->assertNotEmpty($fromMarketplace->getMarketplaceID(), 'check marketplace ID [2]');
    }

    /**
     * testGetModuleInstalled 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetModuleInstalled()
    {
        $fromMarketplace = $this->getModuleByAuthorAndName('CDev', 'DrupalConnector', '1.0', '1');
        $installed = $this->getRepo()->getModuleInstalled($fromMarketplace);

        $this->assertNotNull($installed, 'check if module is found [1]');
        $this->assertEquals($installed->getModuleID(), $fromMarketplace->getModuleID(), 'check module IDs [1]');
        $this->assertEquals('1.0', $installed->getMajorVersion(), 'check major version [1]');
        $this->assertEquals('0', $installed->getMinorVersion(), 'check minor version [1]');

        $fromMarketplace = $this->getModuleByAuthorAndName('CDev', 'TinyMCE', '1.0', '0');
        $installed = $this->getRepo()->getModuleInstalled($fromMarketplace);

        $this->assertNotNull($installed, 'check if module is found [2]');
        $this->assertEquals($installed->getModuleID(), $fromMarketplace->getModuleID(), 'check module IDs [2]');
        $this->assertEquals('1.0', $installed->getMajorVersion(), 'check major version [2]');
        $this->assertEquals('0', $installed->getMinorVersion(), 'check minor version [2]');
    }

    /**
     * testGetModuleForUpgrade 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetModuleForUpgrade()
    {
        $installed = $this->getModuleByAuthorAndName('CDev', 'DrupalConnector', '1.0', '0');
        $forUpgrade = $this->getRepo()->getModuleForUpgrade($installed, '1.0');

        $this->assertNotNull($forUpgrade, 'check if module is found [1]');
        $this->assertNotEquals($installed->getModuleID(), $forUpgrade->getModuleID(), 'check module IDs [1]');
        $this->assertNotEmpty($forUpgrade->getMarketplaceID(), 'check marketplace ID [1]');
        $this->assertEquals('1.0', $forUpgrade->getMajorVersion(), 'check major version [1]');
        $this->assertEquals('1', $forUpgrade->getMinorVersion(), 'check minor version [1]');

        $installed = $this->getModuleByAuthorAndName('CDev', 'Bestsellers', '1.0', '0');
        $forUpgrade = $this->getRepo()->getModuleForUpgrade($installed, '1.0');

        $this->assertNotNull($forUpgrade, 'check if module is found [2]');
        $this->assertNotEquals($installed->getModuleID(), $forUpgrade->getModuleID(), 'check module IDs [2]');
        $this->assertEquals('1.0', $forUpgrade->getMajorVersion(), 'check major version [2]');
        $this->assertEquals('0', $forUpgrade->getMinorVersion(), 'check minor version [2]');

        $installed = $this->getModuleByAuthorAndName('CDev', 'Bestsellers', '1.0', '0');
        $forUpgrade = $this->getRepo()->getModuleForUpgrade($installed, '1.2');

        $this->assertNull($forUpgrade, 'check if module is found [3]');
    }

    // {{{ Protected methods

    /**
     * getRepo
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getRepo()
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Module');
    }

    /**
     * searchTest 
     * 
     * @param mixed $param ____param_comment____
     * @param mixed $value ____param_comment____
     * @param mixed $count ____param_comment____
     * @param mixed $name  ____param_comment____
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function searchTest($param, $value, $count, $name)
    {
        $cnd = new \XLite\Core\CommonCell();

        if (isset($param)) {
            $cnd->{constant('\XLite\Model\Repo\Module::' . $param)} = $value;
        }

        $result = $this->getRepo()->search($cnd, true);
        $this->assertEquals($count, $result, 'check modules count in search result');

        if ($result = $this->getRepo()->search($cnd)) {
            $result = array_pop($result);
            $this->assertEquals($name, $result->getActualName(), 'check module ID for the last item in search result');

        } else {
            $this->fail('Empty result');
        }
    }

    /**
     * getModuleByAuthorAndName
     *
     * @param mixed $author   ____param_comment____
     * @param mixed $name     ____param_comment____
     * @param mixed $version  ____param_comment____
     * @param mixed $revision ____param_comment____
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModuleByAuthorAndName($author, $name, $version = null, $revision = null)
    {
        if (!($module = $this->getRepo()->findOneBy(array('author' => $author, 'name' => $name)))) {
            $module = new \XLite\Model\Module();

            $module->setAuthor($author);
            $module->setName($name);

            if (isset($version)) {
                $module->setMajorVersion($version);
    
                if (isset($revision)) {
                    $module->setMinorVersion($revision);
                }
            }
        }

        return $module;
    }

    // }}}
}

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
 * @version    GIT: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

class XLite_Tests_Model_Module extends XLite_Tests_Model_ModuleAbstract
{

    // TODO: implement using a test module package once installation and packing
    //       is complete. Currently Featured products module is used for testing
    
    public function testCreate()
    {
        $module = $this->getTestModule();

        $this->assertTrue(0 < $module->getModuleId(), 'Check Module ID');

        foreach ($this->testModule as $k => $v) {
            $m = 'get' . \XLite\Core\Converter::convertToCamelCase($k);
            $this->assertEquals($v, $module->$m(), 'Check ' . $k);
        }

        // Test create() method itself
        $module = new \XLite\Model\Module();
        $module->create('CreatedModule', 'CreatedAuthor');

        $module = \XLite\Core\Database::getRepo('XLite\Model\Module')->find($module->getModuleId());
        $this->assertEquals('CreatedModule', $module->getName(), 'Check name using create() method');
        $this->assertEquals('CreatedAuthor', $module->getAuthor(), 'Check author using create() method');
    }

    public function testUpdate()
    {
        $newValues = array(
            'name'          => 'TestModule1',
            'author'        => 'TestAuthor1',
            'moduleName'    => 'Test module name1',
            'authorName'    => 'Test module author1',
            'description'   => 'Test module description1',
            'installed'     => false,
            'version'       => '1.2.31',
            'rating'        => 99,
            'downloads'     => 101,
            'iconURL'       => 'icon/url/test/path1',
            'date'          => 1295513913,
            'changelog'     => 'Test changelog1',
            'currency'      => 'USD',
            'purchased'     => true,
            'price'         => 10.12,
            'uploadCode'    => 'abc',
            'packHash'      => 'asdasd',
            'dependencies'  => array(
                array('CDev', 'AOM')
            ),
        );

        $module = $this->getTestModule();

        // Set values
        foreach ($newValues as $k => $newval) {
            $m = 'set' . \XLite\Core\Converter::convertToCamelCase($k);
            $module->$m($newval);
        }

        $module->setStatus($module::EXISTS);

        \XLite\Core\Database::getEM()->persist($module);
        \XLite\Core\Database::getEM()->flush();

        \XLite\Core\Database::getEM()->clear();

        // Get values
        $module = \XLite\Core\Database::getRepo('XLite\Model\Module')->find($module->getModuleId());

        foreach ($newValues as $k => $newval) {
            $m = 'get' . \XLite\Core\Converter::convertToCamelCase($k);
            $this->assertEquals($newval, $module->$m(), 'check new ' . $k);
            $module->$m();
        }

        $this->assertEquals($module::EXISTS, $module->getStatus(), 'check new status');
    }

    public function testDelete()
    {
        $module = $this->getTestModule();

        $id = $module->getModuleId();

        \XLite\Core\Database::getEM()->remove($module);
        \XLite\Core\Database::getEM()->flush();

        $module = \XLite\Core\Database::getRepo('XLite\Model\Module')
            ->find($id);

        $this->assertTrue(is_null($module), 'check removed module');
    }

    public function testHasIcon()
    {
        $module = $this->getTestModule();
        $this->assertTrue($module->hasIcon(), 'check if module has icon');
    }

    public function testIsFree()
    {
        $module = $this->getTestModule();
        $this->assertFalse($module->isFree(), 'check if module is free');
    }

    /*public function testGetPageURL()
    {
        $module = $this->getTestModule();
        $this->assertEquals(\XLite\RemoteModel\Marketplace::getInstance()->getMarketplaceURL() . 'module/TestAuthor/TestModule', $module->getPageURL(), 'check module URL');
    }

    public function testGetAuthorPageURL()
    {
        $module = $this->getTestModule();
        $this->assertEquals(\XLite\RemoteModel\Marketplace::getInstance()->getMarketplaceURL() . 'module/TestAuthor', $module->getAuthorPageURL(), 'check module author URL');
    }*/

    public function testSetEnabled()
    {
        $module = $this->getEnabledModule();
        $module->setEnabled(false);
        \XLite\Core\Database::getEM()->flush();

        \XLite\Core\Database::getEM()->clear();
 
        $module = \XLite\Core\Database::getRepo('XLite\Model\Module')->find($module->getModuleId());
        $this->assertFalse($module->getEnabled(), 'check if module is disabled');
        
        $module->setEnabled(true);
        \XLite\Core\Database::getEM()->flush();
        $this->assertTrue($module->getEnabled(), 'check if module is enabled');
    }

    public function testProtectedStructures()
    {
        $path = LC_VAR_DIR . '.disabled.structures.php';

        if (file_exists($path)) {
            unlink($path);
        }

        $module = \Xlite\Core\Database::getRepo('XLite\Model\Module')
            ->findOneBy(array('author' => 'CDev', 'name' => 'ProductOptions'));
        $module->disableModule();
        \XLite\Core\Database::getEM()->flush();

        $this->assertFileExists($path, 'check that file .disabled.structures.php exists');

        $etalon = "# <?php if (!defined('LC_DS')) { die(); } ?>

CDev\ProductOptions:
  tables: [options, option_exceptions, option_groups, option_group_translations, option_surcharges, option_translations, order_item_options]
  columns: {  }";

        $subject = file($path);

        $etalon = explode("\n", $etalon);

        foreach ($etalon as $key => $str) {
            $this->assertEquals(trim($subject[$key]), trim($str), 'check file content (' . $path . ')');
        }
        
        if (file_exists($path)) {
            unlink($path);
        }
    }

    public function testPrepareUpdate()
    {
        // TODO: check if possible
    }

    public function testGetDependedModules()
    {
        // TODO: implement after module installation tests are done
    }

    public function testDisableModule()
    {
        $module = $this->getEnabledModule();
        $module->disableModule();
        $this->assertFalse($module->getEnabled(), 'check if module is disabled');
    }

    public function testGetSettingsFormLink()
    {
        $module = $this->getEnabledModule(true, 'FeaturedProducts');
        $this->assertEquals(
            \XLite\Core\Converter::buildURL('module', '', array('moduleId' => $module->getModuleId()), 'admin.php'),
            $module->getSettingsFormLink(),
            'check general settings form link'
        );
        
        $module = $this->getEnabledModule(true, 'AustraliaPost');
        $this->assertEquals('admin.php?target=aupost', $module->getSettingsFormLink(), 'check custom settings form link');
    }
    
    public function testGetMainClass()
    {
        $module = $this->getEnabledModule(true, 'FeaturedProducts');
        $this->assertEquals('\XLite\Module\CDev\FeaturedProducts\Main', $module->getMainClass(), 'check main class');
    }
    
    public function testGetDependenciesModules()
    {
        // TODO: implement after dependencies system refactoring
    }
    
    public function testCanEnable()
    {
        $module = $this->getEnabledModule();

        $module->setInstalled(false);
        $this->assertFalse($module->canEnable(), 'check if module can be enabled');

        // TODO: implement dependencies checker after dependencies system refactoring
        // TODO: implement checking of the static check() method of the main class
    }
    
    public function testGetHash()
    {
        // TODO: implement after all upload/install functions and tests are completed
    }

    public function testUninstall()
    {
        // TODO: after packing mechanism is completed
    }

    public function testAnonymousCall()
    {
        $module = $this->getEnabledModule(true, 'FeaturedProducts');

        $module->setModuleName('aaa');
        $module->setAuthorName('bbb');
        $module->setDescription('ccc');

        $this->assertEquals('This module enables featured products list', $module->__call('getDescription'), 'check description call');
        $this->assertEquals('Featured Products', $module->__call('getModuleName'), 'check name call');
        $this->assertEquals('Creative Development LLC', $module->__call('getAuthorName'), 'check author call');
    }
    
    public function testGetActualName()
    {
        $module = $this->getTestModule();
        $this->assertEquals('TestAuthor\\TestModule', $module->getActualName(), 'check actual name');
    }

    public function testGetPath()
    {
        $module = $this->getTestModule();
        $this->assertEquals('TestAuthor' . LC_DS . 'TestModule', $module->getPath(), 'check path');
    }
 
    public function testGetModel()
    {
        $module = $this->getTestModule();
        $this->assertEquals($module->getModel(), $module->model, 'check model');

    }

    public function testCanUpload()
    {
        $module = $this->getTestModule();
        $this->assertFalse($module->canUpload(), 'check if can upload');

        $module->setUploadCode('1234567890.1234567890.1234567890');
        $this->assertTrue($module->canUpload(), 'check if can upload');
    }

    public function testUpload()
    {
        // TODO:
    }
}

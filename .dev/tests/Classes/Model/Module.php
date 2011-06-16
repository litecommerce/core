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
 * XLite_Tests_Model_Module 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class XLite_Tests_Model_Module extends XLite_Tests_TestCase
{
    /**
     * Test 
     */
    const SOME_WRONG_VALUE = '____SOME_WRONG_VALUE____';

    /**
     * Test author 
     */
    const TEST_AUTHOR = 'CDev';

    /**
     * Test modules 
     */
    const TEST_MODULE_1 = 'DrupalConnector';
    const TEST_MODULE_2 = 'AustraliaPost';

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

        $this->doRestoreDb(__DIR__ . '/Repo/sql/module/setup.sql', false);
    }

    /**
     * testGetMainClass 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetMainClass()
    {
        $this->assertEquals(
            '\XLite\Module\\' . self::TEST_AUTHOR . '\\' . self::TEST_MODULE_1 . '\Main',
            $this->getTestModule1()->getMainClass(),
            'check module main class'
        );
    }

    /**
     * testCallModuleMethod 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testCallModuleMethod()
    {
        $moduleCorrect   = $this->getTestModule1();
        $moduleIncorrect = $this->getModuleByAuthorAndName(self::SOME_WRONG_VALUE, self::SOME_WRONG_VALUE);
        $installed       = $this->getRepo()->getModuleInstalled($moduleCorrect);

        // Check if found
        $this->assertNotNull($installed, 'check if installed');
        $this->assertNull($this->getRepo()->getModuleInstalled($moduleIncorrect), 'check if not installed');

        // Model
        $this->assertEquals(self::TEST_AUTHOR, $installed->getAuthor(), 'check installed module author');
        $this->assertEquals(self::TEST_MODULE_1, $installed->getName(), 'check installed module name');
        $this->assertEquals('1.0', $installed->getMajorVersion(), 'check installed module version');
        $this->assertEquals('0', $installed->getMinorVersion(), 'check installed module revision');

        // Main class
        $data = array(
            'getAuthorName' => 'Creative Development LLC',
            'getModuleName' => 'Drupal Connector',
        );
        foreach ($data as $method => $value) {
            foreach (array('moduleCorrect' => $value, 'moduleIncorrect' => self::SOME_WRONG_VALUE) as $module => $expected) {
                $this->assertEquals(
                    $expected,
                    $$module->callModuleMethod($method, self::SOME_WRONG_VALUE),
                    'check "' . $module . '" for the ' . '"' . $method . '"'
                );
            }
        }
    }

    /**
     * testGetActualName 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetActualName()
    {
        $this->assertEquals(
            self::TEST_AUTHOR . '\\' . self::TEST_MODULE_1,
            $this->getTestModule1()->getActualName(),
            'check module actual name'
        );
    }

    /**
     * testGetVersion 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetVersion()
    {
        $this->assertEquals('1.0.0', $this->getTestModule1()->getVersion(), 'check module version from DB');
    }

    /**
     * testHasIcon 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testHasIcon()
    {
        $this->assertFalse($this->getTestModule1()->hasIcon(), 'check if module has an icon');
    }

    /**
     * testGetSettingsForm 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetSettingsForm()
    {
        $module1 = $this->getTestModule1();
        $module2 = $this->getTestModule2();

        $this->assertEquals(
            'admin.php?target=module&moduleId=' . $module1->getModuleId(),
            $module1->getSettingsForm(),
            'check ' . self::TEST_MODULE_1 . ' settings form link'
        );
        $this->assertEquals(
            'admin.php?target=aupost',
            $module2->getSettingsForm(),
            'check ' . self::TEST_MODULE_2 . ' settings form link'
        );
    }

    /**
     * testGetDependencyModules 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetDependencyModules()
    {
        $module1 = $this->getTestModule1();
        $module2 = $this->getTestModule2();

        $this->assertEmpty($module1->getDependencyModules(), 'check ' . self::TEST_MODULE_1 . ' dependencies');
        $this->assertEmpty($module2->getDependencyModules(), 'check ' . self::TEST_MODULE_2 . ' dependencies');

        $dependencies = array(
            self::TEST_AUTHOR . '\\ProductOptions',
            self::TEST_AUTHOR . '\\FeaturedProducts',
        );
        $dependentModules = array();

        foreach ($dependencies as $dependency) {
            list($author, $name) = explode('\\', $dependency);
            $dependentModules[$dependency] = $this->getModuleByAuthorAndName($author, $name);
        }

        $module1->setDependencies($dependencies);
        $this->assertEquals($dependentModules, $module1->getDependencyModules(), 'check ' . self::TEST_MODULE_1 . ' dependencies [1]');
    }

    /**
     * testGetDependentModules 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetDependentModules()
    {
        $this->assertEmpty($this->getTestModule1()->getDependentModules(), 'check ' . self::TEST_MODULE_1 . ' dependent modules');
        $this->assertEmpty($this->getTestModule2()->getDependentModules(), 'check ' . self::TEST_MODULE_2 . ' dependent modules');
    }

    /**
     * testIsFree 
     * 
     * @return void
     * @see    ____func_see____
        * @since  1.0.0
     */
    public function testIsFree()
    {
        $module = $this->getTestModule1();
        $this->assertTrue($module->isFree(), 'check if module ' . self::TEST_MODULE_1 . ' is free [1]');

        $price = 3.14;
        $module->setPrice($price);
        $this->assertFalse($module->isFree(), 'check if module ' . self::TEST_MODULE_1 . ' is free [2]');
    }

    /**
     * testIsPurchased 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testIsPurchased()
    {
        $module = $this->getTestModule1();
        $this->assertFalse($module->isPurchased(), 'check if module ' . self::TEST_MODULE_1 . ' is purchased [1]');
    }

    /**
     * testIsCustom 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testIsCustom()
    {
        $module = $this->getTestModule1();
        $this->assertFalse($module->isCustom(), 'check if module ' . self::TEST_MODULE_1 . ' is custom [1]');
    }

    /**
     * testGetLicenseKey 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetLicenseKey()
    {
        $module = $this->getTestModule1();
        $this->assertNull($module->getLicenseKey(), 'check module ' . self::TEST_MODULE_1 . ' license key [1]');
    }

    /**
     * testGetRootDirectory 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetRootDirectory()
    {
        $this->assertEquals(
            LC_DIR_MODULES . self::TEST_AUTHOR . LC_DS . self::TEST_MODULE_1 . LC_DS,
            $this->getTestModule1()->getRootDirectory(),
            'check module ' . self::TEST_MODULE_1 . ' root directory'
        );
        $this->assertEquals(
            LC_DIR_MODULES . self::TEST_AUTHOR . LC_DS . self::TEST_MODULE_2 . LC_DS,
            $this->getTestModule2()->getRootDirectory(),
            'check module ' . self::TEST_MODULE_2 . ' root directory'
        );
    }

    /**
     * testGetPath 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetPath()
    {
        $this->assertEquals(
            self::TEST_AUTHOR . LC_DS . self::TEST_MODULE_1,
            $this->getTestModule1()->getPath(),
            'check module ' . self::TEST_MODULE_1 . ' relative path'
        );
        $this->assertEquals(
            self::TEST_AUTHOR . LC_DS . self::TEST_MODULE_2,
            $this->getTestModule2()->getPath(),
            'check module ' . self::TEST_MODULE_2 . ' relative path'
        );
    }

    /**
     * testGetCurrency 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetCurrency()
    {
        $module = $this->getTestModule1();
        $currency = \XLite\Core\Database::getRepo('\XLite\Model\Currency')->findOneByCode('USD');

        $this->assertEquals($currency, $module->getCurrency(), 'check module ' . self::TEST_MODULE_1 . ' currency [1]');

        $currency = 'Test';
        $module->setCurrency($currency);
        $this->assertEquals($currency, $module->getCurrency(), 'check module ' . self::TEST_MODULE_1 . ' currency [2]');
    }

    /**
     * testIsInstalled 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testIsInstalled()
    {
        $module = $this->getTestModule1();
        $this->assertTrue($module->isInstalled(), 'check if module ' . self::TEST_MODULE_1 . ' is installed [1]');

        $module->setInstalled(false);
        $this->assertTrue($module->isInstalled(), 'check if module ' . self::TEST_MODULE_1 . ' is installed [2]');

        $module = new \XLite\Model\Module();

        $module->setInstalled(true);
        $this->assertTrue($module->isInstalled(), 'check if the fake module is installed [1]');

        $module->setInstalled(false);
        $this->assertFalse($module->isInstalled(), 'check if the fake module is installed [2]');
    }

    /**
     * testGetSet 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetSet()
    {
        $module = new \XLite\Model\Module();

        $data = array(
            'name'          => 'Module1',
            'author'        => 'Test',
            'enabled'       => true,
            'installed'     => true,
            'date'          => time(),
            'rating'        => 3.56,
            'votes'         => 13,
            'downloads'     => 123,
            'price'         => 3.12,
            'majorVersion'  => '1.0',
            'minorVersion'  => '1',
            'revisionDate'  => time() - 10,
            'packSize'      => 12345,
            'moduleName'    => 'Test module',
            'authorName'    => 'Test author',
            'description'   => 'Description',
            'iconURL'       => 'http:://www.example.com/1',
            'pageURL'       => 'http:://www.example.com/2',
            'authorPageURL' => 'http:://www.example.com/3',
            'dependencies'  => array('CDev\DrupalConnector', 'CDev\AustraliaPost'),
        );

        foreach ($data as $name => $value) {
            $module->{'set' . ucfirst($name)}($value);
            $this->assertEquals($value, $module->{'get' . ucfirst($name)}(), 'check getter/setter for the "' . $name . '" field');
        }
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
     * getTestModule1 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTestModule1()
    {
        return $this->getModuleByAuthorAndName(self::TEST_AUTHOR, self::TEST_MODULE_1, '1.0', '0');
    }

    /**
     * getTestModule1
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTestModule2()
    {
        return $this->getModuleByAuthorAndName(self::TEST_AUTHOR, self::TEST_MODULE_2, '1.0', '0');
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

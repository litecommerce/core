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
     * testGetMainClass 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetMainClass()
    {
        $this->assertEquals('\XLite\Module\CDev\DrupalConnector\Main', $this->getTestModule()->getMainClass(), 'check module main class');
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
        $moduleCorrect   = $this->getTestModule();
        $moduleIncorrect = $this->getModuleByAuthorAndName(self::SOME_WRONG_VALUE, self::SOME_WRONG_VALUE);
        $installed       = $this->getRepo()->getModuleInstalled($moduleCorrect);

        // Check if found
        $this->assertNotNull($installed, 'check if installed');
        $this->assertNull($this->getRepo()->getModuleInstalled($moduleIncorrect), 'check if not installed');

        // Model
        $this->assertEquals('CDev', $installed->getAuthor(), 'check installed module author');
        $this->assertEquals('DrupalConnector', $installed->getName(), 'check installed module name');
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
                    'check ' . $module . 'for' . '"' . $method . '"'
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
        $this->assertEquals('CDev\DrupalConnector', $this->getTestModule()->getActualName(), 'check module actual name');
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
        $this->assertEquals('1.0.0', $this->getTestModule()->getVersion(), 'check module version from DB');
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
        $this->assertFalse($this->getTestModule()->hasIcon(), 'check if module has an icon');
    }

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
     * getTestModule 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTestModule()
    {
        return $this->getModuleByAuthorAndName('CDev', 'DrupalConnector', '1.0', '0');
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
        $module = new \XLite\Model\Module();

        $module->setAuthor($author);
        $module->setName($name);

        if (isset($version)) {
            $module->setMajorVersion($version);

            if (isset($revision)) {
                $module->setMinorVersion($revision);
            }
        }

        return $module;
    }
}

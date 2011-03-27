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

/**
 * XLite_Tests_RemoteModel_Marketplace 
 * 
 * @see   ____class_see____
 * @since 3.0.0
 */
class XLite_Tests_RemoteModel_Marketplace extends XLite_Tests_TestCase
{
    /**
     * Test modules names 
     */
    const MODULE1_NAME = 'MegaModule48';
    const MODULE2_NAME = 'MegaModule49';

    /**
     * Test author name 
     */
    const AUTHOR = 'Igoryan';


    /**
     * Module identificators container
     * 
     * @var    mixed
     * @access private
     * @see    ____var_see____
     * @since  3.0.0
     */
    private $moduleId = null;


    protected function setUp()
    {
        $this->markTestSkipped('Refactoring is required');
    }

    /**
     * Return module identificator of module with specific name and 'Igoryan' author
     * 
     * @param mixed $name ____param_comment____
     *  
     * @return void
     * @access private
     * @see    ____func_see____
     * @since  3.0.0
     */
    private function getModuleId($name)
    {
        if (empty($this->moduleId[$name])) {

            $module = \XLite\Core\Database::getRepo('\XLite\Model\Module')->findOneBy(
                array(
                    'name'   => $name,
                    'author' => self::AUTHOR,
                )   
            );

            $this->assertTrue(is_object($module), 'There is no "' . $name . '" module');

            $this->moduleId[$name] = $module->getModuleId();
        }

        return $this->moduleId[$name];
    }

    /**
     * Get content of file in the 'marketplace_files' catalog (specific for test purposes)
     * 
     * @param string $name filename
     *  
     * @return string
     * @access private
     * @see    ____func_see____
     * @since  3.0.0
     */
    private function getFile($name)
    {
        $filename = dirname(__FILE__) . LC_DS . 'marketplace_files' . LC_DS . $name;

        return (is_file($filename) && is_readable($filename)) ? file_get_contents($filename) : false;
    }

    /**
     * Test of correct marketplace URL
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetURL()
    {
        \XLite\Core\Database::getRepo('\XLite\Model\Module')->checkModules();

        $this->assertEquals(
            \XLite::getInstance()->getOptions(array('debug', 'marketplace_dev_url')),
            \XLite\RemoteModel\Marketplace::getInstance()->getMarketplaceURL(),
            'Wrong Marketplace URL'
        );
    }

    /**
     * Test of request for LICENSE text for module
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testLicense()
    {
        $testLicense = $this->getFile('test.license');

        $this->assertEquals(
            $testLicense, 
            \XLite\RemoteModel\Marketplace::getInstance()->getLicense(
                $this->getModuleId(self::MODULE1_NAME)
            ), 
            'Wrong license for ' . $this->getModuleId(self::MODULE1_NAME) . ' module'
        );

        $this->assertEquals('No license', \XLite\RemoteModel\Marketplace::getInstance()->getLicense(200), 'Wrong license for absent module');
    }

    /**
     * Test of request for full addons list (XML format)
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testAddonsXML()
    {
        $testXML = $this->getFile('addons.xml');

        $this->assertEquals($testXML, \XLite\RemoteModel\Marketplace::getInstance()->getAddonsXML(), 'Wrong Addons XML');
    }

    /**
     * Test of retrieving module into local repository
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testRetrieveToLocalRepository()
    {
        $filename = 'Igoryan_MegaModule49.phar';

        $fullFilename = LC_LOCAL_REPOSITORY . $filename; 

        if (is_file($fullFilename)) {

            @unlink($fullFilename);
        }

        $result = \XLite\RemoteModel\Marketplace::getInstance()->retrieveToLocalRepository($this->getModuleId(self::MODULE2_NAME)); 

        $this->assertEquals($result, $filename, 'Wrong file name');

        $this->assertTrue(is_file($fullFilename) && is_readable($fullFilename), 'No addons file is downloaded');

        @unlink($fullFilename);
    }

    /**
     * Test of request for last version of addons list
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testLastVersion()
    {
        $this->assertEquals(\XLite\RemoteModel\Marketplace::getInstance()->getLastVersion(), 'xlite_3_0_x', 'Wrong last version');
    }

    /**
     * Test of request for module info by license key checking. 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testModuleInfoByKey()
    {
        $this->assertEquals(
            \XLite\RemoteModel\Marketplace::getInstance()->getModuleInfoByKey('test1'),
            array(
                'module' => self::MODULE1_NAME,
                'author' => self::AUTHOR,
            ),
            'Wrong module info for test1 key'
        );

        $this->assertEquals(
            \XLite\RemoteModel\Marketplace::getInstance()->getModuleInfoByKey('testtesttest'),
            array(
                'error' => 'No such license key',
            ),
            'Wrong module info for wrong key'
        );
    }
}

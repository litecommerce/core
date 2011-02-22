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

class XLite_Tests_RemoteModel_Marketplace extends XLite_Tests_TestCase
{
    // TODO use test marketplace URL
    const URL = 'https://dev.qtmsoft.com/~joy/marketplace/';
//  const URL = 'https://www.litecommerce.com/marketplace/';

    private function getFile($name)
    {
        $filename = dirname(__FILE__) . LC_DS . 'marketplace_files' . LC_DS . $name;
        return (is_file($filename) && is_readable($filename)) ? file_get_contents($filename) : false;
    }

    public function testGetURL()
    {
        $this->assertEquals(self::URL, \XLite\RemoteModel\Marketplace::getInstance()->getMarketplaceURL(), 'Wrong Marketplace URL');
    }

    public function testLicense()
    {
        $testLicense = $this->getFile('test.license');

        $this->assertEquals($testLicense, \XLite\RemoteModel\Marketplace::getInstance()->getLicense(91), 'Wrong license for 91 module');

        $this->assertEquals('No license', \XLite\RemoteModel\Marketplace::getInstance()->getLicense(200), 'Wrong license for absent module');
    }

    public function testAddonsXML()
    {
        $testXML = $this->getFile('addons.xml');

        $this->assertEquals($testXML, \XLite\RemoteModel\Marketplace::getInstance()->getAddonsXML(), 'Wrong Addons XML');
    }

    public function testRetrieveToLocalRepository()
    {
        $filename = 'Igoryan_MegaModule49.phar';
        $fullFilename = LC_LOCAL_REPOSITORY . $filename; 

        if (is_file($fullFilename)) {

            @unlink($fullFilename);
        }

        $result = \XLite\RemoteModel\Marketplace::getInstance()->retrieveToLocalRepository(92); 

        $this->assertEquals($result, $filename, 'Wrong file name');

        $this->assertTrue(is_file($fullFilename) && is_readable($fullFilename), 'No addons file is downloaded');

        @unlink($fullFilename);
    }

    public function testLastVersion()
    {
        $this->assertEquals(\XLite\RemoteModel\Marketplace::getInstance()->getLastVersion(), '', 'Wrong last version');
    }

    public function testModuleInfoByKey()
    {
        $this->assertEquals(
            \XLite\RemoteModel\Marketplace::getInstance()->getModuleInfoByKey('test1'),
            array(
                'module' => 'MegaModule48',
                'author' => 'Igoryan',
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

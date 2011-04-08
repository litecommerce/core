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
 * @since      1.0.0
 */

/**
 * XLite_Tests_Model_PHARModule 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   1.0.0
 */
class XLite_Tests_Model_PHARModule extends XLite_Tests_TestCase
{
    /**
     * setUp 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function setUp()
    {
        parent::setUp();

        \Includes\Utils\FileManager::mkdirRecursive(LC_LOCAL_REPOSITORY);
    }

    /**
     * getFile 
     * 
     * @param mixed $file ____param_comment____
     *  
     * @return void
     * @access private
     * @see    ____func_see____
     * @since  1.0.0
     */
    private function getFile($file)
    {
        return __DIR__ . LC_DS . 'phars' . LC_DS . $file;
    }


    /**
     * testBadConstruct 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testBadConstruct()
    {
        copy($this->getFile('bad.phar'), LC_LOCAL_REPOSITORY . 'bad.phar');

        $phar = new \XLite\Model\PHARModule('bad.phar');
        $this->assertEquals(
            $phar->getStatus(),
            \XLite\Model\PHARModule::STATUS_EXCEPTION,
            'must be corrupted PHAR'
        );

        $this->assertEquals(
            $phar->getMessage(),
            'internal corruption of phar "' . LC_LOCAL_REPOSITORY . 'bad.phar" (truncated entry)',
            'Invalid message for the corrupted PHAR exception'
        );
        
        unlink(LC_LOCAL_REPOSITORY . 'bad.phar');

        $phar = new \XLite\Model\PHARModule('bad-bad-bad-name.phar');

        $this->assertEquals(
            $phar->getStatus(),
            \XLite\Model\PHARModule::STATUS_FILE_NOT_EXISTS,
            'Wrong error when addons catalog/file does not exist'
        );
        $this->assertEquals(
            $phar->getMessage(),
            'PHAR file not exists in local repository',
            'Wrong error when addons catalog/file does not exist'
        );
    }

    /**
     * testGoodConstruct 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGoodConstruct()
    {
        copy($this->getFile('good.phar'), LC_LOCAL_REPOSITORY . 'good.phar');

        $message = '';

        try {

            $phar = new \XLite\Model\PHARModule('good.phar');

        } catch (\Exception $e) {

            $message = $e->getMessage();
        }

        $this->assertEquals('', $message, 'There must be no exceptions in the "Good" module');
        $this->assertTrue(is_object($phar) && !is_null($phar), 'PHAR object was not constructed');

        $phar->cleanUp();

        // Unset is required to release file. Else this file will not be removed under Windows
        unset($phar);

        unlink(LC_LOCAL_REPOSITORY . 'good.phar');
    }

    /**
     * testCheck 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testCheck()
    {
        // Already installed module checking
        /*copy($this->getFile('already.phar'), LC_LOCAL_REPOSITORY . 'already.phar');
        $phar = new \XLite\Model\PHARModule('already.phar');

        $this->assertEquals(
            $phar->getStatus(),
            \XLite\Model\PHARModule::STATUS_ROOT_DIR_EXISTS,
            'Wrong status for already installed module'
        );

        $phar->cleanUp();
        unlink(LC_LOCAL_REPOSITORY . 'already.phar');*/
    }

    /**
     * testDeploy 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    /*public function testDeploy()
    {
        // Deploying only classes directory module
        copy($this->getFile('test_module.phar'), LC_LOCAL_REPOSITORY . 'test_module.phar');

        $phar = new \XLite\Model\PHARModule('test_module.phar');

        $this->assertEquals(
            $phar->getStatus(),
            \XLite\Model\PHARModule::STATUS_OK,
            'Good module must be validated'
        );

        $phar->deploy();

        $phar->cleanUp();

        $dir = LC_CLASSES_DIR . 'XLite' . LC_DS . 'Module' . LC_DS . 'CDev' . LC_DS . 'Good';

        $this->assertTrue(is_dir($dir), 'Classes catalog was not created');

        $files = array(
            'CHANGELOG',
            'Main.php',
            'install.php',
            'install.yaml',
        );

        foreach ($files as $file) {
            $this->assertTrue(is_file($dir . LC_DS . $file));
        }

        \Includes\Utils\FileManager::unlinkRecursive($dir);

        unlink(LC_LOCAL_REPOSITORY . 'test_module.phar');
    }*/

    /**
     * testDeploy2 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testDeploy2()
    {
        // Deploying classes and skins directories module
        /*copy($this->getFile('test_module2.phar'), LC_LOCAL_REPOSITORY . 'test_module2.phar');

        $phar = new \XLite\Model\PHARModule('test_module2.phar');

        $this->assertEquals(
            $phar->getStatus(),
            \XLite\Model\PHARModule::STATUS_OK,
            'new Bestsellers module must be validated'
        );

        $phar->deploy();

        $phar->cleanUp();

        $classesDir = LC_CLASSES_DIR . 'XLite' . LC_DS . 'Module' . LC_DS . 'TestAuthor';

        $skins = array(
            LC_SKINS_DIR . 'admin' . LC_DS . 'en' . LC_DS . 'modules' . LC_DS . 'TestAuthor',
            LC_SKINS_DIR . 'drupal' . LC_DS . 'en' . LC_DS . 'modules' . LC_DS . 'TestAuthor',
        );

        $this->assertTrue(is_dir($classesDir . LC_DS . 'Bestsellers'), 'Classes catalog was not created');

        foreach ($skins as $skin) {
            $this->assertTrue(is_dir($skin . LC_DS . 'Bestsellers'), $skin . ' was not created');
        }

        \Includes\Utils\FileManager::unlinkRecursive($classesDir);

        foreach ($skins as $skin) {
            \Includes\Utils\FileManager::unlinkRecursive($skin);
        }

        unlink(LC_LOCAL_REPOSITORY . 'test_module2.phar');*/
    }


    /**
     * testCleanUp 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testCleanUp()
    {
    }
}

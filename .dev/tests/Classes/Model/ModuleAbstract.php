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

abstract class XLite_Tests_Model_ModuleAbstract extends XLite_Tests_TestCase
{
    protected $testModule = array(
        'name'          => 'TestModule',
        'author'        => 'TestAuthor',
        'moduleName'    => 'Test module name',
        'authorName'    => 'Test module author',
        'description'   => 'Test module description',
        'installed'     => true,
        'version'       => '1.2.3',
        'rating'        => 100,
        'downloads'     => 10,
        'iconURL'       => 'icon/url/test/path',
        'date'          => 1295513912,
        'changelog'     => 'Test changelog',
        'currency'      => 'RUB',
        'purchased'     => false,
        'price'         => 10.11,
        'uploadCode'    => 'bcd',
        'dependencies'  => array(
            array('CDev', 'FeaturedProducts'),
            array('CDev', 'AOM')
        ),
        'packHash'      => '123123',
// TODO:
//        'hash'          => '',
//        'enabled'       => true,
    );

    /**
     * setUp
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function setUp()
    {
        parent::setUp();

        $this->markTestSkipped('Awaiting for new marketplace');
        
        \XLite\Core\Database::getEM()->clear();

        $this->query(file_get_contents(__DIR__ . '/Repo/sql/module/setup.sql'));
        \XLite\Core\Database::getEM()->flush();
    }

    protected function getTestModule()
    {
        $module = new \XLite\Model\Module();

        $module->map($this->testModule);
        \XLite\Core\Database::getEM()->persist($module);
        \XLite\Core\Database::getEM()->flush();

        return $module;
    }

    protected function getEnabledModule($val = true, $name = null)
    {
        $conditions = array(
            'enabled'   => $val,
            'installed' => true,
        );

        if (isset($name)) {
            $conditions['name'] = $name;
        }

        return \XLite\Core\Database::getRepo('XLite\Model\Module')->findOneBy($conditions);
    }
}

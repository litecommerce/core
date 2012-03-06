<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * XLite\Core\DataSource\Ecwid classes tests
 *
 * @category   LiteCommerce
 * @package    Tests
 * @subpackage Classes
 * @author     Creative Development LLC <info@cdev.ru>
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.3
 */

/**
 * XLite_Tests_Core_DataSource_Ecwid 
 * 
 * @see   ____class_see____
 * @since 1.0.17
 */
class XLite_Tests_Core_DataSource_Ecwid extends XLite_Tests_TestCase
{

    /**
     * Test various Ecwid API calls
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.17
     */
    public function testCallApi()
    {
        $model = new \XLite\Model\DataSource();
        $model->setParameterValue('storeid', 1003);
        $model->setType(\XLite\Model\DataSource::TYPE_ECWID);

        $ecwid = $model->detectSource();

        $this->assertTrue($ecwid->isValid());

        $result = $ecwid->callApi('products');

        // Must be an array of products
        $this->assertInternalType('array', $result);

        $this->assertNotEmpty($result, 0, 'Array of products mustn\'t be empty');
    }

    /**
     * testGetInfo 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.17
     */
    public function testGetInfo()
    {
        $model = new \XLite\Model\DataSource();
        $model->setParameterValue('storeid', 1003);
        $model->setType(\XLite\Model\DataSource::TYPE_ECWID);

        $ecwid = $model->detectSource();

        $this->assertTrue($ecwid->isValid());

        $info = $ecwid->getInfo();
        $this->assertEquals('Ecwid Demo Store', $info['storeName']);
    }

}

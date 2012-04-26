<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 *XLite\Core\DataSource\Ecwid\Products class tests
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
 * XLite_Tests_Core_DataSource_Ecwid_Products
 * 
 * @see   ____class_see____
 * @since 1.0.17
 */
class XLite_Tests_Core_DataSource_Ecwid_Products extends XLite_Tests_TestCase
{

    protected $requiredFields = array(
        'id',
        'sku',
        'name',
        'price',
        'url',
        'description',
        'categories',
    );

    /**
     * Test Ecwid products collection iterator
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.17
     */
    public function testProducts()
    {
        $model = new \XLite\Model\DataSource();
        $model->setParameterValue('storeid', 1003);
        $model->setType(\XLite\Model\DataSource::TYPE_ECWID);

        $ecwid = $model->detectSource();

        $this->assertTrue($ecwid->isValid());

        $products = $ecwid->getProductsCollection();

        $this->assertNotEmpty($products->count());

        $this->assertTrue($products->isValid());

        $firstProduct = $products->current();
        foreach ($this->requiredFields as $f) {
            $this->assertNotEmpty($firstProduct[$f]);
        }
    }

}

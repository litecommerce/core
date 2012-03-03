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

    /**
     * Test Ecwid products collection iterator
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.17
     */
    public function testProducts()
    {
        $source = new \XLite\Model\DataSource();
        // Apply store id here

        $ecwid = new \XLite\Core\DataSource\Ecwid($source);

        $this->assertTrue($ecwid->isValid());

        $products = $ecwid->getProductsCollection();

        $this->assertGreaterThan(0, $products->count());

        for ($key = $products->key(); $products->valid(); $products->next()) {
            $product = $products->current();

            $this->assertGreaterThan(0, $product['id']);
        }
    }

}

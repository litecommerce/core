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
 * @subpackage Web
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

require_once __DIR__ . '/ACustomer.php';

class XLite_Web_Customer_AddedMark extends XLite_Web_Customer_ACustomer
{
    /**
     * Test whether the mark is displaed for products added to the cart from the product page
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testAddedMarkAfterRefresh()
    {
        $this->openCategoryPage();
        $id = $this->getProductId(1);
    
        $this->assertElementNotPresent(
            "css=.category-products .productid-$id.product-added .added-to-cart",
            "Added To Cart mark is shown for a product not in the cart"
        );
    
        $this->clickAndWait("css=.category-products .productid-$id a");

        $this->type("css=form.product-details input.quantity", "1");            
        $this->submitAndWait("css=form.product-details");

        $this->openCategoryPage();

        $this->assertElementPresent(
            "css=.category-products .productid-$id.product-added .added-to-cart",
            "Added To Cart mark is shown for a product not in the cart"
        );
 
    }

    /**
     * Test whether the mark is displayed for products added to the cart by AJAX drag'n'drop
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testAddedMarkAfterDrag()
    {
        $products = $this->openCategoryPage();
        $id1 = $this->getProductId(1);
        $id2 = $this->getProductId(2);

        $this->assertElementNotPresent(
            "css=.category-products .productid-$id1.product-added .added-to-cart",
            "Added To Cart mark is shown for a product not in the cart"
        );
 
        $this->dragToCart($id1, $id2);

        $this->assertElementPresent(
            "css=.category-products .productid-$id1.product-added .added-to-cart",
            "Added To Cart mark is shown for a product not in the cart"
        );

    }

    /**
     * Drag a product and drop it into the minicart tray
     * 
     * @param integer $id1 ID of the product to drag
     * @param integer $id2 ID of the product to move the first product over when dragging to the cart
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function dragToCart($id1, $id2)
    {
        $this->assertElementNotPresent('css=.cart-tray.cart-tray-adding');

        $this->windowMaximize();

        $this->dragAndDropDelay("css=.productid-$id1", "css=.productid-$id2", "css=.cart-tray");

        $this->waitForLocalCondition(
            "jQuery('.cart-tray.cart-tray-adding').length == 1",
            3000
        );

        $this->waitForLocalCondition(
            "jQuery('.cart-tray.cart-tray-added').length == 1",
            20000
        );

        $this->waitForLocalCondition(
            "jQuery('.cart-tray.cart-tray-added').length == 0",
            6000
        );

        $this->waitForLocalCondition(
            "jQuery('.minicart-items-number').html() == '1'",
            20000
        );
    }

    /**
     * Open a test category page and return products from the category
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function openCategoryPage()
    {
        $this->skipCoverage();

        $product = $this->getActiveProduct();
        $category = $product->getCategory();
        $this->assertNotNull($category, '$product->getCategory() returned null');

        $this->open('store/category//category_id-' . $category->getCategoryId());

    }

    /**
     * Get ID of a listed product
     * 
     * @param integer $n Number of the product in the list
     *  
     * @return integer
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getProductId($n)
    {
        $class = $this->getJSExpression("jQuery('.category-products .product').eq($n).attr('class')");
        $id = intval(preg_replace('/^.*productid-([\d]+).*$/', '\\1', $class));

        $this->assertTrue($id>0, "Wrong ID for the product number $n");

        return $id;
    }

}

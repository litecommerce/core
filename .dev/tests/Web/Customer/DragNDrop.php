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

class XLite_Web_Customer_DragNDrop extends XLite_Web_Customer_ACustomer
{
    public function testStructure()
    {
        $product = $this->openCategoryPage();

        $this->assertElementPresent(
            "//div[@class='cart-tray ui-droppable']"
            . "/div[@class='tray-area']"
            . "/div[@class='drop-here tray-status']"
        );
        $this->assertElementPresent(
            "//div[@class='cart-tray ui-droppable']"
            . "/div[@class='tray-area']"
            . "/div[@class='product-added tray-status']"
        );
        $this->assertElementPresent(
            "//div[@class='cart-tray ui-droppable']"
            . "/div[@class='tray-area']"
            . "/div[@class='progress-bar']"
            . "/div[@class='block-wait']"
        );
    }

    public function testSimpleDrag()
    {
        $product = $this->openCategoryPage();

        $list = $product->getCategory()->getProducts();
        $product = $list[0];

        $this->assertElementNotPresent('css=.cart-tray.cart-tray-adding');

        $this->windowMaximize();

        $this->dragAndDropDelay(
            'css=.productid-' . $product->getProductId(),
            'css=.productid-' . $list[1]->getProductId(),
            'css=.cart-tray'
        );

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

    public function testDoubleDrag()
    {
        $product = $this->openCategoryPage();

        $list = $product->getCategory()->getProducts();
        $product = $list[0];

        $this->assertElementNotPresent('css=.cart-tray.cart-tray-adding');

        $this->windowMaximize();

        $this->dragAndDropDelay(
            'css=.productid-' . $product->getProductId(),
            'css=.productid-' . $list[1]->getProductId(),
            'css=.cart-tray'
        );
        $this->dragAndDropDelay(
            'css=.productid-' . $product->getProductId(),
            'css=.productid-' . $list[1]->getProductId(),
            'css=.cart-tray'
        );

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
            "jQuery('.minicart-items-number').html() == '2'",
            20000
        );
    }

    public function testDelayedDrag()
    {
        $product = $this->openCategoryPage();

        $list = $product->getCategory()->getProducts();
        $product = $list[0];

        $this->assertElementNotPresent('css=.cart-tray.cart-tray-adding');

        $this->windowMaximize();

        $this->dragAndDropDelay(
            'css=.productid-' . $product->getProductId(),
            'css=.productid-' . $list[1]->getProductId(),
            'css=.cart-tray'
        );

        $this->waitForLocalCondition(
            "jQuery('.cart-tray.cart-tray-adding').length == 1",
            3000
        );

        $this->waitForLocalCondition(
            "jQuery('.cart-tray.cart-tray-added').length == 1",
            20000
        );
        $this->dragAndDropDelay(
            'css=.productid-' . $product->getProductId(),
            'css=.productid-' . $list[1]->getProductId(),
            'css=.cart-tray'
        );

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
            "jQuery('.minicart-items-number').html() == '2'",
            20000
        );
    }

    protected function openCategoryPage()
    {
        $this->skipCoverage();

        $product = $this->getActiveProduct();

        $category = $product->getCategory();

        $this->assertNotNull($category, '$product->getCategory() returned null');

        $this->open('store/category//category_id-' . $category->getCategoryId());

        return $product;
    }
}

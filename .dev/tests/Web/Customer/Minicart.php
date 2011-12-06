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
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 *
 * @use product
 */

require_once __DIR__ . '/ACustomer.php';

class XLite_Web_Customer_Minicart extends XLite_Web_Customer_ACustomer
{
    public function testEmptyCart()
    {
        $this->skipCoverage();

        $product = $this->getActiveProduct();

        $this->open('store/product//product_id-' . $product->getProductId());

        // Empty
        $this->assertElementPresent("//div[@id='lc-minicart-horizontal']/div[@class='minicart-items-number' and text()='0']");
        $this->assertElementPresent("//div[@id='lc-minicart-horizontal']/div[@class='minicart-items-text' and text()='items']");
    }

    public function testAddToCart()
    {
        $this->skipCoverage();

        $product = $this->getActiveProduct();

        $this->open('store/product//product_id-' . $product->getProductId());

        $this->assertElementPresent("//button[@type='submit']/span[text()='Add to Bag']", 'check Add to Bag button');
        $this->click("//button[@type='submit' and @class='bright add2cart']");
        $this->waitForLocalCondition(
            'jQuery(".lc-minicart-horizontal .minicart-items-number").html() == "1"',
            100000,
            'wait minicart'
        );

        $this->assertElementPresent(
            "//div[@id='lc-minicart-horizontal']"
            . "/div[@class='minicart-items-number' and text()='1']"
        );

        $this->assertElementPresent(
            "//div[@id='lc-minicart-horizontal']"
            . "/div[@class='internal-popup items-list']"
            . "/p[@class='title']"
            . "/a[text()='1 items in bag']"
        );

        $this->assertElementPresent(
            "//div[@id='lc-minicart-horizontal']"
            . "/div[@class='internal-popup items-list']"
            . "/ul"
            . "/li"
            . "/span"
            . "/a[text()='" . $product->getName() . "']"
        );

        $this->assertElementPresent(
            "//div[@id='lc-minicart-horizontal']"
            . "/div[@class='internal-popup items-list']"
            . "/ul"
            . "/li"
            . "/div[@class='item-price' and text()='$" . number_format(round($product->getPrice(), 2), 2) . " x 1']"
        );

        $this->assertElementPresent(
            "//div[@id='lc-minicart-horizontal']"
            . "/div[@class='internal-popup items-list']"
            . "/div[@class='cart-checkout']"
            . "/button"
            . "/span[text()='Checkout']"
        );
    }

    public function testAddToCartOnceMore()
    {
        $this->skipCoverage();

        $product = $this->getActiveProduct();

        $this->open('store/product//product_id-' . $product->getProductId());
        $this->click("//button[@type='submit' and @class='bright add2cart']");
        $this->waitForLocalCondition(
            'jQuery(".lc-minicart-horizontal .minicart-items-number").html() == "1"',
            10000,
            'wait minicart'
        );

        $this->open('store/product//product_id-' . $product->getProductId());
        $this->click("//button[@type='submit' and @class='action buy-more']");
        $this->waitForLocalCondition(
            'jQuery(".lc-minicart-horizontal .minicart-items-number").html() == "2"',
            10000,
            'wait minicart'
        );

        $this->assertElementPresent(
            "//div[@id='lc-minicart-horizontal']"
            . "/div[@class='minicart-items-number' and text()='2']"
        );

        $this->assertElementPresent(
            "//div[@id='lc-minicart-horizontal']"
            . "/div[@class='internal-popup items-list']"
            . "/p[@class='title']"
            . "/a[text()='2 items in bag']"
        );

        $this->assertElementPresent(
            "//div[@id='lc-minicart-horizontal']"
            . "/div[@class='internal-popup items-list']"
            . "/ul"
            . "/li"
            . "/span"
            . "/a[text()='" . $product->getName() . "']"
        );

        $this->assertElementPresent(
            "//div[@id='lc-minicart-horizontal']"
            . "/div[@class='internal-popup items-list']"
            . "/ul"
            . "/li"
            . "/div[@class='item-price' and text()='$" . number_format(round($product->getPrice(), 2), 2) . " x 2']"
        );
    }

    public function testAddToCartOnceMore2()
    {
        $this->skipCoverage();

        $product = $this->getActiveProduct();

        $this->open('store/product//product_id-' . $product->getProductId());
        $this->click("//button[@type='submit' and @class='bright add2cart']");
        $this->waitForLocalCondition(
            'jQuery(".lc-minicart-horizontal .minicart-items-number").html() == "1"',
            10000,
            'wait minicart'
        );

        $this->open('store/product//product_id-' . $product->getProductId());
        $this->click("//button[@type='submit' and @class='action buy-more']");
        $this->waitForLocalCondition(
            'jQuery(".lc-minicart-horizontal .minicart-items-number").html() == "2"',
            10000,
            'wait minicart'
        );

        foreach ($this->getActiveProducts() as $p) {
            if ($p->getProductId() != $product->getProductId()) {
                $product = $p;
                break;
            }
        }

        $this->open('store/product//product_id-' . $product->getProductId());
        $this->click("//button[@type='submit' and @class='bright add2cart']");
        $this->waitForLocalCondition(
            'jQuery(".lc-minicart-horizontal .minicart-items-number").html() == "3"',
            10000,
            'wait minicart'
        );

        $this->assertElementPresent(
            "//div[@id='lc-minicart-horizontal']"
            . "/div[@class='minicart-items-number' and text()='3']"
        );

        $this->assertElementPresent(
            "//div[@id='lc-minicart-horizontal']"
            . "/div[@class='internal-popup items-list']"
            . "/p[@class='title']"
            . "/a[text()='3 items in bag']"
        );

        $this->assertElementPresent(
            "//div[@id='lc-minicart-horizontal']"
            . "/div[@class='internal-popup items-list']"
            . "/ul"
            . "/li"
            . "/span"
            . "/a[text()='" . $product->getName() . "']"
        );

        $this->assertElementPresent(
            "//div[@id='lc-minicart-horizontal']"
            . "/div[@class='internal-popup items-list']"
            . "/ul"
            . "/li"
            . "/div[@class='item-price' and text()='$" . number_format(round($product->getPrice(), 2), 2) . " x 1']"
        );
    }

    public function testOpenClose()
    {
        $this->skipCoverage();

        $product = $this->getActiveProduct();

        $this->open('store/product//product_id-' . $product->getProductId());
        $this->click("//button[@type='submit' and @class='bright add2cart']");
        $this->waitForLocalCondition(
            'jQuery(".lc-minicart-horizontal .minicart-items-number").html() == "1"',
            10000,
            'wait minicart'
        );

        $this->assertJqueryNotPresent(
            '#lc-minicart-horizontal .items-list:visible',
            'check closed popup'
        );

        $this->click("//div[@id='lc-minicart-horizontal']");

        $this->assertJqueryPresent(
            '#lc-minicart-horizontal .items-list:visible',
            'check open popup'
        );

        $this->click("//div[@id='lc-minicart-horizontal']");

        $this->assertJqueryNotPresent(
            '#lc-minicart-horizontal .items-list:visible',
            'check closed popup #2'
        );

        $this->click("//div[@id='lc-minicart-horizontal']");
        $this->click("//div[@id='page']");

        $this->assertJqueryNotPresent(
            '#lc-minicart-horizontal .items-list:visible',
            'check closed popup #3'
        );
    }

}

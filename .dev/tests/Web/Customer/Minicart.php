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

class XLite_Web_Customer_Minicart extends XLite_Web_Customer_ACustomer
{
    public function testEmptyCart()
    {
        $this->logOut();

        $this->open('store/cart/clear');
        $this->open('store/cart');

        // Empty
        $this->assertElementPresent("//div[@id='lc-minicart-horizontal']/div[@class='minicart-items-number' and text()='0']");
        $this->assertElementPresent("//div[@id='lc-minicart-horizontal']/div[@class='minicart-items-text' and text()='items']");

        $this->validate();
    }

    public function testAddToCart()
    {
        $product = $this->getActiveProduct();

        $this->open('store/product//product_id-' . $product->getProductId());

        $this->assertElementPresent("//button[@type='submit']/span[text()='Add to Cart']");
        $this->clickAndWait("//button[@type='submit']/span[text()='Add to Cart']");

        $this->assertElementPresent(
            "//div[@id='lc-minicart-horizontal']"
            . "/div[@class='minicart-items-number' and text()='1']"
        );

        $this->assertElementPresent(
            "//div[@id='lc-minicart-horizontal']"
            . "/div[@class='popup items-list']"
            . "/p[@class='title']"
            . "/a[text()='1 items in bag']"
        );

        $this->assertElementPresent(
            "//div[@id='lc-minicart-horizontal']"
            . "/div[@class='popup items-list']"
            . "/ul"
            . "/li"
            . "/span"
            . "/a[text()='" . $product->getName() . "']"
        );

        $this->assertElementPresent(
            "//div[@id='lc-minicart-horizontal']"
            . "/div[@class='popup items-list']"
            . "/ul"
            . "/li"
            . "/div[@class='item-price' and text()='$ " . $product->getPrice() . " x 1']"
        );

        $this->assertElementPresent(
            "//div[@id='lc-minicart-horizontal']"
            . "/div[@class='popup items-list']"
            . "/div[@class='cart-checkout']"
            . "/button"
            . "/span[text()='Checkout']"
        );
    }

    public function testAddToCartOnceMore()
    {
        $product = $this->getActiveProduct();

        $this->open('store/product//product_id-' . $product->getProductId());
        $this->click("//button[@type='submit']/span[text()='Add to Cart']");
 
        $this->open('/~max/xlite_cms/src/store/product//product_id-' . $product->getProductId());
        $this->click("//button[@type='submit']/span[text()='Add to Cart']");
 
        $this->assertElementPresent(
            "//div[@id='lc-minicart-horizontal']"
            . "/div[@class='minicart-items-number' and text()='2']"
        );

        $this->assertElementPresent(
            "//div[@id='lc-minicart-horizontal']"
            . "/div[@class='popup items-list']"
            . "/p[@class='title']"
            . "/a[text()='2 items in bag']"
        );

        $this->assertElementPresent(
            "//div[@id='lc-minicart-horizontal']"
            . "/div[@class='popup items-list']"
            . "/ul"
            . "/li"
            . "/span"
            . "/a[text()='" . $product->getName() . "']"
        );

        $this->assertElementPresent(
            "//div[@id='lc-minicart-horizontal']"
            . "/div[@class='popup items-list']"
            . "/ul"
            . "/li"
            . "/div[@class='item-price' and text()='$ " . $product->getPrice() . " x 2']"
        );
    }

    public function testAddToCartOnceMore2()
    {
        $product = $this->getActiveProduct();

        $this->open('store/product//product_id-' . $product->getProductId());
        $this->clickAndWait("//button[@type='submit']/span[text()='Add to Cart']");

        $this->open('store/product//product_id-' . $product->getProductId());
        $this->clickAndWait("//button[@type='submit']/span[text()='Add to Cart']");

        foreach ($this->getActiveProducts() as $p) {
            if ($p->getProductId() != $product->getProductId()) {
                $product = $p;
                break;
            }
        }

        $this->open('store/product//product_id-' . $product->getProductId());
        $this->clickAndWait("//button[@type='submit']/span[text()='Add to Cart']");

        $this->open('store/product//product_id-' . $product->getProductId());

        $this->assertElementPresent("//button[@type='submit']/span[text()='Add to Cart']");
        $this->clickAndWait("//button[@type='submit']/span[text()='Add to Cart']");

        $this->assertElementPresent(
            "//div[@id='lc-minicart-horizontal']"
            . "/div[@class='minicart-items-number' and text()='3']"
        );

        $this->assertElementPresent(
            "//div[@id='lc-minicart-horizontal']"
            . "/div[@class='popup items-list']"
            . "/p[@class='title']"
            . "/a[text()='3 items in bag']"
        );

        $this->assertElementPresent(
            "//div[@id='lc-minicart-horizontal']"
            . "/div[@class='popup items-list']"
            . "/ul"
            . "/li"
            . "/span"
            . "/a[text()='" . $product->getName() . "']"
        );

        $this->assertElementPresent(
            "//div[@id='lc-minicart-horizontal']"
            . "/div[@class='popup items-list']"
            . "/ul"
            . "/li"
            . "/div[@class='item-price' and text()='$ " . $product->getPrice() . " x 1']"
        );
    }
}

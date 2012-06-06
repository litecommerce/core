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
 * @resource product
 * @use category
 * @use shipping_method
 *
 */

require_once __DIR__ . '/../../../../Customer/ACustomer.php';
require_once __DIR__ . '/../../../../../Classes/Module/CDev/VAT/Model/ATax.php';

/**
 * XLite_Web_Module_CDev_VAT_Customer_VAT
 *
 * @package XLite
 * @see     ____class_see____
 * @since   1.0.0
 */
class XLite_Web_Module_CDev_VAT_Customer_VAT extends XLite_Web_Customer_ACustomer
{
    protected function getTestProduct()
    {
        return parent::getActiveProduct();
    }


    protected function prepareTaxes()
    {
        XLite_Tests_Module_CDev_VAT_Model_ATax::prepareData();
    }


    /**
     * testProductDetailsPage
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testProductDetailsPage()
    {
        $this->prepareTaxes();

        $product = $this->getTestProduct();

        // ====================================================

        // Switch on option 'Display "inc/ex VAT" labels next to prices'
        \XLite\Core\Database::getRepo('\XLite\Model\Config')->createOption(
            array(
                'name' => 'display_inc_vat_label',
                'category' => 'CDev\\VAT',
                'value' => 'Y',
            )
        );

        // Switch off option 'Display "inc/ex VAT" labels next to prices'
        \XLite\Core\Database::getRepo('\XLite\Model\Config')->createOption(
            array(
                'name' => 'display_prices_including_vat',
                'category' => 'CDev\\VAT',
                'value' => 'N',
            )
        );

        \XLite\Core\Config::updateInstance();

        $price = $product->getNetPrice();
        $price = number_format(round($price, 2), 2);


        // Check 'excluding VAT' text on products list

        $this->openAndWait('apparel');

        $this->assertElementPresent('//ul[@class="product-price clearfix"]/li[@class="vat-price"]/span[@class="vat-note-product-price" and contains(text(), "excluding VAT")]', 'Text "excluding VAT not found on products list page"');

        // Check 'excluding VAT' text on product details

        $this->openAndWait('store/product//product_id-' . $product->getProductId());

        $this->assertElementPresent('//ul[@class="product-price clearfix"]/li/span[@class="price product-price" and text()="$' . $price . '"]', 'Wrong product price displayed (expected $' . $price . ') #3');

        $this->assertElementPresent('//ul[@class="product-price clearfix"]/li[@class="vat-price"]/span[@class="vat-note-product-price" and contains(text(), "excluding VAT")]', 'Text "excluding VAT not found on product details page"');


        // ====================================================

        // Switch off option 'Display "inc/ex VAT" labels next to prices'
        \XLite\Core\Database::getRepo('\XLite\Model\Config')->createOption(
            array(
                'name' => 'display_inc_vat_label',
                'category' => 'CDev\\VAT',
                'value' => 'N',
            )
        );

        \XLite\Core\Config::updateInstance();

        // Check 'including VAT' text on products list

        $this->openAndWait('apparel');

        $this->assertElementNotPresent('//ul[@class="product-price clearfix"]/li[@class="vat-price"]/span[@class="vat-note-product-price"]', 'Label "incl/excl VAT" unexpectedly found on products list page" #2');

        // Check 'including VAT' text on product details

        $this->openAndWait('store/product//product_id-' . $product->getProductId());

        $this->assertElementPresent('//ul[@class="product-price clearfix"]/li/span[@class="price product-price" and text()="$' . $price . '"]', 'Wrong product price displayed (expected $' . $price . ') #2');

        $this->assertElementNotPresent('//ul[@class="product-price clearfix"]/li[@class="vat-price"]/span[@class="vat-note-product-price"]', 'Label "incl/excl VAT" unexpectedly found on product details page" #2');


        // ====================================================

        // Switch off option 'Display "inc/ex VAT" labels next to prices'
        \XLite\Core\Database::getRepo('\XLite\Model\Config')->createOption(
            array(
                'name' => 'display_inc_vat_label',
                'category' => 'CDev\\VAT',
                'value' => 'Y',
            )
        );

        // Switch off option 'Display "inc/ex VAT" labels next to prices'
        \XLite\Core\Database::getRepo('\XLite\Model\Config')->createOption(
            array(
                'name' => 'display_prices_including_vat',
                'category' => 'CDev\\VAT',
                'value' => 'Y',
            )
        );

        \XLite\Core\Config::updateInstance();
        var_dump(\XLite\Core\Config::getInstance()->CDev->VAT->display_prices_including_vat);

        // Check 'including VAT' text on products list

        $this->openAndWait('apparel');

        $this->assertElementPresent('//ul[@class="product-price clearfix"]/li[@class="vat-price"]/span[@class="vat-note-product-price" and contains(text(), "including VAT")]', 'Text "including VAT not found on products list page"');

        // Check 'including VAT' text on product details

        $this->openAndWait('store/product//product_id-' . $product->getProductId());

        $price = $product->getDisplayPrice();

        $this->assertElementPresent('//ul[@class="product-price clearfix"]/li/span[@class="price product-price" and text()="$' . $price . '"]', 'Wrong product price displayed (expected $' . $price . ')');

        $this->assertElementPresent('//ul[@class="product-price clearfix"]/li[@class="vat-price"]/span[@class="vat-note-product-price" and contains(text(), "including VAT")]', 'Text "including VAT not found on product details page"');


        // ======================================================

        // Add product to cart

        $this->click("//button[@class='bright add2cart']");

        $this->waitForLocalCondition(
            'jQuery(".product-details .product-buttons-added .buy-more").length > 0',
            100000,
            'check content reloading'
        );
        sleep(2);
        $this->clickAndWait('css=.lc-minicart .title a');

        $this->assertElementPresent(
            "//div[@id='cart']"
            . "/div[@id='cart-right']"
            . "/ul[@class='totals']"
            . "/li[contains(@class, 'order-modifier CDEV.VAT')]"
            . "/strong[text()='VAT:']",
            'VAT not found in cart totals'
        );

    }

}

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
 * @resource user
 * @resource admin_address_book
 * @resource order
 * @resource product
 * @use shipping_method
 */

require_once __DIR__ . '/ACustomer.php';

class XLite_Web_Customer_ZeroTotalCheckout extends XLite_Web_Customer_ACustomer
{
    /**
     * PHPUnit default function.
     * Redefine this method only if you really need to do so.
     * In any other cases redefine the getRequest() one
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function addToCart()
    {
        $product = \XLite\Core\Database::getRepo('XLite\Model\Product')
            ->createQueryBuilder()
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();

        $this->openAndWait('store/product//product_id-' . $product->getProductId());

        $this->click("//button[@class='bright add2cart']");

        $this->waitForLocalCondition(
            'jQuery(".product-details .product-buttons-added .buy-more").length > 0',
            100000,
            'check content reloading'
        );

        return $product;
    }

    public function testPayment()
    {
        $product = $this->addToCart();

        // Change the product price to 0 (Payment is not required should be)
        $this->logInAdmin();

        $this->openShortAdminAndWait('admin.php?target=product&id=' . $product->getProductId());

        $this->type('//input[@name="postedData[price]"]', '0');

        $this->clickAndWait('//button[@type="submit"]');

        $this->openAndWait('store/checkout');

        $this->assertElementPresent("//div[@class='step-box']/p[text()='Billing address is not defined yet']");

        $this->fillShipping();

        // Change the product price to 1.00 (No "Payment is not required" block should be visible)
        $this->openShortAdminAndWait('admin.php?target=product&id=' . $product->getProductId());

        $this->type('//input[@name="postedData[price]"]', '1');

        $this->clickAndWait('//button[@type="submit"]');

        $this->openAndWait('store/checkout');

        $this->assertElementPresent("//ul[@class='payments']");

    }

    protected function fillShipping()
    {
        $this->typeKeys("//input[@id='create_profile_email']", 'test@test.ru');

        $this->waitInlineProgress('#create_profile_email', 'email inline progress');

        $this->typeKeys("//input[@id='shipping_address_name']", 'test name');

        $this->typeKeys("//input[@id='shipping_address_street']", 'test street');

        $this->typeKeys("//input[@id='shipping_address_city']", 'test city');

        $this->typeKeys("//input[@id='shipping_address_zipcode']", '12345');

        $this->waitForLocalCondition(
            'jQuery("ul.shipping-rates").length == 1',
            60000,
            'No shipping rates table'
        );

        $this->click("//input[@id='method1']");

        $this->waitForLocalCondition(
            'jQuery(".shipping-step button.disabled").length == 0',
            600000,
            'Check shipping method'
        );

        $this->click("//button[@class='bright']");

        $this->waitForLocalCondition(
            'jQuery(".address-not-defined").length == 0',
            30000,
            'Billing address is not defined'
        );
    }
}

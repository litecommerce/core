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
 * @block_all
 */

require_once __DIR__ . '/../ACustomer.php';

/**
 * Qiwi payment gateway integration test (Qiwi module)
 *
 * @package XLite
 * @see     ____class_see____
 * @since   1.0.0
 */
class XLite_Web_Customer_Payment_Qiwi extends XLite_Web_Customer_ACustomer
{
    public function testPayFailure()
    {
        $order = $this->createOrder($this->testConfig['qiwi']['wrongPhoneNumber']);

        $this->assertEquals(1, count($order->getPaymentTransactions()), 'check payment transactions count');

        // Payment transaction should've Failed status
        $this->assertEquals('F', $order->getPaymentTransactions()->get(0)->getStatus(), 'check payment transaction status');
    }

    public function testPaySuccess()
    {
        $order = $this->createOrder($this->testConfig['qiwi']['correctPhoneNumber']);

        $this->assertEquals(1, count($order->getPaymentTransactions()), 'check payment transactions count');

        // Payment transaction should have Pending status
        $this->assertEquals('W', $order->getPaymentTransactions()->get(0)->getStatus(), 'check payment transaction status');
    }

    protected function setUp()
    {
        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * Checkout using specific phone number
     * 
     * @param string $phoneNumber Qiwi phone to test with
     *  
     * @return \XLite\Model\Order Created order
     * @see    ____func_see____
     * @since  1.0.23
     */
    protected function createOrder($phoneNumber)
    {
        $pmethod = \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')->findOneBy(array('service_name' => 'Qiwi'));
        $this->assertNotNull($pmethod, 'Qiwi payment method is not found');

        $pid = $pmethod->getMethodId();

        // Set test login
        $s = $pmethod->getSettingEntity('login');
        if (!$s) {
            $s = new \XLite\Model\Payment\MethodSetting;
            $s->setName('login');
            $pmethod->getSettings()->add($s);
        }

        $s->setValue($this->testConfig['qiwi']['login']);

        // Set test password
        $s = $pmethod->getSettingEntity('password');
        if (!$s) {
            $s = new \XLite\Model\Payment\MethodSetting;
            $s->setName('password');
            $pmethod->getSettings()->add($s);
        }

        $s->setValue($this->testConfig['qiwi']['password']);

        // Set test order lifetime
        $s = $pmethod->getSettingEntity('lifetime');
        if (!$s) {
            $s = new \XLite\Model\Payment\MethodSetting;
            $s->setName('lifetime');
            $pmethod->getSettings()->add($s);
        }

        // Set test order id prefix
        $s = $pmethod->getSettingEntity('prefix');
        if (!$s) {
            $s = new \XLite\Model\Payment\MethodSetting;
            $s->setName('prefix');
            $pmethod->getSettings()->add($s);
        }

        $s->setValue($this->testConfig['qiwi']['prefix']);

        // Set test order lifetime
        $s = $pmethod->getSettingEntity('lifetime');
        if (!$s) {
            $s = new \XLite\Model\Payment\MethodSetting;
            $s->setName('lifetime');
            $pmethod->getSettings()->add($s);
        }

        // Set it to 48 hrs
        $s->setValue(48);

        // Enable checking that mobile phone number corresponds
        // to existing qiwi account (i.e. do not create new one)
        $s = $pmethod->getSettingEntity('check_agt');
        if (!$s) {
            $s = new \XLite\Model\Payment\MethodSetting;
            $s->setName('check_agt');
            $pmethod->getSettings()->add($s);
        }

        // Set check_agt to 1 (true)
        $s->setValue(1);

        // Enable payment method
        $pmethod->setEnabled(true);
        \XLite\Core\Database::getEM()->flush();

        // Add-to-cart
        $product = \XLite\Core\Database::getRepo('XLite\Model\Product')
            ->createQueryBuilder()
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();

        $this->openAndWait(
            \XLite\Core\Converter::buildURL(
                'product',
                '',
                array('product_id' => $product->getProductId())
            )
        );

        $this->click("//button[@class='bright add2cart']");

        $this->waitForLocalCondition(
            'jQuery(".product-details .product-buttons-added .buy-more").length > 0',
            20000,
            'check content reloading'
        );

        $this->waitForLocalCondition(
            'jQuery(".cart-checkout button.action").length > 0',
            20000,
            'Wait for Checkout button availability'
        );

        // Checkout
        $this->clickAndWait('//div[@class="cart-checkout"]/button');

        if (0 < intval($this->getJSExpression('jQuery(".current.shipping-step").length'))) {

            $username = 'tester' . time();

            $this->typeKeys('//input[@id="create_profile_email"]', $username . '@cdev.ru');

            $this->waitInlineProgress('#create_profile_email', 'email inline progress');
            $this->assertInputErrorNotPresent('#create_profile_email', 'email has not inline error');

            // Fill minimum address fields
            $this->select(
                '//select[@id="shipping_address_country"]',
                'value=US'
            );
            $this->waitForLocalCondition(
                'jQuery("select#shipping_address_state").length == 1',
                3000,
                'check state selector'
            );

            $this->select(
                '//select[@id="shipping_address_state"]',
                'label=New York'
            );

            $this->typeKeys(
                '//input[@id="shipping_address_zipcode"]',
                '10001'
            );

            // Complete shipping address
            $this->typeKeys(
                '//input[@id="shipping_address_name"]',
                'John Smith'
            );

            $this->typeKeys(
                '//input[@id="shipping_address_street"]',
                'test address'
            );

            $this->typeKeys(
                '//input[@id="shipping_address_city"]',
                'New York'
            );

            $this->waitForLocalCondition(
                'jQuery("ul.shipping-rates li input").length > 0',
                10000,
                'check shipping rates'
            );

            $this->toggleByJquery('ul.shipping-rates li input:eq(0)', true);
            $this->waitForLocalCondition(
                'jQuery(".current .button-row button.disabled").length == 0',
                10000,
                'check enabled main button'
            );

            $this->click('css=.current .button-row button');
            $this->waitForLocalCondition(
                'jQuery(".shipping-step").hasClass("current") == false',
                30000,
                'check switch to next step'
            );
        }

        if (0 == intval($this->getJSExpression('jQuery(".current.payment-step").length'))) {
            $this->click('css=.payment-step .button-row button');
            $this->waitForLocalCondition(
                'jQuery(".payment-step").hasClass("current") == true',
                30000,
                'check switching to prev step'
            );
        }

        if (0 < intval($this->getJSExpression('jQuery(".current.payment-step").length'))) {
            
            $this->waitForCondition(
                "selenium.isElementPresent('pmethod{$pid}') && selenium.isElementPresent(\"//label[@for='pmethod{$pid}' and contains(text(), 'Qiwi')]\")",
                30000,
                'Couldn\t find Qiwi payment method'
            );

            $this->assertElementPresent('pmethod' . $pid, 'Radio-button of Qiwi payment method not found');
            $this->assertElementPresent("//label[@for='pmethod{$pid}' and contains(text(), 'Qiwi')]", 'Label element for Qiwi payment method not found');

            $this->toggleByJquery('#pmethod' . $pid, true);
            $this->waitForLocalCondition(
                'jQuery(".current .button-row button.disabled").length == 0',
                10000,
                'check enabled main button #2'
            );

            $this->click('css=.current .button-row button');
            $this->waitForLocalCondition(
                'jQuery(".review-step.current").length == 1',
                30000,
                'check switching to next step #2'
            );
        }

        $this->typeKeys(
            '//input[@id="payment_qiwi_phone_number"]',
            $phoneNumber
        );

        $this->click('//input[@id="place_order_agree"]');
        $this->waitForLocalCondition(
            'jQuery(".current .button-row button.disabled").length == 0',
            10000,
            'check enabled main button #3'
        );

        $this->clickAndWait('css=.current .button-row button');

        // Get to invoice page
        $this->waitForCondition(
            "selenium.isElementPresent(\"//div[@class='thank-you' and contains(text(), 'Thank you for your order')]\")",
            60000,
            'Waiting for redirecting to order confirmation page failed'
        );

        $this->assertElementPresent("//div[@class='thank-you' and contains(text(), 'Thank you for your order')]", '"Thank you for your order" text noot found');

        $this->assertElementPresent('//div[@class="order-success-box"]/div[@class="invoice-box"]/h2[@class="invoice" and contains(text(), "Invoice")]');

        $invoiceHeader = $this->getText('//div[@class="order-success-box"]/div[@class="invoice-box"]/h2[@class="invoice" and contains(text(), "Invoice")]');

        $orderid = intval(preg_replace('/[^\d]+(\d+)/', '\\1', $invoiceHeader));

        $this->assertTrue(0 < $orderid, 'Wrong order ID (invoice header is "' . $invoiceHeader . '")');

        // Check order
        $order = \XLite\Core\Database::getRepo('XLite\Model\Order')->find($orderid);

        $this->assertNotNull($order, 'check order');

        return $order;
    }
}

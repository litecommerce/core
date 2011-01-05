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

require_once __DIR__ . '/../ACustomer.php';

/**
 * Quantum payment gateway integration test (Quantum module)
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Web_Customer_Payment_Quantum extends XLite_Web_Customer_ACustomer
{
    public function testPay()
    {
        $pmethod = \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')->findOneBy(array('service_name' => 'QuantumGateway'));
        $pid = $pmethod->getmethodId();
        if (!$pmethod) {
            $this->fail('Quantum payment method is not found');
        }

        // Set test settings
        $pmethod->setEnabled(true);
        $pmethod->getSettingEntity('login')->setValue('xcart_arch');
        \XLite\Core\Database::getEM()->flush();

        // Set no-xdebug-coverage flag
        $this->skipCoverage();

        // Add-to-cart
        $product = \XLite\Core\Database::getRepo('XLite\Model\Product')
            ->createQueryBuilder()
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();

        $this->openAndWait('store/product//product_id-' . $product->getProductId());

        $this->click("//button[@class='bright add2cart']");

        $this->waitForLocalCondition(
            'jQuery(".product-details .product-buttons-added .buy-more").length > 0',
            10000,
            'check content reloading'
        );

        // Checkout
        $this->openAndWait('store/checkout');

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
                3000,
                'check enabled main button'
            );

            $this->click('css=.current .button-row button');
            $this->waitForLocalCondition(
                'jQuery(".shipping-step").hasClass("current") == false',
                20000,
                'check swicth to next step'
            );
        }

        if (0 == intval($this->getJSExpression('jQuery(".current.payment-step").length'))) {
            $this->click('css=.payment-step .button-row button');
            $this->waitForLocalCondition(
                'jQuery(".payment-step").hasClass("current") == true',
                10000,
                'check swicth to prev step'
            );
        }

        if (0 < intval($this->getJSExpression('jQuery(".current.payment-step").length'))) {
            $this->toggleByJquery('#pmethod' . $pid, true);
            $this->waitForLocalCondition(
                'jQuery(".current .button-row button.disabled").length == 0',
                3000,
                'check enabled main button #2'
            );
            $this->click('css=.current .button-row button');
            $this->waitForLocalCondition(
                'jQuery(".review-step").hasClass("current") == true',
                10000,
                'check swicth to next step #2'
            );
        }

        $this->click('//input[@id="place_order_agree"]');
        $this->waitForLocalCondition(
            'jQuery(".current .button-row button.disabled").length == 0',
            3000,
            'check enabled main button #3'
        );

        $this->clickAndWait('css=.current .button-row button');

        // Go to payment gateway
        $this->waitForCondition(
            'selenium.browserbot.getCurrentWindow().document.getElementsByTagName("form").length > 0 && selenium.browserbot.getCurrentWindow().document.getElementsByTagName("form")[0].ccnum',
            20000
        );

        // Type test credit card data
        $this->type('//input[@name="ccnum"]', '4111111111111111');
        $this->select('//select[@name="ccyr"]', 'value=2011');
        $this->type('//input[@name="CVV2"]', '666');

        // Payment gateway processing - Selenium TTL prolongation
        $this->setTimeout(120000);
        $this->clickAndWait('//input[@type="submit"]');

        $this->waitForLocalCondition(
            'document.getElementsByTagName("form")[0].ccnum.value == "1111"',
            10000
        );
        $this->setTimeout(self::SELENIUM_TTL);
        $this->clickAndWait('//input[@type="SUBMIT"]');

        // Go to shop
        $this->waitForLocalCondition('location.href.search(/checkoutSuccess/) != -1');

        $ordeid = null;

        if (preg_match('/order_id-(\d+)/Ss', $this->getLocation(), $m)) {
            $ordeid = intval($m[1]);
        }

        $this->assertTrue(0 < $ordeid, 'check order id');

        // Check order
        $order = \XLite\Core\Database::getRepo('XLite\Model\Order')->find($ordeid);

        $this->assertNotNull($order, 'check order');

        $this->assertEquals(1, count($order->getPaymentTransactions()), 'check payment transactions count');
        $this->assertEquals('S', $order->getPaymentTransactions()->get(0)->getStatus(), 'check payment transaction status');
        $this->assertTrue($order->isPayed(), 'check order payed status');
    }
}

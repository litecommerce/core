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
    /**
     * Temporary skipped flag
     * FIXME - test account is obsolete
     *
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $temporarySkipped = true;

    public function testPay()
    {
        // Set test settings
        \XLite\Core\Database::getEM()->getConnection()->executeQuery('UPDATE xlite_payment_methods SET enabled = 1 WHERE method_id = 30', array());
        \XLite\Core\Database::getEM()->getConnection()->executeQuery('UPDATE xlite_payment_method_settings SET value = "xcart_arch" WHERE method_id = 30 AND name = "login"', array());

        // Set no-xdebug-coverage flag
        $this->open('');
        $this->createCookie('no_xdebug_coverage=1');

        // Log-in
        $this->open('user');

        $this->type("//input[@id='edit-name']", "master");
        $this->type("//input[@id='edit-pass']", "master");
        $this->submitAndWait('css=#user-login');

        // Add-to-cart
        $product = \XLite\Core\Database::getRepo('XLite\Model\Product')
            ->createQueryBuilder()
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();

        $this->openAndWait('store/product//product_id-' . $product->getProductId());

        $this->click("//button[@class='bright add2cart']");

        $this->waitForCondition(
            'selenium.browserbot.getCurrentWindow().$(".product-details .product-buttons-added .buy-more").length > 0',
            10000,
            'check content reloading'
        );

        // Checkout
        $this->openAndWait('store/checkout');

        if (0 < intval($this->getJSExpression('$(".current.shipping-step").length'))) {
            $this->toggleByJquery('ul.shipping-rates li input:eq(0)', true);
            $this->click('css=.current .button-row button');
            $this->waitForCondition(
                'selenium.browserbot.getCurrentWindow().$(".payment-step").hasClass("current") == true',
                10000,
                'check swicth to next step'
            );
        }

        if (0 < intval($this->getJSExpression('$(".current.payment-step").length'))) {
            $this->toggleByJquery('#pmethod30', true);
            $this->click('css=.current .button-row button');
            $this->waitForCondition(
                'selenium.browserbot.getCurrentWindow().$(".review-step").hasClass("current") == true',
                10000,
                'check swicth to next step #2'
            );
        }

        if (0 < intval($this->getJSExpression('$(".current.review-step").length'))) {
            $this->click('css=.payment-step .button-row button');
            $this->waitForCondition(
                'selenium.browserbot.getCurrentWindow().$(".payment-step").hasClass("current") == true',
                10000,
                'check return to payment step'
            );
            $this->toggleByJquery('#pmethod30', true);
            $this->click('css=.current .button-row button');
            $this->waitForCondition(
                'selenium.browserbot.getCurrentWindow().$(".review-step").hasClass("current") == true',
                10000,
                'check swicth to next step #3'
            );
        }

        $this->click('//input[@id="place_order_agree"]');
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

        $this->waitForCondition(
            'selenium.browserbot.getCurrentWindow().document.getElementsByTagName("form")[0].ccnum.value == "1111"',
            10000
        );
        $this->setTimeout(self::SELENIUM_TTL);
        $this->clickAndWait('//input[@type="SUBMIT"]');

        // Go to shop
        $this->waitForCondition('selenium.browserbot.getCurrentWindow().location.href.search(/checkoutSuccess/) != -1');

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

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

class XLite_Web_Customer_Checkout extends XLite_Web_Customer_ACustomer
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

        $this->skipCoverage();

        $this->openAndWait('store/product//product_id-' . $product->getProductId());

        $this->click("//button[@class='bright add2cart']");

        $this->waitForLocalCondition(
            'jQuery(".product-details .product-buttons-added .buy-more").length > 0',
            10000,
            'check content reloading'
        );

        $this->openAndWait('store/checkout');

        return $product;
    }

    public function testStructure()
    {
        $product = $this->addToCart();

        $this->assertElementPresent(
            "//h1[@id='page-title' and text()='Checkout']",
            'check page title'
        );

        $this->assertElementPresent(
            "//div[@class='checkout-block']"
            . "/div[@class='profile']"
            . "/form[@class='create']"
            . "/div[@class='create']"
            . "/input[@id='create_profile_email']"
        );

        $this->assertElementPresent(
            "//div[@class='checkout-block']"
            . "/div[@class='profile']"
            . "/form[@class='create']"
            . "/div[@class='create']"
            . "/div[@class='selector']"
            . "/input[@id='create_profile_chk']"
        );  

        $this->assertElementPresent(
            "//div[@class='checkout-block']"
            . "/div[@class='profile']"
            . "/form[@class='create']"
            . "/div[@class='create']"
            . "/div[@class='username']"
            . "/input[@id='create_profile_username']"
        );

        $this->assertElementPresent(
            "//div[@class='checkout-block']"
            . "/div[@class='profile']"
            . "/form[@class='create']"
            . "/div[@class='login']"
            . "/button"
            . "/span[text() = 'Login here']"
        );
    }

    public function testCreateProfile()
    {
        $product = $this->addToCart();

        // Success
        $this->fillProfileData();

        $username = 'tester' . time();

        // Duplicate email
        $this->typeKeys(
            '//input[@id="create_profile_email"]',
            'rnd_tester@cdev.ru'
        );
        $this->waitInlineProgress('#create_profile_email', 'duplicate email');
        $this->assertInputErrorPresent('#create_profile_email', 'check duplicate email error');
        $this->assertJqueryPresent('p.username-verified:visible');

        // Duplicate username
        $this->typeKeys(
            '//input[@id="create_profile_email"]',
            $username . '@cdev.ru'
        );
        $this->waitInlineProgress('#create_profile_email', 'duplicate username and correct email');
        $this->assertInputErrorNotPresent('#create_profile_email');

        $this->typeKeys(
            '//input[@id="create_profile_username"]',
            'master'
        );
        $this->waitInlineProgress('#create_profile_username', 'duplicate username');
        $this->assertInputErrorPresent('#create_profile_username');
        $this->assertJqueryNotPresent('p.username-verified:visible');
    }

    public function testShippingStep()
    {
        $product = $this->addToCart();

        $this->assertJqueryPresent('.current p.address-not-defined:visible', 'address-not-defined is visible');

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

        $this->waitForLocalCondition(
            'jQuery("ul.shipping-rates li input").length > 0',
            15000,
            'check shipping rates'
        );
        $this->assertJqueryNotPresent('ul.shipping-rates li input:checked');

        $this->waitForLocalCondition(
            'jQuery(".current p.address-not-completed:visible").length == 1',
            3000,
            'check address-not-completed node show'
        );

        $this->assertJqueryNotPresent('.current p.address-not-defined:visible', 'address-not-defined is invisible');

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
            'jQuery(".current p.address-not-completed:visible").length == 0',
            3000,
            'check address-not-completed note hide'
        );

        $this->assertJqueryPresent('.current .button-row button.disabled', 'check disabled main button');

        // Select shipping method
        $this->toggleByJquery('ul.shipping-rates li input:eq(0)', true);

        $this->assertJqueryPresent('.current .button-row button.disabled', 'check disabled main button #2');
        $this->assertJqueryPresent('.current p.email-not-defined:visible', 'email-not-defined is visible');

        // Fill profile data
        $this->fillProfileData();

        $this->waitForLocalCondition(
            'jQuery(".current .button-row button.disabled").length == 0',
            3000,
            'check enabled main button'
        );
        $this->assertJqueryNotPresent('.current p.email-not-defined:visible', 'email-not-defined is invisible');

        $this->click('css=.current .button-row button');

        $this->waitForLocalCondition(
            'jQuery(".payment-step.current").length == 1',
            10000,
            'check swicth to next step'
        );
    }

    public function testPaymentStep()
    {
        $product = $this->addToCart();
        $this->fillProfileData();
        $this->fillShippingAddress();

        // Check payment method
        $this->assertJqueryPresent('.current .button-row button.disabled', 'payment not selected - main button is disabled');

        $this->assertJqueryNotPresent('ul.payments li input:checked', 'payment is not selected');

        $this->toggleByJquery('#pmethod6', true);

        $this->waitForLocalCondition(
            'jQuery(".current .button-row button.disabled").length == 0',
            3000,
            'check enabled main button'
        );

        // Fill billing address

        $this->toggleByJquery('#same_address', false);
        $this->assertJqueryPresent('.current .button-row button.disabled', 'same addres disabled and address not loaded - main button is disabled');

        $this->waitForLocalCondition(
            'jQuery("#billing_address_name").length > 0',
            10000,
            'check empty billing address form load'
        );
        $this->assertJqueryNotPresent('.current .button-row button.disabled', 'same addres disabled amd address form loaded - main button is disabled');

        $this->select(
            '//select[@id="billing_address_country"]',
            'value=US'
        );
        $this->waitForLocalCondition(
            'jQuery("select#billing_address_state").length == 1',
            3000,
            'check state selector'
        );

        $this->select(
            '//select[@id="billing_address_state"]',
            'label=New York'
        );

        $this->typeKeys(
            '//input[@id="billing_address_zipcode"]',
            '10002'
        );

        $this->typeKeys(
            '//input[@id="billing_address_name"]',
            'John Smith Jr.'
        );

        $this->typeKeys(
            '//input[@id="billing_address_street"]',
            'test address billy'
        );

        $this->typeKeys(
            '//input[@id="billing_address_city"]',
            'New York'
        );

        $this->waitForLocalCondition(
            'jQuery(".current .button-row button.disabled").length == 0',
            3000,
            'check main button enabled after fill billing address'
        );

        // Submit   
        $this->click('css=.current .button-row button');

        $this->waitForLocalCondition(
            'jQuery(".review-step.current").length == 1',
            10000,
            'check swicth to next step'
        );
 
    }

    public function testReviewStep()
    {
        $product = $this->addToCart();
        $this->fillProfileData();
        $this->fillShippingAddress();
        $this->fillPaymentStep();

        // Check items box
        $this->assertJqueryNotPresent('.review-step .list:visible', 'items box hide');

        $this->click('css=.review-step .items-row a'); 

        $this->waitForLocalCondition(
            'jQuery(".review-step .list:visible").length == 1',
            2000,
            'check items popup'
        );

        $this->click('css=.review-step .items-row a');

        $this->waitForLocalCondition(
            'jQuery(".review-step .list:visible").length == 0',
            2000,
            'check items popup'
        );

        // Check place button
        $this->assertJqueryNotPresent('.current .button-row button.disabled', 'main button is enabled always');
        $this->assertJqueryNotPresent('.current .non-agree', 'non-agree style is NOT applyed');

        $this->click('css=.current .button-row button');

        $this->assertJqueryPresent('.current .non-agree', 'non-agree style is applyed');

        $this->click('//input[@id="place_order_agree"]');

        $this->assertJqueryPresent('.current .non-agree', 'non-agree style is applyed always');

        // Checkout with Check payment method
        $this->click('css=.current .button-row button');

        // Go to shop
        $this->waitForLocalCondition('selenium.browserbot.getCurrentWindow().location.href.search(/checkoutSuccess/) != -1');

        // Check order
        $ordeid = null;

        if (preg_match('/order_id-(\d+)/Ss', $this->getLocation(), $m)) {
            $ordeid = intval($m[1]);
        }

        $this->assertTrue(0 < $ordeid, 'check order id');

        $order = \XLite\Core\Database::getRepo('XLite\Model\Order')->find($ordeid);

        $this->assertNotNull($order, 'check order');

        $this->assertEquals(1, count($order->getPaymentTransactions()), 'check payment transactions count');
        $this->assertEquals('W', $order->getPaymentTransactions()->get(0)->getStatus(), 'check payment transaction status');
        $this->assertFalse($order->isPayed(), 'check order payed status');
    }

    public function testLogged()
    {
        $this->open('user');

        $this->type('css=#edit-name', 'master');
        $this->type('css=#edit-pass', 'master');

        $this->submitAndWait('css=#user-login');

        $product = $this->addToCart();

        // Check profile block
        $this->assertElementPresent(
            "//div[@class='checkout-block']"
            . "/div[@class='profile']"
            . "/div[@class='logged']"
            . "/span[text()='Greetings, master']"
        );

        $this->assertElementPresent(
            "//div[@class='checkout-block']"
            . "/div[@class='profile']"
            . "/div[@class='logged']"
            . "/a[@class='view-profile']"
        );

        $this->assertElementPresent(
            "//div[@class='checkout-block']"
            . "/div[@class='profile']"
            . "/div[@class='logged']"
            . "/a[@class='logoff']"
        );
    }

    public function testAddressBook()
    {
        $this->open('user');

        $this->type('css=#edit-name', 'master');
        $this->type('css=#edit-pass', 'master');

        $this->submitAndWait('css=#user-login');

        $product = $this->addToCart();

        if ($this->getJSExpression('jQuery(".previous.shipping-step").length')) {

            // Return to shipping step
            $this->click('css=.shipping-step .button-row button');
            $this->waitForLocalCondition(
                'jQuery(".shipping-step.current").length == 1',
                10000,
                'check switch to shipping step'
            );
        }

        // Save as new

        $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->find(1);
        $profileCount = count($profile->getAddresses());

        $this->typeKeys(
            '//input[@id="shipping_address_name"]',
            'John Smith aaa'
        );
        $this->toggleByJquery('#save_shipping_address', true);

        // Select shipping method
        $this->toggleByJquery('ul.shipping-rates li input:eq(0)', true);

        $this->waitForLocalCondition(
            'jQuery(".current .button-row button.disabled").length == 0',
            3000,
            'check enabled main button'
        );

        // Submit-and-return
        $this->click('css=.current .button-row button');
        $this->waitForLocalCondition(
            'jQuery(".shipping-step").hasClass("current") == false',
            10000,
            'check swicth to next step'
        );

        $this->click('css=.shipping-step .button-row button');
        $this->waitForLocalCondition(
            'jQuery(".shipping-step.current").length == 1',
            10000,
            'check swicth to previous step'
        );

        \XLite\Core\Database::getEM()->clear();

        $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->find(1);

        $this->assertEquals($profileCount + 1, count($profile->getAddresses()), 'check addresses count #2');
        $this->assertEquals('John Smith aaa', $profile->getAddresses()->get($profileCount)->getName(), 'check name');

        // Open address book
        $this->click('css=.current button.address-book');
        $this->waitForLocalCondition(
            'jQuery("form.select-address").length == 1',
            10000,
            'wait address book popup'
        );

        $cnt = intval($this->getJSExpression('jQuery("form.select-address ul li").length'));
        $this->assertEquals($profileCount + 1, $cnt, 'check addresses count into address book');

        // Select previous address
        $this->getJSExpression('jQuery("form.select-address ul li:eq(0)").click()');
        $this->waitForLocalCondition(
            'jQuery("#shipping_address_name").val() != "John Smith aaa"',
            20000,
            'wait address changes'
        );

        // Swicth to payment step
        $this->click('css=.current .button-row button');
        $this->waitForLocalCondition(
            'jQuery(".shipping-step").hasClass("current") == false',
            10000,
            'check swicth to payment step'
        );
        if (!$this->getJSExpression('jQuery(".payment-step.current").length')) {
            $this->click('css=.payment-step .button-row button');
            $this->waitForLocalCondition(
                'jQuery(".payment-step").hasClass("current") == true',
                10000,
                'check return to payment step'
            );
        }


        // Disabled same address
        $bname = 'John Smith aaa';
        if ('false' == $this->getJSExpression('jQuery("#same_address").get(0).checked')) {
            $this->toggleByJquery('#same_address', true);
            $this->waitForLocalCondition(
                'jQuery("#billing_address_name").length == 0',
                10000,
                'check full billing address form load'
            );
            $bname = 'Admin Admin';
        }

        $this->toggleByJquery('#same_address', false);
        $this->waitForLocalCondition(
            'jQuery("#billing_address_name").length > 0',
            10000,
            'check empty billing address form load'
        );

        $this->assertEquals($bname, $this->getJSExpression('jQuery("#billing_address_name").val()'), 'check billing name');
        $this->typeKeys(
            '//input[@id="billing_address_name"]',
            'John Smith bbb'
        );

        $this->toggleByJquery('#pmethod6', true);

        $this->click('css=.current .button-row button');
        $this->waitForLocalCondition(
            'jQuery(".review-step.current").length == 1',
            10000,
            'check swicth to review step'
        );

        \XLite\Core\Database::getEM()->clear();

        $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->find(1);

        $this->assertEquals($profileCount + 2, count($profile->getAddresses()), 'check addresses count #3');
        $this->assertEquals('John Smith bbb', $profile->getAddresses()->get($profileCount + 1)->getName(), 'check name #2');
        $this->assertFalse((bool)$profile->getAddresses()->get($profileCount + 1)->getIsShipping(), 'check is shipping');
        $this->assertTrue((bool)$profile->getAddresses()->get($profileCount + 1)->getIsBilling(), 'check is billing');

        // Return to payment step
        $this->click('css=.payment-step .button-row button');
        $this->waitForLocalCondition(
            'jQuery(".payment-step.current").length == 1',
            10000,
            'check swicth to payment step #2'
        );

        // Swicth on same address
        $this->toggleByJquery('#same_address', true);
        $this->waitForLocalCondition(
            'jQuery("#billing_address_name").length == 0',
            10000,
            'check empty billing address form load'
        );

        \XLite\Core\Database::getEM()->clear();

        $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->find(1);

        $this->assertEquals($profileCount + 2, count($profile->getAddresses()), 'check addresses count #4');
        $this->assertFalse((bool)$profile->getAddresses()->get($profileCount)->getIsShipping(), 'check is shipping #2');
        $this->assertFalse((bool)$profile->getAddresses()->get($profileCount + 1)->getIsBilling(), 'check is billing #2');
    }

    protected function fillProfileData()
    {
        $username = 'tester' . time();

        $this->typeKeys('//input[@id="create_profile_email"]', $username . '@cdev.ru');

        $this->waitInlineProgress('#create_profile_email', 'email inline progress');
        $this->assertInputErrorNotPresent('#create_profile_email', 'email has not inline error');

        $this->toggleByJquery('#create_profile_chk', true);
        $this->waitForLocalCondition(
            'jQuery(".username:visible").length == 1',
            5000,
            'check open username box'
        );

        $this->typeKeys('//input[@id="create_profile_username"]', $username);

        $this->waitInlineProgress('#create_profile_username', 'username');
        $this->assertInputErrorNotPresent('#create_profile_username');

        $this->assertJqueryPresent('p.username-verified:visible');
    }

    protected function fillShippingAddress()
    {
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
            'new York'
        );

        $this->waitForLocalCondition(
            'jQuery("ul.shipping-rates li input").length > 0',
            10000,
            'check address-not-completed note hide'
        );

        // Select shipping method
        $this->toggleByJquery('ul.shipping-rates li input:eq(0)', true);

        $this->waitForLocalCondition(
            'jQuery(".current .button-row button.disabled").length == 0',
            3000,
            'check enabled main button'
        );

        // Submit
        $this->click('css=.current .button-row button');

        $this->waitForLocalCondition(
            'jQuery(".payment-step.current").length == 1',
            10000,
            'check swicth to next step'
        );

    }

    protected function fillPaymentStep()
    {
        $this->toggleByJquery('#pmethod6', true);

        $this->waitForLocalCondition(
            'jQuery(".current .button-row button.disabled").length == 0',
            3000,
            'check enabled main button'
        );

        // Submit
        $this->click('css=.current .button-row button');

        $this->waitForLocalCondition(
            'jQuery(".review-step.current").length == 1',
            10000,
            'check swicth to next step'
        );

    }

}

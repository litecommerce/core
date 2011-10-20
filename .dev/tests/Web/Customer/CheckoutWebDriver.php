<?php

/**
 * 
 * @resource cart
 * @resource register_user
 * @resource address_book
 * @resource order
 */
 class XLite_Web_Customer_CheckoutWebDriver extends Xlite_WebDriverTestCase
 {
     protected function addToCart()
    {
        $product = \XLite\Core\Database::getRepo('XLite\Model\Product')
            ->createQueryBuilder()
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();

        $this->skipCoverage();

        $this->open('store/product//product_id-' . $product->getProductId());
        
        $this->click("xpath=//button[@class='bright add2cart']");

        $this->assert_element_present('css=.product-details .product-buttons-added .buy-more', 'check content reloading');

        $this->open('store/checkout');

        return $product;
    }

    public function testStructure()
    {
        $product = $this->addToCart();

        $this->assert_element_present(
            "xpath=//h1[@id='page-title' and text()='Checkout']",
            'check page title'
        );

        $this->assert_element_present(
            "xpath=//div[@class='checkout-block']"
            . "/div[@class='profile']"
            . "/form[@class='create']"
            . "/div[@class='create']"
            . "/input[@id='create_profile_email']"
        );

        $this->assert_element_present(
            "xpath=//div[@class='checkout-block']"
            . "/div[@class='profile']"
            . "/form[@class='create']"
            . "/div[@class='create']"
            . "/div[@class='selector']"
            . "/input[@id='create_profile_chk']"
        );

        $this->assert_element_present(
            "xpath=//div[@class='checkout-block']"
            . "/div[@class='profile']"
            . "/form[@class='create']"
            . "/div[@class='create']"
            . "/div[@class='username']"
            . "/input[@id='create_profile_username']"
        );

        $this->assert_element_present(
            "xpath=//div[@class='checkout-block']"
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
        $this->type(
            'xpath=//input[@id="create_profile_email"]',
            'rnd_tester@cdev.ru'
        );
        $this->click('css=#page-title');
        $this->wait_inline_progress('css=#create_profile_email', 'duplicate email');
        $this->assert_input_error_present('xpath=//input[@id="create_profile_email"]', 'check duplicate email error');
        $this->assert_element_visible('css=p.username-verified', 'username-verified note is visible');

        // Duplicate username
        $this->type(
            'xpath=//input[@id="create_profile_email"]',
            $username . '@cdev.ru'
        );
        $this->click('css=#page-title');
        $this->wait_inline_progress('css=#create_profile_email', 'duplicate username and correct email');
        $this->assert_input_error_not_present('css=#create_profile_email');

        $this->type(
            'css=#create_profile_username',
            'master'
        );
        $this->click('css=#page-title');
        $this->wait_inline_progress('css=#create_profile_username', 'duplicate username');
        $this->assert_input_error_present('xpath=//input[@id="create_profile_username"]', 'profile username is duplicate');
        $this->assert_element_not_visible('css=p.username-verified', 'username-verified note is visible #2');
    }

    public function testShippingStep()
    {
        $product = $this->addToCart();

        $this->assert_element_visible('css=.current p.address-not-defined', 'address-not-defined is visible');

        // Fill minimum address fields
        $this->select(
            'xpath=//select[@id="shipping_address_country"]',
            'US'
        );
        $this->assert_element_present('css=#shipping_address_state', 'check state selector');


        $this->select(
            'xpath=//select[@id="shipping_address_state"]',
            'New York',
            'text()'
        );

        $this->type(
            'xpath=//input[@id="shipping_address_zipcode"]',
            '10001'
        );
        $this->click('css=#page-title');
        $this->assert_element_present("css=ul.shipping-rates li input", 'check shipping rates');
        foreach($this->get_all_elements('css=ul.shipping-rates li input[type="radio"]') as $element)
        {
            $element->assert_not_selected();
        }
        $this->assert_element_visible('css=.current p.address-not-completed','check address-not-completed node show');
        $this->assert_element_not_visible('css=.current p.address-not-defined', 'address-not-defined is invisible');

        // Complete shipping address
        $this->type(
            'xpath=//input[@id="shipping_address_name"]',
            'John Smith'
        );

        $this->type(
            'xpath=//input[@id="shipping_address_street"]',
            'test address'
        );

        $this->type(
            'xpath=//input[@id="shipping_address_city"]',
            'New York'
        );
        $this->click('css=#page-title');
        $this->assert_element_not_visible('css=.current p.address-not-completed','check address-not-completed note hide');

        $this->assert_element_present('css=.current .button-row button.disabled', 'check disabled main button');

        // Select shipping method
        $this->click('css=ul.shipping-rates li input:first-child');

        $this->assert_element_present('css=.current .button-row button.disabled', 'check disabled main button #2');
        $this->assert_element_visible('css=.current p.email-not-defined', 'email-not-defined is visible');

        // Fill profile data
        $this->fillProfileData();
        $this->assert_element_not_present('css=.current .button-row button.disabled','check enabled main button');

        $this->assert_element_not_visible('css=.current p.email-not-defined', 'email-not-defined is invisible');

        $this->click('css=.current .button-row button');

        $this->assert_element_present('css=.payment-step.current','check swicth to next step');
    }

    public function testPaymentStep()
    {
        $product = $this->addToCart();
        $this->fillProfileData();
        $this->fillShippingAddress();

        // Check payment method
        $this->assert_element_present('css=.current .button-row button.disabled', 'payment not selected - main button is disabled');

        $this->assert_element_present('css=ul.payments li input');

        foreach($this->get_all_elements('css=ul.payments li input') as $element)
                $element->assert_not_selected('payment is selected');
        //$this->assertJqueryNotPresent('css=ul.payments li input:checked', 'payment is not selected');

        
        $this->toggle($this->getPaymentSelector(), true);
        //$this->toggleByJquery($this->getPaymentSelector(), true);

        $this->assert_element_not_present('css=.current .button-row button.disabled', 'Main button disabled');
        // Fill billing address

        $this->toggle('css=#same_address', false);
        $this->assert_element_present('css=.current .button-row button.disabled', 'same address disabled and address not loaded - main button is disabled');

        $this->assert_element_present('css=#billing_address_name', 'check empty billing address form load');

        $this->assert_element_not_present('css=.current .button-row button.disabled', 'same address disabled and address form loaded - main button is enabled');

        $this->select(
            'xpath=//select[@id="billing_address_country"]',
            'US'
        );
        $this->assert_element_present('css=select#billing_address_state', 'check state selector');
        
        $this->select(
            'xpath=//select[@id="billing_address_state"]',
            'New York',
            'text()'
        );

        $this->type(
            'xpath=//input[@id="billing_address_zipcode"]',
            '10002'
        );

        $this->type(
            'xpath=//input[@id="billing_address_name"]',
            'John Smith Jr.'
        );

        $this->type(
            'xpath=//input[@id="billing_address_street"]',
            'test address billy'
        );

        $this->type(
            'xpath=//input[@id="billing_address_city"]',
            'New York'
        );
        $this->click('css=#page-title');
        //$this->toggle($this->getPaymentSelector(), true);
        $this->assert_element_not_present('css=.current .button-row button.disabled', 'check main button enabled after fill billing address');

        // Submit
        $this->click('css=.current .button-row button');

        $this->assert_element_present('css=.review-step.current', 'check swicth to next step');
    }

    public function testReviewStep()
    {
        $product = $this->addToCart();
        $this->fillProfileData();
        $this->fillShippingAddress();
        $this->fillPaymentStep();

        $this->assert_element_present('css=.review-step.current', 'check review step is active');

        // Check items box
        $this->assert_element_not_visible('css=.review-step .list','items box hide');

        $this->click('css=.review-step .items-row a');

        $this->assert_element_visible('css=.review-step .list','check items popup');

        $this->click('css=.review-step .items-row a');

        $this->assert_element_not_visible('css=.review-step .list', 'check items popup');
        
        // Check place button
        $this->assert_element_not_present('css=.current .button-row button.disabled', 'main button is enabled always');
        $this->assert_element_not_present('css=.current .non-agree', 'non-agree style is NOT applied');

        $this->click('css=.current .button-row button');

        $this->assert_element_present('css=.current .non-agree', 'non-agree style is applied');

        $this->click('xpath=//input[@id="place_order_agree"]');

        $this->assert_element_present('css=.current .non-agree', 'non-agree style is applied always');

        // Checkout with Check payment method
        $this->click('css=.current .button-row button');

        // Go to shop
        $this->get_element('css=#page-title')->assert_text_contains('Thank you for your order');
        $this->assert_url_contains('checkoutSuccess');

        // Check order
        $ordeid = null;

        $m = $this->assert_url_regexp('/order_id-(\d+)/Ss');
        $ordeid = $m[1];

        $this->assertTrue(0 < $ordeid, 'check order id');

        $order = \XLite\Core\Database::getRepo('XLite\Model\Order')->find($ordeid);

        $this->assertNotNull($order, 'check order');

        $this->assertEquals(1, count($order->getPaymentTransactions()), 'check payment transactions count');
        $this->assertEquals('W', $order->getPaymentTransactions()->get(0)->getStatus(), 'check payment transaction status');
        $this->assertFalse($order->isPayed(), 'check order payed status');
    }

    protected function logIn($user = array('login'=>'master', 'pass'=>'master'))
    {
        $this->open('user');

        $this->type('css=#edit-name', $user['login']);
        $this->type('css=#edit-pass', $user['pass']);

        $this->click('css=#edit-submit');
        $this->assert_element_present('xpath=//h1[@id="page-title" and contains(text(), "'.$user['login'].'")]', 'Not logged in');
    }

    public function testLogged()
    {
        $this->logIn();

        $product = $this->addToCart();

        // Check profile block
        $this->assert_element_present(
            "xpath=//div[@class='checkout-block']"
            . "/div[@class='profile']"
            . "/div[@class='logged']"
            . "/span[text()='Greetings, master']"
        );

        $this->assert_element_present(
            "xpath=//div[@class='checkout-block']"
            . "/div[@class='profile']"
            . "/div[@class='logged']"
            . "/a[@class='view-profile']"
        );

        $this->assert_element_present(
            "xpath=//div[@class='checkout-block']"
            . "/div[@class='profile']"
            . "/div[@class='logged']"
            . "/a[@class='logoff']"
        );
    }

    public function testAddressBook()
    {
        $this->logIn();
        
        $product = $this->addToCart();

        if ($this->is_element_present('css=.previous.shipping-step')){
            $this->click('css=.shipping-step .button-row button');
            $this->assert_element_present('css=.shipping-step.current','check switch to shipping step');
        }

        // Save as new

        $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->findOneBy(array('login' => 'rnd_tester@cdev.ru'));
        $profileCount = count($profile->getAddresses());

        $this->type(
            'xpath=//input[@id="shipping_address_name"]',
            'John Smith aaa'
        );

        $this->toggle('css=#save_shipping_address', true);

        // Select shipping method
        $this->toggle('css=ul.shipping-rates li:first-child input', true);

        $this->assert_element_not_present('css=.current .button-row button.disabled', 'check enabled main button');

        // Submit-and-return
        $this->click('css=.current .button-row button');
        $this->assert_element_not_present('css=.shipping-step.current','check swicth to next step');

        $this->click('css=.shipping-step .button-row button');

        $this->assert_element_present('css=.shipping-step.current', 'check swicth to previous step');

        \XLite\Core\Database::getEM()->clear();

        $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->findOneBy(array('login' => 'rnd_tester@cdev.ru'));

        $this->assertEquals($profileCount + 1, count($profile->getAddresses()), 'check addresses count #2');
        $this->assertEquals('John Smith aaa', $profile->getAddresses()->get($profileCount)->getName(), 'check name');

        // Open address book
        $this->click('css=.current button.address-book');
        $this->assert_element_present('css=form.select-address', 'wait address book popup');

        $this->assertEquals(
            $profileCount + 1,
            count($this->get_all_elements('css=form.select-address ul li')),
            'check addresses count into address book');

        // Select previous address
        $this->click('css=form.select-address ul li:first-child');
        //$this->getJSExpression('jQuery("form.select-address ul li:eq(0)").click()');
        $this->assert_element_not_present('css=#shipping_address_name[value="John Smith aaa"]','wait address changes');

        // Swicth to payment step
        $this->click('css=.current .button-row button');
        $this->assert_element_not_present('css=.shipping-step.current','check swicth to payment step');
        if ($this->is_element_not_present('css=.payment-step.current')){
            $this->click('css=.payment-step .button-row button');
            $this->assert_element_present('css=.payment-step.current', 'check return to payment step');
        }

        // Disabled same address
        $bname = 'John Smith aaa';

        if ($this->get_element('css=#same_address')->is_selected()) {
            $this->toggle('css=#same_address', true);
            $this->assert_element_not_present('css=#billing_address_name','check full billing address form load');
            $bname = 'Admin Admin';
        }

        $this->toggle('css=#same_address', false);
        $this->assert_element_present('css=#billing_address_name', 'check empty billing address form load');
        $this->get_element('css=#billing_address_name')->assert_value($bname, 'check billing name');

        $this->type(
            'xpath=//input[@id="billing_address_name"]',
            'John Smith bbb'
        );

        $this->toggle($this->getPaymentSelector(), true);

        $this->click('css=.current .button-row button');
        $this->assert_element_present('css=.review-step.current','check swicth to review step');

        \XLite\Core\Database::getEM()->clear();

        $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->findOneBy(array('login' => 'rnd_tester@cdev.ru'));

        $this->assertEquals($profileCount + 2, count($profile->getAddresses()), 'check addresses count #3');
        $this->assertEquals('John Smith bbb', $profile->getAddresses()->get($profileCount + 1)->getName(), 'check name #2');
        $this->assertFalse((bool)$profile->getAddresses()->get($profileCount + 1)->getIsShipping(), 'check is shipping');
        $this->assertTrue((bool)$profile->getAddresses()->get($profileCount + 1)->getIsBilling(), 'check is billing');

        // Return to payment step
        $this->click('css=.payment-step .button-row button');
        $this->assert_element_present('css=.payment-step.current', 'check swicth to payment step #2');

        // Swicth on same address
        $this->toggle('#same_address', true);
        $this->assert_element_not_present('css=#billing_address_name', 'check empty billing address form load');

        \XLite\Core\Database::getEM()->clear();

        $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->findOneBy(array('login' => 'rnd_tester@cdev.ru'));

        $this->assertEquals($profileCount + 2, count($profile->getAddresses()), 'check addresses count #4');
        $this->assertFalse((bool)$profile->getAddresses()->get($profileCount)->getIsShipping(), 'check is shipping #2');
        $this->assertFalse((bool)$profile->getAddresses()->get($profileCount + 1)->getIsBilling(), 'check is billing #2');
    }

    public function testLowInventory()
    {
        $product = $this->addToCart();
//        $product = \XLite\Core\Database::getRepo('XLite\Model\Product')
//            ->createQueryBuilder()
//            ->setMaxResults(1)
//            ->getQuery()
//            ->getSingleResult();
//
//        $this->skipCoverage();
//
//        $this->open('store/product//product_id-' . $product->getProductId());
//
//        $this->click("xpath=//button[@class='bright add2cart']");
//
//        $this->assert_element_present('css=.product-details .product-buttons-added .buy-more');
        
        $inv = $product->getInventory();

        $inv->setEnabled(true);
        $inv->setAmount(0);
        \XLite\Core\Database::getEM()->flush();

        $this->open('store/checkout');

        $this->assert_url_contains('/store/cart');
        
        $inv->setAmount(50);
        \XLite\Core\Database::getEM()->flush();
    }


    protected function fillProfileData()
    {
        $username = 'tester' . time();

        $this->type('xpath=//input[@id="create_profile_email"]', $username . '@cdev.ru');
        $this->click('css=#page-title');
       // $this->wait_inline_progress('css=#create_profile_email', 'email inline progress');
        $this->assert_input_error_not_present('css=#create_profile_email', 'email has not inline error');

        $this->click('css=#create_profile_chk');
        $this->assert_element_visible('css=.username', 'check open username box');

        $this->type('xpath=//input[@id="create_profile_username"]', $username);
        $this->click('css=#page-title');
        //$this->wait_inline_progress('#create_profile_username', 'username');
        $this->assert_input_error_not_present('xpath=//input[@id="create_profile_username"]', 'profile username is unique');

        $this->assert_element_visible('css=p.username-verified', 'username-verified note is visible (fill profile)');
    }

    protected function fillShippingAddress()
    {
        // Fill minimum address fields
        $this->select(
            'xpath=//select[@id="shipping_address_country"]',
            'US'
        );
        $this->assert_element_present('xpath=//select[@id="shipping_address_state"]', 'check state selector');

        $this->select(
            'xpath=//select[@id="shipping_address_state"]',
            'New York',
            'text()'
        );

        $this->type(
            'xpath=//input[@id="shipping_address_zipcode"]',
            '10001'
        );

        // Complete shipping address
        $this->type(
            'xpath=//input[@id="shipping_address_name"]',
            'John Smith'
        );

        $this->type(
            'xpath=//input[@id="shipping_address_street"]',
            'test address'
        );

        $this->type(
            'xpath=//input[@id="shipping_address_city"]',
            'new York'
        );

        $this->assert_element_not_present('css=ul.shipping-rates li input', 'check address-not-completed note hide');

        // Select shipping method
        $this->click('css=ul.shipping-rates li:first-child input');
        //$this->toggleByJquery('ul.shipping-rates li input:eq(0)', true);

        $this->assert_element_not_present('css=.current .button-row button.disabled', 'check enabled main button');

        // Submit
        $this->click('css=.current .button-row button');

        $this->assert_element_present('css=.payment-step.current', 'check switch to next step');
    }

    protected function fillPaymentStep()
    {
        $selector = $this->getPaymentSelector();

        $this->toggle($selector, true);

        $this->get_element($selector)->assert_selected('Payment method is not selected: ' . $selector);
        //$this->assertChecked('css=' . $selector, 'Payment method is not selected: ' . $selector);

        $this->assert_element_not_present('css=.current .button-row button.disabled', 'check enabled main button');

        // Submit
        $this->click('css=.current .button-row button');

        $this->assert_element_present('css=.review-step.current','check switching to next step (payment->review)');
    }

    protected function getPaymentSelector()
    {
        $selector = 'css=#pmethod' . $this->getPaymentMethodIdByName('PhoneOrdering');
        $this->assert_element_present($selector, "Payment method selector not found: $selector (Phone)");

        return $selector;
    }

 }

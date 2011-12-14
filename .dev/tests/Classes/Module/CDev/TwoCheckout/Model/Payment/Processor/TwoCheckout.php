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
 * @subpackage Classes
 * @author     Creative Development LLC <info@cdev.ru>
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

class XLite_Tests_Module_CDev_TwoCheckout_Model_Payment_Processor_TwoCheckout extends XLite_Tests_Model_Payment_PaymentAbstract
{
    protected $testMethod = array(
        'service_name' => 'test',
        'class'        => 'Module\CDev\TwoCheckout\Model\Payment\Processor\TwoCheckout',
        'orderby'      => 100,
        'enabled'      => true,
        'name'         => 'Test',
        'description'  => 'Description',
    );

    public function testPay()
    {
        $order = $this->getTestOrder();

        $t = $order->getPaymentTransactions()->get(0);
        $method = $order->getPaymentMethod();

        $this->checkTwoCheckoutConfigOptions();

        $this->query(
            'UPDATE xlite_payment_method_settings SET value = "' . $this->testConfig['two_checkout']['account'] . '" WHERE method_id = ' . $method->getMethodId() . ' AND name = "account"',
            array()
        );

        $this->assertFalse(is_null($order->getProfile()), 'check profile');
        $this->assertFalse(is_null($order->getProfile()->getBillingAddress()), 'check billing address');

        $this->assertEquals(0, $order->getOpenTotal(), 'check open total');
        $this->assertTrue($order->isOpen(), 'check open status');

        ob_start();
        $r = $t->handleCheckoutAction();
        $c = ob_get_contents();
        ob_end_clean();

        $this->assertEquals($t::PROLONGATION, $r, 'check result');
        $this->assertEquals($t::STATUS_INPROGRESS, $t->getStatus(), 'check status');
        $this->assertEquals(0, $order->getOpenTotal(), 'check open total #2');
        $this->assertFalse($order->isOpen(), 'check open status #2');
        $this->assertFalse($order->isPayed(), 'check payed status');

        $oid = $order->getOrderId();
        $tid = $t->getTransactionId();
        $sid = \XLite\Core\Session::getInstance()->getID();
        $amount = $t->getValue();

        $etalon = <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body onload="javascript: document.getElementById('form').submit();">
  <form method="post" id="form" name="payment_form" action="https://www.2checkout.com/checkout/spurchase">
    <fieldset style="display: none;">
      <input type="hidden" name="sid" value="260852" />
      <input type="hidden" name="total" value="19.99" />
      <input type="hidden" name="cart_order_id" value="{$tid}" />
      <input type="hidden" name="merchant_order_id" value="{$oid}" />
      <input type="hidden" name="pay_method" value="CC" />
      <input type="hidden" name="lang" value="" />
      <input type="hidden" name="skip_landing" value="1" />
      <input type="hidden" name="card_holder_name" value="Admin Admin" />
      <input type="hidden" name="street_address" value="51 apt, 87 street" />
      <input type="hidden" name="city" value="Edmond" />
      <input type="hidden" name="state" value="OK" />
      <input type="hidden" name="zip" value="73003" />
      <input type="hidden" name="country" value="US" />
      <input type="hidden" name="email" value="rnd_tester@cdev.ru" />
      <input type="hidden" name="phone" value="0123456789" />
      <input type="hidden" name="ship_name" value="Admin Admin" />
      <input type="hidden" name="ship_street_address" value="51 apt, 87 street" />
      <input type="hidden" name="ship_city" value="Edmond" />
      <input type="hidden" name="ship_state" value="OK" />
      <input type="hidden" name="ship_zip" value="73003" />
      <input type="hidden" name="ship_country" value="US" />
      <input type="hidden" name="fixed" value="Y" />
      <input type="hidden" name="id_type" value="1" />
      <input type="hidden" name="sh_cost" value="0.00" />
      <input type="hidden" name="c_prod" value="1,1" />
      <input type="hidden" name="c_name" value="Planet Express Babydoll" />
      <input type="hidden" name="c_price" value="19.99" />
      <input type="hidden" name="c_description" value="Planet Express Babydoll" />
    </fieldset>
    <noscript>
      If you are not redirected within 3 seconds, please <input type="submit" value="press here" />.
    </noscript>
  </form>
</body>
</html>
HTML;

        $this->assertEquals($etalon, $c, 'check form');
    }

    public function testGetInputTemplate()
    {
        $order = $this->getTestOrder();
        $p = $order->getPaymentMethod()->getProcessor();

        $this->assertNull($p->getInputTemplate(), 'check template');
    }

    public function testGetSettingsWidget()
    {
        $order = $this->getTestOrder();
        $p = $order->getPaymentMethod()->getProcessor();

        $this->assertEquals('modules/CDev/TwoCheckout/config.tpl', $p->getSettingsWidget(), 'check settings widget');
    }

    public function testIsConfigured()
    {
        $order = $this->getTestOrder();
        $method = $order->getPaymentMethod();

        $this->checkTwoCheckoutConfigOptions();

        $this->query(
            'UPDATE xlite_payment_method_settings SET value = "' . $this->testConfig['two_checkout']['account'] . '" WHERE method_id = ' . $method->getMethodId() . ' AND name = "account"',
            array()
        );

        \XLite\Core\Database::getEM()->clear();
        $method = \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')->find($method->getMethodId());
        $p = $method->getProcessor();

        $this->assertNotNull($method, 'method not null');
        $this->assertTrue($p->isConfigured($method), 'check configured status');

        $this->query(
            'UPDATE xlite_payment_method_settings SET value = "" WHERE method_id = ' . $method->getMethodId() . ' AND name = "account"',
            array()
        );

        \XLite\Core\Database::getEM()->clear();
        $method = \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')->find($method->getMethodId());
        $p = $method->getProcessor();

        $this->assertNotNull($method, 'method not null #2');
        $this->assertFalse($p->isConfigured($method), 'check configured status (false)');
    }

    public function testGetOperationTypes()
    {
        $method = $this->getTestMethod();
        $p = $method->getProcessor();

        $this->assertEquals(
            array(
                $p::OPERATION_SALE,
            ),
            $p->getOperationTypes(),
            'check operation types'
        );
    }

    public function testProcessReturn()
    {
        $order = $this->getTestOrder();
        $t = $order->getPaymentTransactions()->get(0);
        $p = $t->getPaymentMethod()->getProcessor();

        \XLite\Core\Request::getInstance()->cart_order_id = $t->getTransactionId();

        $this->assertEquals($t::STATUS_INITIALIZED, $t->getStatus(), 'check status');

        \XLite\Core\Request::getInstance()->setRequestMethod('POST');
        \XLite\Core\Request::getInstance()->cart_order_id = $t->getTransactionId();
        \XLite\Core\Request::getInstance()->total = $order->getTotal();
        
        \XLite\Core\Request::getInstance()->key = strtoupper(md5(
            'tango' . '260852' . $order->getOrderId() . $order->getTotal()
        ));

        $p->processReturn($t);
        
        $this->assertEquals($t::STATUS_SUCCESS, $t->getStatus(), 'check status #2');

        $fields = array();

        $this->assertEquals(count($fields), count($t->getData()), 'check trn data length');

        foreach ($t->getData() as $d) {
            $code = $d->getName();

            $this->assertTrue(isset($fields[$code]), 'check set field ' . $code);

            $this->assertEquals($fields[$code], $d->getLabel(), 'check label field ' . $code);

            $this->assertEquals(
                \XLite\Core\Request::getInstance()->$code,
                $d->getValue(),
                'check value field ' . $code
            );
        }
    }
    /**
    * @return XLite\Model\Order
    */
    protected function getTestMethod()
    {
        $method = parent::getTestMethod();

        $s = new \XLite\Model\Payment\MethodSetting();

        $s->setName('account');
        $s->setValue('260852');

        $method->addSettings($s);
        $s->setPaymentMethod($method);

        $s = new \XLite\Model\Payment\MethodSetting();

        $s->setName('secret');
        $s->setValue('tango');

        $method->addSettings($s);
        $s->setPaymentMethod($method);


        $s = new \XLite\Model\Payment\MethodSetting();

        $s->setName('prefix');

        $method->addSettings($s);
        $s->setPaymentMethod($method);

        $s = new \XLite\Model\Payment\MethodSetting();

        $s->setName('language');
        $s->setValue('en');

        $method->addSettings($s);
        $s->setPaymentMethod($method);

        $s->setName('mode');
        $s->setValue('test');

        $method->addSettings($s);
        $s->setPaymentMethod($method);

        $s->setName('currency');
        $s->setValue('USD');

        $method->addSettings($s);
        $s->setPaymentMethod($method);

        \XLite\Core\Database::getEM()->persist($method);
        \XLite\Core\Database::getEM()->flush();

        return $method;
    }

    protected function checkTwoCheckoutConfigOptions()
    {
        if (empty($this->testConfig['two_checkout']['account'])) {
            $this->markTestSkipped('Account for testing 2Checkout.com module is not specified');
        }
    }
}

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

class XLite_Tests_Module_CDev_PaypalWPS_Model_Payment_Processor_PaypalWPS extends XLite_Tests_Model_Payment_PaymentAbstract
{
    protected $testMethod = array(
        'service_name' => 'test',
        'class'        => 'Module\CDev\PaypalWPS\Model\Payment\Processor\PaypalWPS',
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

        $this->checkPaypalConfigOptions();

        $this->query(
            'UPDATE xlite_payment_method_settings SET value = "' . $this->testConfig['paypal_wps']['account'] . '" WHERE method_id = ' . $method->getMethodId() . ' AND name = "account"',
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

        $urla = \XLite::getInstance()->getShopURL('admin.php?target=payment_return&amp;txnId='.$tid.'&amp;txn_id_name=txnId');
        $urla = str_replace('&xid', '&amp;xid', $urla);

        $urld = \XLite::getInstance()->getShopURL('admin.php?target=payment_return&amp;cancel=1&amp;txn_id_name=txnId&amp;txnId=' . $tid);
        $urld = str_replace('&xid', '&amp;xid', $urld);

        $urlc = \XLite::getInstance()->getShopURL('admin.php?target=callback&amp;txnId='.$tid.'&amp;txn_id_name=txnId');
        $urlc = str_replace('&xid', '&amp;xid', $urlc);

        $etalon = <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body onload="javascript: document.getElementById('form').submit();">
  <form method="post" id="form" name="payment_form" action="https://www.paypal.com/cgi-bin/webscr">
    <fieldset style="display: none;">
      <input type="hidden" name="charset" value="UTF-8" />
      <input type="hidden" name="cmd" value="_ext-enter" />
      <input type="hidden" name="custom" value="$oid" />
      <input type="hidden" name="invoice" value="$oid" />
      <input type="hidden" name="redirect_cmd" value="_xclick" />
      <input type="hidden" name="item_name" value="(Order #$oid)" />
      <input type="hidden" name="rm" value="2" />
      <input type="hidden" name="email" value="rnd_tester@cdev.ru" />
      <input type="hidden" name="first_name" value="Admin" />
      <input type="hidden" name="last_name" value="Admin" />
      <input type="hidden" name="business" value="test" />
      <input type="hidden" name="amount" value="19.99" />
      <input type="hidden" name="tax_cart" value="0" />
      <input type="hidden" name="shipping" value="0" />
      <input type="hidden" name="handling" value="0" />
      <input type="hidden" name="weight_cart" value="0" />
      <input type="hidden" name="currency_code" value="USD" />
      <input type="hidden" name="return" value="$urla" />
      <input type="hidden" name="cancel_return" value="$urld" />
      <input type="hidden" name="shopping_url" value="$urld" />
      <input type="hidden" name="notify_url" value="$urlc" />
      <input type="hidden" name="country" value="US" />
      <input type="hidden" name="state" value="OK" />
      <input type="hidden" name="address1" value="51 apt, 87 street" />
      <input type="hidden" name="address2" value="n/a" />
      <input type="hidden" name="city" value="Edmond" />
      <input type="hidden" name="zip" value="73003" />
      <input type="hidden" name="upload" value="1" />
      <input type="hidden" name="bn" value="LiteCommerce" />
      <input type="hidden" name="night_phone_a" value="012" />
      <input type="hidden" name="night_phone_b" value="345" />
      <input type="hidden" name="night_phone_c" value="6789" />
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

        $this->assertEquals('modules/CDev/PaypalWPS/config.tpl', $p->getSettingsWidget(), 'check settings widget');
    }

    public function testIsConfigured()
    {
        $order = $this->getTestOrder();
        $method = $order->getPaymentMethod();

        $this->checkPaypalConfigOptions();

        $this->query(
            'UPDATE xlite_payment_method_settings SET value = "' . $this->testConfig['paypal_wps']['account'] . '" WHERE method_id = ' . $method->getMethodId() . ' AND name = "account"',
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

        $this->assertEquals($t::STATUS_INITIALIZED, $t->getStatus(), 'check status');

        \XLite\Core\Request::getInstance()->setRequestMethod('GET');
        $p->processReturn($t);
        $this->assertEquals($t::STATUS_INITIALIZED, $t->getStatus(), 'check status #2');

        \XLite\Core\Request::getInstance()->setRequestMethod('POST');
        $p->processReturn($t);
        $this->assertEquals($t::STATUS_INITIALIZED, $t->getStatus(), 'check status #3');

        $t->setStatus($t::STATUS_INPROGRESS);

        $p->processReturn($t);
        $this->assertEquals($t::STATUS_PENDING, $t->getStatus(), 'check status #4');

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

        \XLite\Core\Request::getInstance()->cancel = 1;
        $p->processReturn($t);
        $this->assertEquals($t::STATUS_FAILED, $t->getStatus(), 'check status #5');

    }
    /**
    * @return \XLite\Model\Payment\Method
    */
    protected function getTestMethod()
    {
        $method = parent::getTestMethod();

        $s = new \XLite\Model\Payment\MethodSetting();

        $s->setName('account');
        $s->setValue('test');

        $method->addSettings($s);
        $s->setPaymentMethod($method);

        $s = new \XLite\Model\Payment\MethodSetting();

        $s->setName('prefix');

        $method->addSettings($s);
        $s->setPaymentMethod($method);

        $s = new \XLite\Model\Payment\MethodSetting();

        $s->setName('description');
        $s->setValue('123');

        $method->addSettings($s);
        $s->setPaymentMethod($method);

        $s->setName('mode');
        $s->setValue('test');

        $method->addSettings($s);
        $s->setPaymentMethod($method);

        $s->setName('address_override');
        $s->setValue('N');

        $method->addSettings($s);
        $s->setPaymentMethod($method);

        \XLite\Core\Database::getEM()->persist($method);
        \XLite\Core\Database::getEM()->flush();

        return $method;
    }

    protected function checkPaypalConfigOptions()
    {
        if (empty($this->testConfig['paypal_wps']['account'])) {
            $this->markTestSkipped('Account for testing Paypal WPS module is not specified');
        }
    }
}

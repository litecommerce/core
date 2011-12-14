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

class XLite_Tests_Module_CDev_Quantum_Model_Payment_Processor_Quantum extends XLite_Tests_Model_Payment_PaymentAbstract
{
    protected $testMethod = array(
        'service_name' => 'test',
        'class'        => 'Module\CDev\Quantum\Model\Payment\Processor\Quantum',
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

        $this->query(
            'UPDATE xlite_payment_method_settings SET value = "' . $this->testConfig['quantum_gateway']['login'] . '" WHERE method_id = ' . $method->getMethodId() . ' AND name = "login"',
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

        $urla = \XLite::getInstance()->getShopURL('admin.php?target=payment_return&amp;txn_id_name=ID', \XLite\Core\Config::getInstance()->Security->customer_security);
        $urla = str_replace('ID&xid', 'ID&amp;xid', $urla);

        $urld = \XLite::getInstance()->getShopURL('admin.php?target=payment_return&amp;txn_id_name=ID', \XLite\Core\Config::getInstance()->Security->customer_security);
        $urld = str_replace('ID&xid', 'ID&amp;xid', $urld);

        $etalon = <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body onload="javascript: document.getElementById('form').submit();">
  <form method="post" id="form" name="payment_form" action="https://secure.quantumgateway.com/cgi/qgwdbe.php">
    <fieldset style="display: none;">
      <input type="hidden" name="gwlogin" value="test" />
      <input type="hidden" name="post_return_url_approved" value="$urla" />
      <input type="hidden" name="post_return_url_declined" value="$urld" />
      <input type="hidden" name="ID" value="$tid" />
      <input type="hidden" name="amount" value="$amount" />
      <input type="hidden" name="BADDR1" value="51 apt, 87 street" />
      <input type="hidden" name="BZIP1" value="73003" />
      <input type="hidden" name="FNAME" value="Admin" />
      <input type="hidden" name="LNAME" value="Admin" />
      <input type="hidden" name="BCITY" value="Edmond" />
      <input type="hidden" name="BSTATE" value="Oklahoma" />
      <input type="hidden" name="BCOUNTRY" value="US" />
      <input type="hidden" name="BCUST_EMAIL" value="rnd_tester@cdev.ru" />
      <input type="hidden" name="SFNAME" value="Admin" />
      <input type="hidden" name="SLNAME" value="Admin" />
      <input type="hidden" name="SADDR1" value="51 apt, 87 street" />
      <input type="hidden" name="SCITY" value="Edmond" />
      <input type="hidden" name="SSTATE" value="Oklahoma" />
      <input type="hidden" name="SZIP1" value="73003" />
      <input type="hidden" name="SCOUNTRY" value="US" />
      <input type="hidden" name="PHONE" value="0123456789" />
      <input type="hidden" name="trans_method" value="CC" />
      <input type="hidden" name="ResponseMethod" value="POST" />
      <input type="hidden" name="cust_id" value="rnd_tester@cdev.ru" />
      <input type="hidden" name="customer_ip" value="" />
      <input type="hidden" name="invoice_num" value="$oid" />
      <input type="hidden" name="invoice_description" value="Order #$oid; transaction: $tid" />
      <input type="hidden" name="MAXMIND" value="1" />
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

        $this->assertNull($p->getInputTemplate(), 'check tempalte');
    }

    public function testGetSettingsWidget()
    {
        $order = $this->getTestOrder();
        $p = $order->getPaymentMethod()->getProcessor();

        $this->assertEquals('modules/CDev/Quantum/config.tpl', $p->getSettingsWidget(), 'check settings widget');
    }

    public function testIsConfigured()
    {
        $order = $this->getTestOrder();
        $method = $order->getPaymentMethod();

        $this->query(
            'UPDATE xlite_payment_method_settings SET value = "' . $this->testConfig['quantum_gateway']['login'] . '" WHERE method_id = ' . $method->getMethodId() . ' AND name = "login"',
            array()
        );

        \XLite\Core\Database::getEM()->clear();
        $method = \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')->find($method->getMethodId());
        $p = $method->getProcessor();

        $this->assertNotNull($method, 'method not null');
        $this->assertTrue($p->isConfigured($method), 'check configured status');

        $this->query(
            'UPDATE xlite_payment_method_settings SET value = "" WHERE method_id = ' . $method->getMethodId() . ' AND name = "login"',
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

        \XLite\Core\Request::getInstance()->trans_result = 'APPROVED';

        \XLite\Core\Request::getInstance()->transID        = 1;
        \XLite\Core\Request::getInstance()->authCode       = 2;
        \XLite\Core\Request::getInstance()->decline_reason = 3;
        \XLite\Core\Request::getInstance()->errorcode      = 4;
        \XLite\Core\Request::getInstance()->avs_result     = 5;
        \XLite\Core\Request::getInstance()->cvv2_result    = 6;
        \XLite\Core\Request::getInstance()->max_score      = 7;

        \XLite\Core\Request::getInstance()->amount = $t->getValue();

        \XLite\Core\Request::getInstance()->md5_hash = md5(
            '123' . 'test' . '1' . $t->getValue()
        );

        $p->processReturn($t);
        $this->assertEquals($t::STATUS_SUCCESS, $t->getStatus(), 'check status #4');

        $fields = array(
            'transID'        => 'Transaction id',
            'authCode'       => 'Auth. code',
            'decline_reason' => 'Decline reason',
            'errorcode'      => 'Error code',
            'avs_result'     => 'AVS result',
            'cvv2_result'    => 'CVV2 result',
            'max_score'      => 'MaxMind score',
        );

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

        // Wrong MD5 hash
        \XLite\Core\Request::getInstance()->md5_hash = md5(
            '123' . 'test' . '1' . $t->getValue() . 'wrong'
        );

        $p->processReturn($t);
        $this->assertEquals($t::STATUS_FAILED, $t->getStatus(), 'check status #5');

        $h = null;
        foreach ($t->getData() as $d) {
            if ($d->getName() == 'hash_checking') {
                $h = $d;
                break;
            }
        }

        $this->assertNotNull($h, 'check set error record');
        $this->assertEquals('MD5 hash checking', $h->getLabel(), 'check error label');
        $this->assertEquals('failed', $h->getValue(), 'check error value');

        // Failed status
        \XLite\Core\Request::getInstance()->md5_hash = null;
        \XLite\Core\Request::getInstance()->trans_result = 'FAILED';

        $p->processReturn($t);
        $this->assertEquals($t::STATUS_FAILED, $t->getStatus(), 'check status #6');

        // Check amount
        \XLite\Core\Request::getInstance()->trans_result = 'APPROVED';
        \XLite\Core\Request::getInstance()->amount = $t->getValue() + 1;

        $p->processReturn($t);
        $this->assertEquals($t::STATUS_FAILED, $t->getStatus(), 'check status #7');

        $h = null;
        foreach ($t->getData() as $d) {
            if ($d->getName() == 'total_checking_error') {
                $h = $d;
                break;
            }
        }

        $this->assertNotNull($h, 'check set error record #2');
        $this->assertEquals('Hacking attempt', $h->getLabel(), 'check error label #2');
        $this->assertEquals(
            'Total amount doesn\'t match. Transaction total: ' . $t->getValue()
            . '; payment gateway amount: ' . ($t->getValue() + 1),
            $h->getValue(),
            'check error value #2'
        );
    }
    /**
    * @return \XLite\Model\Payment\Method
    */
    protected function getTestMethod()
    {
        $method = parent::getTestMethod();

        $s = new \XLite\Model\Payment\MethodSetting();

        $s->setName('login');
        $s->setValue('test');

        $method->addSettings($s);
        $s->setPaymentMethod($method);

        $s = new \XLite\Model\Payment\MethodSetting();

        $s->setName('prefix');

        $method->addSettings($s);
        $s->setPaymentMethod($method);

        $s = new \XLite\Model\Payment\MethodSetting();

        $s->setName('hash');
        $s->setValue('123');

        $method->addSettings($s);
        $s->setPaymentMethod($method);

        \XLite\Core\Database::getEM()->persist($method);
        \XLite\Core\Database::getEM()->flush();

        return $method;
    }


}

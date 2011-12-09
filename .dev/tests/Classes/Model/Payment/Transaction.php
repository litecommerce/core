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

class XLite_Tests_Model_Payment_Transaction extends XLite_Tests_Model_Payment_PaymentAbstract
{


    public function testCreate()
    {
        $order = $this->getTestOrder();

        $t = $order->getPaymentTransactions()->get(0);
        $method = $order->getPaymentMethod();

        $this->assertTrue(0 < $t->getTransactionId(), 'check trn id');

        $this->assertEquals($method->getServiceName(), $t->getMethodName(), 'check method name');
        $this->assertEquals($method->getName(), $t->getMethodLocalName(), 'check method local name');
        $this->assertEquals($t::STATUS_INITIALIZED, $t->getStatus(), 'check status');
        $this->assertEquals($order->getTotal(), $t->getValue(), 'check value');
        $this->assertEquals('', $t->getNote(), 'check note');
        $this->assertEquals('sale', $t->getType(), 'check type');

        $this->assertEquals($order, $t->getOrder(), 'check order');
        $this->assertEquals($method, $t->getPaymentMethod(), 'check method');
    }

    public function testUpdate()
    {
        $order = $this->getTestOrder();

        $t = $order->getPaymentTransactions()->get(0);
        $method = $order->getPaymentMethod();

        $t->setMethodName('test');
        $t->setMethodLocalName('local test');
        $t->setStatus($t::STATUS_INPROGRESS);
        $t->setValue(10);
        $t->setNote('test note');
        $t->setType('auth');

        \XLite\Core\Database::getEM()->persist($t);
        \XLite\Core\Database::getEM()->flush();

        $t = \XLite\Core\Database::getRepo('XLite\Model\Payment\Transaction')
            ->find($t->getTransactionId());

        $this->assertEquals('test', $t->getMethodName(), 'check method name');
        $this->assertEquals('local test', $t->getMethodLocalName(), 'check method local name');
        $this->assertEquals($t::STATUS_INPROGRESS, $t->getStatus(), 'check status');
        $this->assertEquals(10, $t->getValue(), 'check value');
        $this->assertEquals('test note', $t->getNote(), 'check note');
        $this->assertEquals('auth', $t->getType(), 'check type');
    }

    public function testDelete()
    {
        $order = $this->getTestOrder();

        $t = $order->getPaymentTransactions()->get(0);
        $method = $order->getPaymentMethod();

        $id = $t->getTransactionId();
        $method->getTransactions()->removeElement($t);
        $order->getPaymentTransactions()->removeElement($t);

        \XLite\Core\Database::getEM()->remove($t);
        \XLite\Core\Database::getEM()->flush();

        $t = \XLite\Core\Database::getRepo('XLite\Model\Payment\Transaction')
            ->find($id);

        $this->assertNull($t, 'check removed trn');
    }

    public function testHandleCheckoutAction()
    {
        $order = $this->getTestOrder();

        $t = $order->getPaymentTransactions()->get(0);
        $method = $order->getPaymentMethod();

        $this->assertEquals(0, $order->getOpenTotal(), 'check open total');
        $this->assertTrue($order->isOpen(), 'check open status');

        $r = $t->handleCheckoutAction();

        $this->assertEquals($t::COMPLETED, $r, 'check result');
        $this->assertEquals($t::STATUS_PENDING, $t->getStatus(), 'check status');
        $this->assertEquals(0, $order->getOpenTotal(), 'check open total #2');
        $this->assertFalse($order->isOpen(), 'check open status #2');
        $this->assertFalse($order->isPayed(), 'check payed status');
    }

    public function testGetChargeValueModifier()
    {
        $order = $this->getTestOrder();

        $t = $order->getPaymentTransactions()->get(0);
        $method = $order->getPaymentMethod();

        $this->assertEquals($order->getTotal(), $t->getChargeValueModifier(), 'check total');

        $t->setStatus($t::STATUS_FAILED);

        $this->assertEquals(0, $t->getChargeValueModifier(), 'check total #2');
    }

    public function testIsFailed()
    {
        $order = $this->getTestOrder();

        $t = $order->getPaymentTransactions()->get(0);

        $this->assertFalse($t->isFailed(), 'chec status (false)');

        $t->setStatus($t::STATUS_FAILED);
        $this->assertTrue($t->isFailed(), 'chec status (true)');
    }

    public function testIsCompleted()
    {
        $order = $this->getTestOrder();

        $t = $order->getPaymentTransactions()->get(0);

        $this->assertFalse($t->isCompleted(), 'chec status (false)');

        $t->setStatus($t::STATUS_SUCCESS);
        $this->assertTrue($t->isCompleted(), 'chec status (true)');
    }

    public function testData()
    {
        $order = $this->getTestOrder();

        $t = $order->getPaymentTransactions()->get(0);

        $d = new \XLite\Model\Payment\TransactionData();

        $d->setName('cell1');
        $d->setValue('test');
        $d->setLabel('Cell 1');

        $t->addData($d);
        $d->setTransaction($t);

        \XLite\Core\Database::getEM()->persist($t);
        \XLite\Core\Database::getEM()->flush();

        $this->assertEquals(1, count($t->getData()), 'check data length');
        $this->assertEquals('cell1', $t->getData()->get(0)->getName(), 'check name');
    }


}

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

class XLite_Tests_Model_Payment_TransactionData extends XLite_Tests_Model_Payment_PaymentAbstract
{
       public function testCreate()
    {
        $order = $this->getTestOrder();

        $t = $order->getPaymentTransactions()->get(0);

        $this->assertTrue(0 < $t->getData()->get(0)->getDataId(), 'check record id');
        $this->assertEquals(2, count($t->getData()), 'check data length');

        $r = $t->getData()->get(0);
        $this->assertEquals('r1', $r->getName(), 'check name');
        $this->assertEquals('Record 1', $r->getLabel(), 'check label');
        $this->assertEquals('1', $r->getValue(), 'check value');
        $this->assertEquals($t, $r->getTransaction(), 'check transaction');

        $r = $t->getData()->get(1);
        $this->assertEquals('r2', $r->getName(), 'check name #2');
        $this->assertEquals('Record 2', $r->getLabel(), 'check label #2');
        $this->assertEquals('2', $r->getValue(), 'check value #2');
        $this->assertEquals($t, $r->getTransaction(), 'check transaction #2');
    }

    public function testUpdate()
    {
        $order = $this->getTestOrder();

        $t = $order->getPaymentTransactions()->get(0);
        $r = $t->getData()->get(0);

        $r->setName('r0');
        $r->setLabel('Record 0');
        $r->setValue('0');
        $r->setAccessLevel($r::ACCESS_CUSTOMER);

        \XLite\Core\Database::getEM()->persist($t);
        \XLite\Core\Database::getEM()->flush();

        $r = \XLite\Core\Database::getRepo('XLite\Model\Payment\TransactionData')
            ->find($r->getDataId());

        $this->assertEquals('r0', $r->getName(), 'check name');
        $this->assertEquals('Record 0', $r->getLabel(), 'check label');
        $this->assertEquals('0', $r->getValue(), 'check value');
        $this->assertEquals($r::ACCESS_CUSTOMER, $r->getAccessLevel(), 'check access level');
    }

    public function testDelete()
    {
        $order = $this->getTestOrder();

        $t = $order->getPaymentTransactions()->get(0);
        $r = $t->getData()->get(0);

        $id = $r->getDataId();
        $t->getData()->removeElement($r);

        \XLite\Core\Database::getEM()->remove($r);
        \XLite\Core\Database::getEM()->flush();

        $r = \XLite\Core\Database::getRepo('XLite\Model\Payment\TransactionData')
            ->find($id);

        $this->assertNull($r, 'check removed record');
    }

    public function testIsAvailable()
    {
       $order = $this->getTestOrder();

        $t = $order->getPaymentTransactions()->get(0);
        $r = $t->getData()->get(0);

        if (\XLite::isAdminZone()) {

            $r->setAccessLevel($r::ACCESS_ADMIN);
            $this->assertTrue($r->isAvailable(), 'check admin access');

            $r->setAccessLevel($r::ACCESS_CUSTOMER);
            $this->assertTrue($r->isAvailable(), 'check admin access #2');

        } else {

            $r->setAccessLevel($r::ACCESS_ADMIN);
            $this->assertFalse($r->isAvailable(), 'check customer access');

            $r->setAccessLevel($r::ACCESS_CUSTOMER);
            $this->assertTrue($r->isAvailable(), 'check customer access #2');

        }
    }

    /**
     * @return XLite\Model\Order
     */
    protected function getTestOrder($new_order = false)
    {
        parent::getTestOrder($new_order);

        $this->order->setPaymentMethod($this->getTestMethod());

        \XLite\Core\Database::getEM()->flush();
        \XLite\Core\Database::getEM()->refresh($this->order);

        $t = $this->order->getPaymentTransactions()->get(0);

        $r = new \XLite\Model\Payment\TransactionData();

        $r->setName('r1');
        $r->setLabel('Record 1');
        $r->setValue(1);

        $t->addData($r);
        $r->setTransaction($t);

        $r = new \XLite\Model\Payment\TransactionData();

        $r->setName('r2');
        $r->setLabel('Record 2');
        $r->setValue(2);

        $t->addData($r);
        $r->setTransaction($t);

        \XLite\Core\Database::getEM()->flush();

        return $this->order;
    }
}

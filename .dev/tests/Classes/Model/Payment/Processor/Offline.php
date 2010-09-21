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
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

class XLite_Tests_Model_Payment_Processor_Offline extends XLite_Tests_TestCase
{
    protected $testMethod = array(
        'service_name' => 'test',
        'class'        => 'Model\Payment\Processor\Offline',
        'orderby'      => 100,
        'enabled'      => false,
        'name'         => 'Test',
        'description'  => 'Description',
    );

    public function testPay()
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

        $this->assertNull($p->getSettingsWidget(), 'check settings widget');
    }

    public function testIsConfigured()
    {
        $order = $this->getTestOrder();
        $p = $order->getPaymentMethod()->getProcessor();

        $this->assertTrue($p->isConfigured($order->getPaymentMethod()), 'check configured status');
    }

    protected function getTestMethod()
    {
        $method = new \XLite\Model\Payment\Method();

        $method->map($this->testMethod);

        $s = new \XLite\Model\Payment\MethodSetting();

        $s->setName('t1');
        $s->setValue('1');

        $method->addSettings($s);
        $s->setPaymentMethod($method);

        $s = new \XLite\Model\Payment\MethodSetting();

        $s->setName('t2');
        $s->setValue('2');

        $method->addSettings($s);
        $s->setPaymentMethod($method);

        \XLite\Core\Database::getEM()->persist($method);
        \XLite\Core\Database::getEM()->flush();

        return $method;
    }

    protected function getProduct()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Product')->findOneByEnabled(true);
    }

    protected function getTestOrder()
    {
        $order = new \XLite\Model\Order();

        $profile = new \XLite\Model\Profile();
        $list = $profile->findAll();
        $profile = array_shift($list);
        unset($list);

        $order->setCurrency(\XLite\Core\Database::getRepo('XLite\Model\Currency')->find(840));
        $order->setProfileId(0);

        \XLite\Core\Database::getEM()->persist($order);
        \XLite\Core\Database::getEM()->flush();

        $item = new \XLite\Model\OrderItem();

        $item->setProduct($this->getProduct());
        $item->setAmount(1);
        $item->setPrice($this->getProduct()->getPrice());

        $order->addItem($item);

        \XLite\Core\Database::getEM()->persist($order);
        \XLite\Core\Database::getEM()->flush();

        $order->setProfileCopy($profile);
        $order->calculate();

        $order->setPaymentMethod($this->getTestMethod());

        \XLite\Core\Database::getEM()->persist($order);
        \XLite\Core\Database::getEM()->flush();

        return $order;
    }
}

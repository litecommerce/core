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

class XLite_Tests_Model_Payment_Method extends XLite_Tests_Model_Payment_PaymentAbstract
{
    public function testCreate()
    {
        $method = $this->getTestMethod();

        $this->assertTrue(0 < $method->getMethodId(), 'check method id');

        foreach ($this->testMethod as $k => $v) {
            $m = 'get' . \XLite\Core\Converter::convertToCamelCase($k);
            $this->assertEquals($v, $method->$m(), 'Check ' . $k);
        }
    }

    public function testUpdate()
    {
        $method = $this->getTestMethod();

        $method->setOrderby(99);
        $method->setEnabled(false);

        \XLite\Core\Database::getEM()->persist($method);
        \XLite\Core\Database::getEM()->flush();

        $method = \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')->find($method->getMethodId());

        $this->assertEquals(99, $method->getOrderby(), 'check new order by');
        $this->assertFalse($method->getEnabled(), 'check new enabled status');
    }

    public function testDelete()
    {
        $method = $this->getTestMethod();

        $id = $method->getMethodId();

        \XLite\Core\Database::getEM()->remove($method);
        \XLite\Core\Database::getEM()->flush();

        $method = \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')
            ->find($id);

        $this->assertNull($method, 'check removed method');
    }

    public function testGetProcessor()
    {
        $method = $this->getTestMethod();

        $this->assertEquals('XLite\Model\Payment\Processor\Offline', get_class($method->getProcessor()), 'check class');

        $method->setClass('\XLite\Model\Payment\Processor\CreditCard');

        $this->assertEquals('XLite\Model\Payment\Processor\CreditCard', get_class($method->getProcessor()), 'check class #2');
    }

    public function testSetClass()
    {
        $method = $this->getTestMethod();

        $this->assertEquals('Model\Payment\Processor\Offline', $method->getClass(), 'check class #1');

        $method->setClass('\XLite\Model\Payment\Processor\CreditCard');
        $this->assertEquals('Model\Payment\Processor\CreditCard', $method->getClass(), 'check class #2');

        $method->setClass('XLite\Model\Payment\Processor\CreditCard');
        $this->assertEquals('Model\Payment\Processor\CreditCard', $method->getClass(), 'check class #3');

        $method->setClass('\Model\Payment\Processor\CreditCard');
        $this->assertEquals('Model\Payment\Processor\CreditCard', $method->getClass(), 'check class #4');

        $method->setClass('Model\Payment\Processor\CreditCard');
        $this->assertEquals('Model\Payment\Processor\CreditCard', $method->getClass(), 'check class #5');
    }

    public function testGetSetting()
    {
        $method = $this->getTestMethod();

        $this->assertNull($method->getSetting('xxx'), 'check not-set setting');
        $this->assertEquals('1', $method->getSetting('t1'), 'check set setting');

        $method->setSetting('t1', null);
        $this->assertEquals('', $method->getSetting('t1'), 'check empty setting');
    }

    public function testSetSetting()
    {
        $method = $this->getTestMethod();

        $this->assertFalse($method->setSetting('xxx', 2), 'check not-set setting');
        $this->assertTrue($method->setSetting('t1', 2), 'check set setting');
        $this->assertEquals('2', $method->getSetting('t1'), 'check get setting');
    }

    public function testTransactions()
    {
        $order = $this->getTestOrder();

        $this->assertEquals($this->testMethod['service_name'], $order->getPaymentMethod()->getServiceName(), 'check service name');

        $method = $order->getPaymentMethod();

        $this->assertEquals(1, count($method->getTransactions()), 'check transactions count');
    }

    public function testTranslations()
    {

        $method = $this->getTestMethod();

        $this->assertEquals('Test', $method->getTranslation()->getName(), 'check default name');

        $method->getTranslation('de')->setName('Test de');

        \XLite\Core\Database::getEM()->persist($method);
        \XLite\Core\Database::getEM()->flush();

        $this->assertEquals('Test', $method->getTranslation()->getName(), 'check default name #2');
        $this->assertEquals('Test de', $method->getTranslation('de')->getName(), 'check de name');
    }


}

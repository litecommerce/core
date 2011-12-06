<?php
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

class XLite_Tests_Model_Payment_PaymentAbstract extends XLite_Tests_Model_OrderAbstract
{
    protected $testMethod = array(
        'service_name' => 'test',
        'class' => 'Model\Payment\Processor\Offline',
        'orderby' => 100,
        'enabled' => false,
        'name' => 'Test',
        'description' => 'Description',
    );
    /**
     * @var \XLite\Model\Payment\Method
     */
    protected $method;
    /**
    * @return \XLite\Model\Payment\Method
    */
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
        $this->method = $method;
        return $method;
    }

    /**
    * @return XLite\Model\Order
    */
    protected function getTestOrder($new_order = false)
    {
        $order = parent::getTestOrder($new_order);

        $order->setPaymentMethod($this->getTestMethod());

        \XLite\Core\Database::getEM()->flush();

        return $order;
    }

    protected function tearDown()
    {
        $this->clearEntity($this->method);
        parent::tearDown();
    }
}
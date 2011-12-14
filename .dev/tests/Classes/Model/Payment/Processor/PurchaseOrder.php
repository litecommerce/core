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

class XLite_Tests_Model_Payment_Processor_PurchaseOrder extends XLite_Tests_Model_Payment_PaymentAbstract
{
    protected $testMethod = array(
        'service_name' => 'test',
        'class'        => 'Model\Payment\Processor\PurchaseOrder',
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

        $data = array(
            'number'    => 1,
            'company'   => 2,
            'purchaser' => 3,
            'position'  => 4,
        );

        $proc = $method->getProcessor();
        $r = $proc->pay($t, $data);

        $this->assertEquals($proc::PENDING, $r, 'check result');

        $etalon = array(
            'number'    => 'Purchase order number',
            'company'   => 'Company name',
            'purchaser' => 'Name of purchaser',
            'position'  => 'Position',
        );

        $i = 0;
        foreach ($etalon as $k => $v) {
            $r = $t->getData()->get($i);

            $this->assertEquals($k, $r->getName(), 'check record name #' . $i);
            $this->assertEquals($v, $r->getLabel(), 'check record label #' . $i);
            $this->assertEquals($i + 1, $r->getValue(), 'check record value #' . $i);

            $i++;
        }
    }

    public function testGetInputTemplate()
    {
        $order = $this->getTestOrder();
        $p = $order->getPaymentMethod()->getProcessor();

        $this->assertEquals('checkout/purchase_order.tpl', $p->getInputTemplate(), 'check template');
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
}

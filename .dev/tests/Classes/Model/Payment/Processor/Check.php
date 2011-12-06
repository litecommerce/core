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

class XLite_Tests_Model_Payment_Processor_Check extends XLite_Tests_Model_Payment_PaymentAbstract
{
    protected $testMethod = array(
        'service_name' => 'test',
        'class'        => 'Model\Payment\Processor\Check',
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
            'routing_number' => 1,
            'acct_number'    => 2,
            'type'           => 3,
            'bank_name'      => 4,
            'acct_name'      => 5,
            'number'         => 6,
        );

        $proc = $method->getProcessor();
        $r = $proc->pay($t, $data);

        $this->assertEquals($proc::PENDING, $r, 'check result');

        $etalon = array(
            'routing_number' => 'ABA routing number',
            'acct_number'    => 'Bank Account Number',
            'type'           => 'Type of Account',
            'bank_name'      => 'Name of bank at which account is maintained',
            'acct_name'      => 'Name under which the account is maintained at the bank',
            'number'         => 'Check number',
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

        $this->assertEquals('checkout/echeck.tpl', $p->getInputTemplate(), 'check tempalte');
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

    public function testIsDisplayNumber()
    {
        $order = $this->getTestOrder();
        $p = $order->getPaymentMethod()->getProcessor();


        $this->assertEquals(\XLite\Core\Config::getInstance()->General->display_check_number, $p->isDisplayNumber(), 'check result');
    }


}

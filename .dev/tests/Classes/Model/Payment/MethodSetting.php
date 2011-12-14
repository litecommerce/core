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

class XLite_Tests_Model_Payment_MethodSetting extends XLite_Tests_Model_Payment_PaymentAbstract
{

    protected $testSettings = array(
        't1' => '1',
        't2' => '2',
    );

    public function testCreate()
    {
        $method = $this->getTestMethod();

        foreach ($method->getSettings() as $s) {
            $this->assertTrue(isset($this->testSettings[$s->getname()]), 'check key ' . $s->getName());
            $this->assertEquals($this->testSettings[$s->getname()], $s->getValue(), 'chec value of key ' . $s->getName());
            $this->assertEquals($method, $s->getPaymentMethod(), 'check payment method');
        }
    }

    public function testUpdate()
    {
        $method = $this->getTestMethod();

        $method->setSetting('t1', 3);

        \XLite\Core\Database::getEM()->persist($method);
        \XLite\Core\Database::getEM()->flush();

        $method = \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')->find($method->getMethodId());

        $this->assertEquals('3', $method->getSetting('t1'), 'check new setting');
    }

    public function testDelete()
    {
        $method = $this->getTestMethod();

        $s = $method->getSettings()->get(0);
        $id = $s->getSettingId();

        $method->getSettings()->removeElement($s);

        \XLite\Core\Database::getEM()->remove($s);
        \XLite\Core\Database::getEM()->flush();

        $s = \XLite\Core\Database::getRepo('XLite\Model\Payment\MethodSetting')
            ->find($id);

        $this->assertNull($s, 'check removed setting');
    }


}

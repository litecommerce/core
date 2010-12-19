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

class XLite_Tests_Model_OrderModifier_Tax extends XLite_Tests_Model_OrderAbstract
{
    public function testIsTaxAvailable()
    {
        $order = $this->getTestOrder();

        $this->assertTrue($order->isTaxAvailable(), 'check shipping avalable');

        $order->setProfile(null);

        $this->assertFalse($order->isTaxAvailable(), 'check shipping not avalable');
    }

    public function testIsTaxSummable()
    {
        $order = $this->getTestOrder();

        $this->assertTrue($order->isTaxSummable('Tax'), 'check main tax');
        $this->assertFalse($order->isTaxSummable('T'), 'check secondary tax');
    }

    public function testGetDisplayTaxes()
    {
        $order = $this->getTestOrder();

        $this->assertEquals(array('Tax' => 0.00), $order->getDisplayTaxes(), 'check taxes');

        $order->setProfile(null);

        $this->assertEquals(array(), $order->getDisplayTaxes(), 'check empty taxes');
    }

    public function testGetTaxLabel()
    {
        $order = $this->getTestOrder();

        $this->assertEquals('Tax', $order->getTaxLabel('Tax'), 'check main tax');
        $this->assertEquals('', $order->getTaxLabel('t'), 'check worng tax');

        // TODO - rework after tax subsystem rework
    }

    public function testGetRegistration()
    {
        $order = $this->getTestOrder();

        $this->assertEquals('', $order->getRegistration('Tax'), 'check main tax');
        $this->assertEquals('', $order->getRegistration('t'), 'check worng tax');

        // TODO - rework after tax subsystem rework
    }

    public function testIsTaxRegistered()
    {
        $order = $this->getTestOrder();

        $this->assertFalse($order->isTaxRegistered(), 'check tax registration');

        // TODO - rework after tax subsystem rework
    }

    public function testIsTaxDefined()
    {
        $order = $this->getTestOrder();

        $this->assertTrue($order->isTaxDefined(), 'check tax definition');

        // TODO - rework after tax subsystem rework
    }

}

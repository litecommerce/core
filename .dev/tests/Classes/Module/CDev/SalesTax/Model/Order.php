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
 * PHP version 5.3.0
 * 
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

class XLite_Tests_Module_CDev_SalesTax_Model_Order extends XLite_Tests_Model_OrderAbstract
{
    public function testCalculation()
    {
        $tax = \XLite\Core\Database::getRepo('XLite\Module\CDev\VAT\Model\Tax')->getTax();
        foreach ($tax->getRates() as $rate) {
            $rate->setTax(null);
            \XLite\Core\Database::getEM()->remove($rate);
        }
        $tax->getRates()->clear();

        $tax = \XLite\Core\Database::getRepo('XLite\Module\CDev\SalesTax\Model\Tax')->getTax();
        foreach ($tax->getRates() as $rate) {
            \XLite\Core\Database::getEM()->remove($rate);
        }
        $tax->getRates()->clear();

        $rate = new \XLite\Module\CDev\SalesTax\Model\Tax\Rate;
        $rate->setValue(10);
        $rate->setPosition(1);
        \XLite\Core\Database::getEM()->persist($rate);
        $tax->addRates($rate);
        $rate->setTax($tax);
        \XLite\Core\Database::getEM()->flush();

        $order = $this->getTestOrder();

        $order->calculate();
        $cost = $order->getCurrency()->formatValue($order->getSurchargeSumByType('tax'));
        $etalon = $order->getCurrency()->formatValue($order->getSubtotal() * 0.1);
        $this->assertEquals($etalon, $cost, 'check tax cost 10%');

        // 10%
        $rate = new \XLite\Module\CDev\SalesTax\Model\Tax\Rate;
        $rate->setValue(20);
        $rate->setPosition(2);
        \XLite\Core\Database::getEM()->persist($rate);
        $tax->addRates($rate);
        $rate->setTax($tax);
        \XLite\Core\Database::getEM()->flush();

        $order->calculate();
        $cost = $order->getCurrency()->formatValue($order->getSurchargeSumByType('tax'));
        $etalon = $order->getCurrency()->formatValue($order->getSubtotal() * 0.1);
        $this->assertEquals($etalon, $cost, 'check tax cost 10% #2');

        // 20%
        $rate->setPosition(0);
        \XLite\Core\Database::getEM()->flush();

        $order->calculate();
        $cost = $order->getCurrency()->formatValue($order->getSurchargeSumByType('tax'));
        $etalon = $order->getCurrency()->formatValue($order->getSubtotal() * 0.2);
        $this->assertEquals($etalon, $cost, 'check tax cost 20%');

        // Limit by membership (order)
        $memberships = \XLite\Core\Database::getRepo('XLite\Model\Membership')->findAll();
        $membership = array_shift($memberships);
        $membership2 = array_shift($memberships);
        $order->getProfile()->setMembership($membership);
        $rate->setMembership($membership);
        \XLite\Core\Database::getEM()->flush();

        $order->calculate();
        $cost = $order->getCurrency()->formatValue($order->getSurchargeSumByType('tax'));
        $etalon = $order->getCurrency()->formatValue($order->getSubtotal() * 0.2);
        $this->assertEquals($etalon, $cost, 'check tax cost 20% (membership)');

        // Limit by membership (order and rate)
        $rate->setMembership($membership2);
        \XLite\Core\Database::getEM()->flush();

        $order->calculate();
        $cost = $order->getCurrency()->formatValue($order->getSurchargeSumByType('tax'));
        $etalon = $order->getCurrency()->formatValue($order->getSubtotal() * 0.1);
        $this->assertEquals($etalon, $cost, 'check tax cost 10% (membership)');

        // Limit by product class (rate)
        $rate->setMembership(null);
        foreach (\XLite\Core\Database::getRepo('XLite\Model\ProductClass')->findAll() as $pc) {
            \XLite\Core\Database::getEM()->remove($pc);
        }
        $pc = new \XLite\Model\ProductClass;
        $pc->setName('Test PC');
        \XLite\Core\Database::getEM()->persist($pc);
        $rate->setProductClass($pc);
        \XLite\Core\Database::getEM()->flush();

        $order->calculate();
        $cost = $order->getCurrency()->formatValue($order->getSurchargeSumByType('tax'));
        $etalon = $order->getCurrency()->formatValue($order->getSubtotal() * 0.1);
        $this->assertEquals($etalon, $cost, 'check tax cost 10% (pc)');

        // Limit by product class (item and rate)
        $order->getItems()->get(0)->getProduct()->addClasses($pc);
        \XLite\Core\Database::getEM()->flush();

        $order->calculate();
        $cost = $order->getCurrency()->formatValue($order->getSurchargeSumByType('tax'));
        $etalon = $order->getCurrency()->formatValue($order->getSubtotal() * 0.2);
        $this->assertEquals($etalon, $cost, 'check tax cost 20% (pc)');

        // Limit by product class (only item)
        $rate->setProductClass(null);
        \XLite\Core\Database::getEM()->flush();

        $order->calculate();
        $cost = $order->getCurrency()->formatValue($order->getSurchargeSumByType('tax'));
        $etalon = $order->getCurrency()->formatValue($order->getSubtotal() * 0.2);
        $this->assertEquals($etalon, $cost, 'check tax cost 20% (pc)');

        // Remove test product class
        $order->getItems()->get(0)->getProduct()->getClasses()->removeElement($pc);
        \XLite\Core\Database::getEM()->remove($pc);
        \XLite\Core\Database::getEM()->flush();

        // Zone (order)
        foreach (\XLite\Core\Database::getRepo('XLite\Model\Zone')->findAll() as $z) {
            \XLite\Core\Database::getEM()->remove($z);
        }
        $z = new \XLite\Model\Zone;
        $z->setZoneName('Test zone');
        $e = new \XLite\Model\ZoneElement;
        $e->setElementValue('AF');
        $e->setElementType('C');
        $z->addZoneElements($e);
        \XLite\Core\Database::getEM()->persist($z);

        $rate->setZone($z);
        \XLite\Core\Database::getEM()->flush();

        $order->calculate();
        $cost = $order->getCurrency()->formatValue($order->getSurchargeSumByType('tax'));
        $etalon = $order->getCurrency()->formatValue($order->getSubtotal() * 0.1);
        $this->assertEquals($etalon, $cost, 'check tax cost 10% (zone)');

        // Zone (order and rate)
        $country = \XLite\Core\Database::getRepo('XLite\Model\Country')->findOneBy(array('code' => 'AF'));
        $order->getProfile()->getBillingAddress()->setCountry($country);
        \XLite\Core\Database::getEM()->flush();

        $order->calculate();
        $cost = $order->getCurrency()->formatValue($order->getSurchargeSumByType('tax'));
        $etalon = $order->getCurrency()->formatValue($order->getSubtotal() * 0.2);
        $this->assertEquals($etalon, $cost, 'check tax cost 20% (zone)');
    }


    protected function getTestOrder($new_order = false)
    {
        $order = parent::getTestOrder($new_order);

        $method = \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')
            ->findOneBy(array('service_name' => 'PurchaseOrder'));
        $order->setPaymentMethod($method);

        \XLite\Core\Database::getEM()->flush();

        return $order;
    }

}

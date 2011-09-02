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

class XLite_Tests_Module_CDev_VAT_Model_Order extends XLite_Tests_Model_OrderAbstract
{
    /**
     * Return data needed to start application.
     * Derived class can redefine this method.
     * It's possible to detect current test using the $this->name variable
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getRequest()
    {
        $request = parent::getRequest();

        $request['controller'] = false;

        return $request;
    }

    public function testCalculation()
    {
        $tax = \XLite\Core\Database::getRepo('XLite\Module\CDev\SalesTax\Model\Tax')->find(1);
        foreach ($tax->getRates() as $rate) {
            \XLite\Core\Database::getEM()->remove($rate);
        }
        $tax->getRates()->clear();

        $tax = \XLite\Core\Database::getRepo('XLite\Module\CDev\VAT\Model\Tax')->find(1);
        foreach ($tax->getRates() as $rate) {
            \XLite\Core\Database::getEM()->remove($rate);
        }
        $tax->getRates()->clear();

        $tax->setEnabled(true);

        $rate = new \XLite\Module\CDev\VAT\Model\Tax\Rate;
        $rate->setValue(10);
        $rate->setPosition(1);
        \XLite\Core\Database::getEM()->persist($rate);
        $tax->addRates($rate);
        $rate->setTax($tax);
        \XLite\Core\Database::getEM()->flush();

        $order = $this->getTestOrder();

        $order->calculate();
        $cost = $this->getVATByOrder($order);
        $etalon = $this->getVATEtalonByOrder($order, 0.1);
        $this->assertEquals($etalon, $cost, 'check tax cost 10%');

        /// 10%
        $rate = new \XLite\Module\CDev\VAT\Model\Tax\Rate;
        $rate->setValue(20);
        $rate->setPosition(2);
        \XLite\Core\Database::getEM()->persist($rate);
        $tax->addRates($rate);
        $rate->setTax($tax);
        \XLite\Core\Database::getEM()->flush();

        $order->calculate();
        $cost = $this->getVATByOrder($order);
        $etalon = $this->getVATEtalonByOrder($order, 0.1);
        $this->assertEquals($etalon, $cost, 'check tax cost 10% #2');

        // 20%
        $rate->setPosition(0);
        \XLite\Core\Database::getEM()->flush();

        $order->calculate();
        $cost = $this->getVATByOrder($order);
        $etalon = $this->getVATEtalonByOrder($order, 0.2);
        $this->assertEquals($etalon, $cost, 'check tax cost 20%');

        // Limit by membership (order)
        $memberships = \XLite\Core\Database::getRepo('XLite\Model\Membership')->findAll();
        $membership = array_shift($memberships);
        $membership2 = array_shift($memberships);
        $order->getProfile()->setMembership($membership);
        $rate->setMembership($membership);
        \XLite\Core\Database::getEM()->flush();

        $order->calculate();
        $cost = $this->getVATByOrder($order);
        $etalon = $this->getVATEtalonByOrder($order, 0.2);
        $this->assertEquals($etalon, $cost, 'check tax cost 20% (membership)');

        // Limit by membership (order and rate)
        $rate->setMembership($membership2);
        \XLite\Core\Database::getEM()->flush();

        $order->calculate();
        $cost = $this->getVATByOrder($order);
        $etalon = $this->getVATEtalonByOrder($order, 0.1);
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
        $cost = $this->getVATByOrder($order);
        $etalon = $this->getVATEtalonByOrder($order, 0.1);
        $this->assertEquals($etalon, $cost, 'check tax cost 10% (pc)');

        // Limit by product class (item and rate)
        $order->getItems()->get(0)->getProduct()->addClasses($pc);
        \XLite\Core\Database::getEM()->flush();

        $order->calculate();
        $cost = $this->getVATByOrder($order);
        $etalon = $this->getVATEtalonByOrder($order, 0.2);
        $this->assertEquals($etalon, $cost, 'check tax cost 20% (pc)');

        // Remove test product class
        $order->getItems()->get(0)->getProduct()->getClasses()->removeElement($pc);
        $rate->setProductClass(null);
        \XLite\Core\Database::getEM()->remove($pc);
        \XLite\Core\Database::getEM()->flush();
 
        // Zone (order)
        $z = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findOneBy(array('is_default' => 0));
        foreach ($z->getZoneElements() as $e) {
            \XLite\Core\Database::getEM()->remove($e);
        }
        $z->getZoneElements()->clear();
        $e = new \XLite\Model\ZoneElement;
        $e->setElementValue('AF');
        $e->setElementType(\XLite\Model\ZoneElement::ZONE_ELEMENT_COUNTRY);
        $z->addZoneElements($e);
        $e->setZone($z);
        $rate->setZone($z);
        \XLite\Core\Database::getEM()->flush();

        \XLite\Core\Database::getRepo('XLite\Model\Zone')->deleteCache('all');

        $order->calculate();
        $cost = $this->getVATByOrder($order);
        $etalon = $this->getVATEtalonByOrder($order, 0.1);
        $this->assertEquals($etalon, $cost, 'check tax cost 10% (zone)');

        // Zone (order and rate)
        $country = \XLite\Core\Database::getRepo('XLite\Model\Country')->findOneBy(array('code' => 'AF'));
        $order->getProfile()->getBillingAddress()->setCountry($country);
        $order->getProfile()->getShippingAddress()->setCountry($country);
        \XLite\Core\Database::getEM()->flush();

        $order->calculate();
        $cost = $this->getVATByOrder($order);
        $etalon = $this->getVATEtalonByOrder($order, 0.2);
        $this->assertEquals($etalon, $cost, 'check tax cost 20% (zone)');

        // Check shipping cost
        $memberships = \XLite\Core\Database::getRepo('XLite\Model\Membership')->findAll();
        $membership = array_shift($memberships);
        $rate->setZone(null);
        $rate->setProductClass(null);
        $rate->setMembership($membership);
        $tax->setVATmembership($membership);
        $order->getProfile()->setMembership(null);
        \XLite\Core\Database::getEM()->flush();

        $order->calculate();

        $currency = $order->getCurrency();

        // 10 - (10 - 10 / (1 + 0.2)) = 8.33333333 = 8.33 
        $this->assertEquals(8.33, $order->getItems()->get(0)->getNetPrice(), 'check item net price');
        // Initial price
        $this->assertEquals(10, $order->getItems()->get(0)->getPrice(), 'check item price');
        // 8.33 * 99 = 824.67 
        $this->assertEquals(824.67, $order->getItems()->get(0)->getSubtotal(), 'check item subtotal');

        // Surcharge - only one - VAT
        $this->assertEquals(1, $order->getItems()->get(0)->getSurcharges()->count(), 'check item surcharges count');
        // Surcharge code - CDEV.VAT. + rate id
        $this->assertEquals('CDEV.VAT.1', $order->getItems()->get(0)->getSurcharges()->get(0)->getCode(), 'check item surcharge code');

        // 824.67 * 0.1 = 82.46700 = 82.47
        $this->assertEquals(
            82.47,
            $currency->formatValue($order->getItems()->get(0)->getSurcharges()->get(0)->getValue()),
            'check item surcharge value'
        );

        // 824.67 + 82.47 = 907.14 
        $this->assertEquals(907.14, $currency->formatValue($order->getItems()->get(0)->getTotal()), 'check item total');

        // Surcharges - 2 - shipping + shipping VAT
        $this->assertEquals(2, $order->getSurcharges()->count(), 'check order surcharges count');

        // Shipping code - SHIPPING
        $this->assertEquals('SHIPPING', $order->getSurcharges()->get(0)->getCode(), 'check order shipping surcharge');
        // (10 - (10 - 10 / (1 + 0.2))) * 1.1 = 9.16666667 = 9.17
        $this->assertEquals(9.17, $order->getSurcharges()->get(0)->getValue(), 'check order shipping cost');

        // Shipping VAT code equal CDEV.VAT. + rate id + .SHIPPING
        $this->assertEquals('CDEV.VAT.1.SHIPPING', $order->getSurcharges()->get(1)->getCode(), 'check order shipping VAT surcharge');
        // (10 - (10 - 10 / (1 + 0.2))) * 0.1 = 0.833333333 = 0.83
        $this->assertEquals(0.83, $currency->formatValue($order->getSurcharges()->get(1)->getValue()), 'check order shipping VAT value');

        // Equals item subtotal (because order has only one item)
        $this->assertEquals(907.14, $currency->formatValue($order->getSubtotal()), 'check order subtotal');

        // 907.14 + 9.17 = 916.31
        $this->assertEquals(916.31, $currency->formatValue($order->getTotal()), 'check order total');
    }

    protected function getTestOrder()
    {
        $order = parent::getTestOrder();

        $order->getItems()->get(0)->setPrice(10);
        $order->getItems()->get(0)->setNetPrice(10);
        $order->getItems()->get(0)->getProduct()->setPrice(10);
        $order->getItems()->get(0)->setAmount(99);

        $method = \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')
            ->findOneBy(array('service_name' => 'PurchaseOrder'));
        $order->setPaymentMethod($method);

        $method = null;
        foreach (\XLite\Core\Database::getRepo('XLite\Model\Shipping\Method')->findAll() as $m) {
            if ($m->getName() == 'Courier') {
                $method = $m;
                break;
            }
        }
        foreach ($method->getShippingMarkups() as $m) {
            \XLite\Core\Database::getEM()->remove($m);
        }
        $method->getShippingMarkups()->clear();

        $markup = new \XLite\Model\Shipping\Markup;
        $markup->setMarkupFlat(10);
        $zone = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findOneBy(array('is_default' => 1));
        $markup->setZone($zone);
        $method->addShippingMarkups($markup);
        $markup->setShippingMethod($method);

        \XLite\Core\Database::getEM()->flush();

        $order->setShippingId($method->getMethodId());
 
        $order->calculate();

        \XLite\Core\Database::getEM()->flush();

        return $order;
    }

    protected function getVATByOrder(\XLite\Model\Order $order)
    {
        $total = 0;

        foreach ($order->getItemsIncludeSurchargesTotals() as $surcharge) {
            if ($surcharge['surcharge']->getType() == 'tax') {
                $total += $surcharge['cost'];
            }
        }

        return $order->getCurrency()->formatValue($total);
    }

    protected function getVATEtalonByOrder(\XLite\Model\Order $order, $percent)
    {
        $subtotal = $order->getSubtotal();

        return $order->getCurrency()->formatValue($subtotal - $subtotal / ( 1 + $percent));
    }

}

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

        $tax->setEnabled(true);

        $rate = new \XLite\Module\CDev\SalesTax\Model\Tax\Rate;
        $rate->setValue(10);
        \XLite\Core\Database::getEM()->persist($rate);
        $tax->addRates($rate);
        $rate->setTax($tax);

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
        $this->assertEquals(
            round(10 / (1 + 0.1), 4),
            $order->getItems()->get(0)->getNetPrice(),
            'check order item net price'
        );

        $cost = $this->getVATByOrder($order);
        $etalon = $this->getVATEtalonByOrder($order, 0.1);
        $this->assertEquals($etalon, $cost, 'check tax cost 10%');
        $base = $order->getItems()->get(0)->getPrice();
        

        /// 10%
        $rate = new \XLite\Module\CDev\VAT\Model\Tax\Rate;
        $rate->setValue(20);
        $rate->setPosition(2);
        \XLite\Core\Database::getEM()->persist($rate);
        $tax->addRates($rate);
        $rate->setTax($tax);
        $r10 = $rate;
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
        $m = $order->getModifier(\XLite\Model\Base\Surcharge::TYPE_SHIPPING, 'SHIPPING')->getSelectedRate()->getMethod();;
        $m->addClasses($pc);
        \XLite\Core\Database::getEM()->flush();

        $order->calculate();
        $cost = $this->getVATByOrder($order);
        $etalon = $this->getVATEtalonByOrder($order, 0.2);
        $this->assertEquals($etalon, $cost, 'check tax cost 20% (pc)');

        // Remove test product class
        $order->getItems()->get(0)->getProduct()->getClasses()->removeElement($pc);
        $m->getClasses()->removeElement($pc);
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

        // 10 - (10 - 10 / (1 + 0.2)) = 8.33333333 = 8.3333
        $this->assertEquals(8.3333, $order->getItems()->get(0)->getNetPrice(), 'check item net price');
        // Initial price
        $this->assertEquals(10, $order->getItems()->get(0)->getPrice(), 'check item price');
        // 8.33 * 99 = 824.67 
        $this->assertEquals(824.67, $order->getItems()->get(0)->getSubtotal(), 'check item subtotal');

        // Surcharge - only one - VAT
        $this->assertEquals(0, $order->getItems()->get(0)->getSurcharges()->count(), 'check item surcharges count');

        // 824.67
        $this->assertEquals(824.67, $currency->formatValue($order->getItems()->get(0)->getTotal()), 'check item total');

        // Surcharges - shipping + VAT + sales tax
        $this->assertEquals(3, $order->getSurcharges()->count(), 'check order surcharges count');

        $i = 0;

        // Shipping code - SHIPPING
        $this->assertEquals('SHIPPING', $order->getSurcharges()->get($i)->getCode(), 'check order shipping surcharge');
        // (10 - (10 - 10 / (1 + 0.2))) = 9.333333333 = 8.33
        $this->assertEquals(8.33, $order->getSurcharges()->get($i)->getValue(), 'check order shipping cost');

        $i++;

        // VAT code equal CDEV.VAT. + rate id
        $this->assertEquals('CDEV.VAT.1', $order->getSurcharges()->get($i)->getCode(), 'check order shipping VAT surcharge');
        // (824.67 + 8.33) * 0.1 = 83.3
        $this->assertEquals(83.3, $currency->formatValue($order->getSurcharges()->get($i)->getValue()), 'check order shipping VAT value');

        $i++;

        // Sales tax code - CDEV.STAX.1
        $this->assertEquals('CDEV.STAX.1', $order->getSurcharges()->get($i)->getCode(), 'check order sales tax surcharge');
        // 824.67 * 0.1 = 82.467 = 82.47
        $this->assertEquals(82.47, $currency->formatValue($order->getSurcharges()->get($i)->getValue()), 'check order sales tax cost');

        // Equals item subtotal (because order has only one item)
        $this->assertEquals(824.67, $currency->formatValue($order->getSubtotal()), 'check order subtotal');

        // 824.67 + 8.33 + 83.3 + 82.47 = 998.77
        $this->assertEquals(998.77, $currency->formatValue($order->getTotal()), 'check order total');

        // Test small values
        $order->getItems()->get(0)->getProduct()->setPrice(2.49);
        $order->getItems()->get(0)->setPrice(2.49);
        $order->getItems()->get(0)->setAmount(1);

        $tax->getRates()->removeElement($rate);
        $rate->setTax(null);
        \XLite\Core\Database::getEM()->remove($rate);
        $tax->getRates()->get(0)->setValue(35);
        $tax->setVATMembership(null);
        $tax->getRates()->get(0)->setMembership(null);

        \XLite\Core\Database::getEM()->flush();

        $order->calculate();

        // Initial price
        $this->assertEquals(2.49, $order->getItems()->get(0)->getPrice(), 'check item price #2');
        // 2.49 - (2.49 - 2.49 / (1 + 0.35)) = 1.8444 = 1.8444
        $this->assertEquals(1.8444, $order->getItems()->get(0)->getNetPrice(), 'check item net price #2');
        // 1.84 * 1 = 1.84
        $this->assertEquals(1.84, $order->getItems()->get(0)->getSubtotal(), 'check item subtotal #2');

        // Shipping cost = 10 / 1.35 = 7.40740741 = 7.41
        // (1.84 + 7.41) * 0.35 + 1.84 * 0.1 + (1.84 + 7.41) = 12.6715
        $this->assertEquals(12.67, $currency->formatValue($order->getTotal()), 'check item total #2');

        // Test some quantity
        $order->getItems()->get(0)->getProduct()->setPrice(39.01);
        $order->getItems()->get(0)->setPrice(39.01);
        $order->getItems()->get(0)->setAmount(10);

        $tax->getRates()->get(0)->setValue(50);

        \XLite\Core\Database::getEM()->flush();

        $order->calculate();

        // 39.01 - (39.01 - 39.01 / (1 + 0.5)) = 26.0067 = 26.01
        $this->assertEquals(26.0067, $order->getItems()->get(0)->getNetPrice(), 'check item net price #3');
        // 26.01 * 10 = 260.1
        $this->assertEquals(260.1, $order->getItems()->get(0)->getSubtotal(), 'check item subtotal #3');

        // Shipping cost = 10 / 1.5 = 6.67
        $this->assertEquals(6.67, $order->getSurcharges()->get(0)->getValue(), 'check shipping cost');
        // VAT = (260.1 + 6.67) * 0.5 = 133.38500 = 133.39
        $this->assertEquals(133.385, $order->getSurcharges()->get(1)->getValue(), 'check VAT');
        // Sales tax = 260.1 * 0.1 = 26.01
        $this->assertEquals(26.01, $order->getSurcharges()->get(2)->getValue(), 'check Sales tax');
        // (260.1 + 6.67) + 133.39 + 260.1 * 0.1 = 426.165
        $this->assertEquals(426.165, round($order->getTotal(), 3), 'check order total #3');
    }

    protected function getTestOrder()
    {
        $order = parent::getTestOrder();

        $order->getItems()->get(0)->getProduct()->setPrice(10);
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

        foreach ($order->getExcludeSurcharges() as $surcharge) {
            if (preg_match('/^CDEV\.VAT\./', $surcharge->getCode())) {
                $total += $surcharge->getValue();
            }
        }

        return $order->getCurrency()->roundValue($total);
    }

    protected function getVATEtalonByOrder(\XLite\Model\Order $order, $percent)
    {
        $subtotal = $order->getSubtotal() + $order->getSurchargeSumByType('shipping');

        return $order->getCurrency()->roundValue($subtotal * $percent);
    }

}

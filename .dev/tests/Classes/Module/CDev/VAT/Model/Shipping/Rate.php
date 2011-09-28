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
 * @since     1.0.8
 */

class XLite_Tests_Module_CDev_VAT_Model_Shipping_Rate extends XLite_Tests_TestCase
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

    public function testGetTotalRate()
    {
        $tax = \XLite\Core\Database::getRepo('XLite\Module\CDev\VAT\Model\Tax')->getTax();
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

        $rate = new \XLite\Module\CDev\VAT\Model\Tax\Rate;
        $rate->setValue(20);
        $rate->setPosition(0);
        \XLite\Core\Database::getEM()->persist($rate);
        $tax->addRates($rate);
        $rate->setTax($tax);

        $memberships = \XLite\Core\Database::getRepo('XLite\Model\Membership')->findAll();
        $membership = array_shift($memberships);
        $rate->setMembership($membership);
        $tax->setVATMembership($membership);

        \XLite\Core\Database::getEM()->flush();

        $method = new \XLite\Model\Shipping\Method;
        $method->setEnabled(true);

        $rate = new \XLite\Model\Shipping\Rate;
        $rate->setBaseRate(10);
        $rate->setMarkupRate(10);
        $rate->setMethod($method);

        $this->assertEquals(16.67, \XLite::getInstance()->getCurrency()->formatValue($rate->getTotalRate()), 'check cost');
    }

    protected function getMethodByName($name)
    {
        $method = null;

        foreach (\XLite\Core\Database::getRepo('XLite\Model\Shipping\Method')->findAll() as $m) {
            if ($m->getName() == $name) {
                $method = $m;
                break;
            }
        }

        return $method;
    }

}

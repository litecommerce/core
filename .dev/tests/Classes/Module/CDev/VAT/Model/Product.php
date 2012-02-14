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

class XLite_Tests_Module_CDev_VAT_Model_Product extends XLite_Tests_TestCase
{
    public  static function setUpBeforeClass(){
        xlite_restore_sql_from_backup();
    }
    public function testGetListPrice()
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
        \XLite\Core\Database::getEM()->flush();

        $products = \XLite\Core\Database::getRepo('XLite\Model\Product')->findAll();
        $product = array_shift($products);

        \XLite\Module\CDev\VAT\Logic\Product\Tax::resetInstance();
        $price = $product->getPrice();
        $this->assertEquals(
            $this->getVAT($price, 0.1, 0.1),
            \XLite::getInstance()->getCurrency()->roundValue($product->getListPrice()),
            'check tax cost 10%'
        );

        // 10%
        $rate = new \XLite\Module\CDev\VAT\Model\Tax\Rate;
        $rate->setValue(20);
        $rate->setPosition(2);
        \XLite\Core\Database::getEM()->persist($rate);
        $tax->addRates($rate);
        $rate->setTax($tax);
        \XLite\Core\Database::getEM()->flush();

        \XLite\Module\CDev\VAT\Logic\Product\Tax::resetInstance();
        $this->assertEquals(
            $this->getVAT($price, 0.1, 0.1),
            \XLite::getInstance()->getCurrency()->roundValue($product->getListPrice()),
            'check tax cost 10% #2'
        );

        // 20%
        $rate->setPosition(0);
        $memberships = \XLite\Core\Database::getRepo('XLite\Model\Membership')->findAll();
        $membership = array_shift($memberships);
        $rate->setMembership($membership);
        $tax->setVATMembership($membership);
        \XLite\Core\Database::getEM()->flush();

        \XLite\Module\CDev\VAT\Logic\Product\Tax::resetInstance();
        $this->assertEquals(
            $this->getVAT($price, 0.2, 0.1),
            \XLite::getInstance()->getCurrency()->roundValue($product->getListPrice()),
            'check tax cost 20%'
        );

        // Disabled tax
        $tax->setEnabled(false);
        \XLite\Core\Database::getEM()->flush();
        \XLite\Module\CDev\VAT\Logic\Product\Tax::resetInstance();
        $this->assertEquals($price, $product->getListPrice(), 'check no-tax cost');
    }

    protected function getVAT($value, $percent, $tax)
    {
        $value -= ($value - $value / ( 1 + $percent));

        return \XLite::getInstance()->getCurrency()->roundValue($value);
    }

    protected function getTax($value, $percent, $tax)
    {
        $value -= ($value - $value / ( 1 + $percent));

        return \XLite::getInstance()->getCurrency()->roundValue($value * $tax);
    }

    protected function processTaxes(array $taxes)
    {
        foreach ($taxes as $k => $v) {
            $taxes[$k] = \XLite::getInstance()->getCurrency()->roundValue($v);
        }

        return $taxes;
    }
}

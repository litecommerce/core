<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * XLite\Model\Repo\Product class tests
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

require_once __DIR__ . '/../../../../../Model/AProduct.php';

class XLite_Tests_Module_CDev_VAT_Model_Repo_Product extends XLite_Tests_Model_AProduct
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

    public function testSearchByPrice()
    {
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

        $rate = new \XLite\Module\CDev\VAT\Model\Tax\Rate;
        $rate->setValue(20);
        $rate->setPosition(0);
        $memberships = \XLite\Core\Database::getRepo('XLite\Model\Membership')->findAll();
        $membership = array_shift($memberships);
        $rate->setMembership($membership);
        $tax->setVATMembership($membership);
        \XLite\Core\Database::getEM()->persist($rate);
        $tax->addRates($rate);
        $rate->setTax($tax);
        \XLite\Core\Database::getEM()->flush();

        $cnd = new \XLite\Core\CommonCell(
            array(
                \XLite\Model\Repo\Product::P_PRICE => array(1, 2),
            )
        );
        $result = \XLite\Core\Database::getRepo('\XLite\Model\Product')->search($cnd);
        $this->assertEquals(2, count($result), 'check count');

        $result[0]->setPrice(1.01);
        \XLite\Core\Database::getEM()->flush();

        $result = \XLite\Core\Database::getRepo('\XLite\Model\Product')->search($cnd);
        $this->assertEquals(1, count($result), 'check count #2');

        $rate->setPosition(2);
        \XLite\Core\Database::getEM()->flush();

        $result = \XLite\Core\Database::getRepo('\XLite\Model\Product')->search($cnd);
        $this->assertEquals(3, count($result), 'check count #3');

        $result[0]->setPrice(1);
        \XLite\Core\Database::getEM()->flush();

        $result = \XLite\Core\Database::getRepo('\XLite\Model\Product')->search($cnd);
        $this->assertEquals(2, count($result), 'check count #4');
    }
}



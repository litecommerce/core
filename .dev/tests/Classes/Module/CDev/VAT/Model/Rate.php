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

require_once __DIR__ . '/ATax.php';

class XLite_Tests_Module_CDev_VAT_Model_Rate extends XLite_Tests_Module_CDev_VAT_Model_ATax
{
    /**
     * Data for tests
     */
    protected $tests = array(

        0 => array(
            'zones' => array('Tax-zone-1'),
            'rates' => array(
                30 => true,
                60 => true
            ),
        ),

        1 => array(
            'zones' => array('Tax-zone-2'),
            'rates' => array(
                30 => true
            ),
        ),

        2 => array(
            'membership' => 'M1',
            'rates' => array(
                30 => true
            ),
        ),

        3 => array(
            'membership' => 'M1',
            'zones' => array('Tax-zone-1'),
            'classes' => array('Tax-class-1'),
            'rates' => array(
                10 => true,
                30 => true,
                60 => true
            ),
        ),

        4 => array(
            'membership' => 'M2',
            'zones' => array('Tax-zone-1'),
            'classes' => array('Tax-class-1'),
            'rates' => array(
                30 => true,
                40 => true,
                60 => true
            ),
        ),
    );


    /**
     * testGetFilteredRates 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.24
     */
    public function testIsApplied()
    {
        $tax = $this->getTax();

        $ratesRes = array();
        for ($i = 10; $i <= 60; $i+=10) {
            $ratesRes[$i] = false;
        }

        foreach ($this->tests as $id => $test) {
            $zones = $this->getZones(@$test['zones']);
            $membership = $this->getMembership(@$test['membership']);
            $classes = $this->getClasses(@$test['classes']);

            $rates = $test['rates'] + $ratesRes;

            foreach ($rates as $ratePosition => $rateIsApplied) {
                $rate = $this->getTaxRate($tax, $ratePosition);
                $this->assertEquals(
                    $rateIsApplied,
                    $rate->isApplied($zones, $membership, $classes),
                    sprintf('Wrong isApplied() value for rate #%d (test #%d)', $ratePosition, $id)
                );
            }
        }
    }

    /**
     * testCalculateProductPriceExcludingTax 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.24
     */
    public function testCalculateProductPriceExcludingTax()
    {
        $product = $this->getProduct();

        $tax = $this->getTax();

        $rate = $this->getTaxRate($tax, 10);

        $price = 100;
        $this->assertEquals(9.09, round($rate->calculateProductPriceExcludingTax($product, $price), 2), 'Wrong price calculated (rate pos=10, price=100)');

        $price = 0;
        $this->assertEquals(0, round($rate->calculateProductPriceExcludingTax($product, $price), 2), 'Wrong price calculated (rate pos=10, price=0)');

        $rate = $this->getTaxRate($tax, 40);

        $price = 100;
        $this->assertEquals(11, round($rate->calculateProductPriceExcludingTax($product, $price), 2), 'Wrong price calculated (rate pos=40, price=100)');

        $price = 0;
        $this->assertEquals(0, round($rate->calculateProductPriceExcludingTax($product, $price), 2), 'Wrong price calculated (rate pos=30, price=0)');
   }

    /**
     * testCalculateProductPriceIncludingTax 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.24
     */
    public function testCalculateProductPriceIncludingTax()
    {
        $product = $this->getProduct();

        $tax = $this->getTax();

        $rate = $this->getTaxRate($tax, 10);

        $price = 100;
        $this->assertEquals(10, round($rate->calculateProductPriceIncludingTax($product, $price), 2), 'Wrong price calculated (rate pos=10, price=100)');

        $price = 0;
        $this->assertEquals(0, round($rate->calculateProductPriceIncludingTax($product, $price), 2), 'Wrong price calculated (rate pos=10, price=0)');

        $rate = $this->getTaxRate($tax, 30);

        $price = 100;
        $this->assertEquals(30, round($rate->calculateProductPriceIncludingTax($product, $price), 2), 'Wrong price calculated (rate pos=30, price=100)');

        $price = 0;
        $this->assertEquals(0, round($rate->calculateProductPriceIncludingTax($product, $price), 2), 'Wrong price calculated (rate pos=30, price=0)');
    }

    /**
     * testGetExcludeTaxFormula 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.24
     */
    public function testGetExcludeTaxFormula()
    {
        $tax = $this->getTax();

        $rate = $this->getTaxRate($tax, 10);

        $this->assertEquals(
            'p - p / 1.1',
            $rate->getExcludeTaxFormula('p'),
            'Wrong price field formula generated (percent)'
        );

        $rate = $this->getTaxRate($tax, 40);

        $this->assertEquals(
            '11.0000',
            $rate->getExcludeTaxFormula('p'),
            'Wrong price field formula generated (absolute)'
        );


    }


    /**
     * Return Rate object by its position property
     * 
     * @param \XLite\Module\CDev\VAT\Model\Tax $taxObj       Tax object
     * @param integer                          $ratePosition Rate position
     *  
     * @return \XLite\Module\CDev\VAT\Model\Tax\Rate
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function getTaxRate($taxObj, $ratePosition)
    {
        $result = null;

        $rates = $taxObj->getRates();

        foreach ($rates as $rate) {
            if ($rate->getPosition() == $ratePosition) {
                $result = $rate;
                break;
            }
        }

        $this->assertNotNull($result, sprintf('Tax rate with position=%d not found', $ratePosition));

        return $result;
    }

    /**
     * getProduct 
     * 
     * @return \XLite\Model\Product
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function getProduct()
    {
        $product = \XLite\Core\Database::getRepo('XLite\Model\Product')->find(22); // Find Binary Mom

        $this->assertNotNull($product, 'Product #22 (Binary Mom) not found');

        return $product;
    }
}

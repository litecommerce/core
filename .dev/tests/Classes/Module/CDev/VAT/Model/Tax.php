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

class XLite_Tests_Module_CDev_VAT_Model_Tax extends XLite_Tests_Module_CDev_VAT_Model_ATax
{
    /**
     * Data for tests
     */
    protected $tests = array(

        0 => array(
            'zones' => array('Tax-zone-1'),
            'rates' => array(30, 60),
        ),

        1 => array(
            'zones' => array('Tax-zone-2'),
            'rates' => array(30),
        ),

        2 => array(
            'membership' => 'M1',
            'rates' => array(30),
        ),

        3 => array(
            'membership' => 'M1',
            'zones' => array('Tax-zone-1'),
            'classes' => array('Tax-class-1'),
            'rates' => array(10, 30, 60),
        ),

        4 => array(
            'membership' => 'M2',
            'zones' => array('Tax-zone-1'),
            'classes' => array('Tax-class-1'),
            'rates' => array(30, 40, 60),
        ),
    );


    /**
     * testGetFilteredRates 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.24
     */
    public function testGetFilteredRates()
    {
        $this->checkTests();
    }

    /**
     * testGetFilteredRate 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.24
     */
    public function testGetFilteredRate()
    {
        $this->checkTests(true);
    }


    /**
     * checkTests 
     * 
     * @param boolean $single Flag: if true - test getFilteredRate(), else - getFilteredRates()
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function checkTests($single = false)
    {
        $tax = $this->getTax();

        foreach ($this->tests as $id => $test) {
            $zones = $this->getZones(@$test['zones']);
            $membership = $this->getMembership(@$test['membership']);
            $classes = $this->getClasses(@$test['classes']);
            
            if ($single) {
                $rate = $tax->getFilteredRate($zones, $membership, $classes);
                $this->assertEquals($test['rates'][0], $rate->getPosition(), 'Rate doesn\'t match to expected result (#' . $id . ')');

            } else {
                $rates = $tax->getFilteredRates($zones, $membership, $classes);
                $this->assertEquals($test['rates'], array_keys($rates), 'Rates doesn\'t match to expected result (#' . $id . ')');
            }
        }
    }
}

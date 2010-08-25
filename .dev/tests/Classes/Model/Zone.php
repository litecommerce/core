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
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

class XLite_Tests_Model_Zone extends XLite_Tests_TestCase
{
	/**
	 * testConstruct 
	 * 
	 * @return void
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	public function testGetZoneWeight()
	{
        $data = array();
        $data[] = array(
            'zoneid' => 2,
            'address'  => array(
                'country' => 'US',
                'state'   => 'NY',
                'city'    => 'New York',
                'zipcode' => '10134',
                'address' => 'Some address',
            ),
            'weight' => 0x01 + 0x02 + 0x08 + 0x10,
        );

        $data[] = array(
            'zoneid' => 2,
            'address'  => array(
                'country' => 'US',
                'state'   => 'NY',
                'city'    => 'New Worker',
                'zipcode' => '10134',
                'address' => 'Some address',
            ),
            'weight' => 0x01 + 0x02 + 0x08,
        );

        $data[] = array(
            'zoneid' => 2,
            'address'  => array(
                'country' => 'US',
                'state'   => 'CA',
                'city'    => 'New York',
                'zipcode' => '10134',
                'address' => 'Some address',
            ),
            'weight' => 0,
        );

        $data[] = array(
            'zoneid' => 2,
            'address'  => array(
                'country' => 'FR',
                'state'   => 'NY',
                'city'    => 'New York',
                'zipcode' => '10134',
                'address' => 'Some address',
            ),
            'weight' => 0,
        );

        $data[] = array(
            'zoneid' => 4,
            'address'  => array(
                'country' => 'FR',
                'state'   => 'NY',
                'city'    => 'New York',
                'zipcode' => '10134',
                'address' => 'Some address',
            ),
            'weight' => 0x01,
        );

        $data[] = array(
            'zoneid' => 5,
            'address'  => array(
                'country' => 'FR',
                'state'   => 'NY',
                'city'    => 'New York',
                'zipcode' => '10134',
                'address' => 'Some address',
            ),
            'weight' => 0,
        );

        foreach ($data as $dt) {
            $zone = \XLite\Core\Database::getRepo('XLite\Model\Zone')->getZone($dt['zoneid']);
            $this->assertEquals($dt['weight'], $zone->getZoneWeight($dt['address']));
        }
    }

}

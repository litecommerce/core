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

abstract class XLite_Tests_Module_CDev_VAT_Model_ATax extends XLite_Tests_TestCase
{
    /**
     * Flag: show debug info or no
     */
    protected static $showDebugInfo = false;

    /**
     * Test memberships
     */
    protected static $memberships = array(
        0 => array(
            'name' => 'M1',
        ),
        1 => array(
            'name' => 'M2',
        ),
    );

    /**
     * Test zones
     */
    protected static $zones = array(
        1 => array(
            'zone_name' => 'Tax-zone-1',
            'zone_elements' => array(
                0 => array(
                    'element_value' => 'US',
                    'element_type' => 'C',
                ),
            ),
        ),
        2 => array(
            'zone_name' => 'Tax-zone-2',
            'zone_elements' => array(
                0 => array(
                    'element_value' => 'GB',
                    'element_type' => 'C',
                ),
            ),
        ),
    );

    /**
     * Test product classes
     */
    protected static $classes = array(
        0 => array(
            'name' => 'Tax-class-1',
        ),
        1 => array(
            'name' => 'Tax-class-2',
        ),
    );

    /**
     * Test tax rates
     */
    protected static $taxRates = array(
        0 => array(
            'value' => 10,
            'type' => 'p',
            'position' => 10,
            'zone' => 1, // Tax-zone-1
            'productClass' => 0, // Tax-class-1
            'membership' => 0, // M1
        ),
        1 => array(
            'value' => 20,
            'type' => 'p',
            'position' => 20,
            'zone' => 2, // Tax-zone-2
            'productClass' => 1, // Tax-class-2
            'membership' => 1, // M2
        ),
        2 => array(
            'value' => 30,
            'type' => 'p',
            'position' => 30,
            'zone' => null,
            'productClass' => null,
            'membership' => null,
        ),
        3 => array(
            'value' => 11,
            'type' => 'a',
            'position' => 40,
            'zone' => 1, // Tax-zone-1
            'productClass' => 0, // Tax-class-1
            'membership' => 1, // M2
        ),
        4 => array(
            'value' => 12,
            'type' => 'p',
            'position' => 50,
            'zone' => 2, // Tax-zone-2
            'productClass' => 0, // Tax-class-1
            'membership' => 0, // M1
        ),
        5 => array(
            'value' => 13,
            'type' => 'p',
            'position' => 60,
            'zone' => 1, // Tax-zone-1
            'productClass' => null,
            'membership' => null,
        ),

    );


    /**
     * setUpBeforeClass 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.24
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::prepareData();
    }


    /**
     * prepareData 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.24
     */
    public static function prepareData()
    {
        // Prepare tax
        $taxes = \XLite\Core\Database::getRepo('XLite\Module\CDev\VAT\Model\Tax')->findAll();

        if (!empty($taxes)) {
            \XLite\Core\Database::getRepo('XLite\Module\CDev\VAT\Model\Tax')->deleteInBatch($taxes);

            $taxes = \XLite\Core\Database::getRepo('XLite\Module\CDev\VAT\Model\Tax')->findAll();
        }

        $tax = \XLite\Core\Database::getRepo('XLite\Module\CDev\VAT\Model\Tax')->insert(array('enabled' => true));
        $taxes = \XLite\Core\Database::getRepo('XLite\Module\CDev\VAT\Model\Tax')->findAll();

        // Prepare memberships
        $memberships = \XLite\Core\Database::getRepo('XLite\Model\Membership')->findAll();

        if (!empty($memberships)) {
            \XLite\Core\Database::getRepo('XLite\Model\Membership')->deleteInBatch($memberships);
        }

        foreach (self::$memberships as $m) {
            $newMembership = \XLite\Core\Database::getRepo('XLite\Model\Membership')->insert($m);
        }
        $memberships = \XLite\Core\Database::getRepo('XLite\Model\Membership')->findAll();

        // Prepare zones
        $zones = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findAll();
        foreach ($zones as $k => $z) {
            if ($z->getIsDefault()) {
                unset($zones[$k]);
            }
        }
        if (!empty($zones)) {
            \XLite\Core\Database::getRepo('XLite\Model\Zone')->deleteInBatch($zones);
        }
        foreach (self::$zones as $z) {
            $newZone = \XLite\Core\Database::getRepo('XLite\Model\Zone')->insert(array('zone_name' => $z['zone_name']));
            $zel = array();
            foreach ($z['zone_elements'] as $ze) {
                $zel[] = \XLite\Core\Database::getRepo('XLite\Model\ZoneElement')->insert(array_merge($ze, array('zone' => $newZone)));
            }
            \XLite\Core\Database::getRepo('XLite\Model\Zone')->update($newZone, array('zone_elements' => $zel)); 
        }
        $zones = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findAll();

        $tax->setVATZone($zones[1]);
        $tax->setVATMembership(null);// $memberships[0]);
        $tax->setName('VAT');

        // Switch on option 'Display "inc/ex VAT" labels next to prices'
        \XLite\Core\Database::getRepo('\XLite\Model\Config')->createOption(
            array(
                'name' => 'display_inc_vat_label',
                'category' => 'CDev\\VAT',
                'value' => 'Y',
            )
        );

        // Switch on option 'Display "inc/ex VAT" labels next to prices'
        \XLite\Core\Database::getRepo('\XLite\Model\Config')->createOption(
            array(
                'name' => 'display_prices_including_vat',
                'category' => 'CDev\\VAT',
                'value' => 'Y',
            )
        );

        \XLite\Core\Config::updateInstance();


        // Prepare product classes
        $classes = \XLite\Core\Database::getRepo('XLite\Model\ProductClass')->findAll();
        if (!empty($classes)) {
            \XLite\Core\Database::getRepo('XLite\Model\ProductClass')->deleteInBatch($classes);
        }
        foreach (self::$classes as $c) {
            \XLite\Core\Database::getRepo('XLite\Model\ProductClass')->insert($c);
        }
        $classes = \XLite\Core\Database::getRepo('XLite\Model\ProductClass')->findAll();

        // Prepare tax rates
        $taxRates = array();

        foreach (self::$taxRates as $tr) {
            $trExt = array();
            if (isset($tr['zone'])) {
                $trExt['zone'] = $zones[$tr['zone']];
            }
            if (isset($tr['membership'])) {
                $trExt['membership'] = $memberships[$tr['membership']];
            }
            if (isset($tr['productClass'])) {
                $trExt['productClass'] = $classes[$tr['productClass']];
            }
            $trExt['tax'] = $taxes[0];
            $newTaxRate = \XLite\Core\Database::getRepo('XLite\Module\CDev\VAT\Model\Tax\Rate')->insert(
                array(
                    'value' => $tr['value'],
                    'type' => $tr['type'],
                    'position' => $tr['position'],
                )
            );
            \XLite\Core\Database::getRepo('XLite\Module\CDev\VAT\Model\Tax\Rate')->update($newTaxRate, $trExt);
            $taxRates[] = $newTaxRate;
        }
        \XLite\Core\Database::getRepo('XLite\Module\CDev\VAT\Model\Tax')->update($taxes[0], array('rates' => $taxRates));

        if (self::$showDebugInfo) {

            // Check the data structure
            $taxes = \XLite\Core\Database::getRepo('XLite\Module\CDev\VAT\Model\Tax')->findAll();
            var_dump(array('taxes count: ' => count($taxes)));

            $memberships = \XLite\Core\Database::getRepo('XLite\Model\Membership')->findAll();
            $ms = array();
            foreach ($memberships as $m) {
                $ms[] = array(
                    'id' => $m->getMembershipId(),
                    'name' => $m->getName(),
                );
            }
            var_dump(array('memberships' => $ms));

            $zones = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findAll();
            $zs = array();
            foreach ($zones as $z) {
                $zelements = $z->getZoneElements();
                $ze = array();
                foreach ($zelements as $zel) {
                    $ze[] = sprintf('%s: %s', $zel->getElementValue(), $zel->getElementType()); 
                }
                $zs[] = array(
                    'name' => $z->getZoneName(),
                    'elements' => implode(', ', $ze),
                );
            }
            var_dump(array('zones' => $zs));

            $classes = \XLite\Core\Database::getRepo('XLite\Model\ProductClass')->findAll();
            $cs = array();
            foreach($classes as $c) {
                $cs[] = array(
                    'name' => $c->getName(),
                );
            }
            var_dump(array('product_classes' => $cs));

            $taxes = \XLite\Core\Database::getRepo('XLite\Module\CDev\VAT\Model\Tax')->findAll();
            $ts = array();
            foreach ($taxes as $t) {
                $ts['id'] = $t->getId();
                $ts['enabled'] = $t->getEnabled();
                $taxRates = $t->getRates();
                $trs = array();
                foreach ($taxRates as $tr) {
                    $trs[] = sprintf(
                        '%f%c: membership: %s; zone: %s; class: %s',
                        $tr->getValue(),
                        $tr->getType(),
                        $tr->getMembership() ? $tr->getMembership()->getName() : 'n/a',
                        $tr->getZone() ? $tr->getZone()->getZoneName() : 'n/a',
                        $tr->getProductClass() ? $tr->getProductClass()->getName() : 'n/a'
                    );
                }
                $ts['rates'] = $trs;
            }
            var_dump($ts);
        }
    }

    /**
     * getTax 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function getTax()
    {
        $taxes = \XLite\Core\Database::getRepo('XLite\Module\CDev\VAT\Model\Tax')->findAll();

        return $taxes[0];
    }

    /**
     * Return array of Zone objects from array of zone names
     * 
     * @param array $zones Array of zone names ('Zone1', 'Zone2',...)
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function getZones($zones)
    {
        $result = array();

        if (!empty($zones)) {
            foreach ($zones as $zone) {
                $z = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findOneBy(array('zone_name' => $zone));
                $this->assertNotNull($z, 'Zone "' . $zone . '" not found');
                $result[] = $z->getZoneId();
            }
        }

        return $result;
    }

    /**
     * Return Membership object by its name
     * 
     * @param string $membership Membership name
     *  
     * @return \XLite\Model\Membership
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function getMembership($membership)
    {
        $result = null;

        if (!empty($membership)) {
            $result = \XLite\Core\Database::getRepo('XLite\Model\Membership')->findOneByName($membership);
            $this->assertNotNull($result, 'Membership "' . $membership . '" not found');
        }

        return $result;
    }

    /**
     * Return collection of ProductClass objects
     * 
     * @param array $classes Array of product classes names
     *  
     * @return \Doctrine\Common\Collections\ArrayCollection
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function getClasses($classes)
    {
        $result = null;

        if (!empty($classes)) {
            $result = new \Doctrine\Common\Collections\ArrayCollection();
            $allClasses = \XLite\Core\Database::getRepo('XLite\Model\ProductClass')->findAll();

            foreach ($classes as $class) {
                $found = false;
                foreach ($allClasses as $classObj) {
                    if ($classObj->getName() == $class) {
                        $found = true;
                        $result[] = $classObj;
                    }
                }
                $this->assertTrue($found, 'Product class "' . $class . '" not found');
            }
        }

        return $result;
    }
}

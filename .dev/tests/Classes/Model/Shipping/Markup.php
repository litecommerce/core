<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * \XLite\Model\Shipping\Markup class tests
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

class XLite_Tests_Model_Shipping_Markup extends XLite_Tests_TestCase
{
    /**
     * testCreate
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testCreate()
    {
        $newMarkup = new \XLite\Model\Shipping\Markup();

        $method = \XLite\Core\Database::getRepo('XLite\Model\Shipping\Method')->find(100);
        $newMarkup->setShippingMethod($method);
        $method->addShippingMarkups($newMarkup);

        $zone = \XLite\Core\Database::getRepo('XLite\Model\Zone')->find(1);
        $newMarkup->setZone($zone);
        $zone->addShippingMarkups($newMarkup);

        $newMarkup->setMinWeight(20);
        $newMarkup->setMaxWeight(500);
        $newMarkup->setMinTotal(10);
        $newMarkup->setMaxTotal(1000);
        $newMarkup->setMinItems(20);
        $newMarkup->setMaxItems(2000);
        $newMarkup->setMarkupFlat(15.45);
        $newMarkup->setMarkupPercent(5.45);
        $newMarkup->setMarkupPerItem(1.45);
        $newMarkup->setMarkupPerWeight(0.45);

        \XLite\Core\Database::getEM()->persist($newMarkup);
        \XLite\Core\Database::getEM()->flush();

        $markupId = $newMarkup->getMarkupId();

        $this->assertTrue(isset($markupId), 'Object could not be created');
        
        if (isset($markupId)) {
            $markup = \XLite\Core\Database::getRepo('XLite\Model\Shipping\Markup')->find($markupId);

            $this->assertEquals(100, $markup->getShippingMethod()->getMethodId(), 'Wrong method_id');
            $this->assertEquals(1, $markup->getZone()->getZoneId(), 'Wrong zone_id');
            $this->assertEquals(20, $markup->getMinWeight(), 'Wrong min_weight');
            $this->assertEquals(500, $markup->getMaxWeight(), 'Wrong max_weight');
            $this->assertEquals(10, $markup->getMinTotal(), 'Wrong min_total');
            $this->assertEquals(1000, $markup->getMaxTotal(), 'Wrong max_total');
            $this->assertEquals(20, $markup->getMinItems(), 'Wrong min_items');
            $this->assertEquals(2000, $markup->getMaxItems(), 'Wrong max_items');
            $this->assertEquals(15.45, $markup->getMarkupFlat(), 'Wrong markup_flat');
            $this->assertEquals(5.45, $markup->getMarkupPercent(), 'Wrong markup_percent');
            $this->assertEquals(1.45, $markup->getMarkupPerItem(), 'Wrong markup_per_item');
            $this->assertEquals(0.45, $markup->getMarkupPerWeight(), 'Wrong markup_per_weight');

            $this->assertTrue($markup->getShippingMethod() instanceof \XLite\Model\Shipping\Method, 'Shipping method is wrong object');
            $this->assertTrue($markup->getZone() instanceof \XLite\Model\Zone, 'Zone is wrong object');

            $this->assertEquals(0, $markup->getMarkupValue(), 'Default markup value must be zero');

            $markup->setMarkupValue(23.45);
            $this->assertEquals(23.45, $markup->getMarkupValue(), 'Markup value setup does not work');
        }
    }

}

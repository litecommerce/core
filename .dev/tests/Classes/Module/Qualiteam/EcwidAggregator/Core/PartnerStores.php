<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * \XLite\Module\Qualiteam\EcwidAggregator\Core\PartnerStores tests
 *
 * @category   LiteCommerce
 * @package    Tests
 * @subpackage Classes
 * @author     Creative Development LLC <info@cdev.ru>
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.18
 */

class XLite_Tests_Module_Qualiteam_EcwidAggregator_Core_PartnerStores
extends XLite_Tests_TestCase
{
    
    /**
     * These fields must exist for every store
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.18
     */
    protected $requiredFields = array(
        'channelId',
        'email',
        'id',
        'name',
        'nick',
        'registered',
        'storage',
        'suspended',
        'traffic',
        'url',
    );

    /**
     * Test PartnerStores iterator
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.18
     */
    public function testPartnerStores()
    {
        $stores = new \XLite\Module\Qualiteam\EcwidAggregator\Core\PartnerStores(true);

        $this->assertGreaterThan(0, $stores->count());

        // Test element at the middle
        $middle = intval($stores->count() / 2);
        $stores->seek($middle);
        $someStore = $stores->current();

        $this->assertGreaterThan(0, $someStore['id']);

        $stores->rewind();

        $storeIds = array();
        $prevStoreId = 0;
        for (; $stores->valid(); $stores->next()) {
            $store = $stores->current();

            // Check for required fields
            foreach ($this->requiredFields as $field) {
                $this->assertTrue(isset($store[$field]));
            }

            // Assert id > 0
            $this->assertGreaterThan(0, $store['id']);

            // Check that each store id is unique
            $this->assertNotContains($store['id'], $storeIds);
            $storeIds[] = $store['id'];

            // Assert that the list is sorted by id, ascending
            $this->assertGreaterThan($prevStoreId, $store['id']);
            $prevStoreId = $store['id'];
        }
    }
}

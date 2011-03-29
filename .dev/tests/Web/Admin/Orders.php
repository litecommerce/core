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
 * @category   LiteCommerce
 * @package    Tests
 * @subpackage Web
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    GIT: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

require_once __DIR__ . '/AAdmin.php';

class XLite_Web_Admin_Orders extends XLite_Web_Admin_AAdmin
{
    protected function getTestLowInventoryOrder()
    {
        $order = \XLite\Core\Database::getRepo('XLite\Model\Order')
            ->createQueryBuilder()
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();

        $order->setStatus('Q');

        $item = $order->getItems()->get(0);
        $inv = $item->getProduct()->getInventory();

        $inv->setEnabled(true);
        $inv->setAmount(0);

        \XLite\Core\Database::getEM()->flush();

        return $order;
    }

    /**
     * Test low inventory warning for queued orders
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testLowInventory()
    {
        $this->skipCoverage();

        $this->logIn();

        $order = $this->getTestLowInventoryOrder();
        $this->openAndWait('admin.php?target=order_list&mode=search');

        $oid = $order->getOrderId();

        $optionSelector    = 'select#posteddata-' . $oid . '-status option[value="P"]:disabled';
        $warnIconSelector  = 'div.status-warning a#status_warning_' . $oid;
        $popupMesgSelector = 'div.status_warning_' . $oid . 'formError';

        // Check message
        $this->mouseOver('//a[@id="status_warning_"' . $oid . ']');
        $this->assertJqueryPresent($popupMesgSelector':visible', 'check popup warning message');

        // Check processed disabled status
        $this->assertJqueryPresent($optionSelector, 'check disabled status for Processed option');
        
        // Check warning icon
        $this->assertJqueryPresent($warnIconSelector, 'check warning icon');
    }
}

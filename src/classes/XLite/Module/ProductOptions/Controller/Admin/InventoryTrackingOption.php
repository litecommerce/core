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
 * @package    XLite
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_ProductOptions_Controller_Admin_InventoryTrackingOption extends XLite_Controller_Admin_AAdmin
{
    public $optdata = array();

    function handleRequest()
    {
        $this->set('properties', $_POST);
        parent::handleRequest();
        $url = "admin.php?target=product&product_id=" . $_REQUEST['product_id'] . "&page=inventory_tracking";
        return Header::location($url);
    }

    function action_add()
    {
        if (empty($this->optdata)) {
            return;
        }
        $options[] = $this->product_id;
        foreach ($this->optdata as $class => $optdata) {
            if (isset($optdata['used'])) {
                $options[] = isset($optdata['option']) ?  "$class:" . $optdata['option'] : $class;
            }
        }
        $inventory = new XLite_Module_InventoryTracking_Model_Inventory();
        $inventory->set('inventory_id', implode("|", $options));
        $inventory->set('amount', $this->amount);
        $inventory->set('low_avail_limit', $this->low_avail_limit);
        $inventory->create();
    }

    function action_update()
    {
        $inventory = new XLite_Module_InventoryTracking_Model_Inventory();
        if ($inventory->find("inventory_id='".$this->inventory_id."'")) {
            $inventory->setProperties($this->optdata);
            $inventory->update();
        }
    }

    function action_delete()
    {
        $inventory = new XLite_Module_InventoryTracking_Model_Inventory();
        if ($inventory->find("inventory_id='".$this->inventory_id."'")) {
            $inventory->delete();
        }
    }
}

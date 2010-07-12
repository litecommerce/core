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
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\ProductAdviser\Model;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Inventory extends \XLite\Module\InventoryTracking\Model\Inventory implements \XLite\Base\IDecorator
{
    function set($property, $value)
    {
    	if ($property == "properties") {
    		$oldAmount = $this->get('amount');
    	}

        parent::set($property, $value);

    	if ($property == "properties") {
    		$inventory = $this->properties;
            $inventory['oldAmount'] = $oldAmount;
    		$this->xlite->set('inventoryChangedAmount', $inventory);
    	}
    }

    public function import(array $options)
    {
        $this->xlite->set('inventoryChangedAfterImport', 0);
        $this->xlite->set('checkInventoryChangedAfterImport', true);

    	parent::import($options);

    	if ($this->xlite->get('inventoryChangedAfterImport') > 0) {
    		$inventoryCAI = $this->xlite->get('inventoryChangedAfterImport');
?>
<br>
There <?php echo ($inventoryCAI == 1) ? "is" : "are"; ?> <b><font color=blue><?php echo $this->xlite->get('inventoryChangedAfterImport'); ?></font> Customer Notification<?php echo ($inventoryCAI == 1) ? "s" : ""; ?></b> awaiting.
&nbsp;<a href="admin.php?target=CustomerNotifications&type=product&status=U&period=-1" onClick="this.blur()"><b><u>Click here to view request<?php echo ($inventoryCAI == 1) ? "s" : ""; ?></u></b></a>
<br>
<?php
    	}
    }

    function update()
    {
    	if ($this->xlite->get('checkInventoryChangedAfterImport')) {
            $inventory = new \XLite\Module\InventoryTracking\Model\Inventory($this->get('inventory_id'));
            $oldAmount = $inventory->get('amount');

    		$inventoryChangedAmount = $this->properties;
            $inventoryChangedAmount['oldAmount'] = $oldAmount;

    		$notification = new \XLite\Module\ProductAdviser\Model\Notification();
    		if ($notification->createInventoryChangedNotification($inventoryChangedAmount)) {
        		$this->xlite->set('inventoryChangedAfterImport', $this->xlite->get('inventoryChangedAfterImport') + 1);
    		}
    	}

    	$result = parent::update();

    	return $result;
    }
}

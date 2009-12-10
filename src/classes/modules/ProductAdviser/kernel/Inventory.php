<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URLs:                                                       |
|                                                                              |
| FOR LITECOMMERCE                                                             |
| http://www.litecommerce.com/software_license_agreement.html                  |
|                                                                              |
| FOR LITECOMMERCE ASP EDITION                                                 |
| http://www.litecommerce.com/software_license_agreement_asp.html              |
|                                                                              |
| THIS  AGREEMENT EXPRESSES THE TERMS AND CONDITIONS ON WHICH YOU MAY USE THIS |
| SOFTWARE PROGRAM AND ASSOCIATED DOCUMENTATION THAT CREATIVE DEVELOPMENT, LLC |
| REGISTERED IN ULYANOVSK, RUSSIAN FEDERATION (hereinafter referred to as "THE |
| AUTHOR")  IS  FURNISHING  OR MAKING AVAILABLE TO  YOU  WITH  THIS  AGREEMENT |
| (COLLECTIVELY,  THE "SOFTWARE"). PLEASE REVIEW THE TERMS AND  CONDITIONS  OF |
| THIS LICENSE AGREEMENT CAREFULLY BEFORE INSTALLING OR USING THE SOFTWARE. BY |
| INSTALLING,  COPYING OR OTHERWISE USING THE SOFTWARE, YOU AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE ACCEPTING AND AGREEING  TO  THE  TERMS  OF  THIS |
| LICENSE AGREEMENT. IF YOU ARE NOT WILLING TO BE BOUND BY THIS AGREEMENT,  DO |
| NOT  INSTALL  OR USE THE SOFTWARE. VARIOUS COPYRIGHTS AND OTHER INTELLECTUAL |
| PROPERTY  RIGHTS PROTECT THE SOFTWARE. THIS AGREEMENT IS A LICENSE AGREEMENT |
| THAT  GIVES YOU LIMITED RIGHTS TO USE THE SOFTWARE AND NOT AN AGREEMENT  FOR |
| SALE  OR  FOR TRANSFER OF TITLE. THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/
/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* Inventory_ProductAdviser description.
*
* @package Module_ProductAdviser
* @access public
* @version $Id: Inventory.php,v 1.4 2008/10/23 11:58:38 sheriff Exp $
*/
class Inventory_ProductAdviser extends Inventory
{
    function set($property, $value)
    {
    	if ($property == "properties") {
    		$oldAmount = $this->get("amount");
    	}

        parent::set($property, $value);

    	if ($property == "properties") {
    		$inventory = $this->properties;
            $inventory["oldAmount"] = $oldAmount;
    		$this->xlite->set("inventoryChangedAmount", $inventory);
    	}
    }

    function import(&$options)
    {
        $this->xlite->set("inventoryChangedAfterImport", 0);
        $this->xlite->set("checkInventoryChangedAfterImport", true);

    	parent::import($options);

    	if ($this->xlite->get("inventoryChangedAfterImport") > 0) {
    		$inventoryCAI = $this->xlite->get("inventoryChangedAfterImport");
?>
<br>
There <?php echo ($inventoryCAI == 1) ? "is" : "are"; ?> <b><font color=blue><?php echo $this->xlite->get("inventoryChangedAfterImport"); ?></font> Customer Notification<?php echo ($inventoryCAI == 1) ? "s" : ""; ?></b> awaiting.
&nbsp;<a href="admin.php?target=CustomerNotifications&type=product&status=U&period=-1" onClick="this.blur()"><b><u>Click here to view request<?php echo ($inventoryCAI == 1) ? "s" : ""; ?></u></b></a>
<br>
<?php
    	}
    }

    function update()
    {
    	if ($this->xlite->get("checkInventoryChangedAfterImport")) {
			$inventory =& func_new("Inventory", $this->get("inventory_id"));
			$oldAmount = $inventory->get("amount");

    		$inventoryChangedAmount = $this->properties;
            $inventoryChangedAmount["oldAmount"] = $oldAmount;

    		$notification =& func_new("CustomerNotification");
    		if ($notification->createInventoryChangedNotification($inventoryChangedAmount)) {
        		$this->xlite->set("inventoryChangedAfterImport", $this->xlite->get("inventoryChangedAfterImport") + 1);
    		}
    	}

    	$result = parent::update();

    	return $result;
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

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
| GRANTED  BY  THIS AGREEMENT.                                                 |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/
/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* SystemUpdate description.
*
* @package Module_LiveUpdating
* @access public
* @version $Id$
*/

class SystemUpdate extends Base
{
	var $fields = array
	(
		"update_id" 		=> "",
		"type"				=> "",
		"version"			=> "",
		"module_name"		=> "",
		"description"		=> "",
		"status"			=> "N",
		"importance"		=> "bug fix",
		"date" 				=> 0,
		"applied"			=> 0,
		"dependencies"		=> "",
	);
	var $primaryKey = array("update_id");
	var $defaultOrder = "date";
	var $alias = "updates";

	function getName($update_id = null)
	{
		if (!isset($update_id)) {
			$update_id = $this->get("update_id");
		}
		return sprintf("%011s", $update_id);
	}

	function &get($name)
	{
		$result = parent::get($name);

		if ($name == "statusDependency") {
			$result = parent::get("status");
    		if ($result == "A") {
    			$update_id = parent::get("update_id");
            	$updateItem =& func_new("SystemUpdateItem");
                $updates = $updateItem->findAll("update_id='$update_id'");
                foreach($updates as $ui) {
                    $uif =& func_new("SystemUpdateItemFile");
                    if ($uif->find("update_id='$update_id'")) {
                    	$files =& $uif->findAll("filename='".$uif->get("filename")."'", "update_item_id");
                    	if ($files[count($files)-1]->get("update_id") != $update_id) {
                    		$this->set("overridden", $this->getName($files[count($files)-1]->get("update_id")));
    						$result = "O";
    						break;
                    	}
                    }
                }
    		}
		}

		return $result;
	}

	function isDependenciesResolved()
	{
    	$dependencies = explode(",", $this->get("dependencies"));
    	if (is_array($dependencies) && count($dependencies) > 0) {
    		foreach($dependencies as $dep_key => $dep) {
    			$dependencies[$dep_key] = (strlen($dep) > 0) ? "(update_id='$dep' AND status='N')" : "";
    		}
    		$dependencies = implode(" OR ", $dependencies);
    		if (strlen($dependencies) > 0) {
    			$notApplied = $this->count($dependencies);
    			if ($notApplied > 0) {
    				return false;
    			}
			}
    	}

    	return true;
	}
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

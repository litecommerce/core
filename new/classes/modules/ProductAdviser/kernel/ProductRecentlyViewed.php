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
* ProductRecentlyViewed description.
*
* @package Module_ProductAdviser
* @access public
* @version $Id$
*/
class ProductRecentlyViewed extends Base
{
	var $fields = array
	(
		"sid"			=> "",
		"product_id" 	=> 0,
		"views_number"	=> 0,
		"last_viewed"	=> 0,
	);
	var $primaryKey = array("sid", "product_id");
	var $alias = "products_recently_viewed";
	var $defaultOrder = "views_number DESC, last_viewed DESC";
	var $product = null;

	function constructor()
	{
		parent::constructor();

		$this->collectGarbage();
	}

    function collectGarbage()
    {
		if ($this->xlite->get("RecentlyViewedCleaned")) {
			return;
		}
		$t1 = $this->db->getTableByAlias($this->alias);
		$t2 = $this->session->sql_table;
		$sql = "SELECT $t1.sid FROM $t1 LEFT JOIN $t2 ON $t1.sid=$t2.id WHERE $t2.id IS NULL";
        $expired = $this->db->getAll($sql);
        if (is_array($expired) && count($expired) > 0) {
        	$hash = array();
        	foreach($expired as $sid) {
        		$sid = $sid["sid"];
        		if (!isset($hash[$sid])) {
        			$hash[$sid] = true;
					$sql = "DELETE FROM $t1 WHERE sid='$sid'";
        			$this->db->query($sql);
        		}
        	}
        }

		$this->xlite->set("RecentlyViewedCleaned", true);
    }

    function cleanCurrentGarbage()
    {
		if ($this->xlite->get("CurrentRecentlyViewedCleaned")) {
			return;
		}
		$t1 = $this->db->getTableByAlias($this->alias);
		$sid = $this->session->getID();
		$sql = "DELETE FROM $t1 WHERE sid='$sid'";
		$this->db->query($sql);

		$this->xlite->set("CurrentRecentlyViewedCleaned", true);
    }

	function getProduct()
	{
		if (is_null($this->product)) {
			$this->product = func_new("Product", $this->get("product_id"));
		}
		return $this->product;
	}

	function cleanRelations($product_id)
	{
		$objs = $this->findAll("product_id='$product_id'");
		foreach ($objs as $obj) {
			$obj->delete();
		}
	}
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

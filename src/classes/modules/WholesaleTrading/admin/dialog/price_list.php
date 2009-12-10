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
| The Initial Developer of the Original Code is Creative Development LCC       |
| Portions created by Creative Development LCC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

/**
* Class description.
*
* @package WholesaleTrading
* @access public
* @version $Id: price_list.php,v 1.4 2008/10/23 12:03:43 sheriff Exp $
*/
class Admin_Dialog_Price_List extends Admin_Dialog
{
	var $params = array('target', 'mode', 'category', 'include_subcategories', 'membership');
	var $_priceList;
	
	function &getTemplate()
	{
		if ($this->get('mode') == 'print') {
			return "modules/WholesaleTrading/pl_print.tpl";
		}
		return $this->template;
	}

	function fillPriceList($category_id, $include_subcategories)
	{
		if ($category_id == "") {
			$category_id = 0;
			$include_subcategories = true;
		}
		$cat =& func_new('Category', $category_id); 
		$this->_priceList []= $cat;
		
		if ($include_subcategories == true) {
			foreach ($cat->get('subcategories') as $sc) {
				$this->fillPriceList($sc->get('category_id'), true);
			}
		}	
	}
	
	function &getPriceList()
	{
		$inc_subcategories = isset($_REQUEST['include_subcategories']);
		$this->fillPriceList($_REQUEST['category'], $inc_subcategories);
		return $this->_priceList;
	}

	function &getWholesalePricing($product_id)
	{
		if (!isset($this->wholesale_pricing[$product_id])) {
			$wp =& func_new ("WholesalePricing");
			$where = "product_id=" . $product_id;
			if ($_REQUEST["membership"] != 'all') {
				$where .= " and (membership='all' or membership='" . $_REQUEST["membership"] . "')";
			}
			$this->wholesale_pricing[$product_id] =& $wp->findAll($where);
		}	
		return $this->wholesale_pricing[$product_id];
	}

	function getWholesaleCount($product_id)
	{
		return count($this->getWholesalePricing($product_id));
	}
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

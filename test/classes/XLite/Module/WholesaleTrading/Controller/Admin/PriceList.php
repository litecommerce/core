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
* @version $Id$
*/
class XLite_Module_WholesaleTrading_Controller_Admin_PriceList extends XLite_Controller_Admin_Abstract
{	
	public $params = array('target', 'mode', 'category', 'include_subcategories', 'membership');	
	
	protected $_priceList = array();
	protected $wholesale_pricing = array();

	/**
	 * getRegularTemplate 
	 * 
	 * @return void
	 * @access protected
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	protected function getRegularTemplate()
	{
		if ('print' == XLite_Core_Request::getInstance()->mode) {
			$return = "modules/WholesaleTrading/pl_print.tpl";

		} else {
			$return = parent::getRegularTemplate();
		}

		return $return;
	}

	function fillPriceList($category_id, $include_subcategories)
	{
		if ($category_id == "") {
			$category_id = 0;
			$include_subcategories = true;
		}
		$cat = new XLite_Model_Category($category_id); 
		$this->_priceList[] = $cat;
		
		if ($include_subcategories == true) {
			foreach ($cat->get('subcategories') as $sc) {
				$this->fillPriceList($sc->get('category_id'), true);
			}
		}	
	}
	
	function getPriceList()
	{
		$this->fillPriceList(isset($_REQUEST['category']) ? $_REQUEST['category'] : '', isset($_REQUEST['include_subcategories']));

		return $this->_priceList;
	}

	function getWholesalePricing($product_id)
	{
		if (!isset($this->wholesale_pricing[$product_id])) {
			$wp = new XLite_Module_WholesaleTrading_Model_WholesalePricing();
			$where = "product_id=" . $product_id;
			if ($_REQUEST["membership"] != 'all') {
				$where .= " and (membership='all' or membership='" . $_REQUEST["membership"] . "')";
			}
			$this->wholesale_pricing[$product_id] = $wp->findAll($where);
		}	
		return $this->wholesale_pricing[$product_id];
	}

	function getWholesaleCount($product_id)
	{
		return count($this->getWholesalePricing($product_id));
	}
}


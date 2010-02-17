<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URL: http://www.litecommerce.com/license.php                |
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
| The Initial Developer of the Original Code is Ruslan R. Fazliev              |
| Portions created by Ruslan R. Fazliev are Copyright (C) 2003 Creative        |
| Development. All Rights Reserved.                                            |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* Class description.
*
* @package SnsIntegration
* @access public
* @version $Id$
*/
class XLite_Module_SnsIntegration_Controller_Customer_AdvancedSearch extends XLite_Module_AdvancedSearch_Controller_Customer_AdvancedSearch implements XLite_Base_IDecorator
{	
	public $snsActionSend = false;

    function getProducts() // {{{
    {
		if (isset($this->mode) && isset($this->action) && $this->snsActionSend !== true && is_array($this->search)) {
			require_once LC_MODULES_DIR . 'SnsIntegration' . LC_DS . 'include' . LC_DS . 'misc.php';
			$snsClientId  = func_get_sns_client_id();
			$actions = array();
			$action = "name=AdvancedSearch";
			$action .= "&searchPhrase=" . urlencode($this->search["substring"]);
			if (isset($this->search["category"]) && $this->search["category"] != 0) {
				$cat = new XLite_Model_Category($this->search["category"]);
				$categoryName = $cat->get("stringPath");

			} else {
				$categoryName = "";
			}
			$action .= "&categoryName=" . urlencode($categoryName);
			$actions[]= $action;
			func_sns_request($this->config, 
								$snsClientId, 
								$actions);
			$this->snsActionSend = true;
										
		}
		return parent::getProducts();
    } // }}}
}

// WARNING:
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

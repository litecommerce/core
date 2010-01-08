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
* @package Module_WholesaleTrading
* @access public
* @version $Id$
*/
class XLite_Module_WholesaleTrading_Controller_Customer_Cart extends XLite_Module_WholesaleTrading_Controller_Customer_CartUpdate implements XLite_Base_IDecorator
{
	var $updateErrors = null;
	var $params = array("target","mode");
	var $currentItem = null;
	
    function action_add()
    {
		$amount = 1;
		$items = $this->cart->get('items');

        // alternative way to set product options
        if ($this->xlite->get("ProductOptionsEnabled") && is_object($this->getProduct()) && isset($_REQUEST["OptionSetIndex"][$this->product->get("product_id")])) {
            $options_set = $this->product->get("expandedItems");
            foreach($options_set[$_REQUEST["OptionSetIndex"][$this->product->get("product_id")]] as $_opt) {
                $this->product_options[$_opt->class] = $_opt->option_id;
            }
        }
        
		if (isset($_REQUEST['amount']) && $_REQUEST['amount'] > 0) {
			$amount = $_REQUEST['amount'];
		}
		if (isset($_REQUEST['wishlist_amount']) && $_REQUEST['wishlist_amount'] > 0) {
			$amount = $_REQUEST['wishlist_amount'];
		}
		if (!isset($_REQUEST["opt_product_qty"])) {
    		// min/max purchase amount check
    		$pl = new XLite_Module_WholesaleTrading_Model_PurchaseLimit();
    		if ($pl->find("product_id=" . $this->get('currentItem.product.product_id'))) {
    			$exists_amount = 0;
    			for ($i=0; $i < count($items); $i++) {
    				if ($items[$i]->get('product.product_id') == $this->get('currentItem.product.product_id')) {
    					$exists_amount += $items[$i]->get('amount');
    				}
    			}
    			if ($amount + $exists_amount < $pl->get('min') || 
    				($pl->get('max') > 0 && $pl->get('max') < $amount + $exists_amount)) {
    				$this->set("returnUrl", "cart.php?mode=add_error&error=range&max=" . $pl->get('max') . "&min=" . $pl->get('min') . "&added=" . $exists_amount);
    				return;
    			}
    		}
		}
		// check if product sale available
        $this->product->set("product_id", $this->product_id);
		if (!$this->product->is("saleAvailable")) {
			$this->set("returnUrl", "cart.php?mode=add_error");
			return;
		}

		$this->currentItem = parent::get("currentItem");
		$this->currentItem->set("amount", $amount);
 
        parent::action_add();

	    if ($this->config->get("WholesaleTrading.direct_addition")) {
    		$this->product->assignDirectSaleAvailable(false);
    	}
	}

    function action_update()
    {
		$items = $this->cart->get('items');
		$raw_items = array();
		$amounts = $this->get("amount");
		for ($i = 0; $i < count($items); $i++) {
			$key = $items[$i]->get("product.product_id");
			if ($key == NULL) continue;
			(!isset($raw_items[$key])) ? $raw_items[$key] = $amounts[$i] : $raw_items[$key] += $amounts[$i];	
		}

		foreach($raw_items as $key => $amount) {
			$purchase_limit = new XLite_Module_WholesaleTrading_Model_PurchaseLimit();
			$limit = array();
			if ($purchase_limit->find("product_id = ". $key)) {
				$limit = $purchase_limit->get("properties");
				if (!empty($limit['min']) && $amount < intval($limit['min'])) {
					$this->updateErrors[$key]['min'] = $limit['min'];	
					$this->updateErrors[$key]['amount'] = $amount;
				}	
				if (!empty($limit['max']) &&  $amount > intval($limit['max'])) {
					$this->updateErrors[$key]['max'] = $limit['max'];
					$this->updateErrors[$key]['amount'] = $amount;
				}	
			}	
		}
		if (empty($this->updateErrors)) {
			$this->set("mode",null);
			parent::action_update();
		} else {
			foreach($this->updateErrors as $key => $error) {
				$product = new XLite_Model_Product($key);
				$this->updateErrors[$key]['name'] = $product->get("name");
			}
			
			$this->set("valid",false);
			$this->set("mode","update_error");
		}
	  	  
    }
}


// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

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
*
* @package Dialog
* @access public
* @version $Id$
*/
class Admin_Dialog_product_list extends Admin_Dialog
{
    var $params = array('target', 'mode', 'search_productsku', 'substring', 'search_category', 'subcategory_search', 'pageID', 'status');

    var $productsList = null;
    var $productsFound = 0;

    function _getExtraParams()
    {
    	return array('search_productsku', 'substring', 'search_category', 'subcategory_search', 'status');
    }

    function getExtraParams()
    {
    	$form_params = $this->_getExtraParams();

        $result = parent::getAllParams();
        if (is_array($result)) {
        	foreach ($result as $param => $name) {
        		if (in_array($param, $form_params)) {
        			if (isset($result[$param])) {
        				unset($result[$param]);
        			}
        		}
            }
        }

        return $result;
    }

    function getProducts()
    {
    	if ($this->mode != "search") {
    		return null;
    	}

    	if (is_null($this->productsList)) {
            $p = func_new("Product");
            $p->collectGarbage();
            $this->productsList = $p->advancedSearch($this->substring,
                                          $this->search_productsku,
                                          $this->search_category,
                                          $this->subcategory_search,
                                          false, true);
            $this->productsFound = count($this->productsList);
		}
        return $this->productsList;
    }

    function action_update()
    {
		foreach ($this->product_orderby as $product_id => $order_by) {
			$p = func_new("Product", $product_id);
			$p->set("order_by", $order_by);
			$p->set("price", $this->product_price[$product_id]);
			$p->update();
		}	
        $this->set("status", "updated");
    }

    function action_delete()
    {
		if (!(isset($this->product_ids) && is_array($this->product_ids))) {
			return;
		}

		if (isset($this->confirmed)) {
			$this->set("mode", "search");
			if (!$this->confirmed) {
				return;
			}

            foreach ($this->product_ids as $product_id) {
    			$p = func_new("Product", $product_id);
                $p->delete();
            }
            if (!empty($this->product_ids)) {
                $this->set("status", "deleted");
            } else {
                $this->set("status", null);
            }
		} else {
    		$this->set("valid", false);
    		$this->set("mode", "confirmation");
        	if (isset($this->product_ids) && is_array($this->product_ids)) {
                foreach ($this->product_ids as $idx => $product_id) {
                	$this->product_ids[$idx] = func_new("Product", $product_id);
                }
            }
        }
    }

	function action_clone()
	{
    	if (isset($this->product_ids) && is_array($this->product_ids)) {
            foreach ($this->product_ids as $product_id) {
    			$p = func_new("Product", $product_id);
                $product = $p->cloneObject();
    			foreach ($p->get("categories") as $category) {
    				$product->addCategory($category);
    			}
    			$product->set("name", $product->get("name") . " (CLONE)");
    			$product->update();
				$this->set("status", "cloned");
            }
        }
	}
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

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
* Admin_Dialog_product_ProductAdviser description.
*
* @package Module_ProductAdviser
* @access public
* @version $Id$
*/
class Admin_Dialog_product_list_ProductAdviser extends Admin_Dialog_product_list
{
	var $notifyPresentedHash = array();

    function init()
    {
    	$this->params[] = "new_arrivals_search";
    	parent::init();
    }

    function _getExtraParams()
    {
    	return array('search_productsku', 'substring', 'search_category', 'subcategory_search', 'new_arrivals_search');
    }

    function &getExtraParams()
    {
    	$form_params = $this->_getExtraParams();

        $result =& parent::getAllParams();
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

    function isNotifyPresent($product_id)
    {
    	if (!isset($this->notifyPresentedHash[$product_id])) {
        	$check = array();
            $check[] = "type='" . CUSTOMER_NOTIFICATION_PRICE . "'";
    		$check[] = "notify_key='" . addslashes($product_id) . "'";
    		$check[] = "status='" . CUSTOMER_REQUEST_UPDATED . "'";
    		$check = implode(" AND ", $check);

    		$notification =& func_new("CustomerNotification");
    		$this->notifyPresentedHash[$product_id] = $notification->count($check);
    	}
		return $this->notifyPresentedHash[$product_id];
    }

    function &getProducts()
    {
    	if ($this->mode != "search") {
    		return null;
    	}

    	$this->productsList =& parent::getProducts();
    	if (is_array($this->productsList) && $this->new_arrivals_search) {
    		$removedItems = array();
    		for($i=0; $i<count($this->productsList); $i++) {
        		if (is_array($this->productsList[$i]) && isset($this->productsList[$i]["class"]) && isset($this->productsList[$i]["data"])) {
            		$object =& func_new($this->productsList[$i]["class"]);
                    $object->isPersistent = true;
                    $object->isRead = false;
                    $object->properties = $this->productsList[$i]["data"];
                    if ($object->getNewArrival() == 0) {
                    	$removedItems[] = $i;
                    }
        		} else {
                    if (is_object($this->productsList[$i]) && $this->productsList[$i]->getNewArrival() == 0) {
                    	$removedItems[] = $i;
                    }
        		}
    		}
    		if (count($removedItems) > 0) {
        		foreach($removedItems as $i) {
    				unset($this->productsList[$i]);
        		}
            	$this->productsFound = count($this->productsList);
    		}
		}
        return $this->productsList;
    }
}

?>

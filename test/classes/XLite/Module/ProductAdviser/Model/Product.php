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
* Product_ProductAdviser description.
*
* @package Module_ProductAdviser
* @access public
* @version $Id$
*/
class XLite_Module_ProductAdviser_Model_Product extends XLite_Model_Product
{
	var $_RelatedProducts = null;
	var $_ProductsAlsoBuy = null;
	var $_ProductMainCategory = null;

    function getRelatedProducts()
    {
        require_once "modules/ProductAdviser/encoded.php";
		ProductAdviser_getRelatedProducts($this);

        return $this->_RelatedProducts; 
    }

    function getProductsAlsoBuy()
    {
        require_once "modules/ProductAdviser/encoded.php";
		ProductAdviser_getProductsAlsoBuy($this);

        return $this->_ProductsAlsoBuy; 
    }

	function addRelatedProducts($products)
	{
		if (is_array($products)) {
    		foreach($products as $p_key => $product) {
				$relatedProduct = new XLite_Module_ProductAdviser_Model_RelatedProduct();
                $relatedProduct->set("product_id", $this->get("product_id"));
                $relatedProduct->set("related_product_id", $product->get("product_id"));
    			if (!$relatedProduct->isExists()) {
    				$relatedProduct->create();
    			}
    		}
    	}
	}

	function deleteRelatedProducts($products)
	{
		if (is_array($products)) {
    		foreach($products as $p_key => $product) {
				$relatedProduct = new XLite_Module_ProductAdviser_Model_RelatedProduct();
                $relatedProduct->set("product_id", $this->get("product_id"));
                $relatedProduct->set("related_product_id", $product->get("product_id"));
    			if ($relatedProduct->isExists()) {
    				$relatedProduct->delete();
    			}
    		}
    	}
	}

    function create()
    {
    	parent::create();
    	if ($this->config->get("ProductAdviser.period_new_arrivals") > 0) {
    		$added = time();
            //$added = mktime(date("H", $added), 0, 0, date("m", $added), date("d", $added), date("Y", $added));
            $product_id = $this->get("product_id");

        	$statistic = new XLite_Module_ProductAdviser_Model_ProductNewArrivals();
            $statistic->set("product_id", $product_id);
        	if ($statistic->find("product_id='$product_id'")) {
        		$statistic->set("updated", $added);
                $statistic->update();
        	} else {
            	$statistic->set("added", $added);
        		$statistic->set("updated", $added);
                $statistic->create();
        	}
    	}
    }

    function delete()
    {
		$product_id = $this->get("product_id");
		$linked = array
		(
			"ProductAlsoBuy",
			"ProductNewArrivals",
			"ProductRecentlyViewed",
			"RelatedProduct",
		);

    	parent::delete();

		foreach ($linked as $objName) {
    		$object = new $objName();
    		$objs = $object->cleanRelations($product_id);
		}
    }

	function getNewArrival()
	{
        $stats = new XLite_Module_ProductAdviser_Model_ProductNewArrivals();
        $timeCondition = $this->config->get("ProductAdviser.period_new_arrivals") * 3600;
		$timeLimit = time();
        if (!$stats->find("product_id='".$this->get("product_id")."'")) {
        	return 0;
        }

        if ($stats->get("new") == "Y") {
        	return 2;
        }

        if (($stats->get("updated") + $timeCondition) > $timeLimit) {
        	return 1;
        }

		return 0;
	}

    function set($property, $value)
    {
    	if ($property == "price") {
			$oldPrice = $this->get("price");
    	}

        parent::set($property, $value);

        if (!$this->config->get("ProductAdviser.customer_notifications_enabled")) {
        	return;
        }
    	if ($property == "price") {
			$newPrice = $this->get("price");
            $price = null;
			if ($newPrice < $oldPrice) {
        		$price = $this->properties;
                $price["oldPrice"] = $oldPrice;
            }
			$this->xlite->set("productChangedPrice", $price);
    	}
    }

    function update()
    {
        parent::update();

        require_once "modules/ProductAdviser/encoded.php";
		ProductAdviser_updateProduct($this);
    }

    function checkHasOptions()
    {
    	if (!$this->xlite->get("ProductOptionsEnabled")) {
    		return false;
    	}
    	return $this->hasOptions();
    }

    function _checkSafetyMode()
    {
    	if ($this->xlite->get("HTMLCatalogWorking")) {
    		return true;
    	}
    	return false;
    }

    function checkSafetyMode()
    {
    	if ($this->_checkSafetyMode()) {
    		$category_id = $this->get("category.category_id");
    	}
    }

    function getCategory()
    {
    	if (is_null($this->_ProductMainCategory) || $this->_checkSafetyMode()) {
    		if ($this->_checkSafetyMode()) {
    			$adminZone = $this->xlite->is("adminZone");
    			$this->xlite->set("adminZone", true);
    		}
    		$categories = $this->getCategories(null, null, false);
    		if ($this->_checkSafetyMode()) {
    			$this->xlite->set("adminZone", $adminZone);
    		}
    		$this->_ProductMainCategory = $categories[0];
    	}
    	return $this->_ProductMainCategory;
    }

	function import(&$options)
	{
		parent::import($options);

		$check = array();
		$check[] = "type='" . CUSTOMER_NOTIFICATION_PRICE . "'";
		$check[] = "status='" . CUSTOMER_REQUEST_UPDATED . "'";
		$check = implode(" AND ", $check);

		$notification = new XLite_Module_ProductAdviser_Model_Notification();
		$pricingCAI = $notification->count($check);

		if ($pricingCAI > 0) {
?>
<br>
There <?php echo ($pricingCAI == 1) ? "is" : "are"; ?> <b><font color=blue><?php echo $pricingCAI; ?></font> Customer Notification<?php echo ($pricingCAI == 1) ? "s" : ""; ?></b> awaiting.
&nbsp;<a href="admin.php?target=CustomerNotifications&type=price&status=U&period=-1" onClick="this.blur()"><b><u>Click here to view request<?php echo ($pricingCAI == 1) ? "s" : ""; ?></u></b></a>
<br>
<?php
		}

	}

	function isPriceNotificationAllowed()
	{
		if (intval($this->get("price")) <= 0) {
			return false;
		}
		$mode = $this->config->get("ProductAdviser.customer_notifications_mode");
		return (($mode & 1) != 0) ? true : false;
	}

}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

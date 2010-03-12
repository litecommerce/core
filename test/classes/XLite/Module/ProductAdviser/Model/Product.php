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
class XLite_Module_ProductAdviser_Model_Product extends XLite_Model_Product implements XLite_Base_IDecorator
{	
	public $relatedProducts = null;	
	public $productsAlsoBuy = null;	
	public $_ProductMainCategory = null;

    /**
     * Get the list of related products
     * 
     * @return array of XLite_Model_Product objects
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    function getRelatedProducts()
    {
		if (!isset($this->relatedProducts)) {

			$productId = $this->get("product_id");

			$relatedProduct = new XLite_Module_ProductAdviser_Model_RelatedProduct();
			$relatedProducts = $relatedProduct->findAll("product_id='$productId'");
			$products = array();

			if (is_array($relatedProducts)) {

				foreach($relatedProducts as $p_key => $product) {

		            $rp = new XLite_Model_Product($product->get("related_product_id"));
					$addSign = true;
					$addSign &= $rp->filter();
					$addSign &= $rp->is("available");

					// additional check
					if (!$rp->is("available") || (isset($rp->properties) && is_array($rp->properties) && !isset($rp->properties["enabled"]))) {
						// removing link to non-existing product
						if (intval($rp->get("product_id")) > 0) {
							$rp->delete();
						}

						$addSign = false;
					}

		            if ($addSign) {
						$rp->checkSafetyMode();
						$products[$p_key] = $rp;
					}
				}

				if (!empty($products)) {
					$this->relatedProducts = $products;
				}
			}
		}

        return $this->relatedProducts; 
    }

    /**
     * Get the list of recommended products (products that are also buy with current product)
     * 
     * @return array of XLite_Model_Product objects
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    function getProductsAlsoBuy()
    {
		if (!isset($this->productsAlsoBuy)) {

			$productId = $this->get("product_id");
		    $pabObj = new XLite_Module_ProductAdviser_Model_ProductAlsoBuy();
			$pabAll = $pabObj->findAll("product_id='$productId'");
			$products = array();

			if (is_array($pabAll)) {

				foreach($pabAll as $p_key => $product) {

		            $pab = new XLite_Model_Product($product->get("product_id_also_buy"));
					$addSign = true;
					$addSign &= $pab->filter();
					$addSign &= $pab->is("available");

					// additional check
					if (!$pab->is("available") || (isset($pab->properties) && is_array($pab->properties) && !isset($pab->properties["enabled"]))) {
						// removing link to non-existing product
						if (intval($pab->get("product_id")) > 0) {
							$pab->delete();
						}

						$addSign = false;
					}

		            if ($addSign) {
						$pab->checkSafetyMode();
		            	$products[$p_key] = $pab;
		            }
				}

				if (!empty($products)) {
					$this->productsAlsoBuy = $products;
				}
			}
		}
		
        return $this->productsAlsoBuy; 
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
    	if ($this->config->getComplex('ProductAdviser.period_new_arrivals') > 0) {
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

		$result = 0;

        if ($stats->find("product_id = '" . $this->get("product_id") . "'")) {

	        $timeCondition = $this->config->ProductAdviser->period_new_arrivals * 3600;
    	    $timeLimit = time();

	        if ($stats->get("new") == "Y") {
    	    	$result = 2;

        	} elseif (($stats->get("updated") + $timeCondition) > $timeLimit) {
        		$result =  1;
	        }
		}

		return $result;
	}

    function set($property, $value)
    {
    	if ($property == "price") {
			$oldPrice = $this->get("price");
    	}

        parent::set($property, $value);

        if (!$this->config->getComplex('ProductAdviser.customer_notifications_enabled')) {
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

        require_once LC_MODULES_DIR . 'ProductAdviser' . LC_DS . 'encoded.php';
		ProductAdviser_updateProduct($this);
    }

    function checkHasOptions()
    {
    	return $this->xlite->get("ProductOptionsEnabled") ? $this->hasOptions() : false;
    }

    function _checkSafetyMode()
    {
    	return $this->xlite->get("HTMLCatalogWorking");
    }

    function checkSafetyMode()
    {
    	if ($this->_checkSafetyMode()) {
    		$category_id = $this->getComplex('category.category_id');
    	}
    }

    function getCategory($where = null, $orderby = null, $useCache = true)
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
			if (isset($categories[0])) {
				$this->_ProductMainCategory = $categories[0];
			}
    	}
    	return $this->_ProductMainCategory;
    }

	public function import(array $options)
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

	/**
	 * Check - price notification is allowed for product or not 
	 * 
	 * @return boolean
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	public function isPriceNotificationAllowed()
	{
		return 0 < intval($this->get('price'))
			&& ($this->config->ProductAdviser->customer_notifications_mode & 1) != 0;
	}

}

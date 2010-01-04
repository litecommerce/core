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
* CNewArrivalsProducts description.
*
* @package Module_ProductAdviser
* @access public
* @version $Id$
*/
class XLite_Module_ProductAdviser_View_CNewArrivalsProducts extends XLite_View
{
	var $productsNumber = 0;
	var $additionalPresent = false;

    function getVisible()
    {
    	if ($this->config->get("ProductAdviser.number_new_arrivals") <= 0) {
    		return false;
    	}

    	$this->getNewArrivalsProducts();

        return ($this->productsNumber > 0) ? true : false;
    }

    function getDialogCategory()
    {
        if (isset($_REQUEST["target"]) && ($_REQUEST["target"] == "category" || $_REQUEST["target"] == "product") && isset($_REQUEST["category_id"]) && intval($_REQUEST["category_id"]) > 0) {
        	$category = new XLite_Model_Category(intval($_REQUEST["category_id"]));
        	return $category;
        }
        return null;
    }

    function getDialogProductId()
    {
        if (isset($_REQUEST["target"]) && $_REQUEST["target"] == "product" && isset($_REQUEST["product_id"]) && intval($_REQUEST["product_id"]) > 0) {
        	return intval($_REQUEST["product_id"]);
        }
        return null;
    }

    function isDisplayedDialog()
    {
        if ($this->config->get("ProductAdviser.new_arrivals_type") == "dialog" && isset($this->dialog)) {
        	return true;
        } else {
        	return isset($this->target) ? true : false;
        }
    }

    function isDisplayed()
    {
        if 
        (
        	($this->config->get("ProductAdviser.new_arrivals_type") == "dialog" && !isset($this->dialog)) 
        	||
        	($this->config->get("ProductAdviser.new_arrivals_type") != "dialog" && isset($this->dialog)) 
        )
        {
        	return (isset($this->target) && ($this->target == "NewArrivals") && isset($this->dialog)) ? true : false;
        }
        else
        {
        	return (isset($this->target) && ($this->target != "NewArrivals")) ? false : true;
        }
    }

    function inCategory(&$product, &$category)
    {
		$signCategory = $product->inCategory($category);
		if ($signCategory) {
			return $signCategory;
		} else {
			$subcategories = $category->getSubcategories();
			foreach($subcategories as $cat_idx => $cat) {
				$signCategory |= $this->inCategory($product, $subcategories[$cat_idx]);
				if ($signCategory) {
					return $signCategory;
				}
			}
		}
		return false;
    }

	function recursiveArrivalsSearch($_category)
	{
		if (!$this->isDisplayedDialog() && $this->additionalPresent && count($this->_new_arrival_products) >= $this->config->get("ProductAdviser.number_new_arrivals")) {
			return true;
		}

		$timeLimit = time();
		$timeCondition = $this->config->get("ProductAdviser.period_new_arrivals") * 3600;
		$category_id = $_category->get("category_id");

		$obj = new XLite_Module_ProductAdviser_Model_ProductNewArrivals();
		$arrival_table = $this->db->getTableByAlias($obj->alias);
		$links_table = $this->db->getTableByAlias("product_links");

		$fromSQL = array();
		$fromSQL[] = "$links_table AS links";
		$fromSQL[] = "$arrival_table AS arrivals";

		$whereSQL = array();
		$whereSQL[] = "links.product_id=arrivals.product_id";
		$whereSQL[] = "links.category_id='$category_id'";
		$whereSQL[] = "(arrivals.new='Y' OR ((arrivals.updated + '$timeCondition') > '$timeLimit'))";

		$querySQL = "SELECT arrivals.product_id, arrivals.updated FROM ".implode(", ", $fromSQL)." WHERE ".implode(" AND ", $whereSQL)." ORDER BY arrivals.updated DESC";
		$rows = $this->db->getAll($querySQL);

		foreach ((array)$rows as $row) {
			$product_id = $row["product_id"];

			$obj = new XLite_Module_ProductAdviser_Model_ProductNewArrivals($product_id);
			if ($this->checkArrivalCondition($_category, $obj)) {
				if (!$this->isDisplayedDialog() && count($this->_new_arrival_products) >= $this->config->get("ProductAdviser.number_new_arrivals")) {
					$this->additionalPresent = true;
					return true;
				}

				if (!isset($this->_new_arrival_products[$product_id])) {
					$this->_new_arrival_products[$product_id] = new XLite_Model_Product($product_id);
					$this->_new_arrival_products_updated[$product_id] = $row["updated"];
				}
			}
		}

		// get subcategories list
		$category = new XLite_Model_Category();
		$categories = $category->findAll("parent='$category_id'");
		foreach ($categories as $category) {
			if ($this->recursiveArrivalsSearch($category))
				return true;
		}

		return false;
	}

	function checkArrivalCondition($category, $ps)
	{
		$product_id = $this->getDialogProductId();
		$product = new XLite_Model_Product($ps->get("product_id"));

		$addSign = (isset($product_id) && $product->get("product_id") == $product_id) ? false : true;
		if ($addSign) {
			$addSign &= $product->filter();
			$addSign &= $product->is("available");
			// additional check
			if (!$product->is("available") || (isset($product->properties) && is_array($product->properties) && !isset($product->properties["enabled"]))) {
				// removing link to non-existing product
				if (intval($ps->get("product_id")) > 0) {
					$ps->delete();
				}
				$addSign &= false;
			}
		}

		return $addSign;
	}

    function getNewArrivalsProducts()
    {
        if (!$this->isDisplayed()) {
        	$this->productsNumber = 0;
        	return array();
        }

    	$products = $this->xlite->get("NewArrivalsProducts");
        if (isset($products)) {
        	$this->productsNumber = count($products);
            return $products;
        }    

		$category = $this->getDialogCategory();
		$product_id = $this->getDialogProductId();


		// recursive search
		if ($this->config->get("ProductAdviser.category_new_arrivals")) {
			$this->_new_arrival_products = array();
			$this->additionalPresent = false;

			$categories = array();
			if (is_null($category)) {
				// deal with root category
				$obj = new XLite_Model_Category();
				$categories = $obj->findAll("parent='0'");
			} else {
				$categories[] = $category;
			}

			// recursively search new arrival products
			foreach ($categories as $cat) {
				if ($this->recursiveArrivalsSearch($cat))
					break;
			}

			if (is_array($this->_new_arrival_products_updated) && is_array($this->_new_arrival_products)) {
   				arsort($this->_new_arrival_products_updated, SORT_NUMERIC);
                // sort by keys, 'cos values are objects
                krsort($this->_new_arrival_products, SORT_NUMERIC);
			}

			$products = array_values($this->_new_arrival_products);
			$this->productsNumber = count($products);
			$this->xlite->set("NewArrivalsProducts", $products);

			return $products;
		}

        $maxViewed = $this->config->get("ProductAdviser.number_new_arrivals");
        $products = array();
        $productsStats = array();
        $statsOffset = 0;
        $stats = new XLite_Module_ProductAdviser_Model_ProductNewArrivals();
        $timeCondition = $this->config->get("ProductAdviser.period_new_arrivals") * 3600;
		$timeLimit = time();
        $maxSteps = ($this->isDisplayedDialog()) ? 1 : ceil($stats->count("new='Y' OR ((updated + '$timeCondition') > '$timeLimit')") / $maxViewed);

        for ($i=0; $i<$maxSteps; $i++) {
        	$limit = ($this->isDisplayedDialog()) ? null : "$statsOffset, $maxViewed";
        	$productsStats = $stats->findAll("new='Y' OR ((updated + '$timeCondition') > '$timeLimit')", null, null, $limit);
        	foreach ($productsStats as $ps) {
				$product = new XLite_Model_Product($ps->get("product_id"));
				$addSign = $this->checkArrivalCondition($category, $ps);
                if ($addSign) {
                    $product->checkSafetyMode();
                	$products[] = $product;
                	if (count($products) > $maxViewed) {
						if (!$this->isDisplayedDialog()) {
    						$this->additionalPresent = true;
    						unset($products[count($products)-1]);
                			break;
                		}
                	}
                }
        	}

        	if ($this->additionalPresent) {
				break;
        	}

        	if (count($products) > $maxViewed) {
				if (!$this->isDisplayedDialog()) {
					$this->additionalPresent = true;
					unset($products[count($products)-1]);
        			break;
        		}
        	}

            $statsOffset += $maxViewed;
        }

    	$this->productsNumber = count($products);
        $this->xlite->set("NewArrivalsProducts", $products);

        return $products;
	}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

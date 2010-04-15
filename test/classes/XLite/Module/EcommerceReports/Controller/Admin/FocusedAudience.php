<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 * 
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_EcommerceReports_Controller_Admin_FocusedAudience extends XLite_Module_EcommerceReports_Controller_Admin_EcommerceReports
{	
    public $crlf = "\r\n";

    function getProfiles() // {{{
    {
        if (is_null($this->profiles)) {
            $this->profiles = array();
            // fetch all original profiles
			$ids = array();
            foreach ($this->get("focusedSales") as $sale) {
            	$orig_profile_id = $sale->get("orig_profile_id");
            	if ($orig_profile_id == 0) {
            		$orig_profile_id = $sale->get("profile_id");
            	}
				$ids[] = $orig_profile_id;
            }
            // compact
			$ids = array_unique($ids);

			if ($this->get("match_condition") == "all") {
				$ids = $this->getFullOwners($ids);
			}
            // create list of original profiles
			foreach ($ids as $id) {
				$this->profiles[] = new XLite_Model_Profile($id);
			}
        }
        return $this->profiles;
    } // }}}
    
    function action_profiles() // {{{
    {
        if ($this->get("send_newsletter")) {
            $this->send_newsletter();
        } elseif ($this->get("export_profiles")) {
            $this->export_profiles();
        }
    } // }}}
    
    function send_newsletter() // {{{
    {
        $recipients = array();
        foreach ($this->get("targetProfiles") as $profile) {
            $recipients[] = strtolower($profile->get("login"));
        }
        $recipients = array_unique($recipients);
        echo "Sending newsletters .. ";
        if (!empty($recipients)) {
            echo "Newsletters module required!<br><br>";
        } else {
            echo "no recipients found, select one or more orders!<br><br>"; 
        }
        $this->set("silent", true); // do not redirect
        $url = $this->get("url");
        echo "<a href=\"$url\">New search..</a>";
    } // }}}

    function export_profiles() // {{{
    {
        $w = new XLite_View_Abstract();
        $w->component = $this;
        $w->set("template", "modules/EcommerceReports/export_csv.tpl");
        $this->startDownload("users.csv");
        $w->init();
        $w->display();
        $this->set("silent", true);
    } // }}}

    function getTargetProfiles() // {{{
    {
        if (is_null($this->targetProfiles)) {
            $this->targetProfiles = array();
            $profileIDs = (array)$this->get("profile_ids");
            foreach ($profileIDs as $pid) {
                $this->targetProfiles[] = new XLite_Model_Profile($pid);
            }
        }
        return $this->targetProfiles;
    } // }}}
    
    function getOrders() // {{{
    {
        if (is_null($this->orders)) {
            $this->orders = array();
            $orderIDs = (array)$this->get("order_ids");
            foreach ($orderIDs as $oid) {
                $this->orders[] = new XLite_Model_Order($oid);
            }
        }
        return $this->orders;
    } // }}}
    
    function getDelimiter() // {{{
    {
        global $DATA_DELIMITERS;
        return $DATA_DELIMITERS[$this->delimiter];
    } // }}}

    function isDefaultField($field) // {{{
    {
        $defaultFields = array(
                "name",
                "email",
                );
        return in_array($field, $defaultFields);
    } // }}}
    
    function getExportFields() // {{{
    {
        return array(
                "profile_id" => "Profile #",
                "name" => "Name",
                "email" => "E-mail",
                "billing_info" => "Billing address details",
                "shipping_info" => "Shipping address details",
                );
    } // }}}

    function getQueryString() // {{{
    {
        return $_SERVER["QUERY_STRING"];
    } // }}}

    function getFocusedSales() // {{{
    {
		if (is_null($this->focusedSales)) {
			$this->focusedSales = array();

			// PASS 1
			// select raw items
			$items = $this->get("rawItems");

			// PASS 2
			// summarize orders info
			$this->totals = array();
			$this->orders = array();
			array_map(array($this, 'sumOrders'), $items);

			// PASS 3
			// apply/purchased products filter
			// orders not passed through filter are marked
			// order["passed"] == false
			array_map(array($this, 'filterOrders'), $this->orders);

			//echo "ORDERS<pre>"; print_r($this->orders); echo "</pre>";
			//echo "TOTALS<pre>"; print_r($this->totals); echo "</pre>";

			// PASS 4
			// collect passed orders to focusedSales
			array_map(array($this, 'collectFocusedSales'), $this->orders);

		}
        return $this->focusedSales;
    } // }}}

    function collectFocusedSales($order) // {{{
    {
        if ($order["passed"]) {
            $this->focusedSales[] = new XLite_Model_Order($order["order_id"]);
        }
    } // }}}

    function filterOrders($order) // {{{
    {
        // apply products filter
        foreach ($order["products"] as $pidx => $product) {
            $passed = $this->filterProduct($product);
            if (!$passed) {
                $order["passed"] = false; // order not passed through filter
                return;
            }
        }
        // apply purchases filter
        $mod   = $this->get("total_purchases_mod");
        $total = $this->get("total_purchases");
        $value = $this->totals[$order["profile_id"]]["total"];
        if (!empty($mod) && !empty($total) && !$this->cmp($mod, $value, $total)) {
            $order["passed"] = false;
            return;
        }
        // apply number of orders filter
        $mod   = $this->get("number_mod");
        $qty   = $this->get("number_qty");
        $value = $this->totals[$order["profile_id"]]["number"];
        if (!empty($mod) && !empty($qty) && !$this->cmp($mod, $value, $qty)) {
            $order["passed"] = false;
            return;
        }
    } // }}}

    function cmp($mod, $val1, $val2) // {{{
    {
        if ( ($mod == "less"  && $val1 >= $val2) ||
             ($mod == "more"  && $val1 <= $val2) ||
             ($mod == "equal" && $val1 != $val2))
        {
            return false;
        }
        return true;
    } // }}}
    
    function filterProduct($product) // {{{
    {
        // 3 filters available
        for ($i = 1; $i <= 3; $i++) {
            $id  = $this->get("product{$i}_id");
            $mod = $this->get("product{$i}_mod");
            $qty = $this->get("product{$i}_qty");
            if (!empty($id) && $product["product_id"] == $id && !empty($mod) && !empty($qty)) {
                return $this->cmp($mod, $product["amount"], $qty);
            }
        }
        // no filter found, assume as passed
        return true;
    } // }}}

    function sumOrders($item) // {{{
    {
        $orderID = $item["order_id"];
		$created = false;
        // summarize order details
        if (!isset($this->orders[$orderID])) {
            // init array w/ orders data
		    $created = true; 
            $this->orders[$orderID] = array(
                "order_id"   => $orderID,
                "products"   => array(
                                    array(
                                        "product_id" => $item["product_id"],
                                        "amount"  => $item["amount"],
                                    ),
                                ),
                "profile_id" => $item["orig_profile_id"],
                "total"      => $item["total"],
                "number"     => 1,
                "passed"     => true, // for product filter
                );
        } else {
            // update data array
            $this->orders[$orderID]["number"]++;
            $found = false;
            foreach ($this->orders[$orderID]["products"] as $idx => $product) {
                if ($product["product_id"] == $item["product_id"]) {
                    $info = $this->orders[$orderID]["products"][$idx];
                    $info["amount"] += $item["amount"];
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $this->orders[$orderID]["products"][] = array(
                    "product_id" => $item["product_id"],
                    "amount"  => $item["amount"],
                );
            }
        }
        // summarize profile->order details
        $profileID = $item["orig_profile_id"]; 
        if (!isset($this->totals[$profileID])) {
            $this->totals[$profileID] = array(
                    "total"  => $this->orders[$orderID]["total"],
                    "number" => 1
                    );
        } else {
            $this->totals[$profileID]["total"] += $this->orders[$orderID]["total"];
            if ($created) $this->totals[$profileID]["number"]++;
        }
    } // }}}

    function getInMembership($table) // {{{
    {
        $ms = $this->get("membership");
        if (!is_null($ms)) {
            if ($ms == '%') {
                return " AND $table.membership LIKE '%' ";
            } else {
                return " AND $table.membership='$ms' ";
            }    
        }
        return "";
    } // }}}

    function getInCities($table) // {{{
    {
        $city = $this->get("city");
        if (empty($city)) {
            return "";
        }
        $city = addslashes($city);
        $cities = "";
        $location = $this->get("location_address");
        if ($location == "billing" || $location == "") {
            $cities .= " AND $table.billing_city='$city' ";
        }
        if ($location == "shipping" || $location == "") {
            $cities .= " AND $table.shipping_city='$city' ";
        }
        return $cities; 
    } // }}}
    
    function getInCountries($table) // {{{
    {
        $countries = "";
        $cc = $this->get("country");
        $location = $this->get("location_address");
        if (!empty($cc)) {
            $cc = addslashes($cc);
            if ($location == "billing" || $location == "") {
                $countries .= " AND $table.billing_country='$cc' ";
            }
            if ($location == "shipping" || $location == "") {
                $countries .= " AND $table.shipping_country='$cc' ";
            }
        }
        return $countries;
    } // }}}
    
    function getInStates($table) // {{{
    {
        $states = "";
        $sc = $this->get("state");
        $location = $this->get("location_address");
        if ($sc != 0) {
            if ($location == "billing" || $location == "") {
                $states .= " AND $table.billing_state=$sc ";
            }
            if ($location == "shipping" || $location == "") {
                $states .= " AND $table.shipping_state=$sc ";
            }
        }
        return $states;
    } // }}}
    
    // SELECT extra fields {{{
    function getSelect($ot, $it, $pt)
    {
        $select = "";
        if ($discountCoupon = $this->get("discountCoupon") != null) {
            $select .= " , $ot.discountCoupon ";
        }
        if ($gcid = $this->get("gcid") != null) {
            $select .= " , $ot.gcid ";
        }
        return $select;
    }
    function getFrom()
    {
        return "";
    }
    function getWhere($ot, $it, $pt)
    {
        $where = "";
        if (($discountCoupon = $this->get("discountCoupon")) != null) {
        	$dc = new XLite_Module_Promotion_Model_DiscountCoupon();
        	if (is_object($dc) && $dc->find("coupon='$discountCoupon' AND order_id='0'")) {
            	$where .= " AND $ot.discountCoupon='".$dc->get("coupon_id")."' ";
			}
        }
        if (($gcid = $this->get("gcid")) != null) {
            $where .= " AND $ot.gcid='".$gcid."' ";
        }
        return $where;
    } // }}}

    function getProductIDs() // {{{
    {
        $ids = array();
        $res = array();
        $res[] = $this->get("product1_id");
        $res[] = $this->get("product2_id");
        $res[] = $this->get("product3_id");
        foreach ($res as $p) {
            if (!empty($p)) {
                $ids[] = $p;
            }
        }
        return $ids;
    } // }}}

	function hasProduct($user_id, $product_id) // {{{
	{
		$product = new XLite_Model_Product();
		$fromDate = $this->getComplex('period.fromDate');
		$toDate   = $this->getComplex('period.toDate');
		$ot = $product->db->getTableByAlias("orders");
		$it = $product->db->getTableByAlias("order_items");

		$sql = 	"SELECT $it.product_id ".
				"	FROM $ot, $it ". 
                "	WHERE $it.order_id=$ot.order_id ".
				"		AND $ot.orig_profile_id=$user_id ".
                "       AND ($ot.status='C' OR $ot.status='P') ".
                "       AND $ot.date BETWEEN $fromDate AND $toDate ";
				
		$product_ids = (array)$product->db->getAll($sql);
		$products = array();
		foreach($product_ids as $found_pid) {
			$products[] = $found_pid["product_id"];
		}
		
		$product_ids = array_unique($products);
		return in_array($product_id, $product_ids);
	} // }}}

	function hasCategory($user_id, $category_id) // {{{
	{
		$product = new XLite_Model_Product();
		$fromDate = $this->getComplex('period.fromDate');
		$toDate   = $this->getComplex('period.toDate');
		$ot = $product->db->getTableByAlias("orders");
		$it = $product->db->getTableByAlias("order_items");
		$lt	= $product->db->getTableByAlias("product_links");

		$sql = 	"SELECT $lt.category_id ".
				"	FROM $ot, $it, $lt ". 
                "	WHERE $it.order_id=$ot.order_id ".
				"		AND $ot.orig_profile_id=$user_id ".
				"		AND $lt.product_id=$it.product_id ".
                "       AND ($ot.status='C' OR $ot.status='P') ".
                "       AND $ot.date BETWEEN $fromDate AND $toDate ";
				
		$categories = (array)$product->db->getAll($sql);
		$category_ids = array();
		foreach($categories as $category_info) {
			$category_ids[] = $category_info["category_id"];
		}
		$categories = array_unique($category_ids);
		
		return (in_array($category_id, $categories));
	} // }}}

	function hasProducts($u_id, $product_ids) // {{{
	{
		foreach($product_ids as $pid) {
			if (!$this->hasProduct($u_id, $pid)) {
				return false;
			}
		}
		return true;
	} // }}}

	function hasCategories($u_id, $category_ids) // {{{
	{
		if (!is_array($category_ids)) {
			return true;
		}
		foreach($category_ids as $cid) {
			if (!$this->hasCategory($u_id, $cid)) {
				return false;
			}
		}
		return true;
	} // }}}

	function getFullOwners($uids) // {{{
	{
		$result = array();
		foreach ($uids as $uid) {
			if ($this->hasCategories($uid, $this->get("selected_categories")) &&
				$this->hasProducts($uid, $this->getProductIDs())) {
				$result[] = $uid;
			}
		}
		return $result;
	} // }}}

}

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

/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

/**
* DiscountCoupon description.
*
* @package Module_Promotion
* @access public
* @version $Id$
*/
class XLite_Module_Promotion_Model_DiscountCoupon extends XLite_Model_Abstract
{	
	public $fields = array(
		"coupon_id" => "", // coupon unique id
		"parent_id" => "", // parent coupon id
		"new_link_mode" => 0, // link mode flag: 0 - via the same 'coupon' field, 1 - via 'parent_id' field, pointing to parent
		"order_id" => 0, // order to which the coupon belongs
		"coupon" => "", // coupon #
		"status" => "A", // Active, Disabled, Used
		"times" => 1, // times to use
		"timesUsed" => 0, // how many times it is used
		"discount" => 0,
		"type" => "absolute", // absolute, percent, freeship
		"expire" => 0,
		"applyTo" => "total", // total, product, category
		"minamount" => 0, // minimum order total
		"product_id" => 0, // discount on product
		"category_id" => 0, // discount on category
		);	

	public $alias = "discount_coupons";	
	public $autoIncrement = "coupon_id";	
	public $order = null;	
	public $product = null;	
	public $category = null;	
	public $_oldCouponCode = null;

	function checkCondition($order)
	{
		switch ($this->get("applyTo")) {
			case "total":
				return ( $order->get("subtotal") >= $this->get("minamount") ) ? true : false;
			break;

			case "product":
				$cart = XLite_Model_Cart::getInstance();
				foreach($cart->get("items") as $item) {
					if ($item->get("product_id") == $this->get("product_id")) {
						return true;
					}
				}
				return false;
			break;

			case "category":
        		require_once LC_MODULES_DIR . 'Promotion' . LC_DS . 'encoded.php';

				$category = $this->get("category");
				$cart = XLite_Model_Cart::getInstance();
				foreach($cart->get("items") as $item) {
					if (func_in_category_recursive($item->get("product"), $category)) {
						return true;
					}
				}
				return false;
			break;

			default:
				return false;
		}
	}

    /**
    * Retrieves the origination DiscountCoupon object.
    */
    function getPeer()
    {
        $peer = new XLite_Module_Promotion_Model_DiscountCoupon();
		$condition = "coupon='".$this->get("coupon")."' AND parent_id='0'";
		if ($this->get("new_link_mode")) {
			$condition = "coupon_id='".$this->get("parent_id")."'";
		}

        if (!$peer->find($condition)) {
            $peer = null;
        }
        return $peer;
    }
	
	function getCategory()
	{
	    if (!is_null($this->category)) {
	    	return $this->category;
	    }

		if ($this->get("category_id")) {
            $this->category = new XLite_Model_Category($this->get("category_id"));
            if (is_object($this->category)) {
                if (!$this->category->find("category_id='".$this->get("category_id")."'")) {
            		$this->category = null;
            	}
            } else {
    			$this->category = null;
            }
        } else {
    		$this->category = null;
        }
		return $this->category;
	}	

	function isTimesOverused()
	{
		return ($this->get("times") > $this->get("timesUsed")) ? false : true;
	}

	function getOrder()
	{
	    if (!is_null($this->order)) {
	    	return $this->order;
	    }

		if ($this->get("order_id")) {
            $this->order = new XLite_Model_Order($this->get("order_id"));
            if (is_object($this->order)) {
                if (!$this->order->find("order_id='".$this->get("order_id")."'")) {
            		$this->order = null;
            	}
            } else {
    			$this->order = null;
            }
        } else {
			$this->order = null;
        }
		return $this->order;
	}

	function getProduct()
	{
	    if (!is_null($this->product)) {
	    	return $this->product;
	    }

		if ($this->get("product_id")) {
            $this->product = new XLite_Model_Product($this->get("product_id"));
            if (is_object($this->product)) {
                if (!$this->product->find("product_id='".$this->get("product_id")."'")) {
            		$this->product = null;
            	}
            } else {
    			$this->product = null;
            }
        } else {
    		$this->product = null;
        }
		return $this->product;
	}

	function getChildren($coupon = null) // {{{
	{
		if (is_null($this->_children)) {
			if (empty($coupon)) $coupon = $this->get('coupon');
			$condition = "coupon='$coupon' AND (parent_id='0' OR parent_id='".$this->get("coupon_id")."')";
			if ($this->get("new_link_mode")) {
				$condition = "parent_id='".$this->get("coupon_id")."'";
			}
			$this->_children = (array) $this->findAll("order_id<>'0' AND $condition", "order_id");
			foreach($this->_children as $child_key => $child) {
				if (is_null($child->get("order")) || $child->getComplex('order.status') == "T") {
					unset($this->_children[$child_key]);
				}
			}
		}
		return $this->_children;
	} // }}}

	function getChildrenCount()
	{
		if (!isset($this->_children_count)) {
			$o = new XLite_Model_Order();
			$coupons_table = $this->getTable();
			$orders_table = $o->getTable();

			if (empty($coupon)) $coupon = $this->get('coupon');
			$condition = "coupon='$coupon' AND (parent_id='0' OR parent_id='".$this->get("coupon_id")."')";
			if ($this->get("new_link_mode")) {
				$condition = "parent_id='".$this->get("coupon_id")."'";
			}
			$count_query = <<<EOT
			SELECT count(*) 
			FROM $coupons_table coupons 
			LEFT OUTER JOIN $orders_table orders ON coupons.order_id=orders.order_id 
			WHERE coupons.order_id<>'0' 
			AND $condition 
			AND orders.status<>'T'
EOT;
			$this->_children_count = $this->db->getOne($count_query); 
		}
		return $this->_children_count;
	}

	function set($name, $value) // {{{
	{
		if (($name == "coupon") && (is_null($this->_oldCouponCode))) {
			if ($value != $this->get($name)) {
				$this->_oldCouponCode = $this->get($name);
			}
		}
		parent::set($name, $value);
	} // }}}

	function update() // {{{
	{
		if (($this->get("order_id") == 0) && (!$this->get("new_link_mode")) && 
			($this->_oldCouponCode != $this->get('coupon')) &&
			(!is_null($this->_oldCouponCode))) {
			$this->reattachChildren($this->_oldCouponCode);
			$this->set("new_link_mode", 1);
		}
		parent::update();
	} // }}}

	function create() // {{{
	{
		// all new coupons must be in the new link mode by default
		$this->set("new_link_mode", 1);
		parent::create();
	} // }}}

	/*
	 * Reattach children to to new link mode
	 */
	function reattachChildren($coupon) // {{{
	{
		$children = $this->getChildren($coupon);
		foreach ((array)$children as $child) {
			$child->set("new_link_mode", 1); // in order to avoid recursive calls
			$child->set("parent_id", $this->get("coupon_id"));
			$child->update();
		}
	} // }}}
    
    function isExpired() {
        return $this->get("expire") < time();
    }
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

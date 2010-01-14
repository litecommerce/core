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

if (!defined('GC_OK')) {
    define('GC_OK', 2);
}
if (!defined('GC_DISABLED')) {
    define('GC_DISABLED', 3);
}

/**
* Module_GiftCertificates_Order description.
*
* @package Module_GiftCertificates
* @access public
* @version $Id$
*/
class XLite_Module_GiftCertificates_Model_Order extends XLite_Model_Order implements XLite_Base_IDecorator
{	
	public $gc = null;	

    public $skipShippingCostRecursion = false;	

    public $shippedCertificates = null;	

    public $shippedItems = null;	

    public $shippingCost = null;

    public function __construct($param = null)
    {
        // new fields
		$this->fields["gcid"] = ""; // gift certificate unique ID or 0
		$this->fields["payedByGC"] = ""; // how much of the order is payed by GC
        parent::__construct($param);
    }

    /**
    * Take into account GC while calculating order's total.
    */
	function calcTotal()
	{
		$this->shippingCost = null;
		parent::calcTotal();
		if (($gcid = $this->get("gcid")) != "") {
			if ($this->xlite->is("adminZone") && ($this->get("payedByGC") > 0)) {
				$this->_payedByGC = min($this->get("total"), $this->get("payedByGC"));
			} else {
				$this->_payedByGC = min($this->get("total"), $this->get("gc.debit"));
			}
			$this->set("total", $this->get("total") - $this->_payedByGC);
		} else {
			$this->_payedByGC = 0;
		}
		$this->set("payedByGC", $this->_payedByGC);
	}

	function calcTotals()
	{
		// for PHP 5.2.4 in order not to affect the items during tax calculation, the cache must be cleaned up
		$this->refresh("items");
		parent::calcTotals();
		$this->refresh("items");
	}

	function getGC()
	{
        if (is_null($this->gc)) {
            if ($this->get("gcid")) {
                $this->gc = new XLite_Module_GiftCertificates_Model_GiftCertificate($this->get("gcid"));
            } else {
                $this->gc = null;
            }
        }
        return $this->gc;
	}

    /**
    * Sets a Gift Certificate object for the order. null to unset.
    */
	function setGC($gc)
    {
        $this->gc = $gc;
        if (is_null($gc)) {
            $this->set("gcid", '');
            $this->calcTotals();
        } else {
            if ($gc->get("status") != "A") {
                return GC_DISABLED;
            }
            $this->set("gcid", $gc->get("gcid"));
            $this->calcTotals();
        }
        return GC_OK;
    }
    
	function processed()
	{
		parent::processed();
		$this->setGCStatus("A");
    }

    function checkedOut()
    {
		if ($_REQUEST['target'] != 'callback') {
			$this->calcTotals();
		}
        parent::checkedOut();
		$this->changeGCDebit(-1);
	}

	function declined()
	{
		parent::declined();
		$this->setGCStatus("P");
    }

    function queued()
    {
        parent::queued();
		$this->setGCStatus("P"); // becomes pending
    }

    function uncheckedOut()
    {
        parent::uncheckedOut();
		$this->changeGCDebit(1);
		$this->setGCStatus("D");
	}

	function changeGCDebit($sign)
	{
        // call crypted code
        require_once LC_MODULES_DIR . 'GiftCertificates' . LC_DS . 'encoded.php';
        GiftCertificates_changeGCDebit($this, $sign);
    }

	function setGCStatus($status)
	{
		$items = $this->get("items");
		for($i=0; $i<count($items); $i++) {
            $item = $items[$i];
			if (!is_null($item->get("gc"))) {
				$gc = $item->get("gc");
				$gc->set("status", $status);
				$gc->update();
			}
		}
	}

	function checkout()
	{
		if (!is_null($this->get("gc"))) {
			// re-calculate total during checkout to prevemt double-payment
			$this->calcTotals();
			$this->update();
		}
		parent::checkout();
	}

    function get($name)
    {
        // arounding problem in the "skins/default/en/shopping_cart/totals.tpl"
    	if ($name == "shipping_cost") {
    	    if (!$this->skipShippingCostRecursion) {
        		$this->skipShippingCostRecursion = true;
        		$cost = $this->getShippingCost();
        		$this->skipShippingCostRecursion = false;
        		return $cost;
        	}
		}
		return parent::get($name);
	}

    function getShippingCost()
    {
    	if ( is_null($this->shippingCost) ) {
            // LiteCommerce 1.2.2 bug fix
            if (!$this->is("shipped")) {
            	$this->shippingCost = 0;
                return false;
            }
            // find shipped certificates
            $count = $this->countShippedCertificates();
            if ($count) {
                $this->shippingCost = $this->hasShippedItems() ? parent::getShippingCost() : 0;
               	$this->shippingCost += $count*$this->config->get("GiftCertificates.shippingCost");
            } else {
                $this->shippingCost = parent::getShippingCost();
            }
        }
		return $this->shippingCost;
    }

    function isShipped()
    {
        if (parent::isShipped()) {
            return true;
        }
        return $this->countShippedCertificates()>0;
    }

    function countShippedCertificates()
    {
    	if (!isset($this->shippedCertificates)) {
            $count = 0;
            foreach ($this->get("items") as $item) {
                if (!is_null($item->get("gc")) && $item->get("gc.send_via") == "P") {
                    $count++;
                }
            }
            $this->shippedCertificates = $count;
        }
        return $this->shippedCertificates;
    }    

    function hasShippedItems()
    {
    	if (!isset($this->shippedItems)) {
			$this->shippedItems = false;
        	foreach ($this->get("items") as $item) {
            	if ($item->is("shipped")) {
            		$this->shippedItems = true;
                	break;
            	}
            }
        }
        return $this->shippedItems;
    } 

    function isShippingAvailable()
    {
        if ($this->is("shipped"))
            return ($this->getItemsCount() == $this->countShippedCertificates()) ? true : parent::isShippingAvailable();
        else
        	return parent::isShippingAvailable();
    }

    function isShippingDefined()
    {
    	if (!parent::isShippingDefined() && $this->is("shipped"))
        	return true;
        else
        	return parent::isShippingDefined();
    }

    function set($property, $value)
    {
    	if ($property != "shippingTaxes") {
    		parent::set($property, $value);
    	} else {
    		if ($this->getItemsCount() == $this->countShippedCertificates() && !$this->config->get("Taxes.prices_include_tax")) {
    			parent::set($property, array());
    		} else {
    			parent::set($property, $value);
    		}
    	}
    }

    function hasGC($gcid)
    {
        $has = false;
        if ($gcid) {
            $items = $this->get("items");
            for ($i=0; $i<count($items); $i++) {
                if ($items[$i]->get("gcid") == $gcid) {
                    $has = true;
                    break;
                }
            }
        }
        return $has;
    }
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

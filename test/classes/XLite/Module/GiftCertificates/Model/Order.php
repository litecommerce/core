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
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Order
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_GiftCertificates_Model_Order extends XLite_Model_Order implements XLite_Base_IDecorator
{
    const GC_OK = 2;
    const GC_DISABLED = 3;
    
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
                $this->_payedByGC = min($this->get("total"), $this->getComplex('gc.debit'));
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
     * Apply gift certificate
     * 
     * @param mixed $gc Gift certificate
     *  
     * @return integer Operation status
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setGC($gc)
    {
        $result = 0;

        $this->gc = $gc;

        if (is_null($gc)) {
            $this->set('gcid', '');
            $this->calcTotals();
            $result = self::GC_OK;

        } elseif ($gc instanceof XLite_Module_GiftCertificates_Model_GiftCertificate) {

            if ('A' == $gc->get('status') && 0 < $gc->get('debit')) {
                $this->set('gcid', $gc->get('gcid'));
                $this->calcTotals();
                $result = self::GC_OK;

            } else {
                $result = self::GC_DISABLED;
            }
        }

        return $result;
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
                   $this->shippingCost += $count*$this->config->getComplex('GiftCertificates.shippingCost');
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
                if (!is_null($item->get("gc")) && $item->getComplex('gc.send_via') == "P") {
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
            if ($this->getItemsCount() == $this->countShippedCertificates() && !$this->config->getComplex('Taxes.prices_include_tax')) {
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

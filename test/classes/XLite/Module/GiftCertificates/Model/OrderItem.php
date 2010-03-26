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
 * Order item
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_GiftCertificates_Model_OrderItem extends XLite_Model_OrderItem implements XLite_Base_IDecorator
{    
    protected $gc = null;

    public function __construct()
    {
        $this->fields["gcid"] = ""; // Gift Certificate unique ID
        parent::__construct();
    }

    function getGC()
    {
        if (is_null($this->gc)) {
            if (parent::get("gcid")) {
                $this->gc = new XLite_Module_GiftCertificates_Model_GiftCertificate(parent::get("gcid"));
            } else {
                $this->gc = null;
            }
        }
        return $this->gc;
    }

    function getKey()
    {
        if ($this->get("gcid")) {
            return "GC".$this->get("gcid");
        } else {
            return parent::getKey();
        }
    }
    function getTaxableTotal()
    {
        if (!is_null($this->getGC())) {
            return 0;
        }
        return parent::getTaxableTotal();
    }
    function isShipped()
    {
        if (!is_null($this->getGC())) {
            return false;
        }
        return parent::isShipped();
    }
    function getDescription()
    {
        if (!is_null($this->getGC())) { 
            return "Gift certificate # ".$this->get("gcid");
        }
        return parent::getDescription();
    }
    function getDiscountablePrice()
    {
        return is_null($this->getGC()) ? parent::getDiscountablePrice() : 0;
    }
    function getShortDescription($limit = 30)
    {
        if (!is_null($this->getGC())) { 
            return "GC #".$this->get("gcid");
        }
        return parent::getShortDescription($limit);
    }
    function get($name)
    {
        if (!is_null($this->getGC())) {
            if ($name == 'name')   return $this->getDescription();
            if ($name == 'brief_description') return $this->getDescription();
            if ($name == 'description') return $this->getDescription();
            if ($name == 'sku')   return "";
            if ($name == 'amount') return 1;
        }
        return parent::get($name);
    }

    function delete()
    {
        // remove disabled GCs
        if (!is_null($this->getGC()) && $this->getGC()->get('status') == "D") {
            $this->getGC()->delete();
        }
        parent::delete();
    }

    function isValid()
    {
        $gc = $this->getGC();
        if (!is_null($gc)) {
            return $this->getGC()->is('exists');
        }
        return parent::isValid();
    }
                    
    function setGC($gc)
    {
        $this->gc = $gc;
        if (is_null($gc)) {
            $this->set("gcid", "");
        } else {
            $this->set("gcid", $gc->get("gcid"));
            $this->set("product_id", "");
            $this->set("price", $gc->get("amount"));
        }
    }

    function hasOptions()
    {
        // check if the ProductOptions module is on
        if (is_null($this->xlite->getComplex('mm.activeModules.ProductOptions'))) {
            return false;
        }
        if (is_null($this->get("product"))) {
            return false;
        }
        return parent::hasOptions();
    }

    function isUseStandardTemplate()
    {
        return $this->get("gcid") == "" && parent::isUseStandardTemplate();
    }

    /**
     * Get item URL 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getURL()
    {
        $url = false;

        if ($this->get('gcid')) {
            $url = XLite_Core_Converter::getInstance()->buildURL(
                'add_gift_certificate',    
                '',
                array(
                    'gcid' => $this->get('gcid'),
                )
            );

        } else {
            $url = parent::getURL();
        }

        return $url;
    }

}

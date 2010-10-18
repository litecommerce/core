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

namespace XLite\Module\WholesaleTrading\Model;

/**
 * Product
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Product extends \XLite\Model\Product implements \XLite\Base\IDecorator
{
    /**
     * Perform direct sale check if the product does not exist
     * TODO - check if it's really needed or make it protected
     * 
     * @var    bool
     * @access public
     * @since  3.0.0
     */
    public $_checkExistanceRequired = false;


    /**
     * Return value of min/max purchase limit 
     * 
     * @param string $type  "min" or "max"
     * @param int    $value value to use if the purchase limit is not set
     *  
     * @return int
     * @access protected
     * @since  3.0.0
     */
    protected function getPurchaseLimitValue($type, $value)
    {
        $limit = $this->getPurchaseLimit();

        return ($limit && 0 < $limit->get($type)) ? $limit->get($type) : $value;
    }


    /**
     * Minimal available amount
     *
     * @return int
     * @access public
     * @since  3.0.0
     */
    public function getMinPurchaseLimit()
    {
        return $this->getPurchaseLimitValue('min', parent::getMinPurchaseLimit());
    }

    /**
     * Maximal available amount
     *
     * @return int
     * @access public
     * @since  3.0.0
     */
    public function getMaxPurchaseLimit()
    {
        return $this->getPurchaseLimitValue('max', parent::getMaxPurchaseLimit());
    }


    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->fields['selling_membership'] = 0;
        $this->fields['validaty_period'] = "";
    }
    
    function getShowExpandedOptions()
    {
        return $this->xlite->get('ProductOptionsEnabled')
            && $this->hasOptions()
            && (bool)$this->get('expansion_limit');
    }

    function hasExpandedOptions()
    {
        return (count($this->get('expandedItems')) > 0);
    }
    
    function getExpandedOptionsNames()
    {
        if (isset($this->_expandedOptionsNames)) {
            return $this->_expandedOptionsNames;
        }
        
        $this->_expandedOptionsNames = array();
        $options = $this->get('productOptions');
        foreach ($options as $opt) {
            $type = strtolower($opt->get('opttype'));
            if ($type == "radio button" || $type == "selectbox") {
                $this->_expandedOptionsNames []= $opt->get('opttext');
            }
        }
        return $this->_expandedOptionsNames;
    }

    function getFlatOptions()
    {
        if (isset($this->_flatOptions)) {
            return $this->_flatOptions;
        }
        
        $this->_flatOptions = array();
        $options = $this->get('productOptions');
        foreach ($options as $opt) {
            $type = strtolower($opt->get('opttype'));
            if ($type != "radio button" && $type != "selectbox") {
                $this->_flatOptions []= $opt;
            }
        }
        return $this->_flatOptions;
    }
    
    function getExpandedItems()
    {
        if (isset($this->expandedProductOptions)) {
            return $this->expandedProductOptions;
        }
        
        $found_options = array();
        
        if (!$this->xlite->get('ProductOptionsEnabled') || !$this->hasOptions()) {
            $this->expandedProductOptions = $found_options;
            return $found_options;
        }
        $options = $this->get('productOptions');
        $dst = array();
        foreach ($options as $option) {
            $type = strtolower($option->get('opttype'));
            if ($type == "radio button" || $type == "selectbox") {
                $dst []= $option->get('productOptions');
            }
        }

        if (empty($dst)) {
            $this->expandedProductOptions = $found_options;
            return $found_options;
        }

        $this->getSelections($dst, $found_options);

        // remove options marked as exceptions {{{
        $exceptions_list = $this->get('optionExceptions');
        if (!empty($exceptions_list)) {
            foreach ($exceptions_list as $k => $v) {
                $exceptions = array();
                $exception = $v->get('exception');
                $columns = explode(";", $exception);
                // Trim exceptions
                foreach ($columns as $subvalue) {
                    $exception = explode ("=", $subvalue);
                    $exception_optclass = trim($exception[0]);
                    $exception_option = trim($exception[1]);
                    $exceptions[$exception_optclass] = $exception_option;
                }
            }

            $found = false;
            do {
                $found = false;
                for ($i = 0; $i < count($found_options); $i++) {
                    $opt_array = array();
                    foreach ($found_options[$i] as $_opt) {
                        $opt_array[$_opt->class] = $_opt->option;
                    }
                    $ex_size = sizeof($exceptions);
                    $ex_found = 0;
                    foreach ($exceptions as $subkey => $subvalue) {
                        if ($opt_array[$subkey] == $subvalue) {
                            $ex_found ++;
                        }
                    }
                    if ($ex_found == $ex_size) {
                        array_splice($found_options, $i, 1);
                        $found = true;
                        break;
                    }
                }
            } while ($found != false);
        }


        $this->expandedProductOptions = $found_options;
        return $this->expandedProductOptions;
    }

    function getFullPrice($amount, $optionIndex = null, $use_wholesale_price = true)
    {
        if (!$this->is('priceAvailable') && !$this->xlite->is('adminZone')) {
            return $this->config->WholesaleTrading->price_denied_message;
        }

        $wholesale_price = false;
        if ($use_wholesale_price) {
            $wp = new \XLite\Module\WholesaleTrading\Model\WholesalePricing();
            $profile = $this->auth->get('profile');
            $membership = is_object($profile) ? " OR membership='" . $profile->get('membership') . "'" : "";
            $wholesale_prices = $wp->getProductPrices($this->get('product_id'), $amount, $membership);
            if (count($wholesale_prices) != 0) {
                $wholesale_price = $wholesale_prices[count($wholesale_prices) - 1]->get('price');
                $this->set('price', $wholesale_price);
            }
        }
        $price = $this->get('listPrice');
        if (!is_null($optionIndex)) {
            $surcharge = 0;
            $originalPrice = $price;

            $opts = $this->get('expandedItems');
            foreach ($opts[$optionIndex] as $option) {
                $po = new \XLite\Module\ProductOptions\Model\ProductOption();
                $po->set('product_id', $this->get('product_id'));

                $modifiedPrice = ($wholesale_price === false)?($po->_modifiedPrice($option)):($po->_modifiedPrice($option, false, $wholesale_price));
                $surcharge += $modifiedPrice - $originalPrice;
            }

            $price = $originalPrice + $surcharge;
        }

        return $price;
    }

    function getAmountByOptions($optionsIndex)
    {
        if (!isset($optionsIndex)) {
            return -1; // -1 means infinity
        }

        $options_arr = $this->get('expandedItems');

        if (!(isset($options_arr[$optionsIndex]) && is_array($options_arr[$optionsIndex]))) {
            return -1; // -1 means infinity
        }

        foreach ($options_arr[$optionsIndex] as $_opt) {
            $option_keys[] = sprintf("%s:%s", $_opt->class, $_opt->option);
        }

        $key = $this->get('key')."|".implode("|", $option_keys);
        $inventory = new \XLite\Module\InventoryTracking\Model\Inventory();
        $inventories = $inventory->findAll("inventory_id LIKE '".$this->get('product_id')."|%' AND enabled=1", "order_by");
        foreach ($inventories as $i) {
            if ($i->keyMatch($key)) {
                return $i->get('amount');
            }
        }

        return -1; // -1 means infinity
    }

    function _available_action($action)
    {
        $productId = $this->get('product_id');

        $result = \XLite\Model\CachingFactory::getObject(
            __METHOD__ . $productId,
            '\XLite\Module\WholesaleTrading\Model\ProductAccess',
            array($productId)
        );

        // It's the hack to prevent multiple readings for different actions
        if ($result->isPersistent && !$result->isRead) {
            $result->read();
            $result->isRead = true;
        }
        
        return \XLite\Model\CachingFactory::getObjectFromCallback(
            __METHOD__ . $productId . $action,
            $result,
            'groupInAccessList',
            array($this->auth->getComplex('profile.membership'), $action . '_group')
        );
    }
    
    function isShowAvailable()
    {
        return $this->checkDirectSaleAvailable() ? true : $this->_available_action('show');
    }
    
    function isPriceAvailable()
    {
        if ($this->checkDirectSaleAvailable()) {
            return true;
        }

        return $this->is('showAvailable') ? $this->_available_action('show_price') : false;
    }

    function isSaleAvailable()
    {
        if ($this->checkDirectSaleAvailable()) {
            return true;
        }

        return $this->is('priceAvailable') ? $this->_available_action('sell') : false;
    }

    function assignDirectSaleAvailable($assign=true)
    {
        $access = $this->session->get('DirectSaleAvailable');
        if (!is_array($access)) {
            $access = array();
        }
        $access[$this->get('product_id')] = $assign;
        $this->session->set('DirectSaleAvailable', $access);
    }

    function checkDirectSaleAvailable()
    {
        $access = $this->session->get('DirectSaleAvailable');
        if (!is_array($access)) {
            $access = array();
        }

        return (isset($access[$this->get('product_id')]) ? $access[$this->get('product_id')] : false);
    }

    function isDirectSaleAvailable()
    {
        if ($this->config->WholesaleTrading->direct_addition) {
            $this->assignDirectSaleAvailable($this->_available_action('sell'));

            return $this->_available_action('sell');

        }

        return $this->isSaleAvailable();
    }

    function filter()
    {
        if ($this->xlite->is('adminZone')) {
            return parent::filter();
        }

        if (parent::filter()) {
            return $this->is('showAvailable');

        }

           return $this->checkDirectSaleAvailable();
    }

    function isExists()
    {
        $exists = parent::isExists();

        return ((!$exists) && $this->_checkExistanceRequired && $this->_available_action('sell')) ?  true : $exists;
    }

    function get($name)
    {
        if ($name == "price" && !$this->is('priceAvailable') && !$this->xlite->is('adminZone')) {
            return $this->config->WholesaleTrading->price_denied_message;
        }
        return parent::get($name);
    }

    function getListPrice()
    {
        return (!$this->is('priceAvailable') && !$this->xlite->is('adminZone'))
            ? $this->config->WholesaleTrading->price_denied_message
            : parent::getListPrice();
    }

    function hasWholesalePricing()
    {
        $this->_avail_wholesale_pricing = $this->get('wholesalePricing');

        return count($this->_avail_wholesale_pricing) > 0;
    }

    function getWholesalePricing()
    {
        if (is_null($this->wholesale_pricing)) {
            $wp = new \XLite\Module\WholesaleTrading\Model\WholesalePricing();

            $sqlStr = "product_id = " . $this->get('product_id');
            $sqlStr .= $this->auth->isLogged()
                ? " AND (membership = 0 OR membership = '" . $this->auth->getProfile()->getMembershipId() . "')"
                : " AND membership = 0";
            $wholesale_pricing = $wp->findAll($sqlStr);

            $wholesale_pricing_hash = array();
            foreach ($wholesale_pricing as $wpIdx => $wp) {
                if (!isset($wholesale_pricing_hash[$wp->get('amount')])) {
                    $wholesale_pricing_hash[$wp->get('amount')] = $wpIdx;

                } elseif (
                    $this->auth->isLogged()
                    && $this->auth->getProfile()->getMembershipId() == $wp->get('membership')
                    && $wholesale_pricing[$wholesale_pricing_hash[$wp->get('amount')]]->get('membership') == 0
                ) {
                    $wholesale_pricing_hash[$wp->get('amount')] = $wpIdx;
                }
            }

            $this->wholesale_pricing = array();
            foreach ($wholesale_pricing_hash as $wp => $wpIdx) {
                $this->wholesale_pricing[] = $wholesale_pricing[$wpIdx];
            }
        }

        if ($this->config->Taxes->prices_include_tax) {
            $oldPrice = $this->get('price');

            foreach ($this->wholesale_pricing as $wp_idx => $wp) {
                $this->set('price', $wp->get('price'));
                $this->wholesale_pricing[$wp_idx]->set('price', $this->get('listPrice'));
            }

            $this->set('price', $oldPrice);
        }

        return $this->wholesale_pricing;
    }

    function isSellingMembership()
    {
        return 0 != $this->get('selling_membership');
    }

    function getPurchaseLimit()
    {
        $purchase_limit = new \XLite\Module\WholesaleTrading\Model\PurchaseLimit();

        return $purchase_limit->find("product_id =" . $this->get('product_id')) ? $purchase_limit : false;
    }

    function delete()
    {
        // delete product accesses, purchase limits and wholesale prices
        $pa = new \XLite\Module\WholesaleTrading\Model\ProductAccess();
        $pl = new \XLite\Module\WholesaleTrading\Model\PurchaseLimit();
        $wp = new \XLite\Module\WholesaleTrading\Model\WholesalePricing();
        $this->db->query("DELETE FROM ".$pa->getTable(). " WHERE product_id=".$this->get('product_id'));
        $this->db->query("DELETE FROM ".$pl->getTable(). " WHERE product_id=".$this->get('product_id'));
        $this->db->query("DELETE FROM ".$wp->getTable(). " WHERE product_id=".$this->get('product_id'));

        // delete product
        parent::delete();
    }

    /**
    * Remove all unused Wholesale records
    */
    function collectGarbage()
    {
        parent::collectGarbage();

        $products_table = $this->db->getTableByAlias('products');
        $classes = array('ProductAccess', "PurchaseLimit", "WholesalePricing");
        foreach ($classes as $class) {

            $className = '\XLite\Module\WholesaleTrading\Model\\' . $class;
            $obj = new $className();

            $class_table = $obj->getTable();
            $sql = "SELECT DISTINCT(o.product_id) AS product_id FROM ".$class_table." o LEFT OUTER JOIN $products_table p ON o.product_id=p.product_id WHERE p.product_id IS NULL";
            $result = $this->db->getAll($sql);

            if (is_array($result) && count($result) > 0) {
                foreach ($result as $info) {
                    $this->db->query("DELETE FROM ".$class_table. " WHERE product_id='".$info['product_id']."'");
                }
            }
        }
    }
    
    function cloneObject()
    {
        $p = parent::cloneObject();

        $originalId = $this->get('product_id');
        $newId = $p->get('product_id');
        
        if ($this->config->WholesaleTrading->clone_wholesale_productaccess) {
            $productAccess = new \XLite\Module\WholesaleTrading\Model\ProductAccess();
            foreach ($productAccess->findAll("product_id=$originalId") as $access) {
                $foo = new \XLite\Module\WholesaleTrading\Model\ProductAccess();
                $foo->set('product_id', $newId);
                $foo->set('show_group', $access->get('show_group'));
                $foo->set('show_price_group', $access->get('show_price_group'));
                $foo->set('sell_group', $access->get('sell_group'));
                $foo->create();
            }
        }
        
        if ($this->config->WholesaleTrading->clone_wholesale_purchaselimit) {
            $purchaseLimit = new \XLite\Module\WholesaleTrading\Model\PurchaseLimit();
            foreach ($purchaseLimit->findAll("product_id=$originalId") as $limit) {
                $foo = new \XLite\Module\WholesaleTrading\Model\PurchaseLimit();
                $foo->set('product_id', $newId);
                $foo->set('min', $limit->get('min'));
                $foo->set('max', $limit->get('max'));
                $foo->create();
            }
        }
            
        if ($this->config->WholesaleTrading->clone_wholesale_pricing) {
            $wholesalePricing = new \XLite\Module\WholesaleTrading\Model\WholesalePricing();
            foreach ($wholesalePricing->findAll("product_id=$originalId") as $pricing) {
                $foo = new \XLite\Module\WholesaleTrading\Model\WholesalePricing();
                $foo->set('product_id', $newId);
                $foo->set('amount', $pricing->get('amount'));
                $foo->set('price', $pricing->get('price'));
                $foo->set('membership', $pricing->get('membership'));
                $foo->create();
            }
        }
        return $p;
    }

    protected function getSelections($src, &$result, $tmp_val = null)
    {
        if (is_null($tmp_val)) {
            $tmp_val = array();
        }

        if (is_array($src[0])) {
            foreach ($src[0] as $el) {
                $c = array_slice($src, 1);
                $t2 = $tmp_val;
                $t2[] = $el;
                if (count($c) > 0) {
                    $this->getSelections($c, $result, $t2);

                } else {
                    $result[] = $t2;
                }
            }
        }
    }

}


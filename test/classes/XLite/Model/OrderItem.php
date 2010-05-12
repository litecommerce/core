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
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Something customer can put into its cart
 * 
 * @package    XLite
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
class XLite_Model_OrderItem extends XLite_Model_Abstract
{
    /**
     * Check for purchase limits 
     * 
     * @param int $value product amount
     *  
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function correctAmount(&$value)
    {
        $product   = $this->getProduct();
        $origValue = $value;

        $min = $product->getMinPurchaseLimit();
        $max = $product->getMaxPurchaseLimit();

        $value = max($value, $min);
        $value = min($value, $max);

        if ($origValue != $value) {
            XLite_Core_TopMessage::getInstance()->add(
                'Amount of the "' . $product->get('name') . '" product '
                . 'has been corrected: it must be between ' . $min . ' and ' . $max
            );
        }
    }


    /**
     * Sets the specified property value 
     *                                   
     * @param string $property field name
     * @param mixed  $value    field value
     *                                    
     * @return void                       
     * @access public                     
     * @since  3.0                        
     */                                   
    public function set($property, $value)
    {
        if ('amount' == $property) {
            $this->correctAmount($value);
        }

        return parent::set($property, $value);
    }

    /**
     * Return reference to the associated order object
     * 
     * @return XLite_Model_Order
     * @access public
     * @since  3.0.0
     */
    public function getOrder()
    {
        return XLite_Model_CachingFactory::getObject(
            __METHOD__ . $this->_uniqueKey,
            'XLite_Model_Order',
            array($this->get('order_id'))
        );
    }

    /**
     * A reference to the product object 
     * 
     * @return XLite_Model_Product
     * @access public
     * @since  3.0.0
     */
    public function getProduct()
    {
        return XLite_Model_CachingFactory::getObject(__METHOD__ . $this->_uniqueKey, 'XLite_Model_Product', array($this->get('product_id')));
    }

    /**
     * Flag; is this item needs to be shipped
     * 
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isShipped()
    {
        return !$this->getProduct()->is('free_shipping');
    }



    public $fields = array(
        'order_id'    => '',
        'item_id'     => '',
        'orderby'     => 0,
        'product_id'  => '',
        'product_name'  => '',
        'product_sku'  => '',
        'price'       => '0',
        'amount'      => '1');

    public $primaryKey = array('order_id', 'item_id');
    public $alias = 'order_items';
    public $defaultOrder = "orderby";

    public function __construct()
    {
        $this->_uniqueKey = uniqid("order_item_");
        parent::__construct();
    }

    public function setProduct($product)
    {
        $this->product = $product;

        if (is_null($product)) {
            $this->set("product_id", 0);

        } else {
        	if ($this->config->Taxes->prices_include_tax) {
        		$this->set("price", $this->formatCurrency($product->get("taxedPrice")));
        	} else {
            	$this->set("price", $product->get("price"));
        	}

            $this->set("product_id", $product->get("product_id"));
            $this->set("product_name", $product->get("name"));
            $this->set("product_sku", $product->get("sku"));
        }
    }

    function create()
    {
        $this->set("item_id", $this->get("key"));
        parent::create();
    }
    
    /**
    * Returns a scalar key value used to identify items in shopping cart
    */
    function getKey()
    {
        return $this->get("product_id"); // . product_options
    }

    function updateAmount($amount)
    {
        $amount = (int)$amount;
        if ($amount <= 0) {
            $this->getOrder()->deleteItem($this);
        } else {
            $this->set("amount", $amount);
            $this->update();
        }
    }

    /**
     * Update object
     *
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function update()
    {
        $result = parent::update();

        if ($result) {
            $key = $this->getKey();
            if ($key != $this->get('item_id')) {
                $item = new XLite_Model_OrderItem();
                $sql = 'order_id = \'' . $this->get('order_id')
                    . ' \' AND item_id = \'' . addslashes($key) . '\'';

                if ($item->find($sql)) {

                    // Unite items
                    $item->updateAmount($this->get('amount') + $item->get('amount'));
                    $this->getOrder()->deleteItem($this);

                } else {

                    // Update item id
                    $sql = 'UPDATE ' . $this->getTable()
                        . ' SET item_id = \'' . $key . '\''
                        . ' WHERE order_id = \'' . $this->get('order_id')
                        . ' \' AND item_id = \'' . addslashes($this->get('item_id')) . '\'';
                    $this->db->query($sql);
                    $this->set('item_id', $key);
                    $this->getOrder()->updateItem($this);
                }
            }
        }

        return $result;
    }

    function getOrderby()
    {
        $sql = "SELECT MAX(orderby)+1 FROM %s WHERE order_id=%d";
        $sql = sprintf($sql, $this->get("table"), $this->get("order_id"));

        return $this->db->getOne($sql);
    }

    function getDiscountablePrice()
    {
        return $this->get("price");
    }

    function getTaxableTotal()
    {
        return $this->get("total");
    }

    function getPrice()
    {
        return $this->formatCurrency($this->get('price'));
    }

    /**
     * Get item cost
     * 
     * @return float
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTotal()
    {
        return $this->formatCurrency($this->get('price') * $this->get('amount'));
    }

    /**
     * Get item weight 
     * 
     * @return float
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getWeight()
    {
        return $this->getComplex('product.weight') * $this->get('amount');
    }

    function getRealProduct()
    {
        $this->realProduct = null;
    	$product = new XLite_Model_Product();
        $product->find("product_id='".$this->get("product_id")."'");
    	if ($product->get("product_id") == $this->get("product_id")) {
    		$this->realProduct = $product;
            return true;
        }
        return false;
    }

    /**
     * Getter
     * 
     * @param string $name Property name
     *  
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function get($name)
    {
        $result = null;

        if (in_array($name, array('name', 'brief_description', 'description', 'sku'))) {
            $product = $this->get('product');

        	if (
                is_object($product)
                && $this->getRealProduct()
                && (!isset($product->properties[$name]) || !$this->realProduct->get('enabled'))
            ) {
                $result = $this->realProduct->get($name);

            } elseif ($name == 'name' || $name == 'sku') {
                $result = $this->get("product_$name");

            } else {
                $result = $this->getProduct()->get($name);
            }

        } else {
            $result = parent::get($name);
        }

        return $result;
    }
    
    /**
     * Check - has item thumbnail or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function hasThumbnail()
    {
        return (!$this->isValid() && $this->getRealProduct())
            ? $this->realProduct->hasThumbnail()
            : $this->getProduct()->hasThumbnail();
    }

    /**
     * Get item thumbnail URL
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getThumbnailURL()
    {
        return (!$this->isValid() && $this->getRealProduct())
            ? $this->realProduct->getThumbnailURL()
            : $this->getProduct()->getThumbnailURL();
    }

    /**
     * Get item thumbnail
     *
     * @return XLite_Model_Image
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getThumbnail()
    {
        return (!$this->isValid() && $this->getRealProduct())
            ? null
            : $this->getProduct()->getThumbnail();
    }
 
    /**
     * Get item description 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDescription()
    {
        return $this->get('name') . ' (' . $this->get('amount') . ')';
    }

    function getShortDescription($limit = 30)
    {
        if (strlen($this->get("sku"))) {
            $desc = $this->get("sku");
        } else {
            $desc = substr($this->get("name"), 0, $limit);
        }
        if ($this->get("amount") == 1) {
            return $desc;
        } else {
            return $desc . ' (' . $this->get("amount") . ')';
        }
    }

    /**
    * Validates the order item (e.g. the product_id supplied is an existing
    * product id, the amount is greater than zero etc.).
    * You cannot add an invalid item to a cart (prevented in Order::addItem()).
    * This procedure disabled possible work-arounds of standard dialog 
    * restrictions and is not intended to, say, restrict product options
    * and other cases when the cart must show an error/explanation message
    * to customer.
    */
    function isValid()
    {
        $product = $this->get("product");
        if (is_object($product)) {
            $res = $this->isComplex('product.exists') && $this->getComplex('product.product_id') && $this->get("amount")>0;
        } else {
            $res = $this->get("amount")>0;
        }
        return $res;
    }

    /**
    * Decide whether to use shopping_cart/item.tpl widget to display
    * this item. Must be false if you want to use an alternative template.
    */
    function isUseStandardTemplate()
    {
        return true;
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
        return XLite_Core_Converter::getInstance()->buildURL(
            'product',
            '',
            array(
                'product_id' => $this->getProduct()->get('product_id'),
            )
        );
    }

    public function hasOptions()
    {
        return false;
    }
}


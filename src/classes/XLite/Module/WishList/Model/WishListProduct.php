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

namespace XLite\Module\WishList\Model;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class WishListProduct extends \XLite\Model\AModel
{
    public $product   = null;
    public $orderItem = null;
       
    public $fields = array (
        "item_id"     => 0,
        "wishlist_id" => 0,
        "product_id"  => 0,
        "amount"      => 0,
        "purchased"   => 0,
        "options"     => 0,
        "order_by"    => 0,
    );
        
    public $alias        = "wishlist_products";
    public $defaultOrder = "order_by";
    public $primaryKey   = array('item_id',"wishlist_id");


    /**
     * Search wishlist item by product id 
     * 
     * @param int $wishlistId ID of current wishlist
     *  
     * @return self
     * @access public
     * @since  3.0.0
     */
    public function searchWishListItem($wishlistId)
    {
        // Primary keys
        $properties = array(
            'wishlist_id' => $wishlistId,
            'item_id'     => $this->getOrderItem()->getKey(),
        );

        // Search in current wishlist by item ID
        $result = $this->find(\XLite\Core\Converter::buildQuery(array_map('addslashes', $properties), '=', ' AND ', '\''));

        if (!$result) {
            // Assign object properties manually, since they were not assigned in "find()"
            $this->setProperties($properties);
        }

        return array($result, $this);
    }

    function getProduct()  
    {
        if (!isset($this->product)) {
            $this->product = new \XLite\Model\Product($this->get('product_id'));
        }

        return $this->product;
    }

    /**
     * Return the Thumbnai image instance for this product 
     * 
     * @return \XLite\Model\Image
     * @see    ____func_see____
     * @since  3.0.0
     */
    function getThumbnail()
    {
        return ($product = $this->getProduct()) ? $product->getThumbnail() : null;
    }
    
    function getOrderItem() 
    {
        if (!isset($this->orderItem)) {
            $this->orderItem = new \XLite\Model\OrderItem();
            $this->orderItem->set('product', $this->getProduct());
        }
        
        return $this->orderItem;
    }

    function changeOrderItem(&$orderItem) 
    {
        $isChanged = false;
        if ($this->xlite->get('WholesaleTradingEnabled')) {
            $orderItem->set('amount', $this->get('amount'));
            $isChanged = true;
        }

        if ($this->hasOptions()) {
            $orderItem->set('options', serialize($this->getProductOptions()));
            $isChanged = true;
        }

        return $isChanged;
    }

    function get($name) 
    {
        $value = null;

        switch($name) {
            case "listPrice" :
            case "price" :
            case "weight":
                $orderItem = $this->get('orderItem');
                if ($this->changeOrderItem($orderItem)) {
                    $value = $orderItem->get($name);
                    break;
                }

            case "name" : 
            case "brief_description" :    
            case "sku" :
            case "description" :    
                $value = $this->getProduct()->get($name);
                break;

            default:
                $value = parent::get($name);
        }

        return $value;
    }

    function getImageURL() 
    {
        return $this->getProduct()->getImageURL();
    }

    /**
     * Get wishlist item page URL 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getUrl() 
    {
        return \XLite\Core\Converter::getInstance()->buildUrl(
            'product',
            '',
            array('product_id' => $this->get('product_id'))
        );
    }

    function getTotal() 
    {
        return $this->get('price') * $this->get('amount');
    }
    
    function hasImage() 
    {
        return $this->getProduct()->hasImage();
     }
    
    function hasOptions()  
    {
        return $this->get('options');
    }
    
    /* returns two-dimensional array like this
        ( 
            ([Color]=>Red, [Size]=>Medium),
            ([Color]=>Green, [Size]=>Large)
        )
    */
    function getOptionExceptionsAsArray() {
        $exceptions = array();

        foreach ($this->getProduct()->get('optionExceptions') as $oneException) {
            $tempArray = array();
            foreach (explode(";", $oneException->get('exception')) as $exceptionElement) {
                list($class, $option) = explode("=", $exceptionElement, 2);
                $tempArray[$class] = $option;
            }
            $exceptions[] = $tempArray;
        }

        return $exceptions;
    }
    
    function getSelectedOptionsAsArray() {
        $selectedOptions = $this->getProductOptions();
        $result = array();
        foreach ($selectedOptions as $selectedOption) {
            $result[$selectedOption->class] = $selectedOption->option;
        }

        return $result;
    }
    
    // bool
    function isOptionsInvalid() {

        $value = false;

        if ($this->xlite->get('ProductOptionsEnabled')) {
            $selectedOptions = $this->getSelectedOptionsAsArray();

            foreach ($this->getOptionExceptionsAsArray() as $exception) {
                $stillInvalid = true;
                foreach ($exception as $class => $option) {
                    if (
                        !isset($selectedOptions[$class])
                        || $selectedOptions[$class] != $option
                    ) {
                        $stillInvalid = false;
                    }
                }

                if ($stillInvalid) {
                    $value = true;
                    break;
                }
            }
        }

        return $value;
    }
    
    function isOptionsExist() {
        if (!$this->xlite->get('ProductOptionsEnabled')) {
            // if ProductOptions disabled - all options are exists
            return true;
        }
        $product = $this->getProduct();
        $selectedOptions = $this->getSelectedOptionsAsArray();
        
        $result = true;
        foreach ($product->getProductOptions() as $productOptions) {
            if ($productOptions->get('opttype') == "Text" || $productOptions->get('opttype') == "Textarea") 
                continue;

            $class = $productOptions->get('optclass');
            if (isset($selectedOptions[$class])) {
                // check that pruduct options still have this option
                $option = $selectedOptions[$class];
                $options = $productOptions->get('options');
                // $options - string like this - "Green\n\Blue\nRed" or "Green\r\nBlue\r\nRed"
                if (!preg_match("/(\r|(\r\n)|\n)?$option(\r|(\r\n)|\n)?/", $options)) {
                    $result = false;
                    break;
                }
            }
        }

        return $result;
    }
    
       function getProductOptions() 
    {
        $options = $this->get('options');

        return empty($options) ? array() : unserialize($options);
    }

    function setProductOptions(&$options)  
    {
        $orderItem = $this->get('orderItem');
        $orderItem->setProductOptions($options);
        $this->set('options', $orderItem->get('options'));
    }

}

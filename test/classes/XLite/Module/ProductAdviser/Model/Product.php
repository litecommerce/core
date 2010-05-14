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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * XLite_Module_ProductAdviser_Model_Product
 * 
 * @package    XLite
 * @subpackage View
 * @since      3.0.0
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
    public function getRelatedProducts()
    {
        if (!isset($this->relatedProducts)) {

            $productId = $this->get('product_id');

            $relatedProduct = new XLite_Module_ProductAdviser_Model_RelatedProduct();
            $relatedProducts = $relatedProduct->findAll("product_id='$productId'");
            $products = array();

            if (is_array($relatedProducts)) {

                foreach ($relatedProducts as $p_key => $product) {

                    $rp = new XLite_Model_Product($product->get('related_product_id'));
                    $addSign = true;
                    $addSign &= $rp->filter();
                    $addSign &= $rp->is('available');

                    // additional check
                    if (!$rp->is('available') || (isset($rp->properties) && is_array($rp->properties) && !isset($rp->properties['enabled']))) {
                        // removing link to non-existing product
                        if (intval($rp->get('product_id')) > 0) {
                            $rp->delete();
                        }

                        $addSign = false;
                    }

                    if ($addSign) {
                        $rp->checkSafetyMode();
                        $_product = $relatedProducts[$p_key];
                        $_product->set('product', $rp);
                        $products[] = $_product;
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
    public function getProductsAlsoBuy()
    {
        if (!isset($this->productsAlsoBuy)) {

            $productId = $this->get('product_id');
            $pabObj = new XLite_Module_ProductAdviser_Model_ProductAlsoBuy();
            $pabAll = $pabObj->findAll("product_id='$productId'");
            $products = array();

            if (is_array($pabAll)) {

                foreach ($pabAll as $p_key => $product) {

                    $pab = new XLite_Model_Product($product->get('product_id_also_buy'));
                    $addSign = true;
                    $addSign &= $pab->filter();
                    $addSign &= $pab->is('available');

                    // additional check
                    if (!$pab->is('available') || (isset($pab->properties) && is_array($pab->properties) && !isset($pab->properties['enabled']))) {
                        // removing link to non-existing product
                        if (intval($pab->get('product_id')) > 0) {
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

    /**
     * addRelatedProducts 
     * 
     * @param mixed $products ____param_comment____
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function addRelatedProducts($products)
    {
        if (is_array($products)) {
    		foreach ($products as $p_key => $product) {
                $relatedProduct = new XLite_Module_ProductAdviser_Model_RelatedProduct();
                $relatedProduct->set('product_id', $this->get('product_id'));
                $relatedProduct->set('related_product_id', $product->get('product_id'));
    			if (!$relatedProduct->isExists()) {
    				$relatedProduct->create();
    			}
    		}
    	}
    }

    /**
     * deleteRelatedProducts 
     * 
     * @param mixed $products ____param_comment____
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function deleteRelatedProducts($products)
    {
        if (is_array($products)) {
    		foreach ($products as $p_key => $product) {
                $relatedProduct = new XLite_Module_ProductAdviser_Model_RelatedProduct();
                $relatedProduct->set('product_id', $this->get('product_id'));
                $relatedProduct->set('related_product_id', $product->get('product_id'));
    			if ($relatedProduct->isExists()) {
    				$relatedProduct->delete();
    			}
    		}
    	}
    }

    /**
     * create 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function create()
    {
        parent::create();

    	if ($this->config->ProductAdviser->period_new_arrivals > 0) {
    		$added = time();
            //$added = mktime(date('H', $added), 0, 0, date('m', $added), date('d', $added), date('Y', $added));
            $product_id = $this->get('product_id');

        	$statistic = new XLite_Module_ProductAdviser_Model_ProductNewArrivals();
            $statistic->set('product_id', $product_id);
        	if ($statistic->find("product_id='$product_id'")) {
        		$statistic->set('updated', $added);
                $statistic->update();
        	} else {
            	$statistic->set('added', $added);
        		$statistic->set('updated', $added);
                $statistic->create();
        	}
    	}
    }

    /**
     * delete 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function delete()
    {
        $product_id = $this->get('product_id');
        $linked = array
        (
            "ProductAlsoBuy",
            "ProductNewArrivals",
            "ProductRecentlyViewed",
            "RelatedProduct",
        );

    	parent::delete();

        foreach ($linked as $objName) {
            $objName = 'XLite_Module_ProductAdviser_Model_' . $objName;
    		$object = new $objName();
    		$objs = $object->cleanRelations($product_id);
        }
    }

    /**
     * getNewArrival 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getNewArrival()
    {
        $stats = new XLite_Module_ProductAdviser_Model_ProductNewArrivals();

        $result = 0;

        if ($stats->find("product_id = '" . $this->get('product_id') . "'")) {

            $timeCondition = $this->config->ProductAdviser->period_new_arrivals * 3600;
    	    $timeLimit = time();

            if ($stats->get('new') == "Y") {
    	    	$result = 2;

        	} elseif (($stats->get('updated') + $timeCondition) > $timeLimit) {
        		$result =  1;
            }
        }

        return $result;
    }

    /**
     * set 
     * 
     * @param mixed $property ____param_comment____
     * @param mixed $value    ____param_comment____
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function set($property, $value)
    {
    	if ($property == "price") {
            $oldPrice = $this->get('price');
    	}

        parent::set($property, $value);

        if (!$this->config->ProductAdviser->customer_notifications_enabled) {
        	return;
        }
    	if ($property == "price") {
            $newPrice = $this->get('price');
            $price = null;
            if ($newPrice < $oldPrice) {
        		$price = $this->properties;
                $price['oldPrice'] = $oldPrice;
            }
            $this->xlite->set('productChangedPrice', $price);
    	}
    }

    /**
     * update 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function update()
    {
        parent::update();

        if ($this->config->ProductAdviser->customer_notifications_enabled) {

            $price = $this->xlite->get('productChangedPrice');

            if (isset($price) && is_array($price)) {

            	$check = array();
                $check[] = "type='" . CUSTOMER_NOTIFICATION_PRICE . "'";
                $check[] = "notify_key='" . $price['product_id'] . "'";
                $check = implode(' AND ', $check);

                $notification = new XLite_Module_ProductAdviser_Model_Notification();
                $notifications = $notification->findAll($check);

                if (is_array($notifications) && count($notifications) > 0) {

                    foreach ($notifications as $notification) {
                        $notification->set('status', CUSTOMER_REQUEST_UPDATED);
                        $notification->update();
                    }
                }
            }
        }
    }

    /**
     * checkHasOptions 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function checkHasOptions()
    {
    	return $this->xlite->get('ProductOptionsEnabled') ? $this->hasOptions() : false;
    }

    /**
     * _checkSafetyMode 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function _checkSafetyMode()
    {
    	return false;
    }

    /**
     * checkSafetyMode 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function checkSafetyMode()
    {
    	if ($this->_checkSafetyMode()) {
    		$category_id = $this->getComplex('category.category_id');
    	}
    }

    /**
     * getCategory 
     * 
     * @param string $where    ____param_comment____
     * @param string $orderby  ____param_comment____
     * @param bool   $useCache ____param_comment____
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCategory($where = null, $orderby = null, $useCache = true)
    {
    	if (is_null($this->_ProductMainCategory) || $this->_checkSafetyMode()) {
    		if ($this->_checkSafetyMode()) {
    			$adminZone = $this->xlite->is('adminZone');
    			$this->xlite->set('adminZone', true);
    		}
    		$categories = $this->getCategories(null, null, false);
    		if ($this->_checkSafetyMode()) {
    			$this->xlite->set('adminZone', $adminZone);
            }
            if (isset($categories[0])) {
                $this->_ProductMainCategory = $categories[0];
            }
    	}
    	return $this->_ProductMainCategory;
    }

    /**
     * import 
     * 
     * @param array $options ____param_comment____
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function import(array $options)
    {
        parent::import($options);

        $check = array();
        $check[] = "type='" . CUSTOMER_NOTIFICATION_PRICE . "'";
        $check[] = "status='" . CUSTOMER_REQUEST_UPDATED . "'";
        $check = implode(' AND ', $check);

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

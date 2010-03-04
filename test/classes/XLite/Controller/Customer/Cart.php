<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * ____file_title____
 *  
 * @category   Lite Commerce
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @version    SVN: $Id$
 * @link       http://www.qtmsoft.com/
 * @since      3.0.0 EE
 */

/**
 * XLite_Controller_Customer_Cart 
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0 EE
 */
class XLite_Controller_Customer_Cart extends XLite_Controller_Customer_Abstract
{
	/**
	 * Cart item to operate 
	 * 
	 * @var    XLite_Model_OrderItem
	 * @access protected
	 * @since  3.0.0 EE
	 */
	protected $currentItem = null;


	/**
     * Common method to determine current location 
     * 
     * @return string
     * @access protected 
     * @since  3.0.0 EE
     */
    protected function getLocation()
    {   
        return 'Shopping cart';
    }



    function getCurrentItem()
    {
        if (is_null($this->currentItem)) {
            $this->currentItem = new XLite_Model_OrderItem();
            $this->currentItem->set('product', $this->get('product'));
        }
        return $this->currentItem;
    }

    function action_add()
    {
		if (!$this->canAddProductToCart()) {
			return;
		}
		$this->collectCartGarbage();

        // add product to the cart
        $this->cart->addItem($this->getCurrentItem());
        $this->updateCart(); // recalculate shopping cart

        // switch back to product catalog or to shopping cart
        $this->set('returnUrlAbsolute', false);
        $productListUrl = ($this->config->General->add_on_mode && isset($_SERVER['HTTP_REFERER']))
			? $_SERVER['HTTP_REFERER']
			: $this->session->get('productListURL');

        if ($this->config->General->redirect_to_cart) {
            $this->session->set('continueURL', $productListUrl);

        } else {
            $this->set('returnUrl', $productListUrl);
            $this->set('returnUrlAbsolute', $this->config->General->add_on_mode && isset($_SERVER['HTTP_REFERER']));
        }
    }

    function action_delete()
    {
        // delete an item from the shopping cart
        $items = $this->cart->get('items');
        if (isset($items[$this->cart_id])) {
            $this->cart->deleteItem($items[$this->cart_id]);
            $this->updateCart();
        }

        if ($this->cart->isEmpty()) {
            $this->cart->delete();
		}
    }

    function action_update()
    {
        // update the specified product quantity in cart
        $items = $this->cart->get('items');
        foreach ($items as $key => $i) {
            if (isset($this->amount[$key]) && (!isset($this->cart_id) || $this->cart_id == $key)) {
                $items[$key]->updateAmount($this->amount[$key]);
                $this->cart->updateItem($items[$key]);
            }
        }

        if (isset($this->shipping)) {
            $this->cart->set('shipping_id', $this->shipping);
        }

        $this->updateCart();

        if ($this->cart->isEmpty()) {
            $this->cart->delete();
		}
    }
    
    function action_checkout()
    {
        $this->action_update();
        // switch to checkout dialog 
        $this->set('returnUrl', 'cart.php?target=checkout');
    }

    function action_clear()
    {
    	if (!$this->cart->isEmpty()) {
            $this->cart->delete();
        }
    }

    function isSecure()
    {
    	if ($this->is('HTTPS')) {
    		return true;
    	}
        return parent::isSecure();
    }

	function canAddProductToCart()
	{
		if (!$this->getProduct()->filter()) {
			$this->set('valid', false);
			return false;	
		}
		return true;
	}

	function collectCartGarbage()
	{
		// don't collect garbage, if the cart already has products
		if ($this->cart->is('empty')) {
			$this->cart->collectGarbage(5);
		}
	}

    /**
     * Get page instance data (name and URL)
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPageInstanceData()
    {
		$this->target = 'cart';

        return parent::getPageInstanceData();
    }

    /**
     * Get page type name
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPageTypeName()
    {
        return 'Shopping cart';
    }

}


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
 * Cart controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_WholesaleTrading_Controller_Customer_Cart extends XLite_Controller_Customer_Cart implements XLite_Base_IDecorator
{
    /**
     * Update errors list
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $updateErrors = null;

    /**
     * Controller parameters
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $params = array('target', 'mode');

    /**
     * Add item to cart
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function action_add()
    {
        $items = $this->getCart()->get('items');

        // alternative way to set product options
        // FIXME - resolve the issue with the "product_options" property
        /*if (
            $this->xlite->get('ProductOptionsEnabled')
            && is_object($this->getProduct())
            && isset(XLite_Core_Request::getInstance()->OptionSetIndex[$this->getProduct()->get('product_id')])
        ) {
            $options_set = $this->getProduct()->get('expandedItems');
            foreach ($options_set[XLite_Core_Request::getInstance()->OptionSetIndex[$this->getProduct()->get('product_id')]] as $_opt) {
                $this->product_options[$_opt->class] = $_opt->option_id;
            }
        }*/

        // Detect amount
        if (
            isset(XLite_Core_Request::getInstance()->amount)
            && XLite_Core_Request::getInstance()->amount > 0
        ) {
            $amount = XLite_Core_Request::getInstance()->amount;
        }

        if (
            isset(XLite_Core_Request::getInstance()->wishlist_amount)
            && XLite_Core_Request::getInstance()->wishlist_amount > 0
        ) {
            $amount = XLite_Core_Request::getInstance()->wishlist_amount;
        }

        if (!isset(XLite_Core_Request::getInstance()->opt_product_qty)) {

            // min/max purchase amount check
            $pl = new XLite_Module_WholesaleTrading_Model_PurchaseLimit();
            if ($pl->find("product_id=" . $this->getCurrentItem()->getProduct()->get('product_id'))) {
                $exists_amount = 0;
                foreach ($items as $i) {
                    if ($i->getProduct()->get('product_id') ==  $this->getCurrentItem()->getProduct()->get('product_id')) {
                        $exists_amount += $i->get('amount');
                    }
                }

                if (!isset($amount)) {
                    $amount = $pl->get('min');

                } elseif (
                    $amount + $exists_amount < $pl->get('min') || 
                    ($pl->get('max') > 0 && $pl->get('max') < $amount + $exists_amount)
                ) {

                    // TODO - add top message
                    $this->set('returnUrl', $this->buildUrl('product', '', array('product_id' => $this->getCurrentItem()->getProduct()->get('product_id'))));
                    return;
                }
            }
        }

        if (!isset($amount)) {
            $amount = 1;
        }

        // check if product sale available
        $this->getProduct()->set('product_id', $this->product_id);
        if (!$this->getProduct()->is('saleAvailable')) {

            // TODO - add top message
            $this->set('returnUrl', $this->buildUrl('product', '', array('product_id' => $this->getCurrentItem()->getProduct()->get('product_id'))));
            return;
        }

        $this->getCurrentItem()->set('amount', $amount);
 
        parent::action_add();

        if ($this->config->WholesaleTrading->direct_addition) {
            $this->getProduct()->assignDirectSaleAvailable(false);
        }
    }

    /**
     * Update cart
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionUpdate()
    {
        $items = $this->getCart()->get('items');

        $cartId = XLite_Core_Request::getInstance()->cart_id;
        $amount = XLite_Core_Request::getInstance()->amount;
        if (!is_array($amount)) {
            $amount = array($cartId => $amount);
        }

        $raw_items = array();
        foreach ($items as $i => $item) {
            $key = $item->getComplex('product.product_id');
            if (!is_null($key)) {
                if (!isset($raw_items[$key])) {
                    $raw_items[$key] = 0;
                }

                $raw_items[$key] += isset($amount[$i]) ? $amount[$i] : $item->get('amount');
            }
        }

        foreach ($raw_items as $key => $amount) {
            $purchase_limit = new XLite_Module_WholesaleTrading_Model_PurchaseLimit();
            $limit = array();
            if ($purchase_limit->find('product_id = ' . $key)) {
                $limit = $purchase_limit->get('properties');

                if (!empty($limit['min']) && $amount < intval($limit['min'])) {
                    $this->updateErrors[$key]['min'] = $limit['min'];
                    $this->updateErrors[$key]['amount'] = $amount;
                }

                if (!empty($limit['max']) &&  $amount > intval($limit['max'])) {
                    $this->updateErrors[$key]['max'] = $limit['max'];
                    $this->updateErrors[$key]['amount'] = $amount;
                }
            }
        }

        if (empty($this->updateErrors)) {
            $this->set('mode', null);
            parent::doActionUpdate();

        } else {
            foreach ($this->updateErrors as $key => $error) {
                $product = new XLite_Model_Product($key);
                $this->updateErrors[$key]['name'] = $product->get('name');
            }

            // TODO - add top message
            
            $this->set('valid', false);
            $this->set('mode', 'update_error');
        }
    }

    /**
     * Recalculates the shopping cart
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function updateCart()
    {
        if ($this->xlite == null || !$this->xlite->get('dont_update_cart')) {
            parent::updateCart();
        }
    }
}


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
 * XLite_Module_WholesaleTrading_Controller_Customer_Product 
 * 
 * @package    XLite
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
class XLite_Module_WholesaleTrading_Controller_Customer_Product extends XLite_Controller_Customer_Product implements XLite_Base_IDecorator
{
	// FIXME - must be completely revised; do not uncomment
    /*function init()
    {
		$this->get("product");
		if (is_object($this->product)) {
			if ($this->product->get("product_id") <= 0) {
				// recover product_id if unset by read() method
				$this->product->set("product_id", $_REQUEST['product_id']);
			}
			if (!isset($_REQUEST['action']) || 'buynow' != $_REQUEST['action']) {
				// don't show the product if it is available for direct sale only
				$this->product->assignDirectSaleAvailable(false);
				$this->product = null;
			} else {
				// perform direct sale check if the product does not exist
				$this->product->_checkExistanceRequired = true;
				if (!$this->product->is("directSaleAvailable")) {
					$this->redirect("cart.php?mode=add_error");
					exit;
				}
			}
		}

		parent::init();
    }*/

    function _conditionActionBuynow()
    {
        $product = $this->get("product");
		if (!is_object($product)) return false;

        $product->set("product_id", $this->product_id);
		if (!$product->is("directSaleAvailable")) {
        	$this->set("returnUrl", "cart.php?mode=add_error");
			return false;
		}	

		// min/max purchase amount check
		$pl = new XLite_Module_WholesaleTrading_Model_PurchaseLimit();
		if ($pl->find("product_id=" . $product->get("product_id"))) {
			$category_id = $this->get("category_id");
			if (!isset($category_id)) {
				$category_id = $product->getComplex('Category.category_id');
				$this->set("category_id", $category_id);
			}
			return false;
		}	
		
		return true;
    }

    function action_buynow()
    {
    	if ($this->_conditionActionBuynow()) {
			parent::action_buynow();
		}
    }

    function isAvailableForSale()
    {
		if (!$this->isComplex('product.saleAvailable')) {
			return false;
		}	
        return parent::isAvailableForSale();
    }

	function getWholesalePricing()
	{
		if (is_null($this->wholesale_pricing)) {
			$product = new XLite_Model_Product($this->getComplex('product.product_id'));
			$this->wholesale_pricing = $product->getWholesalePricing();
		}	
		return $this->wholesale_pricing;
	}

	function option_selected($p_id, $key)
	{
		return $key == 0;
	}
}


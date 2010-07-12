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

namespace XLite\Module\ProductAdviser\Controller\Admin;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ProductList extends \XLite\Controller\Admin\ProductList implements \XLite\Base\IDecorator
{
    public $notifyPresentedHash = array();

    function init()
    {
    	$this->params[] = "new_arrivals_search";
    	parent::init();
    }

    function _getExtraParams()
    {
    	return array('search_productsku', 'substring', 'search_category', 'subcategory_search', 'new_arrivals_search');
    }

    function getExtraParams()
    {
    	$form_params = $this->_getExtraParams();

        $result = parent::getAllParams();
        if (is_array($result)) {
        	foreach ($result as $param => $name) {
        		if (in_array($param, $form_params)) {
        			if (isset($result[$param])) {
        				unset($result[$param]);
        			}
        		}
            }
        }

        return $result;
    }

    function isNotifyPresent($product_id)
    {
    	if (!isset($this->notifyPresentedHash[$product_id])) {
        	$check = array();
            $check[] = "type='" . CUSTOMER_NOTIFICATION_PRICE . "'";
    		$check[] = "notify_key='" . addslashes($product_id) . "'";
    		$check[] = "status='" . CUSTOMER_REQUEST_UPDATED . "'";
    		$check = implode(' AND ', $check);

    		$notification = new \XLite\Module\ProductAdviser\Model\Notification();
    		$this->notifyPresentedHash[$product_id] = $notification->count($check);
    	}
        return $this->notifyPresentedHash[$product_id];
    }

    function getProducts()
    {
    	if ($this->mode != "search") {
    		return null;
    	}

    	$this->productsList = parent::getProducts();
    	if (is_array($this->productsList) && $this->new_arrivals_search) {
    		$removedItems = array();
    		for ($i=0; $i<count($this->productsList); $i++) {
        		if (is_array($this->productsList[$i]) && isset($this->productsList[$i]['class']) && isset($this->productsList[$i]['data'])) {
            		$object = new $this->productsList[$i]['class'];
                    $object->isPersistent = true;
                    $object->isRead = false;
                    $object->properties = $this->productsList[$i]['data'];
                    if ($object->getNewArrival() == 0) {
                    	$removedItems[] = $i;
                    }
        		} else {
                    if (is_object($this->productsList[$i]) && $this->productsList[$i]->getNewArrival() == 0) {
                    	$removedItems[] = $i;
                    }
        		}
    		}
    		if (count($removedItems) > 0) {
        		foreach ($removedItems as $i) {
    				unset($this->productsList[$i]);
        		}
            	$this->productsFound = count($this->productsList);
    		}
        }
        return $this->productsList;
    }
}

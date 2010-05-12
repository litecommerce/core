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
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_WholesaleTrading_Controller_Admin_Product extends XLite_Controller_Admin_Product implements XLite_Base_IDecorator
{
    public function __construct(array $params)
    {
        parent::__construct($params);
        $this->pages['access_list'] = "Product access";
        $this->pageTemplates['access_list'] = "modules/WholesaleTrading/product_access/access_list.tpl";
        $this->pages['wholesale_pricing'] = "Wholesale pricing";
        $this->pageTemplates['wholesale_pricing'] = "modules/WholesaleTrading/wholesale_pricing.tpl";
        $this->pages['purchase_limit'] = "Purchase limit";
        $this->pageTemplates['purchase_limit'] = "modules/WholesaleTrading/purchase.tpl";
    }

    function action_update_access()
    {
        $pa = new XLite_Module_WholesaleTrading_Model_ProductAccess();
        $found = false;
        if ($pa->find("product_id='" . intval($this->product_id) . "'")) {
            $found = true;
        }

        $pa->set("product_id", $this->product_id);
        $pa->set("show_group", $this->parseAccess($_REQUEST['access_show']));
        $pa->set("show_price_group", $this->parseAccess($_REQUEST['access_show_price']));
        $pa->set("sell_group", $this->parseAccess($_REQUEST['access_sell']));
        
        if (true === $found) {
            $pa->update();

        } else {
            $pa->create();
        }
    }

    function getProductAccess()
    {
        if (is_null($this->product_access)) {
            $pa = new XLite_Module_WholesaleTrading_Model_ProductAccess();
            if (!$pa->find("product_id='" . intval($this->product_id) . "'")) {
                $pa->set("porduct_id", $this->product_id);
            }
            $this->product_access = $pa;
            $pa->collectGarbage();
        }
        return $this->product_access;
    }

    function getWholesalePricing()
    {
        if (is_null($this->wholesale_pricing)) {
            $wp = new XLite_Module_WholesaleTrading_Model_WholesalePricing();
            $this->wholesale_pricing = $wp->findAll("product_id='" . intval($this->product_id) . "'");
            $wp->collectGarbage();
        }
        return $this->wholesale_pricing;
    }

    function action_add_wholesale_pricing()
    {
        $wp = new XLite_Module_WholesaleTrading_Model_WholesalePricing();
        $wp->set("product_id", $this->product_id);
        $wp->set("price", $_REQUEST['wp_price']);
        $wp->set("amount", $_REQUEST['wp_amount']);
        $wp->set("membership", $_REQUEST['wp_membership']);
        $wp->create();
    }

    function action_delete_wholesale_price()
    {
        $wp = new XLite_Module_WholesaleTrading_Model_WholesalePricing($_REQUEST['wprice_id']);
        $wp->delete();
    }

    function action_update_wholesale_pricing()
    {
        $wp = new XLite_Module_WholesaleTrading_Model_WholesalePricing($_REQUEST['wprice_id']);
        $wp->set("product_id", $this->product_id);
        $wp->set("price", $_REQUEST['w_price']);
        $wp->set("amount", $_REQUEST['w_amount']);
        $wp->set("membership", $_REQUEST['w_membership']);
        $wp->update();
    }

    function action_add_purchase_limit()
    {
        $pl = new XLite_Module_WholesaleTrading_Model_PurchaseLimit();
        $action = "create";
        if ($pl->find("product_id='" . intval($this->product_id) . "'")) {
            $action = "update";
        }
        $pl->set("product_id", $this->product_id);
        $pl->set("min", $_REQUEST['min_purchase']);
        $pl->set("max", $_REQUEST['max_purchase']);
        $pl->$action();
    }
    
    function getPurchaseLimit()
    {
        if (is_null($this->purchase_limit)) {
            $pl = new XLite_Module_WholesaleTrading_Model_PurchaseLimit();
            if (!$pl->find("product_id='" . intval($this->product_id) . "'")) {
                $pl->set("product_id", $this->product_id);
            }
            $this->purchase_limit = $pl;
            $pl->collectGarbage();
        }
        return $this->purchase_limit;
    }

    function action_info()
    {
        $_POST['validaty_period'] = $_POST['vp_modifier'] . $_POST['vperiod'];
        parent::action_info();
    }

    function getValidatyModifier()
    {
        return substr($this->getProduct()->get('validaty_period'), 0, 1);
    }

    function getValidatyPeriod()
    {
        return substr($this->getProduct()->get('validaty_period'), 1);
    }

    protected function parseAccess($groups)
    {
        $result = null;

        if (empty($groups)) {
    	    $result = '';

        } elseif (in_array('all', $groups)) {
    	    $result = 'all';

        } elseif (in_array('registered', $groups)) {
        	$result = 'registered';

        } else {
            $result = implode(',', $groups);
        }

        return $result;
    }
}

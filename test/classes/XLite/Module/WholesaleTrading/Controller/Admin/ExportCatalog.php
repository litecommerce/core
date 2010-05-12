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
class XLite_Module_WholesaleTrading_Controller_Admin_ExportCatalog extends XLite_Controller_Admin_ExportCatalog implements XLite_Base_IDecorator
{
    public function __construct(array $params)
    {
        parent::__construct($params);
        $this->pages['export_wholesale_pricing'] = "Export wholesale pricing";
        $this->pageTemplates['export_wholesale_pricing'] = "modules/WholesaleTrading/export_wholesale_pricing.tpl";
        $this->pages['export_product_access'] = "Export product access";
        $this->pageTemplates['export_product_access'] = "modules/WholesaleTrading/export_product_access.tpl";
        $this->pages['export_purchase_limit'] = "Export purchase limit";
        $this->pageTemplates['export_purchase_limit'] = "modules/WholesaleTrading/export_purchase_limit.tpl";
    }

    function init()
    {
    	parent::init();

    	switch ($this->get('page')) {
    		case "export_wholesale_pricing":
    			$wp = new XLite_Module_WholesaleTrading_Model_WholesalePricing();
                $wp->collectGarbage();
    		break;
    		case "export_product_access":
    			$pa = new XLite_Module_WholesaleTrading_Model_ProductAccess();
                $pa->collectGarbage();
    		break;
            case "export_purchase_limit":
                $pl = new XLite_Module_WholesaleTrading_Model_PurchaseLimit();
                $pl->collectGarbage();
            break;
    	}
    }

    function isArrayUnique($arr, &$firstValue, $skipValue="")
    {
    	if(function_exists('func_is_array_unique')) {
    		return func_is_array_unique($arr, $firstValue, $skipValue);
    	}

        if (!is_array($arr)) {
        	return false;
        }
        for ($i = 0; $i < count($arr); $i++) {
            if (strcmp($arr[$i], $skipValue) === 0) {
            	continue;
            }
               
            for ($j = 0; $j < count($arr); $j++) {
                if ($i != $j && strcmp($arr[$i], $arr[$j]) === 0) {
                    $firstValue = $arr[$i];
                    return false;
                }
            }
        }
            
        return true;
    }

    function handleRequest()
    {
        $name = '';
        if
        (
            (
                $this->action == 'export_wholesale_pricing'
                &&
                !$this->isArrayUnique($this->wholesale_pricing_layout, $name, 'NULL')
            )
            ||
            (
                $this->action == 'export_product_access'
                &&
                !$this->isArrayUnique($this->product_access_layout, $name, 'NULL')
            )
            ||
            (
                $this->action == 'export_purchase_limit'
                &&
                !$this->isArrayUnique($this->purchase_limit_layout, $name, 'NULL')
            )
        ) {
            $this->set('valid', false);
            $this->set('invalid_field_order', true);
            $this->set('invalid_field_name', $name);
        }
    
        parent::handleRequest();
    }

    function action_export_wholesale_pricing()
    {
        global $DATA_DELIMITERS;

        // save layout & export
        $dlg = new XLite_Controller_Admin_ImportCatalog();
        $dlg->action_layout('wholesale_pricing_layout');
        $this->startDownload('wholesale_pricing.csv');
        $wp = new XLite_Module_WholesaleTrading_Model_WholesalePricing();
        $wp->export($this->wholesale_pricing_layout, $DATA_DELIMITERS[$this->delimiter], null, "product_id");
        exit();
    }
    
    function action_export_product_access()
    {
        global $DATA_DELIMITERS;

        // save layout & export
        $dlg = new XLite_Controller_Admin_ImportCatalog();
        $dlg->action_layout('product_access_layout');
        $this->startDownload('product_access.csv');
        
        $pa = new XLite_Module_WholesaleTrading_Model_ProductAccess();
        $pa->export($this->product_access_layout, $DATA_DELIMITERS[$this->delimiter], null, 'product_id');
        exit();
    }
    
    function action_export_purchase_limit() {
        global $DATA_DELIMITERS;

        // save layout & export
        $dlg = new XLite_Controller_Admin_ImportCatalog();
        $dlg->action_layout('purchase_limit_layout');
        $this->startDownload('purchase_limit.csv');
        $pl = new XLite_Module_WholesaleTrading_Model_PurchaseLimit();
        $pl->export($this->purchase_limit_layout, $DATA_DELIMITERS[$this->delimiter], null, "product_id");
        exit();
    }

    /**
    * @param int    $i          field number
    * @param string $value      current value
    * @param bolean $default    default state
    */
    function isOrderFieldSelected($id, $value, $default)
    {
        if ($this->action == 'export_wholesale_pricing' && $id < count($this->wholesale_pricing_layout)) {
            return ($this->wholesale_pricing_layout[$id] === $value);
        } elseif ($this->action == 'export_product_access' && $id < count($this->product_access_layout)) {
            return ($this->product_access_layout[$id] === $value);
        } elseif ($this->action == 'export_purchase_limit' && $id < count($this->purchase_limit_layout)) {
            return ($this->purchase_limit_layout[$id] === $value);
        } else {
            return $default;
        }
    }
}

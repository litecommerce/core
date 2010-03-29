<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URLs:                                                       |
|                                                                              |
| FOR LITECOMMERCE                                                             |
| http://www.litecommerce.com/software_license_agreement.html                  |
|                                                                              |
| FOR LITECOMMERCE ASP EDITION                                                 |
| http://www.litecommerce.com/software_license_agreement_asp.html              |
|                                                                              |
| THIS  AGREEMENT EXPRESSES THE TERMS AND CONDITIONS ON WHICH YOU MAY USE THIS |
| SOFTWARE PROGRAM AND ASSOCIATED DOCUMENTATION THAT CREATIVE DEVELOPMENT, LLC |
| REGISTERED IN ULYANOVSK, RUSSIAN FEDERATION (hereinafter referred to as "THE |
| AUTHOR")  IS  FURNISHING  OR MAKING AVAILABLE TO  YOU  WITH  THIS  AGREEMENT |
| (COLLECTIVELY,  THE "SOFTWARE"). PLEASE REVIEW THE TERMS AND  CONDITIONS  OF |
| THIS LICENSE AGREEMENT CAREFULLY BEFORE INSTALLING OR USING THE SOFTWARE. BY |
| INSTALLING,  COPYING OR OTHERWISE USING THE SOFTWARE, YOU AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE ACCEPTING AND AGREEING  TO  THE  TERMS  OF  THIS |
| LICENSE AGREEMENT. IF YOU ARE NOT WILLING TO BE BOUND BY THIS AGREEMENT,  DO |
| NOT  INSTALL  OR USE THE SOFTWARE. VARIOUS COPYRIGHTS AND OTHER INTELLECTUAL |
| PROPERTY  RIGHTS PROTECT THE SOFTWARE. THIS AGREEMENT IS A LICENSE AGREEMENT |
| THAT  GIVES YOU LIMITED RIGHTS TO USE THE SOFTWARE AND NOT AN AGREEMENT  FOR |
| SALE  OR  FOR TRANSFER OF TITLE. THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* Class description.
*
* @package Module_WholesaleTrading
* @access public
* @version $Id$
*/
class XLite_Module_WholesaleTrading_Controller_Admin_ExportCatalog extends XLite_Controller_Admin_ExportCatalog implements XLite_Base_IDecorator
{
    public function __construct(array $params)
    {
        parent::__construct($params);
        $this->pages["export_wholesale_pricing"] = "Export wholesale pricing";
        $this->pageTemplates["export_wholesale_pricing"] = "modules/WholesaleTrading/export_wholesale_pricing.tpl";
        $this->pages["export_product_access"] = "Export product access";
        $this->pageTemplates["export_product_access"] = "modules/WholesaleTrading/export_product_access.tpl";
        $this->pages["export_purchase_limit"] = "Export purchase limit";
        $this->pageTemplates["export_purchase_limit"] = "modules/WholesaleTrading/export_purchase_limit.tpl";
    }

    function init()
    {
    	parent::init();

    	switch ($this->get("page")) {
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
        $dlg->action_layout("wholesale_pricing_layout");
        $this->startDownload("wholesale_pricing.csv");
        $wp = new XLite_Module_WholesaleTrading_Model_WholesalePricing();
        $wp->export($this->wholesale_pricing_layout, $DATA_DELIMITERS[$this->delimiter], null, "product_id");
        exit();
    }
	
    function action_export_product_access()
    {
        global $DATA_DELIMITERS;

        // save layout & export
        $dlg = new XLite_Controller_Admin_ImportCatalog();
        $dlg->action_layout("product_access_layout");
        $this->startDownload("product_access.csv");
        
        $pa = new XLite_Module_WholesaleTrading_Model_ProductAccess();
        $pa->export($this->product_access_layout, $DATA_DELIMITERS[$this->delimiter], null, 'product_id');
        exit();
    }
    
    function action_export_purchase_limit() {
        global $DATA_DELIMITERS;

        // save layout & export
        $dlg = new XLite_Controller_Admin_ImportCatalog();
        $dlg->action_layout("purchase_limit_layout");
        $this->startDownload("purchase_limit.csv");
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

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

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
class XLite_Module_WholesaleTrading_Controller_Admin_ImportCatalog extends XLite_Controller_Admin_ImportCatalog implements XLite_Base_IDecorator
{	
    public $unique_identifier = null;

    public function __construct(array $params)
    {
        parent::__construct($params);
        $this->pages["import_wholesale_pricing"] = "Import wholesale pricing";
        $this->pageTemplates["import_wholesale_pricing"] = "modules/WholesaleTrading/import_wholesale_pricing.tpl";
        $this->pages["import_product_access"] = "Import product access";
        $this->pageTemplates["import_product_access"] = "modules/WholesaleTrading/import_product_access.tpl";
        $this->pages["import_purchase_limit"] = "Import purchase limit";
        $this->pageTemplates["import_purchase_limit"] = "modules/WholesaleTrading/import_purchase_limit.tpl";
    }

    function init()
    {
    	parent::init();

    	switch ($this->get("page")) {
    		case "import_wholesale_pricing":
    			$wp = new XLite_Module_WholesaleTrading_Model_WholesalePricing();
                $wp->collectGarbage();
    		break;
    		case "import_product_access":
    			$pa = new XLite_Module_WholesaleTrading_Model_ProductAccess();
                $pa->collectGarbage();
    		break;
       		case "import_purchase_limit":
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
                $this->action == 'import_wholesale_pricing'
                &&
                !$this->isArrayUnique($this->wholesale_pricing_layout, $name, 'NULL')
            )
            ||
            (
                $this->action == 'import_product_access'
                &&
                !$this->isArrayUnique($this->product_access_layout, $name, 'NULL')
            )
            ||
            (
               $this->action == 'import_purchase_limit'
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

    function action_import_wholesale_pricing()
    {
        $this->startDump();
        $options = array(
            "file"              => $this->getUploadedFile(),
            "layout"            => $this->wholesale_pricing_layout,
            "delimiter"         => $this->delimiter,
            "text_qualifier"    => $this->text_qualifier,
            'unique_identifier' => $this->unique_identifier,
			"return_error"		=> true,
            );
        $wp = new XLite_Module_WholesaleTrading_Model_WholesalePricing();
		if ($this->delete_prices) {
            $wps = $wp->findAll(); 
            if ($wps) 
                foreach($wps as $wp_) 
                    $wp_->delete();
        }
        $wp->import($options);
		$this->importError = $wp->importError;

		$text = "<font color=red>Import process failed.</font>";
		if (!$this->importError) $text = "<font color=green>Wholesale pricing imported successfully.</font>";
		$text = '<br>' . $text . '<br>' . $this->importError . '<br><a href="admin.php?target=import_catalog&page=import_wholesale_pricing"><u>Click here to return to admin interface</u></a><br><br>';

		echo $text;
		func_refresh_end();
		exit();
    }
	
    function action_import_product_access()
    {
        $this->startDump();
        $options = array(
            "file"              => $this->getUploadedFile(),
            "layout"            => $this->product_access_layout,
            "delimiter"         => $this->delimiter,
            "text_qualifier"    => $this->text_qualifier,
            'unique_identifier' => $this->unique_identifier,
			"return_error"		=> true,
            );
        $pa = new XLite_Module_WholesaleTrading_Model_ProductAccess();
        $pa->import($options);
		$this->importError = $pa->importError;

		$text = "<font color=red>Import process failed.</font>";
		if (!$this->importError) $text = "<font color=green>Product access imported successfully.</font>";
		$text = '<br>' . $text . '<br>' . $this->importError . '<br><a href="admin.php?target=import_catalog&page=import_product_access"><u>Click here to return to admin interface</u></a><br><br>';

		echo $text;
		func_refresh_end();
		exit();
    }
    
    function action_import_purchase_limit() {
        $this->startDump();
        $options = array(
            "file"              => $this->getUploadedFile(),
            "layout"            => $this->purchase_limit_layout,
            "delimiter"         => $this->delimiter,
            "text_qualifier"    => $this->text_qualifier,
            'unique_identifier' => $this->unique_identifier,
			"return_error"		=> true,
            );
        $pl = new XLite_Module_WholesaleTrading_Model_PurchaseLimit();
        $pl->import($options);
		$this->importError = $pl->importError;

		$text = "<font color=red>Import process failed.</font>";
		if (!$this->importError) $text = "<font color=green>Purchase limit imported successfully.</font>";
		$text = '<br>' . $text . '<br>' . $this->importError. '<br><a href="admin.php?target=import_catalog&page=import_purchase_limit"><u>Click here to return to admin interface</u></a><br><br>';

		echo $text;
		func_refresh_end();
		exit();
    }

    /**
    * @param int    $i          field number
    * @param string $value      current value
    * @param bolean $default    default state
    */
    function isOrderFieldSelected($id, $value, $default)
    {
        if ($this->action == 'import_wholesale_pricing' && $id < count($this->wholesale_pricing_layout)) {
            return ($this->wholesale_pricing_layout[$id] === $value);
        } elseif ($this->action == 'import_product_access' && $id < count($this->product_access_layout)) {
            return ($this->product_access_layout[$id] === $value);
        } elseif ($this->action == 'import_purchase_limit' && $id < count($this->purchase_limit_layout)) {
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

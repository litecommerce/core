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
class XLite_Module_WholesaleTrading_Controller_Admin_ImportCatalog extends XLite_Controller_Admin_ImportCatalog implements XLite_Base_IDecorator
{
    public $unique_identifier = null;

    public function __construct(array $params = array())
    {
        parent::__construct($params);
        $this->pages['import_wholesale_pricing'] = "Import wholesale pricing";
        $this->pageTemplates['import_wholesale_pricing'] = "modules/WholesaleTrading/import_wholesale_pricing.tpl";
        $this->pages['import_product_access'] = "Import product access";
        $this->pageTemplates['import_product_access'] = "modules/WholesaleTrading/import_product_access.tpl";
        $this->pages['import_purchase_limit'] = "Import purchase limit";
        $this->pageTemplates['import_purchase_limit'] = "modules/WholesaleTrading/import_purchase_limit.tpl";
    }

    function init()
    {
    	parent::init();

    	switch ($this->get('page')) {
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

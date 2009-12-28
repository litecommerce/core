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
| GRANTED  BY  THIS AGREEMENT.                                                 |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

/**
* @package View
* @version $Id$
*/
class XLite_View_ExtraFields extends XLite_View
{
    var $template = "extra_fields.tpl";
    var $product;

    function init()
    {
        parent::init();
        $this->mapRequest();
    }
 
    function getExtraFields()
    {
		$this->extraFields = $this->get("product.extraFields");

		if (isset($this->product_id)) 
		{
			$product = new XLite_Model_Product($this->product_id);
			if (is_object($product))
			{
    			if ($this->config->get("General.enable_extra_fields_inherit") == "Y") {
    				$isAdminZone = $this->xlite->get("adminZone");
    				$this->xlite->set("adminZone", true);
    			}
				$product_categories = $product->getCategories();
    			if ($this->config->get("General.enable_extra_fields_inherit") == "Y") {
    				$this->xlite->set("adminZone", $isAdminZone);
    			}
                $extraFields_root = array();
        		foreach ($this->extraFields as $idx => $extraField) 
        		{
                	$extraFields_categories = $extraField->getCategories();
                	if (count($extraFields_categories) > 0)
                	{
                    	$found = false;
                    	foreach($product_categories as $cat)
                    	{
                    		if (in_array($cat->get("category_id"), $extraFields_categories))
                    		{
                    			$found = true;
                    			break;
                    		}
                    	}
                    	if (!$found)
                    	{
                    		unset($this->extraFields[$idx]);
                    	}
                    	else
                    	{
                    		if ($extraField->get("product_id") == 0)
                    		{
                    			$extraFields_root[$extraField->get("field_id")] = $idx;
                    		}
                    	}
                    }
        		}
        		foreach ($this->extraFields as $idx => $extraField) 
        		{
        			if (isset($extraFields_root[$extraField->get("parent_field_id")]))
        			{
        				if (isset($this->extraFields[$extraFields_root[$extraField->get("parent_field_id")]]))
        				{
        					unset($this->extraFields[$extraFields_root[$extraField->get("parent_field_id")]]);
        				}
        			}
        		}
        		foreach ($this->extraFields as $idx => $extraField) 
        		{
        			if ($extraField->get("parent_field_id") == 0)
        			{
                		$ef_child = new XLite_Model_ExtraField();
                        $ef_child->set("ignoreFilter", true);
                		if ($ef_child->find("parent_field_id='".$extraField->get("field_id")."' AND enabled='0'".((isset($this->product_id)&&!empty($this->product_id))?" AND product_id='".$this->product_id."'":"")))
                		{
        					unset($this->extraFields[$idx]);
                		}
        			}
        		}
			}
		}

        return $this->extraFields;
    }
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

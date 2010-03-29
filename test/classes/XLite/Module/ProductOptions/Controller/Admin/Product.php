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
* Product options dialog tab.
*
* @package Module_ProductOptions
* @access public
* @version $Id$
*/
class XLite_Module_ProductOptions_Controller_Admin_Product extends XLite_Controller_Admin_Product implements XLite_Base_IDecorator
{
    public function __construct(array $params)
    {
        parent::__construct($params);
        $this->pages["product_options"] = "Product options";
        $this->pageTemplates["product_options"] = "modules/ProductOptions/product_options.tpl";
    }

	function action_update_limit()
	{
		$product = new XLite_Model_Product($this->product_id);
		$limit = isset($this->expansion_limit) ? 1 : 0;
		$product->set('expansion_limit', $limit);
		$product->update();	
	}

    // PRODUCT OPTION METHODS {{{

    function getProductOption() // {{{
    {
        if (is_null($this->option)) {
            $this->option = new XLite_Module_ProductOptions_Model_ProductOption();
            if (isset($this->option_id)) {
                $this->option->set("option_id", $this->option_id);
            }
            if (isset($this->product_id)) {
                $this->option->set("product_id", $this->product_id);
            }
            if (isset($this->optdata)) {
				foreach ((array)$this->optdata as $key=>$value) {
					$this->optdata[$key] = preg_replace("/\|/", "-", $value);
				}
                $this->option->set("properties", $this->optdata);
            }
            if (isset($this->opttype) && $this->opttype == "Text" && isset($this->text)) {
                $this->option->set("properties", $this->text);
            }
            if (isset($this->opttype) && $this->opttype == "Textarea" && isset($this->textarea)) {
                $this->option->set("properties", $this->textarea);
            }
        }
        return $this->option;
    } // }}}
    
    function action_update_product_option() // {{{
    {
        $option = $this->get("productOption");
        $option->update();

        $this->params["option_id"] = $option->get("option_id");
        $this->option_id = $option->get("option_id");
    } // }}}

    function action_delete_product_option() // {{{
    {
        $option = $this->get("productOption");
        $option->delete();

		if (isset($this->option_id)) {
			unset($this->option_id);
		}
    } // }}}

    function action_add_product_option() // {{{
    {
    	if (isset($this->option_id)) {
    		unset($this->option_id);
    	}

        $option = $this->get("productOption");
        $option->create();

        $this->params["option_id"] = $option->get("option_id");
        $this->option_id = $option->get("option_id");
    } // }}}
    
    // }}}

    // OPTION EXCEPTION METHODS {{{

    function getOptionException() // {{{
    {
        if (is_null($this->optionException)) {
            $this->optionException = new XLite_Module_ProductOptions_Model_OptionException();
            if (isset($this->option_id)) {
                $this->optionException->set("option_id", $this->option_id);
            }
            if (isset($this->product_id)) {
                $this->optionException->set("product_id", $this->product_id);
            }
            if (isset($this->exception) && strlen(trim($this->exception))) {
                $this->optionException->set("exception", $this->exception);
            }
        }
        return $this->optionException;
    } // }}}

    function action_update_option_exception() // {{{
    {
        $exception = $this->get("optionException");
        $exception->update();
    } // }}}

    function action_delete_option_exception() // {{{
    {
        $exception = $this->get("optionException");
        $exception->delete();
    } // }}}

    function action_add_option_exception() // {{{
    {
    	if (isset($this->option_id)) {
    		unset($this->option_id);
    	}

        $exception = $this->get("optionException");
        if (!$exception->find("product_id='".$exception->get("product_id")."' AND exception='".addslashes($exception->get("exception"))."'")) {
        	$exception->create();
        }
    } // }}}
    
    // }}}

    // OPTION VALIDATOR METHOD {{{
    
    function action_product_option_validator()
    {
        $validator = new XLite_Module_ProductOptions_Model_OptionValidator();
        $validator->set("product_id", $this->product_id);
        if (isset($this->javascript_code) && strlen(trim($this->javascript_code))) {
            $validator->set("javascript_code", $this->javascript_code);
        }

        // add / update / delete
        if ($validator->read()) {
            if (!strlen(trim($this->javascript_code))) {
                $validator->delete();
            } else {
                $validator->update();
            }
        } elseif (strlen(trim($validator->get("javascript_code")))) {
            $validator->create();
        }
    }

    // }}}

	function isOddRow($row)
	{
		return (($row % 2) == 0) ? true : false;
	}

	function getRowClass($row,$odd_css_class,$even_css_class = null)
	{
		return ($this->isOddRow($row)) ? $odd_css_class : $even_css_class;
	}

    function action_info()
    {
		$product = new XLite_Model_Product($this->product_id);
        $oldCategories = array();
        $categories = $product->get("categories");
        if (is_array($categories)) {
        	foreach($categories as $cat) {
        		$oldCategories[] = $cat->get("category_id");
        	}
        }

    	parent::action_info();

		$product->updateGlobalProductOptions($oldCategories);
    }

    function getAllParams($exeptions = null)
    {
        $result = parent::getAllParams();
        if (isset($this->action)) {
        	if (!isset($this->option_id) && isset($result["option_id"])) {
        		unset($result["option_id"]);
        	}
        	if (isset($this->option_id)) {
        		$result["option_id"] = $this->option_id;
        	}
        }
       	return $result;
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

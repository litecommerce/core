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
* @package Module_ProductOptions
* @access public
* @version $Id$
*/
class Admin_Dialog_global_product_options extends Admin_dialog
{
	var $_categories = null;

    function &getCategories() // {{{
    {
        if (is_null($this->_categories)) {
            $c =& func_new("Category");
            $this->_categories =& $c->findAll();
            $names = array();
            $names_hash = array();
            for ($i = 0; $i < count($this->_categories); $i++) {
                $name = $this->_categories[$i]->get("stringPath");
                while (isset($names_hash[$name])) {
                    $name .= " ";
                }
                $names_hash[$name] = true;
                $names[] = $name;
            }
            array_multisort($names, $this->_categories);
        }
        return $this->_categories;
    } // }}}

    function action_add()
    {
        $option =& func_new("ProductOption");
        $option->set("properties", $this->optdata);
        if (isset($this->opttype) && $this->opttype == "Text" && isset($this->text)) {
            $option->set("properties", $this->text);
        }
        if (isset($this->opttype) && $this->opttype == "Textarea" && isset($this->textarea)) {
            $option->set("properties", $this->textarea);
        }
        $option->create();

        $option->setCategoriesList($this->categories);
        $option->update();

        $this->params["option_id"] = $option->get("option_id");
        $this->option_id = $option->get("option_id");
	}	

    function &getAllParams()
    {
        $result =& parent::getAllParams();
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

    function action_delete()
    {
		if (isset($this->option_id) && isset($this->global_options) && is_array($this->global_options)) {
			$po =& func_new("ProductOption");
			$child_po =& $po->findAll("parent_option_id='".$this->option_id."'");
			if ($child_po) {
				foreach($child_po as $option_) {
					$option_->delete();
				}
			}
			$po->set("option_id", $this->option_id);
			$po->delete();

			if (isset($this->option_id)) {
				unset($this->option_id);
			}
		}
	}
	
	function action_update_product_option()
	{
		if (isset($this->option_id) && isset($this->global_options) && is_array($this->global_options)) {
			$po =& func_new("ProductOption", $this->option_id);
			$categories = "";
			if (isset($this->global_options["categories"])) {
				$categories = $this->global_options["categories"];
				unset($this->global_options["categories"]);
			}
			if ($this->global_options["global_categories"]) {
				$categories = "";
			}
			$po->set("properties", $this->global_options);
			$po->setCategoriesList($categories);
			$po->update();
		}	
	}
	
    function &getGlobalOptions()
    {
        if (is_null($this->globalOptions)) {
            $go =& func_new("ProductOption");
            $this->globalOptions = $go->findAll("product_id=0");  // global options
        }
        return $this->globalOptions;
    }

}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

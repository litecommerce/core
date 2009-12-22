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
| The Initial Developer of the Original Code is Creative Development LCC       |
| Portions created by Creative Development LCC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
*
* @package Module_Egoods
* @access public
*/
class PinCode extends Base
{
	var $alias = "pin_codes";

	var $primaryKey = array("pin_id");
	var $defaultOrder = "pin_id";

	var $fields = array
	(
		"pin_id"		=> 0,
		"pin"			=> '',
		"enabled"		=> 0,
		"product_id"	=> 0,
		"item_id"		=> '',
		"order_id"		=> 0
	);

	var	$importFields = array
	(
		"NULL"			=> false,
		"pin"			=> false,
		"enabled"		=> false,
		"product"		=> false,
		"category"		=> false
	);

	function getFreePinCount($product_id) // {{{
	{
		$product = func_new('Product', $product_id);
		if ($product->get('pin_type') == 'D') {
			return count($this->findAll("item_id='' AND enabled=1 AND order_id=0 AND product_id=" . $product_id));
		} else if ($product->get('pin_type') == 'E') {
			return 99999999;
		}	
	} // }}}

	function isFree() // {{{
	{
		if ($this->get('item_id') == '' && $this->get('order_id') == 0) {
			return true;
		}
		return false;
	} // }}}

	function _export($layout, $delimiter) // {{{
	{
		$data = array();
		$values = $this->get("properties");

		foreach ($layout as $field) {
			if ($field == "NULL") {
				$data[] = "";
			} elseif ($field == "product") {
				$product = func_new("Product",$values['product_id']);
				$data[] = $this->_stripSpecials($product->get("name"));
			} elseif ($field == "category") {
                $product = func_new("Product",$values['product_id']);
				$category = func_new("Category");
				$data[] =  $category->createCategoryField($product->get("categories"));
			} elseif (isset($values[$field])) {
				$data[] =  $this->_stripSpecials($values[$field]);
			}
		}
		return $data;
	} // }}}

    function _import(&$options) // {{{
    {
        $properties = $options["properties"];
		
        static $line_no;
        !isset($line_no) ? $line_no = 1 : $line_no++;

        echo "<b>Importing CSV file line # $line_no: </b>";

		$product = func_new("Product");
		$product = $product->findImportedProduct("",$properties['category'],$properties['product'],false);
		if (!is_object($product)) {
			echo "product <b>\"".$properties['product']."\"</b> not found in category <b>\"".$properties['category']."\"</b>. Pin code not imported.<br>";
			return false;
		}
		$pin = func_new('PinCode');
		$found = $pin->find("pin = '".$properties['pin']."' AND product_id =". $product->get("product_id"));

        $pin->set("pin", $properties['pin']);
        $pin->set("enabled", $properties['enabled']);
        $pin->set("product_id", $product->get("product_id"));

        if ($found) {
        	if ($options["update_existing"]) {
            	echo "Updating";
            	$pin->update();
            } else {
            	echo "Skiping existing";
            }
        } else {
            echo "Creating";
            $pin->create();
        }
		echo "  PIN code \"" . $pin->get("pin") . "\"";

		echo " for product <a href=\"admin.php?target=product&product_id=".$product->get("product_id")."\">\"" . $product->get("name") . "\"</a><br>";

    } // }}} 
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

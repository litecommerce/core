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
* @package Module_WholesaleTrading
* @access public
* @version $Id: WholesalePricing.php,v 1.13 2008/10/23 12:04:35 sheriff Exp $
*/
class WholesalePricing extends Base
{
	/**
	 * @var string $alias The product access database table alias.
	 * @access public
	 */
	var $alias = "wholesale_pricing";

	var $primaryKey = array("price_id");
	var $defaultOrder = "amount";
    
    var $importError = "";

	/**
	 * @var array $fields product access properties.
	 * @access private
	 */
	var $fields = array(
			"price_id"		=> 0,
			"product_id"	=> 0,
			"amount"		=> 0,
			"price"			=> 0.00,
			"membership"	=> ''
			);

	var	$importFields = array(
			"NULL"			=> false,
			"product"		=> false,
			"sku"			=> false,	
			"amount"		=> false,
			"price"			=> false,
			"membership"	=> false
			);

	function getProductPrices($product_id, $amount, $membership_condition = "")
	{
		return $this->findAll("product_id='" . intval($product_id) . "' AND amount<='" . intval($amount) . "' AND (membership='all' $membership_condition)");
	}

	function _export($layout, $delimiter) // {{{
	{
		$data = array();

		$values = $this->get("properties");

		foreach ($layout as $field) {
			if ($field == "NULL") {
				$data[] = "";
			} elseif ($field == "product") {
				$product = &func_new("Product",$values["product_id"]);
				$data[] = $product->get("name");
			} elseif ($field == "sku") {
                $product = &func_new("Product",$values["product_id"]);
                $data[] = $product->get("sku");
			} elseif (isset($values[$field])) {
				$data[] =  $this->_stripSpecials($values[$field]);
			}
		}
		return $data;
	} // }}}

    function _import(&$options) // {{{
    {
        static $line_no;
        if (!isset($line_no)) $line_no = 1; else $line_no++;
        
        $properties       = $options["properties"];
        $wp =& func_new('WholesalePricing');
        $product = &func_new("Product");

		$product = $product->findImportedProduct($properties['sku'], '',$properties['product'], false, $options["unique_identifier"]);
		if (!is_null($product)) {
			$found = $wp->find("product_id = " . $product->get("product_id") . " AND amount=" . $properties["amount"] . " AND membership = '" . $properties['membership']. "'");
			$wp->set("product_id", $product->get("product_id"));
			$wp->set("amount",$properties["amount"]);
			$wp->set("price",$properties["price"]);
			$wp->set("membership",$properties["membership"]);
	
            echo "<b>Importing CSV file line# $line_no: </b>";
	
			if ($found) { 
				echo "Update wholesale price for '".$product->get("name")."' product";	
				$wp->update(); 
			} else {
				$wp->create();
                echo "Create wholesale price for '".$product->get("name")."' product";
			}
			echo "<br>\n";
		} else {
            $this->importError = "Error: trying to create wholesale price for non-existent product. CSV file line #". $line_no;
			echo $this->importError;
		}

    } // }}} 

    function collectGarbage()
    {
    	$product =& func_new("Product");
        $product_table_name = $product->db->getTableByAlias($product->alias);
        $table_name = $this->db->getTableByAlias($this->alias);

        $sql =<<<EOSQL
        SELECT $table_name.product_id
        FROM $table_name
        LEFT JOIN $product_table_name
        ON $product_table_name.product_id = $table_name.product_id
        WHERE $product_table_name.product_id IS NULL
        GROUP BY product_id
EOSQL;

        $collection = $this->db->getAll($sql);
        foreach ($collection as $item) {
            $wp =& func_new("WholesalePricing");
            if ($wp->find("product_id='".$item["product_id"]."'")) {
            	$wp->delete();
            }
        }
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

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
* @version $Id$
*/
class ProductAccess extends Base
{
    /**
    * @var string $alias The product access database table alias.
    * @access public
    */
    var $alias = "product_access";

    var $primaryKey = array("product_id");
    
    var $importError = "";

    /**
    * @var array $fields product access properties.
    * @access private
    */
    var $fields = array(
			"product_id"		=> 0,
			"show_group"		=> 'all',
			"show_price_group"	=> 'all',
			"sell_group"		=> 'all'
        );

    var $importFields = array(
			"NULL"				=> false,
            "sku"       		=> false,
			"product"           => false,
            "show_group"		=> false,
			"show_price_group"	=> false,
			"sell_group"		=> false
        );

	function groupInAccessList($group, $access, $expand_all=true)
	{
		// $group - membership level
		// $access - show, show price, sell
		require_once "modules/WholesaleTrading/encoded.php";
		$acc_list = func_wholesaleTrading_get_access_list($this->get($access));
		if (true === $expand_all) {
			if (in_array("all", $acc_list)) {
				return true;
			}	
			if ($this->auth->is("logged") && in_array("registered", $acc_list)) {
				return true;
			}
		}
		if ($group != "") {
			return in_array($group, $acc_list);
		} else {
			return false;
		}	
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
                    
        $properties = $options["properties"];
        $pa         =& func_new('ProductAccess');
        $product    =& func_new('Product');

        $product = $product->findImportedProduct($properties['sku'], '', $properties['product'], false, $options["unique_identifier"]);
        if(!is_null($product)) {
            $found = $pa->find("product_id = '".$product->get('product_id')."'");
            $pa->set('product_id',       $product->get('product_id'));
            $pa->set('show_group',       $properties['show_group']);
            $pa->set('show_price_group', $properties['show_price_group']);
            $pa->set('sell_group',       $properties['sell_group']);
            
		    echo "<b>Importing CSV file line# $line_no: </b>";
		    
            if ($found) {
            	echo "Update access for product ";
            	$pa->update();
        	} else {
            	echo "Create access for product ";
            	$pa->create();
        	}
        	echo  $product->get('name') . "<br>\n";
		} else {
            $this->importError = "Product not found. CSV file line # $line_no";
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
            $pa =& func_new("ProductAccess");
            if ($pa->find("product_id='".$item["product_id"]."'")) {
            	$pa->delete();
            }
        }
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

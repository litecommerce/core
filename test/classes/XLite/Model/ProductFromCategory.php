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

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* Class _ProductFromCategory provides access to category products. 
*
* @access private
* @version $Id$
* @package Kernel
*/
class XLite_Model_ProductFromCategory extends XLite_Model_Product
{
    var $fetchKeysOnly = true;

    public function __construct($catId = null)
    {
        parent::__construct();
        $this->catId = $catId; 
    }

    /*
    * Builds the sql SELECT query for the category products.
    *
    * @access private
    */
    function _buildSelect($where = null, $orderby = null, $groupby = null, $limit = null)
    {
        $table = $this->db->getTableByAlias("products");
        $link_table = $this->db->getTableByAlias("product_links");
        $sql = "SELECT links.product_id FROM $table, $link_table links WHERE $table.product_id=links.product_id AND links.category_id='$this->catId'";
        if (!empty($where)) {
            $sql .= " AND $where";
        }
        if (!empty($orderby)) {
            $sql .= " ORDER BY $orderby";
        }
        return $sql;
    }

    function _buildRead() // {{{
    {       
        $condition = array();
        foreach ($this->primaryKey as $field) {
            $condition[] = "$field='".addslashes($this->properties[$field])."'";
        }   
        $condition = implode(" AND ", $condition);
        
        $this->fetchKeysOnly = false;
        $sql = parent::_buildSelect($condition);
        $this->fetchKeysOnly = true;
        return $sql;
    } // }}} 

    function getProductsNumber($enabled=true, $where="")
    {
        $table  = $this->db->getTableByAlias("products");
        $link_table = $this->db->getTableByAlias("product_links");
        $sql  = "SELECT COUNT(*) FROM $table, $link_table links";
        $sql .= " WHERE $table.product_id=links.product_id";
        $sql .= " AND links.category_id='$this->catId'";
        if ($enabled) {
        	$sql .= " AND $table.enabled=1";
        }
        $sql .= $where;
        return $this->db->getOne($sql);
    }

    /*
    * Returns number of available for sale products
    *
    * @access public
    * @return bool
    */
    function hasProducts($enabled=true)
    {
        return (bool) $this->getProductsNumber(); 
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

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
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Model;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ProductFromCategory extends Product
{
    public $fetchKeysOnly = true;

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
        $table = $this->db->getTableByAlias('products');
        $link_table = $this->db->getTableByAlias('product_links');
        $sql = "SELECT links.product_id FROM $table, $link_table links WHERE $table.product_id=links.product_id AND links.category_id='$this->catId'";
        if (!empty($where)) {
            $sql .= " AND $where";
        }
        if (!empty($orderby)) {
            $sql .= " ORDER BY $orderby";
        }
        return $sql;
    }

    function _buildRead() 
    {
        $condition = array();
        foreach ($this->primaryKey as $field) {
            $condition[] = "$field='".addslashes($this->properties[$field])."'";
        }
        $condition = implode(' AND ', $condition);
        
        $this->fetchKeysOnly = false;
        $sql = parent::_buildSelect($condition);
        $this->fetchKeysOnly = true;
        return $sql;
    }

    function getProductsNumber($enabled=true, $where="")
    {
        $table  = $this->db->getTableByAlias('products');
        $link_table = $this->db->getTableByAlias('product_links');
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

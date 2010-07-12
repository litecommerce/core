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
class CategoriesFromProducts extends \XLite\Model\Category
{
    public $prodId;

    function _buildSelect($where = null, $orderby = null, $groupby = null, $limit = null)
    {
        $table = $this->getTable();
        $fields = array();
        foreach ($this->fields as $field=>$val) {
            $fields[] = $table.".".$field." $field";
        }
        $fields = implode(',', $fields);
        $link_table = $this->db->getTableByAlias('product_links');
        $sql = "SELECT $fields FROM $table, $link_table links WHERE $table.category_id=links.category_id AND links.product_id='$this->prodId'";
        if (!empty($where)) {
            $sql .= " AND $where";
        }
        if (!empty($orderby)) {
            $sql .= " ORDER BY $orderby";
        }
        return $sql;
    }
}

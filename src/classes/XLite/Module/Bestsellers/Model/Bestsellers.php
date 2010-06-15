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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Bestsellers model 
 * 
 * @package XLite
 * @see     ____class_see____
 * @see        ____class_see____
 * @since   3.0.0
 */
class XLite_Module_Bestsellers_Model_Bestsellers extends XLite_Base
{
    /**
     * Subcategories id
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $ids = array();

    /**
     * Get bestsellers list
     * 
     * @param integer $category_id ____param_comment____
     *  
     * @return array of XLite_Model_Product objects
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getBestsellers($numberOfBestsellers = 0, $category_id = 0)
    {
        $bestsellers = array();

        $category_id = intval($category_id);

        $bestsellersCategories = $this->xlite->get('BestsellersCategories');
        if (!is_array($bestsellersCategories)) {
            $bestsellersCategories = array();
        }

        if (isset($bestsellersCategories[$category_id])) {
            $bestsellers = $bestsellersCategories[$category_id];

        } else {
            $bestsellers = $this->calculateBestsellers($numberOfBestsellers, $category_id);

            $bestsellersCategories[$category_id] = $bestsellers;
            $this->xlite->set('BestsellersCategories', $bestsellersCategories);
        }

        return $bestsellers;
    }

    /**
     * Calculate bestsellers list
     * 
     * @param integer $cat_id Category id
     *  
     * @return array of XLite_Model_Product objects
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function calculateBestsellers($numberOfBestsellers, $cat_id)
    {
        $category = new XLite_Model_Category($cat_id);

        $bestsellers = array();

        $products = '';
        if ($cat_id != $category->get('topCategory')->get('category_id')) {

            // get all subcategories ID
            $this->ids = array();
            $this->getSubcategories($category);

            $products = false;

            if (!empty($this->ids)) {
                $sql = 'SELECT product_id FROM '
                    . $this->db->getTableByAlias('product_links')
                    . ' WHERE category_id IN (' . implode(', ', $this->ids). ')';
                $this->ids = array();

                $ids = $category->db->getAll($sql);
                if ($ids) {

                    foreach ($ids as $k => $id) {
                        $ids[$k] = $id['product_id'];
                    }

                    $products = 'AND items.product_id IN (' . implode(', ', $ids) . ')';
                }
            }
        }

        // build SQL query to select bestsellers
        if (false !== $products) {
            $order_items_table = $this->db->getTableByAlias('order_items');
            $orders_table = $this->db->getTableByAlias('orders');
            $products_table = $this->db->getTableByAlias('products');
        
            $limit = intval($numberOfBestsellers);
            $limit = 0 >= $limit ? $limit : 5;

            $limitGrace = $limit * 10;

            $sql =<<<EOT
                SELECT items.product_id, SUM(items.amount) as amount
                FROM $order_items_table as items
                LEFT OUTER JOIN $orders_table as orders ON items.order_id=orders.order_id
                LEFT OUTER JOIN $products_table as products ON items.product_id=products.product_id
                WHERE (orders.status = 'P' OR orders.status = 'C') AND products.enabled = 1
                $products
                GROUP BY items.product_id
                ORDER BY amount DESC
                LIMIT $limitGrace
EOT;

            // fill bestsellers array with product instances
            $best = $category->db->getAll($sql);
            foreach ($best as $p) {
                $product = new XLite_Model_Product($p['product_id']);
                $categories = $product->get('categories');
                if (!empty($categories) && $product->filter()) {
                    $product->category_id = $categories[0]->get('category_id');
                    $bestsellers[] = $product;
                }
            }
        }

        return $bestsellers;
    }

    /**
     * Get subcategories 
     * 
     * @param XLite_Model_Category $category Category
     *  
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSubcategories($category)
    {
        $this->ids[] = $category->get('category_id');

        $categories = $category->getSubcategories();

        for ($i = 0; $i < count($categories); $i++) {
            $this->getSubcategories($categories[$i]);
        }
    }
}

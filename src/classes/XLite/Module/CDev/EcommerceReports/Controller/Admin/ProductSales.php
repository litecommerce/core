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
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\CDev\EcommerceReports\Controller\Admin;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ProductSales extends \XLite\Module\CDev\EcommerceReports\Controller\Admin\EcommerceReports
{
    function getProductSales() 
    {
        if (is_null($this->productSales)) {
            $this->productSales = array();
            $items = $this->get('rawItems');
            array_map(array($this, 'sumProductSales'), $items);
            usort($this->productSales, array($this, "cmpProducts"));
            $productSales = array_reverse($this->productSales);
            $this->productSales = $productSales;
        }
        return $this->productSales;
    }

    function cmpProducts($p1, $p2) 
    {
        $key = $this->sort_by;
        if ($p1[$key] == $p2[$key]) {
            return 0;
        }
        return ($p1[$key] < $p2[$key]) ? -1 : 1;
    }

    function sumProductSales($item) 
    {
        $id = $item['product_id'] . (strlen($item['options']) ? md5($item['options']) : "");
        $orderItem = new \XLite\Model\OrderItem();
        $found = $orderItem->find("order_id=".$item['order_id']." AND item_id='".addslashes($item['item_id'])."'");
        $order = new \XLite\Model\Order($item['order_id']);
        $orderItem->set('order', $order);
        $item['price'] = $orderItem->get('price');
         
        if (!isset($this->productSales[$id])) {
            $this->productSales[$id] = $item;
            $this->productSales[$id]['total'] = 0;
            $this->productSales[$id]['order_item'] = $orderItem;
        } else {
            $this->productSales[$id]['amount'] += $item['amount'];
        }

        $this->productSales[$id]['total'] += $item['amount'] * $item['price'];
        $this->productSales[$id]['avg_price'] = $this->productSales[$id]['total'] / $this->productSales[$id]['amount'];
    }
    
    function sumTotal($field) 
    {
        $total = 0;
        foreach ($this->get('productSales') as $sale) {
            $total += $sale[$field];
        }
        return $total;
    }

    function getAveragePrice($total, $amount)
    {
        return $this->sumTotal($total)/$this->sumTotal($amount);
    }
}

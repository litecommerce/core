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

namespace XLite\Module\FeaturedProducts\Controller\Admin;

/**
 * \XLite\Module\FeaturedProducts\Controller\Admin\Categories
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Categories extends \XLite\Controller\Admin\Categories implements \XLite\Base\IDecorator
{
    /**
     * init 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function init() 
    {
        parent::init();

        if (!isset(\XLite\Core\Request::getInstance()->search_category)) {
            \XLite\Core\Request::getInstance()->search_category = \XLite\Core\Request::getInstance()->category_id;
        }
    }

    /**
     * doActionAddFeaturedProducts 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionAddFeaturedProducts()
    {
        if (isset(\XLite\Core\Request::getInstance()->product_ids)) {
            $products = array();

            foreach (\XLite\Core\Request::getInstance()->product_ids as $product_id => $value) {
                $products[] = new \XLite\Model\Product($product_id);
            }

            $category = new \XLite\Model\Category($this->category_id);
            $category->addFeaturedProducts($products);
        }
    }

    /**
     * getProducts 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getProducts()
    {
        if (\XLite\Core\Request::getInstance()->mode != 'search') {
            return array();
        }

        $p = new \XLite\Model\Product();
        $result = $p->advancedSearch(
            $this->substring,
            $this->search_productsku,
            $this->search_category,
            $this->subcategory_search
        );
        $this->productsFound = count($result);

        return $result;
    }

    /**
     * Process action 'update_featured_products'
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionUpdateFeaturedProducts()
    {
        // Delete featured products if it was requested
        $deleteProducts = \XLite\Core\Request::getInstance()->delete;
        
        if (!is_null($deleteProducts) && is_array($deleteProducts) && !empty($deleteProducts)) {

            foreach (array_keys($deleteProducts) as $productId) {
                $products[] = new \XLite\Model\Product($productId);
            }

            $category = new \XLite\Model\Category(\XLite\Core\Request::getInstance()->category_id);

            if ($category->isExists() || 0 === intval(\XLite\Core\Request::getInstance()->category_id)) {
                $category->deleteFeaturedProducts($products);
            }
        }

        // Update order_by of featured products list is it was requested
        $orderProducts = \XLite\Core\Request::getInstance()->orderbys;

        if (!is_null($orderProducts) && is_array($orderProducts) && !empty($orderProducts)) {

            foreach ($orderProducts as $productId => $orderBy) {
                $fp = new \XLite\Module\FeaturedProducts\Model\FeaturedProduct();
                $fp->set('category_id', \XLite\Core\Request::getInstance()->category_id);
                $fp->set('product_id', $productId);
                $fp->set('order_by', $orderBy);
                $fp->update();
            }
        }
    }
}

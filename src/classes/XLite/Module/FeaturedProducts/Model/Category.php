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
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\FeaturedProducts\Model;

/**
 * \XLite\Module\FeaturedProducts\Model\Category 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Category extends \XLite\Model\Category implements \XLite\Base\IDecorator
{
    /**
     * Cached featured products list
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $featuredProducts = null;


    /**
     * Get featured products list
     * 
     * @param string $orderby orderby string
     *  
     * @return array of \XLite\Module\FeaturedProducts\Model\FeaturedProduct objects
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getFeaturedProducts($orderby = null)
    {
        if (!isset($this->featuredProducts)) {

            $featuredProduct = new \XLite\Module\FeaturedProducts\Model\FeaturedProduct();

            foreach ($featuredProduct->findAll('category_id = \'' . $this->category_id . '\'', $orderby) as $handler) {
                $_featuredProducts = array();
                $_featuredProducts = $handler;
                $_featuredProducts->set('product', $handler->getProduct());

                $this->featuredProducts[] = $_featuredProducts;
            }
        }

        return $this->featuredProducts;
    }

    /**
     * Add specified products to the featured products list
     * 
     * @param array $products Array of \XLite\Model\Product objects
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function addFeaturedProducts($products)
    {
        if (is_array($products)) {
            foreach ($products as $product) {
    			$fp = new \XLite\Module\FeaturedProducts\Model\FeaturedProduct();
    			$fp->set('category_id', $this->category_id);
       			$fp->set('product_id', $product->get('product_id'));
        		if (!$fp->isExists()) {
            		$fp->create();
    			}
    		}
        }
    }

    /**
     * Delete specified products from the featured products list
     * 
     * @param array $products Array of \XLite\Model\Product objects
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function deleteFeaturedProducts($products)
    {
        if (is_array($products)) {
            foreach ($products as $product) {
    			$fp = new \XLite\Module\FeaturedProducts\Model\FeaturedProduct();
    			$fp->set('category_id', $this->category_id);
    			$fp->set('product_id', $product->get('product_id'));
    			$fp->delete();
    		}
        }
    }

    /**
     * Delete product
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function delete()
    {
        $this->deleteFeaturedProducts($this->getFeaturedProducts());
        parent::delete();
    }
}

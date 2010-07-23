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

namespace XLite\Controller\Admin;


/**
 * Products list controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ProductList extends AAdmin
{
    /**
     * Search mode 
     */
    const MODE_SEARCH = 'search';


    /**
     * Return params for product search 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSearchParams()
    {
        $request = \XLite\Core\Request::getInstance();

        return new \XLite\Core\CommonCell(
            array(
                \XLite\Model\Repo\Product::P_SUBSTRING => $request->substring,
            )
        );
    }


    /**
     * getOrderBy 
     * 
     * @param \XLite\Model\Product $product current product
     *  
     * @return int
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getOrderBy(\XLite\Model\Product $product)
    {
        return $product->getOrderBy(\XLite\Core\Request::getInstance()->search_category);
    }

    /**
     * Search products 
     * TODO - add caching here
     * 
     * @return \Doctrine\ORM\PersistentCollection
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getProducts()
    {
        $result = null;

    	if (self::MODE_SEARCH === \XLite\Core\Request::getInstance()->mode) {
            $result = \XLite\Core\Database::getRepo('\XLite\Model\Product')->search($this->getSearchParams());
        }

        return $result;



/*        	if (!isset($this->productsList)) {
                $p = new \XLite\Model\Product();
                $p->collectGarbage();
    			$this->productsList = $p->advancedSearch(
    				$this->substring,
    				$this->search_productsku,
    				$this->search_category,
    				$this->subcategory_search,
    				false,
    				false
    			);
                $this->productsFound = count($this->productsList);
            }

            $result = $this->productsList;
        }

        return $result;*/
    }

    /**
     * getProductsFound 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getProductsFound()
    {
        $this->getProducts();
        return $this->productsFound;
    }

    /**
     * doActionUpdate 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionUpdate()
    {
        foreach ($this->product_orderby as $product_id => $order_by) {
            $p = new \XLite\Model\Product($product_id);
            $p->set('order_by', $order_by);
            $p->set('price', $this->product_price[$product_id]);
            $p->update();
        }

        $this->set('status', 'updated');
    }

    /**
     * doActionDelete 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionDelete()
    {
        $productIds = (isset(\XLite\Core\Request::getInstance()->product_ids) && is_array(\XLite\Core\Request::getInstance()->product_ids) ? \XLite\Core\Request::getInstance()->product_ids : null);

        if (!empty($productIds)) {

            if (isset(\XLite\Core\Request::getInstance()->confirmed)) {

                $this->set('mode', 'search');

    			if (!\XLite\Core\Request::getInstance()->confirmed) {
    				return;
    			}

                foreach ($productIds as $productId) {
        			$p = new \XLite\Model\Product($productId);
                    $p->delete();
                }

                $this->set('status', 'deleted');
 
            } else {

        		$this->set('valid', false);
                $this->set('mode', 'confirmation');

                $products = array();

                foreach ($productIds as $idx => $productId) {
                    $products[$idx] = new \XLite\Model\Product($productId);
                }

                $this->set('product_ids', $products);
            }
        }
    }

    /**
     * doActionClone 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionClone()
    {
        if (isset(\XLite\Core\Request::getInstance()->product_ids) && is_array(\XLite\Core\Request::getInstance()->product_ids)) {

            foreach (\XLite\Core\Request::getInstance()->product_ids as $product_id) {
    			$p = new \XLite\Model\Product($product_id);
                $product = $p->cloneObject();

    			foreach ($p->get('categories') as $category) {
    				$product->addCategory($category);
                }

    			$product->set('name', $product->get('name') . ' (CLONE)');
    			$product->update();
                $this->set('status', 'cloned');
            }
        }
    }
}


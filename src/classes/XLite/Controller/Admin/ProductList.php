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
     * params 
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $params = array('target', 'mode', 'search_productsku', 'substring', 'search_category', 'subcategory_search', 'pageID', 'status');

    /**
     * productsList 
     * 
     * @var    mixed
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $productsList = null;

    /**
     * productsFound 
     * 
     * @var    float
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $productsFound = 0;

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
        $this->set('mode', \XLite\Core\Request::getInstance()->mode);
    }

    /**
     * _getExtraParams 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function _getExtraParams()
    {
    	return array('search_productsku', 'substring', 'search_category', 'subcategory_search', 'status');
    }

    /**
     * getExtraParams 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getExtraParams()
    {
    	$form_params = $this->_getExtraParams();

        $result = parent::getAllParams();

        if (is_array($result)) {

            foreach ($result as $param => $name) {

                if (in_array($param, $form_params)) {

        			if (isset($result[$param])) {
        				unset($result[$param]);
        			}
        		}
            }
        }

        return $result;
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
        $result = null;

    	if ('search' == \XLite\Core\Request::getInstance()->mode) {

        	if (is_null($this->productsList)) {
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

        return $result;
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


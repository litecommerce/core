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

namespace XLite\Module\CDev\ProductAdviser\View;

/**
 * New arrivals widget
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class NewArrivals extends \XLite\View\ItemsList\Product\Customer\ACustomer
{
     /*
     * Parameter specifies that widget is displayed as a page content
     */
   
    const PARAM_PAGE_CONTENT = 'pageContent';

    /**
     * Parameter specifies if new arrivals must be prepared for current category or for all catalog
     */

    const PARAM_USE_NODE = 'useNode';

    /**
     * Widget parameters names 
     */
    const PARAM_PRODUCT_ID  = 'product_id';
    const PARAM_CATEGORY_ID = 'category_id';


    /**
     * Flag that means if it is need to display link 'See more...'
     * 
     * @var    bool
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $additionalPresent = false;

    /**
     * Get widget title
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getHead()
    {
        return 'New arrivals';
    }

    /**
     * Return class name for the list pager
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getPagerClass()
    {
        // TODO - rework
        return null;
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        // pageContent - is a service parameter for displaying widget as a page content
        $this->widgetParams += array(
            self::PARAM_PAGE_CONTENT => new \XLite\Model\WidgetParam\Checkbox(
                'Widget is displayed as page content', false, false
            ),
            self::PARAM_USE_NODE     => new \XLite\Model\WidgetParam\Checkbox(
                'Show category-specific new arrivals', ('Y' == $this->config->CDev->ProductAdviser->category_new_arrivals), true
            ),
            self::PARAM_PRODUCT_ID  => new \XLite\Model\WidgetParam\ObjectId\Product('Product ID', 0, false),
            self::PARAM_CATEGORY_ID => new \XLite\Model\WidgetParam\ObjectId\Category('Category ID', 0, false),
        );

        $this->widgetParams[self::PARAM_WIDGET_TYPE]->setValue($this->config->CDev->ProductAdviser->new_arrivals_type);
        $this->widgetParams[self::PARAM_DISPLAY_MODE]->setValue(self::DISPLAY_MODE_LIST);
        $this->widgetParams[self::PARAM_GRID_COLUMNS]->setValue(3);
        $this->widgetParams[self::PARAM_SHOW_DESCR]->setValue(true);
        $this->widgetParams[self::PARAM_SHOW_PRICE]->setValue(true);
        $this->widgetParams[self::PARAM_SHOW_ADD2CART]->setValue(true);
        $this->widgetParams[self::PARAM_SIDEBAR_MAX_ITEMS]->setValue(
            $this->config->CDev->ProductAdviser->number_new_arrivals
        );

        $this->widgetParams[self::PARAM_SHOW_DISPLAY_MODE_SELECTOR]->setValue(false);
        $this->widgetParams[self::PARAM_SHOW_ALL_ITEMS_PER_PAGE]->setValue(true);
        $this->widgetParams[self::PARAM_SHOW_SORT_BY_SELECTOR]->setValue(false);
        $this->widgetParams[self::PARAM_SORT_BY]->setValue('Name');
        $this->widgetParams[self::PARAM_SORT_ORDER]->setValue('asc');

        $this->widgetParams[self::PARAM_PAGE_CONTENT]->setValue(false);
    }

    /**
     * Define so called "request" parameters
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineRequestParams()
    {
        parent::defineRequestParams();

        $this->requestParams[] = self::PARAM_CATEGORY_ID;
        $this->requestParams[] = self::PARAM_PRODUCT_ID;
    }

    /**
     * Check if there are product to display 
     * 
     * @return boolean 
     * @access protected
     * @since  3.0.0
     */
    protected function checkProductsToDisplay()
    {
        return 0 < $this->getParam(self::PARAM_SIDEBAR_MAX_ITEMS)
            && $this->getNewArrivalsProducts();
    }

    /**
     * Check if widget is visible
     *
     * @return boolean 
     * @access public
     * @since  3.0.0
     */
    protected function isVisible()
    {
        $visibility = parent::isVisible()
            && $this->checkProductsToDisplay();

        if ($visibility && !$this->getParam(self::PARAM_PAGE_CONTENT)) {

            if ('new_arrivals' == \XLite\Core\Request::getInstance()->target) {

                // Do not display widget on page with target='new_arrivals' if it's not page content widget
                $visibility = false;

            } elseif (
                !$this->getParam(self::PARAM_IS_EXPORTED)
                && $this->getParam(self::PARAM_WIDGET_TYPE) != $this->config->CDev->ProductAdviser->new_arrivals_type
            ) {
                /**
                 * Do not display widget in standalone mode if it is passed
                 * widgetType argument different from setting in the config
                 */
                $visibility = false;
            }
        }

        if (
            $visibility
            && self::WIDGET_TYPE_CENTER == $this->getParam(self::PARAM_WIDGET_TYPE)
            && 'new_arrivals' == \XLite\Core\Request::getInstance()->target
        ) {
            // Display pager if widget is a page content widget
            $this->widgetParams[self::PARAM_SHOW_ALL_ITEMS_PER_PAGE]->setValue(false);
        }

        return $visibility;
    }

    /**
     * Get Value of 'Show category-specific new arrivals' option
     * 
     * @return boolean 
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCategorySpecificArrivals()
    {
        return $this->getParam(self::PARAM_USE_NODE);
    }

    /**
     * Return products list
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getData(\XLite\Core\CommonCell $cnd, $countOnly = false)
    {
        return $this->getNewArrivalsProducts();
    }

    /**
     * Check status of 'More...' link for sidebar list
     * 
     * @return boolean 
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isShowMoreLink()
    {
        return $this->additionalPresent;
    }

    /**
     * Get 'More...' link URL for sidebar list
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getMoreLinkURL()
    {
        return $this->buildURL('new_arrivals');
    }

    /**
     * Get 'More...' link text for sidebar list
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getMoreLinkText()
    {
        return 'All new arrivals...';
    }

    /**
     * Get current category
     * 
     * @return \XLite\Model\Category
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDialogCategory()
    {
        $allowedtargets = array('category', 'product');

        return (in_array($this->target, $allowedtargets) && intval($this->getParam(self::PARAM_CATEGORY_ID)) > 0)
            ? $this->getCategory()
            : null;
    }

    /**
     * Get current product Id
     * 
     * @return integer
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDialogProductId()
    {
        return ('product' == $this->target && $this->getProduct()->isExists())
            ? $this->getProduct()->get('product_id')
            : null;
    }

    /**
     * Check if widget displayed as a page content 
     * 
     * @return boolean 
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isContentDialog()
    {
        return $this->getParam(self::PARAM_PAGE_CONTENT)
            && 'new_arrivals' == \XLite\Core\Request::getInstance()->target;
    }

    /**
     * Check if product is in the category or its subcategories
     * 
     * @param \XLite\Model\Product  $product  Product
     * @param \XLite\Model\Category $category Category
     *  
     * @return boolean 
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isInCategory(\XLite\Model\Product $product, \XLite\Model\Category $category)
    {
        $return = false;

        $signCategory = $product->inCategory($category);

        if ($signCategory) {
            $return = true;

        } else {
            foreach ($category->getSubcategories() as $cat_idx => $cat) {
                $signCategory |= $this->isInCategory($product, $subcategories[$cat_idx]);
                if ($signCategory) {
                    $return = true;
                    break;
                }
            }
        }

        return $return;
    }

    /**
     * Recursive search of products in current category and its subcategories
     * 
     * @param \XLite\Model\Category $_category Category
     *  
     * @return boolean 
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function recursiveArrivalsSearch(\XLite\Model\Category $_category)
    {
        if (
            $this->isContentDialog()
            && $this->additionalPresent
            && count($this->_new_arrival_products) >= $this->getParam(self::PARAM_SIDEBAR_MAX_ITEMS)
        ) {
            return true;
        }

        $timeLimit = time();
        $timeCondition = $this->config->CDev->ProductAdviser->period_new_arrivals * 3600;
        $category_id = $_category->get('category_id');

        $obj = new \XLite\Module\CDev\ProductAdviser\Model\ProductNewArrivals();
        $arrival_table = $this->db->getTableByAlias($obj->alias);
        $links_table = $this->db->getTableByAlias('product_links');

        $fromSQL = array(
            $links_table . ' AS links',
            $arrival_table . ' AS arrivals',
        );

        $whereSQL = array(
            "links.product_id=arrivals.product_id",
            "links.category_id='$category_id'",
            "(arrivals.new='Y' OR ((arrivals.updated + '$timeCondition') > '$timeLimit'))",
        );

        $querySQL = 'SELECT arrivals.product_id, arrivals.updated FROM ' . implode(', ', $fromSQL) . ' WHERE ' . implode(' AND ', $whereSQL) . ' ORDER BY arrivals.updated DESC';

        foreach ($this->db->getAll($querySQL) as $row) {
            $product_id = $row['product_id'];

            $obj = new \XLite\Module\CDev\ProductAdviser\Model\ProductNewArrivals($product_id);
            if ($this->checkArrivalCondition($_category, $obj)) {
                if (
                    !$this->isContentDialog()
                    && count($this->_new_arrival_products) > $this->getParam(self::PARAM_SIDEBAR_MAX_ITEMS)
                ) {
                    $this->additionalPresent = true;
                    return true;
                }

                if (!isset($this->_new_arrival_products[$product_id])) {
                    $this->_new_arrival_products[$product_id] = new \XLite\Model\Product($product_id);
                    $this->_new_arrival_products_updated[$product_id] = $row['updated'];
                }
            }
        }

        // get subcategories list
        $category = new \XLite\Model\Category();
        $categories = $category->findAll("parent='$category_id'");
        foreach ($categories as $category) {
            if ($this->recursiveArrivalsSearch($category)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if product is available
     * 
     * @param mixed $category \XLite\Model\Category object
     * @param mixed $ps       \XLite\Model\Product object
     *  
     * @return boolean 
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkArrivalCondition($category, $ps)
    {
        $product_id = $this->getDialogProductId();
        $product = new \XLite\Model\Product($ps->get('product_id'));

        $addSign = (isset($product_id) && $product->get('product_id') == $product_id) ? false : true;
        if ($addSign) {
            $addSign &= $product->filter();
            $addSign &= $product->is('available');
            // additional check
            if (!$product->is('available') || (isset($product->properties) && is_array($product->properties) && !isset($product->properties['enabled']))) {
                // removing link to non-existing product
                if (intval($ps->get('product_id')) > 0) {
                    $ps->delete();
                }
                $addSign &= false;
            }
        }

        return $addSign;
    }

    /**
     * Get the list of new arrival products
     * 
     * @return array(\XLite\Model\Product) Objects
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getNewArrivalsProducts()
    {
        $products = $this->xlite->NewArrivalsProducts;

        if (isset($products)) {
            $this->additionalPresent = $this->xlite->NewArrivalsAdditionalPresent;
            return $products;
        }

        $category = $this->getDialogCategory();
        $product_id = $this->getDialogProductId();

        // Recursive search
        if ($this->getCategorySpecificArrivals()) {

            $this->_new_arrival_products = array();
            $this->_new_arrival_products_updated = array();
            $this->additionalPresent = false;

            if (is_null($category)) {
                // deal with root category
                $obj = new \XLite\Model\Category();
                $categories = $obj->findAll('parent = \'0\'');

            } else {
                $categories = array($category);
            }

            // recursively search new arrival products
            foreach ($categories as $cat) {
                if ($this->recursiveArrivalsSearch($cat)) {
                    break;
                }
            }

            if (is_array($this->_new_arrival_products_updated) && is_array($this->_new_arrival_products)) {
                arsort($this->_new_arrival_products_updated, SORT_NUMERIC);
                // sort by keys, 'cos values are objects
                krsort($this->_new_arrival_products, SORT_NUMERIC);
            }

            $products = array_values($this->_new_arrival_products);
            $this->productsNumber = count($products);
            $this->xlite->set('NewArrivalsProducts', $products);
            $this->xlite->set('NewArrivalsAdditionalPresent', $this->additionalPresent);

            return $products;
        }

        $maxViewed = $this->getParam(self::PARAM_SIDEBAR_MAX_ITEMS);
        $infinityRange = 0 >= $maxViewed;
        $products = array();
        $productsStats = array();
        $statsOffset = 0;
        $stats = new \XLite\Module\CDev\ProductAdviser\Model\ProductNewArrivals();
        $timeCondition = $this->config->CDev->ProductAdviser->period_new_arrivals * 3600;
        $timeLimit = time();
        $maxSteps = ($this->isContentDialog() || $infinityRange)
            ? 1
            : ceil($stats->count('new = \'Y\' OR ((updated + \'' . $timeCondition . '\') > \'' . $timeLimit . '\')') / $maxViewed);

        for ($i = 0; $i < $maxSteps; $i++) {
            $limit = ($this->isContentDialog() || $infinityRange)
                ? null
                : $statsOffset . ', ' . $maxViewed;

            $productsStats = $stats->findAll("new='Y' OR ((updated + '$timeCondition') > '$timeLimit')", null, null, $limit);
            foreach ($productsStats as $ps) {
                $product = new \XLite\Model\Product($ps->get('product_id'));

                if ($this->checkArrivalCondition($category, $ps)) {
                    $product->checkSafetyMode();
                    if (!$infinityRange && count($products) == $maxViewed && !$this->isContentDialog()) {
                        $this->additionalPresent = true;
                        break;
                    }

                    $products[] = $product;
                }
            }

            if ($this->additionalPresent) {
                break;
            }

            $statsOffset += $maxViewed;
        }

        $this->productsNumber = count($products);
        $this->xlite->set('NewArrivalsProducts', $products);
        $this->xlite->set('NewArrivalsAdditionalPresent', $this->additionalPresent);

        return $products;
    }


    /**
     * Return list of targets allowed for this widget
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getAllowedTargets()
    {
        $result = parent::getAllowedTargets();
        $result[] = 'main';
        $result[] = 'category';
        $result[] = 'product';
        $result[] = 'cart';
        $result[] = 'recently_viewed';
        $result[] = 'new_arrivals';
    
        return $result;
    }
}


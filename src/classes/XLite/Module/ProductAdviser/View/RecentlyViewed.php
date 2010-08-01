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

namespace XLite\Module\ProductAdviser\View;

/**
 * Recently viewed products list
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class RecentlyViewed extends \XLite\View\ItemsList\Product\Customer\ACustomer
{
    /*
     * Parameter specifies that widget is displayed as a page content
     */
    const PARAM_PAGE_CONTENT = 'pageContent';
    const PARAM_PRODUCT_ID   = 'product_id';


    /**
     * The number of products displayed by widget 
     * 
     * @var    integer
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $productsNumber = 0;

    /**
     * Flag that means if it is need to display link 'See more...'
     * 
     * @var    bool
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $additionalPresent = false;

    /**
     * Recently viewed products array
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $recentlyViewedProducts = null;

    /**
     * Get widget title 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getHead()
    {
        return 'Recently viewed';
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
            self::PARAM_PRODUCT_ID => new \XLite\Model\WidgetParam\ObjectId\Product('Product ID', 0, false),
        );

        $this->requestParams[] = self::PARAM_PRODUCT_ID;

        $this->widgetParams[self::PARAM_DISPLAY_MODE]->setValue(self::DISPLAY_MODE_LIST);
        $this->widgetParams[self::PARAM_GRID_COLUMNS]->setValue(3);
        $this->widgetParams[self::PARAM_SHOW_DESCR]->setValue(true);
        $this->widgetParams[self::PARAM_SHOW_PRICE]->setValue(true);
        $this->widgetParams[self::PARAM_SHOW_ADD2CART]->setValue(true);
        $this->widgetParams[self::PARAM_SIDEBAR_MAX_ITEMS]->setValue(
            $this->config->ProductAdviser->number_recently_viewed
        );

        $this->widgetParams[self::PARAM_SHOW_ALL_ITEMS_PER_PAGE]->setValue(true);
        $this->widgetParams[self::PARAM_SORT_BY]->setValue('Name');
        $this->widgetParams[self::PARAM_SORT_ORDER]->setValue('asc');

        $this->widgetParams[self::PARAM_PAGE_CONTENT]->setValue(false);
    }

    /**
     * Check if there are product to display
     *
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function checkProductsToDisplay()
    {
        return 0 < $this->getParam(self::PARAM_SIDEBAR_MAX_ITEMS)
            && $this->getRecentliesProducts();
    }

    /**
     * Return current product Id (if presented)
     * 
     * @return mixed
     * @access protected
     * @since  3.0.0
     */
    protected function getDialogProductId()
    {
        return 'product' == \XLite\Core\Request::getInstance()->target
            ? \XLite\Core\Request::getInstance()->product_id
            : null;
    }

    /**
     * Check if widget is visible
     *
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isVisible()
    {
        if (
            self::WIDGET_TYPE_CENTER == $this->getParam(self::PARAM_WIDGET_TYPE)
            && 'recently_viewed' == \XLite\Core\Request::getInstance()->target
        ) {
            $this->widgetParams[self::PARAM_SHOW_ALL_ITEMS_PER_PAGE]->setValue(false);
        }

        return parent::isVisible()
            && $this->checkProductsToDisplay()
            && (
                'recently_viewed' != \XLite\Core\Request::getInstance()->target
                || $this->getParam(self::PARAM_PAGE_CONTENT)
                || \XLite\Core\Request::getInstance()->isAJAX()
            );
    }

    /**
     * Return products list
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getData(\XLite\Core\CommonCell $cnd, $countOnly = false)
    {
        return $this->getRecentliesProducts();
    }

    /**
     * Check status of 'More...' link for sidebar list
     * 
     * @return void
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
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getMoreLinkURL()
    {
        return $this->buildURL('recently_viewed');
    }

    /**
     * Get 'More...' link text for sidebar list
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getMoreLinkText()
    {
        return 'All viewed...';
    }

    /**
     * Get recently viewed products list
     * 
     * @return array of \XLite\Model\Product objects
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getRecentliesProducts()
    {
        // Check if products list has been prepared before
        if (!isset($this->recentlyViewedProducts)) {

            // Do not include into the products list currently viewed product
            $productId = (int)$this->getDialogProductId();

            $rvObj = new \XLite\Module\ProductAdviser\Model\ProductRecentlyViewed();
            $rvProducts = $rvObj->findAll(
                'sid = \'' . $this->session->getID() . '\' AND product_id != \'' . $productId . '\'',
                'last_viewed DESC'
            );
            $products = array();

            // Prepare products list
            if (is_array($rvProducts)) {

                foreach ($rvProducts as $rvProduct) {

                    $product = new \XLite\Model\Product($rvProduct->get('product_id'));

                    $addSign = !(isset($productId) && $product->get('product_id') == $productId);

                    if ($addSign) {
                        $available = $product->is('available');

                        $addSign &= $product->filter();
                        $addSign &= $available;

                        $unsetEnabled = isset($product->properties)
                            && is_array($product->properties)
                            && !isset($product->properties['enabled']);

                        // additional check
                        if (!$available || $unsetEnabled) {

                            // removing link to non-existing product
                            if (0 < intval($rvProduct->get('product_id'))) {
                                $rvProduct->delete();
                            }

                            $addSign = false;
                        }

                        if ($addSign) {
                            $product->checkSafetyMode();
                            $products[] = $product;
                        }
                    }
                }
            }

            if (!empty($products)) {
                // Save prepared products list for further usage (cache)
                $this->recentlyViewedProducts = $products;
            }
        }

        // Check if widget displayed not as a page content
        if (
            'recently_viewed' != \XLite\Core\Request::getInstance()->target
            && is_array($this->recentlyViewedProducts)
            && count($this->recentlyViewedProducts) > $this->getParam(self::PARAM_SIDEBAR_MAX_ITEMS)
        ) {

            // Cut products list
            $return = array_slice($this->recentlyViewedProducts, 0, $this->getParam(self::PARAM_SIDEBAR_MAX_ITEMS));

            // Set up flag that products list is longer than it could be displayed
            $this->additionalPresent = true;

        } else {
            $return = $this->recentlyViewedProducts;
        }

        return $return;
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
        $result[] = 'checkout';
    
        return $result;
    }
}

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
 * @version    SVN: $Id: CategoryProducts.php 3650 2010-08-01 14:39:12Z vvs $
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\View\ItemsList\Product\Customer;

/**
 * Category products list widget
 *
 * @package XLite
 * @see     ____class_see____
 * @since   3.0
 */
class Category extends \XLite\View\ItemsList\Product\Customer\ACustomer
{
    /**
     * Widget parameter names
     */

    const PARAM_CATEGORY_ID = 'category_id';


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
        return '\XLite\View\Pager\ProductsList\CategoryProducts';
    }

    /**
     * Return title
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getHead()
    {
        return 'Catalog';
    }

    /**
     * getCategory 
     * 
     * @return XLite\Model\Category
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCategory()
    {
        return $this->getWidgetParams(self::PARAM_CATEGORY_ID)->getObject();
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_CATEGORY_ID => new \XLite\Model\WidgetParam\ObjectId\Category('Category ID', 0),
        );
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
    }

    /**
     * getSortBy 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSortBy()
    {
        return $this->getParam(self::PARAM_SORT_BY);
    }

    /**
     * getSortOrder 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSortOrder()
    {
        return strtoupper($this->getParam(self::PARAM_SORT_ORDER));
    }

    /**
     * addOrderByCondition 
     * 
     * @param \XLite\Core\CommonCell $cnd search condition
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function addOrderByCondition(\XLite\Core\CommonCell $cnd)
    {
        $cnd->{\XLite\Model\Repo\Product::P_ORDER_BY} = array($this->getSortBy(), $this->getSortOrder());

        return $cnd;
    }

    /**
     * Return products list
     * 
     * @param \XLite\Core\CommonCell $cnd       search condition
     * @param bool                   $countOnly return items list or only its size
     *  
     * @return array|int
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getData(\XLite\Core\CommonCell $cnd, $countOnly = false)
    {
        $result = null;

        if ($category = $this->getCategory()) {
            $result = $category->getProducts($this->addOrderByCondition($cnd), $countOnly);
        }

        return $result;
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
        $result[] = 'category';
    
        return $result;
    }
}

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

namespace XLite\Module\Bestsellers\View;

/**
 * Bestsellers widget 
 * 
 * @package XLite
 * @see     ____class_see____
 * @see        ____class_see____
 * @since   3.0.0
 */
class Bestsellers extends \XLite\View\ItemsList\Product\Customer\ACustomer
{
    /**
     * Widget parameter names
     */

    const PARAM_ROOT_ID     = 'rootId';
    const PARAM_USE_NODE    = 'useNode';
    const PARAM_CATEGORY_ID = 'category_id';

    /**
     * Category id
     * 
     * @var    mixed
     * @access protected
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected $rootCategoryId = null;

    /**
     * Bestsellers products
     * 
     * @var    mixed
     * @access protected
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected $bestsellProducts = null;

    /**
     * Get title
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getHead()
    {
        return 'Bestsellers';
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
        return '\XLite\Module\Bestsellers\View\Pager\Pager';
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_USE_NODE => new \XLite\Model\WidgetParam\Checkbox(
                'Use current category id', true, true
            ),
            self::PARAM_ROOT_ID => new \XLite\Model\WidgetParam\ObjectId\Category(
                'Root category Id', 0, true, true
            ),
            self::PARAM_CATEGORY_ID => new \XLite\Model\WidgetParam\ObjectId\Category(
                'Category ID', 0, false
            ),
        );

        $this->widgetParams[self::PARAM_WIDGET_TYPE]->setValue(
            $this->config->Bestsellers->bestsellers_menu
            ? self::WIDGET_TYPE_SIDEBAR
            : self::WIDGET_TYPE_CENTER
        );

        $this->widgetParams[self::PARAM_DISPLAY_MODE]->setValue(self::DISPLAY_MODE_LIST);
        $this->widgetParams[self::PARAM_GRID_COLUMNS]->setValue(3);
        $this->widgetParams[self::PARAM_SHOW_THUMBNAIL]->setValue('Y' == $this->config->Bestsellers->bestsellers_thumbnails);
        $this->widgetParams[self::PARAM_SHOW_DESCR]->setValue(true);
        $this->widgetParams[self::PARAM_SHOW_PRICE]->setValue(true);
        $this->widgetParams[self::PARAM_SHOW_ADD2CART]->setValue(true);
        $this->widgetParams[self::PARAM_SIDEBAR_MAX_ITEMS]->setValue($this->config->Bestsellers->number_of_bestsellers);

        $this->widgetParams[self::PARAM_SHOW_DISPLAY_MODE_SELECTOR]->setValue(false);
        $this->widgetParams[self::PARAM_SHOW_ALL_ITEMS_PER_PAGE]->setValue(true);
        $this->widgetParams[self::PARAM_SHOW_SORT_BY_SELECTOR]->setValue(false);
        $this->widgetParams[self::PARAM_SORT_BY]->setValue('Name');
        $this->widgetParams[self::PARAM_SORT_ORDER]->setValue('asc');
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
     * Return products list
     * 
     * @return mixed
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getData(\XLite\Core\CommonCell $cnd, $countOnly = false)
    {
        if (is_null($this->bestsellProducts)) {

            $limit = (int)(self::WIDGET_TYPE_SIDEBAR == $this->getParam(self::PARAM_WIDGET_TYPE)
                ? $this->getParam(self::PARAM_SIDEBAR_MAX_ITEMS)
                : $this->config->Bestsellers->number_of_bestsellers);

            $this->bestsellProducts = \XLite\Core\Database::getRepo('XLite\Model\Product')
                ->findBestsellers(
                    $limit,
                    $this->getRootId()
                );
        }

        $result = true === $countOnly
            ? count($this->bestsellProducts)
            : $this->bestsellProducts;

        return $result;
    }

    /**
     * Return category Id to use
     * 
     * @return int
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getRootId()
    {
        if (is_null($this->rootCategoryId)) {

            $this->rootCategoryId = $this->getParam(self::PARAM_USE_NODE) 
                ? intval(\XLite\Core\Request::getInstance()->category_id) 
                : $this->getParam(self::PARAM_ROOT_ID);

        }

        return $this->rootCategoryId;
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
    
        return $result;
    }

}

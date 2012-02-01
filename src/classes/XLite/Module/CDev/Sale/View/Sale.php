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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Module\CDev\Sale\View;

/**
 * Sale products block widget
 *
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @ListChild (list="center.bottom", zone="customer", weight="600")
 * @ListChild (list="sidebar.first", zone="customer", weight="170")
 */
class Sale extends \XLite\Module\CDev\Sale\View\ASale
{
    /**
     * Widget parameter
     */
    const PARAM_MAX_ITEMS_TO_DISPLAY = 'maxItemsToDisplay';

    /**
     * Return list of targets allowed for this widget
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getAllowedTargets()
    {
        $result = parent::getAllowedTargets();

        $result[] = 'main';
        $result[] = 'category';

        return $result;
    }

    /**
     * Initialize widget (set attributes)
     *
     * @param array $params Widget params
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function setWidgetParams(array $params)
    {
        parent::setWidgetParams($params);

        unset($this->widgetParams[\XLite\View\Pager\APager::PARAM_SHOW_ITEMS_PER_PAGE_SELECTOR]);
        unset($this->widgetParams[\XLite\View\Pager\APager::PARAM_ITEMS_PER_PAGE]);
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_MAX_ITEMS_TO_DISPLAY => new \XLite\Model\WidgetParam\Int(
                'Maximum products to display', $this->getMaxCountInBlock(), true, true
            ),
        );

        $widgetType = \XLite\Core\Config::getInstance()->CDev->Sale->sale_menu
            ? self::WIDGET_TYPE_SIDEBAR
            : self::WIDGET_TYPE_CENTER;

        $this->widgetParams[self::PARAM_WIDGET_TYPE]->setValue($widgetType);

        unset($this->widgetParams[self::PARAM_SHOW_DISPLAY_MODE_SELECTOR]);
        unset($this->widgetParams[self::PARAM_SHOW_SORT_BY_SELECTOR]);
    }

    /**
     * Returns search products conditions
     *
     * @param \XLite\Core\CommonCell $cnd Initial search conditions
     *
     * @return \XLite\Core\CommonCell
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getSearchConditions(\XLite\Core\CommonCell $cnd)
    {
        $cnd = parent::getSearchConditions($cnd);

        if ($this->getMaxItemsCount()) {
            $cnd->{\XLite\Model\Repo\Product::P_LIMIT} = array(
                0,
                $this->getMaxItemsCount()
            );
        }

        return $cnd;
    }

    /**
     * Returns maximum allowed items count
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getMaxItemsCount()
    {
        return $this->getParam(self::PARAM_MAX_ITEMS_TO_DISPLAY) ?: $this->getMaxCountInBlock();
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isVisible()
    {
        $result = parent::isVisible()
            && static::getWidgetTarget() != \XLite\Core\Request::getInstance()->target
            && 0 < $this->getData(new \XLite\Core\CommonCell(), true);

        if ($result) {

            if (!\XLite\Core\CMSConnector::isCMSStarted()) {

                if (self::WIDGET_TYPE_SIDEBAR == $this->getParam(self::PARAM_WIDGET_TYPE)) {
                    $result = ('sidebar.first' == $this->viewListName);

                } elseif (self::WIDGET_TYPE_CENTER == $this->getParam(self::PARAM_WIDGET_TYPE)) {
                    $result = ('center.bottom' == $this->viewListName);
                }
            }
        }

        return $result;
    }

    /**
     * Get 'More...' link URL for Sale products list
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getMoreLinkURL()
    {
        return $this->buildURL(self::WIDGET_TARGET_SALE_PRODUCTS);
    }

    /**
     * Get 'More...' link text for Sale products list
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getMoreLinkText()
    {
        return static::t('All products on sale');
    }

    /**
     * Check status of 'More...' link for sidebar list
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isShowMoreLink()
    {
        return true;
    }
}

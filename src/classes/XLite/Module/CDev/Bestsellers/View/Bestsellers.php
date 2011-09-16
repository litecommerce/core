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

namespace XLite\Module\CDev\Bestsellers\View;

/**
 * Bestsellers widget
 *
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @ListChild (list="center.bottom", zone="customer", weight="400")
 * @ListChild (list="sidebar.first", zone="customer", weight="150")
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
     * @var   mixed
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $rootCategoryId = null;

    /**
     * Bestsellers products
     *
     * @var   mixed
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $bestsellProducts = null;


    /**
     * Define and set widget attributes; initialize widget
     *
     * @param array $params Widget params OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.1
     */
    public function __construct(array $params = array())
    {
        parent::__construct($params);

        unset($this->sortByModes[self::SORT_BY_MODE_AMOUNT_ASC]);
        unset($this->sortByModes[self::SORT_BY_MODE_AMOUNT_DESC]);
    }

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

        $this->widgetParams[\XLite\View\Pager\APager::PARAM_SHOW_ITEMS_PER_PAGE_SELECTOR]->setValue(false);
        $this->widgetParams[\XLite\View\Pager\APager::PARAM_ITEMS_COUNT]
            ->setValue(\XLite\Core\Config::getInstance()->CDev->Bestsellers->number_of_bestsellers);
    }

    /**
     * Get title
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getHead()
    {
        return 'Bestsellers';
    }

    /**
     * Return class name for the list pager
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getPagerClass()
    {
        return '\XLite\Module\CDev\Bestsellers\View\Pager\Pager';
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
            self::PARAM_USE_NODE => new \XLite\Model\WidgetParam\Checkbox(
                'Show products only for current category', true, true
            ),
            self::PARAM_ROOT_ID => new \XLite\Model\WidgetParam\ObjectId\Category(
                'Root category Id', 0, true, true
            ),
            self::PARAM_CATEGORY_ID => new \XLite\Model\WidgetParam\ObjectId\Category(
                'Category ID', 0, false
            ),
        );

        $widgetType = \XLite\Core\Config::getInstance()->CDev->Bestsellers->bestsellers_menu
            ? self::WIDGET_TYPE_SIDEBAR
            : self::WIDGET_TYPE_CENTER;

        $this->widgetParams[self::PARAM_WIDGET_TYPE]->setValue($widgetType);

        $this->widgetParams[self::PARAM_DISPLAY_MODE]->setValue(self::DISPLAY_MODE_LIST);
        $this->widgetParams[self::PARAM_GRID_COLUMNS]->setValue(3);
    }

    /**
     * Define so called "request" parameters
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineRequestParams()
    {
        parent::defineRequestParams();

        $this->requestParams[] = self::PARAM_CATEGORY_ID;
    }

    /**
     * Return products list
     *
     * @param \XLite\Core\CommonCell $cnd       Search condition
     * @param boolean                $countOnly Return items list or only its size OPTIONAL
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getData(\XLite\Core\CommonCell $cnd, $countOnly = false)
    {
        if (!isset($this->bestsellProducts)) {
            $limit = \XLite\Core\Config::getInstance()->CDev->Bestsellers->number_of_bestsellers;

            $this->bestsellProducts = \XLite\Core\Database::getRepo('XLite\Model\Product')
                ->findBestsellers(
                    $cnd,
                    (int)$limit,
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
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getRootId()
    {
        if (!isset($this->rootCategoryId)) {
            $this->rootCategoryId = $this->getParam(self::PARAM_USE_NODE)
                ? intval(\XLite\Core\Request::getInstance()->category_id)
                : $this->getParam(self::PARAM_ROOT_ID);

        }

        return $this->rootCategoryId;
    }

    /**
     * Return template of Bestseller widget. It depends on widget type:
     * SIDEBAR/CENTER and so on.
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTemplate()
    {
        $template = parent::getTemplate();

        if (
            $template == $this->getDefaultTemplate()
            && self::WIDGET_TYPE_SIDEBAR == $this->getParam(self::PARAM_WIDGET_TYPE)
        ) {
            $template = 'common/sidebar_box.tpl';
        }

        return $template;
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
        $result = parent::isVisible();

        if (!\XLite\Core\CMSConnector::isCMSStarted()) {
            if (self::WIDGET_TYPE_SIDEBAR == $this->getParam(self::PARAM_WIDGET_TYPE)) {
                $result = $result && 'sidebar.first' == $this->viewListName;

            } else {
                $result = $result && 'center.bottom' == $this->viewListName;
            }
        }

        return $result;
    }
}

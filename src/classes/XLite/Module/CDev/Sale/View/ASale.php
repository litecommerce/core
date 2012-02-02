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
 * Sale products abstract widget class
 *
 * @see   ____class_see____
 * @since 1.0.0
 *
 */
abstract class ASale extends \XLite\View\ItemsList\Product\Customer\ACustomer
{
    /**
     * Widget target
     */
    const WIDGET_TARGET_SALE_PRODUCTS = 'sale_products';


    /**
     * Return target to retrive this widget from AJAX
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getWidgetTarget()
    {
        return self::WIDGET_TARGET_SALE_PRODUCTS;
    }

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
     * Returns CSS classes for the container element
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getListCSSClasses()
    {
        return parent::getListCSSClasses() . ' sale-products';
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
        return static::t('Sale');
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
        return '\XLite\Module\CDev\Sale\View\Pager\Pager';
    }

    /**
     * Return params list to use for search
     *
     * @return \XLite\Core\CommonCell
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getSearchConditions(\XLite\Core\CommonCell $cnd)
    {
        $cnd->{\XLite\Module\CDev\Sale\Model\Repo\Product::P_PARTICIPATE_SALE} = true;

        return $cnd;
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
        return \XLite\Core\Database::getRepo('XLite\Model\Product')
            ->search($this->getSearchConditions($cnd), $countOnly);
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
        return parent::isVisible()
            && static::getWidgetTarget() == \XLite\Core\Request::getInstance()->target;
    }

    /**
     * Get max number of products displayed in block
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getMaxCountInBlock()
    {
        return intval(\XLite\Core\Config::getInstance()->CDev->Sale->sale_max_count_in_block) ?: 3;
    }

}

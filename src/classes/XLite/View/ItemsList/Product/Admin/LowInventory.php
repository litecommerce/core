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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\View\ItemsList\Product\Admin;

/**
 * LowInventory
 *
 */
class LowInventory extends \XLite\View\ItemsList\Product\Admin\AAdmin
{
    /**
     * Return title
     *
     * @return string
     */
    protected function getHead()
    {
        return 'Products with low inventory';
    }

    /**
     * Return class name for the list pager
     *
     * @return string
     */
    protected function getPagerClass()
    {
        return '\XLite\View\Pager\Admin\Product';
    }

    /**
     * getDisplayStyle
     *
     * @return string
     */
    protected function getDisplayStyle()
    {
        return 'brief';
    }

    /**
     * Do not display 'Products with low inventory' block if low-limit-products list is empty
     *
     * @return boolean
     */
    protected function isVisible()
    {
        return parent::isVisible() && 0 < $this->getData($this->getSearchCondition(), true);
    }

    /**
     * isFooterVisible
     *
     * @return boolean
     */
    protected function isFooterVisible()
    {
        return true;
    }

    /**
     * Return params list to use for search
     *
     * @return \XLite\Core\CommonCell
     */
    protected function getSearchCondition()
    {
        $result = parent::getSearchCondition();

        $result->{\XLite\Model\Repo\Product::P_INVENTORY} = \XLite\Model\Repo\Product::INV_LOW;

        return $result;
    }

    /**
     * Define view list
     *
     * @param string $list List name
     *
     * @return array
     */
    protected function defineViewList($list)
    {
        $result = parent::defineViewList($list);

        if ($this->getListName() . '.footer' === $list) {
            $result[] = $this->getWidget(array('label' => 'Update'), '\XLite\View\Button\Submit');
        }

        return $result;
    }

    /**
     * Return products list
     *
     * @param \XLite\Core\CommonCell $cnd       Search condition
     * @param boolean                $countOnly Return items list or only its size OPTIONAL
     *
     * @return array|integer
     */
    protected function getData(\XLite\Core\CommonCell $cnd, $countOnly = false)
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Product')->search($cnd, $countOnly);
    }
}

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

namespace XLite\View\ItemsList\Order;

/**
 * Abstract orders list
 *
 */
abstract class AOrder extends \XLite\View\ItemsList\AItemsList
{
    /**
     * Allowed sort criterions
     */

    const SORT_BY_MODE_ID      = 'o.order_id';
    const SORT_BY_MODE_STATUS  = 'o.status';
    const SORT_BY_MODE_DATE    = 'o.date';
    const SORT_BY_MODE_PROFILE = 'p.login';
    const SORT_BY_MODE_TOTAL   = 'o.total';


    /**
     * Get a list of CSS files required to display the widget properly
     *
     * @return array
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        // Static call of the non-static function
        $list[] = self::getDir() . '/orders_list.css';

        return $list;
    }

    /**
     * Get a list of JavaScript files required to display the widget properly
     *
     * @return array
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        // Static call of the non-static function
        $list[] = self::getDir() . '/orders_list.js';

        return $list;
    }

    /**
     * Define and set widget attributes; initialize widget
     *
     * @param array $params Widget params OPTIONAL
     *
     * @return void
     */
    public function __construct(array $params = array())
    {
        $this->sortByModes += array(
            self::SORT_BY_MODE_ID => '# Id',
        );

        parent::__construct($params);
    }


    /**
     * Return name of the base widgets list
     *
     * @return string
     */
    protected function getListName()
    {
        return parent::getListName() . '.order';
    }

    /**
     * Get widget templates directory
     * NOTE: do not use "$this" pointer here (see "get[CSS/JS]Files()")
     *
     * @return string
     */
    protected function getDir()
    {
        return parent::getDir() . '/order';
    }

    /**
     * Return dir which contains the page body template
     *
     * @return string
     */
    protected function getPageBodyDir()
    {
        return null;
    }

    /**
     * getSortByModeDefault
     *
     * @return string
     */
    protected function getSortByModeDefault()
    {
        return self::SORT_BY_MODE_ID;
    }

    /**
     * Return params list to use for search
     *
     * @return \XLite\Core\CommonCell
     */
    protected function getSearchCondition()
    {
        $result = parent::getSearchCondition();
        $result->{\XLite\Model\Repo\Order::P_ORDER_BY} = array($this->getSortBy(), $this->getSortOrder());

        return $result;
    }

    /**
     * getJSHandlerClassName
     *
     * @return string
     */
    protected function getJSHandlerClassName()
    {
        return 'OrdersList';
    }
}

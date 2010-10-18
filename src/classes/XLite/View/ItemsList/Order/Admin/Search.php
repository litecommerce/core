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

namespace XLite\View\ItemsList\Order\Admin;

/**
 * Search 
 * 
 * @package    XLite
 * @see        ____class_see____
 * @since      3.0.0
 */
class Search extends \XLite\View\ItemsList\Order\Admin\AAdmin
{
    /**
     * Widget param names 
     */

    const PARAM_ORDER_ID = 'orderId';
    const PARAM_LOGIN    = 'login';
    const PARAM_STATUS   = 'status';
    const PARAM_DATE     = 'date';


    /**
     * Return name of the base widgets list
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getListName()
    {
        return parent::getListName() . '.search';
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
        return 'Search result';
    }

    /**
     * Define view list
     *
     * @param string $list List name
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineViewList($list)
    {
        $result = parent::defineViewList($list);

        if ('itemsList.admin.footer' === $list) {
            $result[] = $this->getWidget(array('label' => 'Update'), '\XLite\View\Button\Submit');
            $result[] = $this->getWidget(array(), '\XLite\View\Button\DeleteSelected');
        }

        return $result;
    }

    /**
     * isFooterVisible
     *
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isFooterVisible()
    {
        return true;
    }

    /**
     * Return list of the modes allowed by default
     *
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultModes()
    {
        $list = parent::getDefaultModes();
        $list[] = 'search';

        return $list;
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
        return '\XLite\View\Pager\Admin\Order\Search';
    }

    /**
     * Get URL common parameters
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCommonParams()
    {
        return parent::getCommonParams() + array('mode' => 'search');
    }

    /**
     * getSearchParams 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    static public function getSearchParams()
    {
        return array(
            \XLite\Model\Repo\Order::P_ORDER_ID => self::PARAM_ORDER_ID,
            \XLite\Model\Repo\Order::P_EMAIL    => self::PARAM_LOGIN,
            \XLite\Model\Repo\Order::P_STATUS   => self::PARAM_STATUS,
            \XLite\Model\Repo\Order::P_DATE     => self::PARAM_DATE,
        );
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
            self::PARAM_ORDER_ID => new \XLite\Model\WidgetParam\Int(
                'Order ID', null
            ),
            self::PARAM_LOGIN => new \XLite\Model\WidgetParam\String(
                'Email', ''
            ),
            self::PARAM_STATUS => new \XLite\Model\WidgetParam\Set(
                'Status', null, array_keys(\XLite\Model\Order::getAllowedStatuses())
            ),
            self::PARAM_DATE => new \XLite\Model\WidgetParam\Int(
                'Date', null
            ),
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

        $this->requestParams = array_merge($this->requestParams, $this->getSearchParams());
    }

    /**
     * Return params list to use for search
     *
     * @return \XLite\Core\CommonCell
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSearchCondition()
    {
        $result = parent::getSearchCondition();

        foreach ($this->getSearchParams() as $modelParam => $requestParam) {
            $result->$modelParam = $this->getParam($requestParam);
        }

        return $result;
    }

    /**
     * Return orders list
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
        return \XLite\Core\Database::getRepo('\XLite\Model\Order')->search($cnd, $countOnly);
    }
}

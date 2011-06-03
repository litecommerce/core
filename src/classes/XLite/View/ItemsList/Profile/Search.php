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

namespace XLite\View\ItemsList\Profile;

/**
 * Search
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Search extends \XLite\View\ItemsList\Profile\AProfile
{
    /**
     * Widget param names
     */
    const PARAM_LOGIN    = 'login';


    /**
     * getSearchParams
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getSearchParams()
    {
        return array(
        );
    }

    /**
     * Return name of the base widgets list
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getListName()
    {
        return parent::getListName() . '.search';
    }

    /**
     * Return title
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getHead()
    {
        return null;
    }

    /**
     * Define view list
     *
     * @param string $list List name
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineViewList($list)
    {
        $result = parent::defineViewList($list);

        if ($this->getListName() . '.footer' === $list) {

            $result[] = $this->getWidget(array('label' => 'Delete user'), '\XLite\View\Button\DeleteProfile');
        }

        return $result;
    }

    /**
     * isFooterVisible
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isFooterVisible()
    {
        return false;
    }

    /**
     * Return list of the modes allowed by default
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getPagerClass()
    {
        return '\XLite\View\Pager\Admin\Profile\Search';
    }

    /**
     * Get URL common parameters
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getCommonParams()
    {
        return parent::getCommonParams() + array('mode' => 'search');
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
            self::PARAM_LOGIN => new \XLite\Model\WidgetParam\String(
                'Email', ''
            ),
        );
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

        $this->requestParams = array_merge($this->requestParams, $this->getSearchParams());
    }

    /**
     * Return params list to use for search
     *
     * @return \XLite\Core\CommonCell
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getSearchCondition()
    {
        $result = parent::getSearchCondition();

        foreach ($this->getSearchParams() as $modelParam => $requestParam) {
            $result->$modelParam = $this->getParam($requestParam);
        }

        $result->order_id = 0;

        return $result;
    }

    /**
     * Return orders list
     *
     * @param \XLite\Core\CommonCell $cnd       Search condition
     * @param boolean                $countOnly Return items list or only its size OPTIONAL
     *
     * @return array|integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getData(\XLite\Core\CommonCell $cnd, $countOnly = false)
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Profile')->search($cnd, $countOnly);
    }
}

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

namespace XLite\View\ItemsList\Profile\Admin;

/**
 * Search 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @ListChild (list="admin.center", zone="admin")
 */
class Search extends \XLite\View\ItemsList\Profile\Admin\AAdmin
{
    /**
     * Widget param names
     */
    const PARAM_PATTERN     = 'pattern';
    const PARAM_USER_TYPE   = 'user_type';
    const PARAM_MEMBERSHIP  = 'membership';
    const PARAM_COUNTRY     = 'country';
    const PARAM_STATE       = 'state';
    const PARAM_ADDRESS     = 'address';
    const PARAM_PHONE       = 'phone';
    const PARAM_DATE_TYPE   = 'date_type';
    const PARAM_DATE_PERIOD = 'date_period';
    const PARAM_START_DATE  = 'startDate';
    const PARAM_END_DATE    = 'endDate';

    /**
     * List of search params for this widget (cache)
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $searchParams;

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
        $result[] = 'profile_list';

        return $result;
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
     * Auxiliary method to check visibility
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isDisplayWithEmptyList()
    {
        return true;
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
     * Return widget default template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTemplate()
    {
        return $this->getDir() . '/' . $this->getPageBodyDir() . '/items_list.tpl';
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

        foreach ($this->getSearchParamsRepo() as $repoParam => $widgetParam) {
            $result->$repoParam = $this->getParam($widgetParam);
        }

        return $result;
    }

    /**
     * Return profiles list
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

        $this->widgetParams += $this->getSearchParams();
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

        $this->requestParams = array_merge($this->requestParams, array_keys($this->getSearchParams()));
    }

    /**
     * Return list of search params for this widget
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getSearchParams()
    {
        if (!isset($this->searchParams)) {
            $this->searchParams = array(
                self::PARAM_PATTERN     => new \XLite\Model\WidgetParam\String('Pattern', ''),
                self::PARAM_USER_TYPE   => new \XLite\Model\WidgetParam\Set('Type', '', false, array('', 'A', 'C')),
                self::PARAM_MEMBERSHIP  => new \XLite\Model\WidgetParam\String('Membership', ''),
                self::PARAM_COUNTRY     => new \XLite\Model\WidgetParam\String('Country', ''),
                self::PARAM_STATE       => new \XLite\Model\WidgetParam\Int('State', -1),
                self::PARAM_ADDRESS     => new \XLite\Model\WidgetParam\String('Address', ''),
                self::PARAM_PHONE       => new \XLite\Model\WidgetParam\String('Phone', ''),
                self::PARAM_DATE_TYPE   => new \XLite\Model\WidgetParam\Set('Date type', '', false, array('', 'R', 'L')),
                self::PARAM_DATE_PERIOD => new \XLite\Model\WidgetParam\Set('Date period', '', false, array('', 'M', 'W', 'D', 'C')),
                self::PARAM_START_DATE  => new \XLite\Model\WidgetParam\Int('Start date', null),
                self::PARAM_END_DATE    => new \XLite\Model\WidgetParam\Int('End date', null),
            );
        }

        return $this->searchParams;
    }

    /**
     * Return list of search params for this widget associated with the Repo params
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getSearchParamsRepo()
    {
        return array_combine(
            array_map(
                function ($item) { return constant('\XLite\Model\Repo\Profile::SEARCH_' . $item); },
                array(
                    'PATTERN',
                    'USER_TYPE',
                    'MEMBERSHIP',
                    'COUNTRY',
                    'STATE',
                    'ADDRESS',
                    'PHONE',
                    'DATE_TYPE',
                    'DATE_PERIOD',
                    'START_DATE',
                    'END_DATE',
                )
            ),
            array_keys($this->getSearchParams())
        );
    }
}

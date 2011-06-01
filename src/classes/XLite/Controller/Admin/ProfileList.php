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

namespace XLite\Controller\Admin;

/**
 * Profile list controller
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class ProfileList extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Return the current page title (for the content area)
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTitle()
    {
        return 'Search user profiles';
    }

    /**
     * Get search condition parameter by name
     *
     * @param string $paramName Parameter name
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCondition($paramName)
    {
        $searchParams = $this->getConditions();

        return isset($searchParams[$paramName])
            ? $searchParams[$paramName]
            : null;
    }

    /**
     * Common method to determine current location
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getLocation()
    {
        return $this->t('Search profiles');
    }

    /**
     * doActionDelete
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionDelete()
    {

    }

    /**
     * doActionSearch
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionSearch()
    {
        $profilesSearch = array();
        $searchParams   = \XLite\View\ItemsList\Profile\Search::getSearchParams();

        foreach ($searchParams as $modelParam => $requestParam) {

           if (isset(\XLite\Core\Request::getInstance()->$requestParam)) {

                $profilesSearch[$requestParam] = \XLite\Core\Request::getInstance()->$requestParam;
            }
        }

        \XLite\Core\Session::getInstance()->{\XLite\View\ItemsList\Profile\Search::getSessionCellName()} = $profilesSearch;

        $this->setReturnURL($this->buildURL('profile_list', '', array('mode' => 'search')));
    }

    /**
     * Get search conditions
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getConditions()
    {
        $searchParams = \XLite\Core\Session::getInstance()->{\XLite\View\ItemsList\Profile\Search::getSessionCellName()};

        if (!is_array($searchParams)) {

            $searchParams = array();
        }

        return $searchParams;
    }
}

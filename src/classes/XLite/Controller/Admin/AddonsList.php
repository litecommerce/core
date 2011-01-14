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
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Controller\Admin;

/**
 * Modules
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class AddonsList extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Common method to determine current location
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getLocation()
    {
        return 'Install new add-ons';
    }

    /**
     * Method to create quick links
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineQuickLinks()
    {
        parent::defineQuickLinks();

        // Count upgradable add-ons
        $upgradablesCount = count(\XLite\Core\Database::getRepo('\XLite\Model\Module')->findUpgradableModules());
        $upgradablesLabel = 0 < $upgradablesCount
            ? ' <i>(' . $upgradablesCount . ')</i>'
            : '';
        
        $this->addQuickLink(
            $this->t('Manage add-ons') . $upgradablesLabel,
            $this->buildURL('modules')
        );

        $this->addQuickLink(
            $this->t('Install new add-ons'),
            $this->buildURL('addons_list', '', array('mode' => 'featured')),
            true
        );
    }

    /**
     * doActionSearch
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionSearch()
    {
        $addonsSearch = array();
        $searchParams   = \XLite\View\ItemsList\Module\Install::getSearchParams();

        foreach ($searchParams as $modelParam => $requestParam) {
            if (isset(\XLite\Core\Request::getInstance()->$requestParam)) {
                $addonsSearch[$requestParam] = \XLite\Core\Request::getInstance()->$requestParam;
            }
        }

        $this->session->set(\XLite\View\ItemsList\Module\Install::getSessionCellName(), $addonsSearch);
        $this->set('returnUrl', $this->buildUrl('addons_list', '', array('mode' => 'search')));
    }

    /**
     * Get search conditions
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getConditions()
    {
        $searchParams = $this->session->get(\XLite\View\ItemsList\Module\Install::getSessionCellName());

        if (!is_array($searchParams)) {
            $searchParams = array();
        }

        return $searchParams;
    }

    /**
     * Get search condition parameter by name
     *
     * @param string $paramName Parameter name
     *
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCondition($paramName)
    {
        $searchParams = $this->getConditions();

        if (isset($searchParams[$paramName])) {
            $return = $searchParams[$paramName];
        }

        return isset($searchParams[$paramName])
            ? $searchParams[$paramName]
            : null;
    }

    /**
     * Handles the request. Parses the request variables if necessary.
     * Attempts to call the specified action function 
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function handleRequest()
    {
        \XLite\Core\Database::getRepo('\XLite\Model\Module')->checkModules();

        parent::handleRequest();
    }

}

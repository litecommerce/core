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

namespace XLite\Module\CDev\Demo\Controller\Admin;

/**
 * AAdmin
 *
 */
abstract class AAdmin extends \XLite\Controller\Admin\AAdmin implements \XLite\Base\IDecorator
{
    /**
     * Controllers which actions are all forbidden in demo mode
     *
     * @var array
     */
    protected $demoControllers = array(
        'XLite\Controller\Admin\AddonsInstall',
        'XLite\Controller\Admin\AddonsListInstalled',
        'XLite\Controller\Admin\AddonsListMarketplace',
        'XLite\Controller\Admin\AddressBook',
        'XLite\Controller\Admin\CacheManagement',
        'XLite\Controller\Admin\DbBackup',
        'XLite\Controller\Admin\DbRestore',
        'XLite\Controller\Admin\Languages',
        'XLite\Controller\Admin\Measure',
        'XLite\Controller\Admin\Memberships',
        'XLite\Controller\Admin\Module',
        'XLite\Controller\Admin\ModuleInstallation',
        'XLite\Controller\Admin\Modules',
        'XLite\Controller\Admin\Profile',
        'XLite\Controller\Admin\PackDistr',
        'XLite\Controller\Admin\Settings',
        'XLite\Controller\Admin\Upgrade',
        'XLite\Controller\Admin\Aupost',
        'XLite\Controller\Admin\Storefront',
    );

    /**
     * Actions permitted in demo mode
     *
     * @var array
     */
    protected $demoPermittedActions = array(
        'XLite\Controller\Admin\Languages' => array(
            'search',
        ),
    );

    /**
     * Actions forbidden in demo mode
     *
     * @var array
     */
    protected $demoForbiddenActions = array();


    /**
     * Message to display if action is forbidden
     *
     * @return string
     */
    protected function getForbidInDemoModeMessage()
    {
        return 'You are not allowed to do this in demo mode.';
    }

    /**
     * URL to redirect if action is forbidden
     *
     * @return string
     */
    protected function getForbidInDemoModeRedirectURL()
    {
        return \XLite\Core\Converter::buildURL(\XLite\Core\Request::getInstance()->target);
    }

    /**
     * This function is called if action is forbidden in demo mode
     *
     * @return void
     */
    protected function forbidInDemoMode()
    {
        $message = $this->getForbidInDemoModeMessage();
        if ($message) {
            \XLite\Core\TopMessage::addWarning($message);
        }

        $url = $this->getForbidInDemoModeRedirectURL();
        if ($url) {
            \Includes\Utils\Operator::redirect($url);
        }
    }

    /**
     * Check if we need to forbid current action
     *
     * @return boolean
     */
    protected function checkForDemoController()
    {
        $class = \Includes\Utils\Converter::trimLeadingChars(get_class($this), '\\');

        return in_array($class, $this->demoControllers)
            ? !$this->isDemoActionPermitted($class)
            : $this->isDemoActionForbidden($class);
    }

    /**
     * Check if specific action is permitted
     *
     * @return boolean
     */
    protected function isDemoActionPermitted($class)
    {
        return isset($this->demoPermittedActions[$class])
            && in_array(\XLite\Core\Request::getInstance()->action, $this->demoPermittedActions[$class]);
    }

    /**
     * Check if specific action is forbidden
     *
     * @return boolean
     */
    protected function isDemoActionForbidden($class)
    {
        return isset($this->demoForbiddenActions[$class])
            && in_array(\XLite\Core\Request::getInstance()->action, $this->demoForbiddenActions[$class]);
    }

    /**
     * Call controller action
     *
     * @return void
     */
    protected function callAction()
    {
        $this->checkForDemoController() ? $this->forbidInDemoMode() : parent::callAction();
    }
}

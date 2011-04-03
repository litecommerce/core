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
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    GIT: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\CDev\Demo\Controller\Admin;

/**
 * AAdmin 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class AAdmin extends \XLite\Controller\Admin\AAdmin implements \XLite\Base\IDecorator
{
    /**
     * Controllers which actions are all forbidden in demo mode
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $demoControllers = array(
        'XLite\Controller\Admin\AddonsList',
        'XLite\Controller\Admin\AddressBook',
        'XLite\Controller\Admin\CacheManagement',
        'XLite\Controller\Admin\BackupRestore',
        'XLite\Controller\Admin\Module',
        'XLite\Controller\Admin\ModuleInstallation',
        'XLite\Controller\Admin\Modules',
        'XLite\Controller\Admin\Profile',
        'XLite\Controller\Admin\Settings',
    );


    /**
     * Message to display if action is forbidden
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getForbidInDemoModeMessage()
    {
        return 'You are not allowed to do this in demo mode.';
    }

    /**
     * URL to redirect if action is forbidden
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getForbidInDemoModeRedirectURL()
    {
        return \XLite\Core\Converter::buildURL(\XLite\Core\Request::getInstance()->target);
    }

    /**
     * This function is called if action is forbidden in demo mode
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function forbidInDemoMode()
    {
        if ($message = $this->getForbidInDemoModeMessage()) {
            \XLite\Core\TopMessage::getInstance()->addWarning($message);
        }

        if ($url = $this->getForbidInDemoModeRedirectURL()) {
            \Includes\Utils\Operator::redirect($url);
        }
    }

    /**
     * Check if we need to forbid current action
     * 
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkForDemoController()
    {
        return in_array(\Includes\Utils\Converter::trimLeadingChars(get_class($this), '\\'), $this->demoControllers);
    }

    /**
     * Call controller action
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function callAction()
    {
        $this->checkForDemoController() ? $this->forbidInDemoMode() : parent::callAction();
    }
}

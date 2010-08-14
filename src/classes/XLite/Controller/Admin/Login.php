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
 * Login
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Login extends \XLite\Controller\Admin\AAdmin
{
    /**
     * getViewerTemplate
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function getViewerTemplate()
    {
        return 'login.tpl';
    }


    public function getAccessLevel()
    {
        return \XLite\Model\Auth::getInstance()->getCustomerAccessLevel();
    }

    function fillForm()
    {
        parent::fillForm();
        $login = $this->get('login');
        if (empty($login)) {
            $this->set('login', $this->auth->remindLogin());
        }
    }

    /**
     * Login 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionLogin()
    {
        $profile = $this->auth->adminLogin(
            \XLite\Core\Request::getInstance()->login,
            \XLite\Core\Request::getInstance()->password
        );

        if (is_int($profile) && ACCESS_DENIED === $profile) {

            $this->set('valid', false);
            \XLite\Core\TopMessage::getInstance()->add('Invalid login or password', \XLite\Core\TopMessage::ERROR);
            $returnUrl = $this->buildUrl('login');

        } elseif ($this->xlite->session->isRegistered('lastWorkingURL')) {
            $returnUrl = $this->xlite->session->get('lastWorkingURL');
            $this->xlite->session->set('lastWorkingURL', null);

        } else {
            $returnUrl = $this->buildUrl();
        }

        $this->setReturnUrl($returnUrl);

        $this->initSBStatuses();
    }

    function action_logoff()
    {
        $this->auth->logoff();
        $this->clearSBStatuses();
    }

    protected function initSBStatuses()
    {
        if ($this->auth->isLogged()) {
            $profile = $this->auth->getProfile();
            $sidebar_box_statuses = $profile->get('sidebar_boxes');

            if (strlen($sidebar_box_statuses) > 0) {
                $sidebar_box_statuses = unserialize($sidebar_box_statuses);
                $this->session->set('sidebar_box_statuses', $sidebar_box_statuses);
                $this->session->writeClose();

            } else {
                $profile->set('sidebar_boxes', serialize($this->session->get('sidebar_box_statuses')));
                $profile->update();
            }
        }
    }
    
    function clearSBStatuses()
    {
        $this->session->set('sidebar_box_statuses', null);
        $this->session->writeClose();
    }
    
    function getSecure()
    {
        return $this->session->get('no_https') ? false : $this->config->Security->admin_security;
    }
}

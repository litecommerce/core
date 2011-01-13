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

namespace XLite\Controller\Customer;

/**
 * AdminAreaRedirector 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class AdminAreaRedirector extends \XLite\Controller\Customer\ACustomer
{
    /**
     * Check if current page is accessible
     *
     * @return boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected function checkAccess()
    {
        return parent::checkAccess() && \XLite\Core\Auth::getInstance()->isAdmin();
    }

    /**
     * Must be accessible
     * 
     * @return boolean 
     * @access protected
     * @since  3.0.0
     */
    protected function checkStorefrontAccessability()
    {
        return true;
    }

    /**
     * Return URL to redirect to
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getAdminAreaURLArgs()
    {
        return \XLite\Core\Session::getInstance()->getName() . '=' . \XLite\Core\Session::getInstance()->getId();
    }

    /**
     * Perform redirect 
     * 
     * @return null
     * @access public
     * @since  3.0.0
     */             
    public function handleRequest()
    {
        $this->redirect(\Includes\Utils\URLManager::getShopURL('admin.php?' . $this->getAdminAreaURLArgs()));
    }
}

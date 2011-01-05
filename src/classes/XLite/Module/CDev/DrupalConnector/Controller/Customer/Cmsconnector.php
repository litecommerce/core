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

namespace XLite\Module\CDev\DrupalConnector\Controller\Customer;

/**
 * FIXME - change name
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Cmsconnector extends \XLite\Controller\Customer\ACustomer
{
    /**
     * Must be accessible
     * TODO: check if it's needed
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
     * Landing from another system action
     * FIXME - to revize
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionLanding()
    {
        $link = \XLite\Core\Database::getRepo('XLite\Module\CDev\DrupalConnector\Model\LandingLink')
            ->find(\XLite\Core\Request::getInstance()->id);

        if ($link) {
            \XLite\Core\Session::getInstance()->loadBySid($link->getSessionId());

            \XLite\Core\Database::getEM()->remove($link);
            \XLite\Core\Database::getEM()->flush();
        }

        $this->set('returnUrl', 'admin.php');

        // FIXME - to remove
        $this->redirect();
    }

}

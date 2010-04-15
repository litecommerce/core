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

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_WishList_Controller_Customer_SendFriend extends XLite_Controller_Customer_Abstract
{
    /**
     * Get page title 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
	public function getTitle()
	{
        return 'Send to friend';
	}

    /**
     * 'send_friend' action 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function action_send_friend()
    {
        if ($this->getProduct()) {
            $Mailer = new XLite_Model_Mailer();
            $Mailer->sender_name  = $this->getSenderName();
            $Mailer->sender_email = $this->getSenderEmail();
            $Mailer->recipient_email = XLite_Core_Request::getInstance()->recipient_email;
            $Mailer->product = $this->getProduct();
            $Mailer->url = $this->buildURL('product', '', array('product_id' => $this->getProduct()->get('product_id')));
            $Mailer->compose(
                $this->getSenderEmail,
                XLite_Core_Request::getInstance()->recipient_email,
                "modules/WishList/send_friend"
            );
            $Mailer->send();

            $this->set('returnUrl', $this->buildURL('product', '', array('product_id' => $this->getProduct()->get('product_id'))));

        } else {
            $this->set('returnUrl', $this->buildURL('main'));
        }
    }

    /**
     * Get sender name 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSenderName()
    {
        return isset(XLite_Core_Request::getInstance()->sender_name)
            ? XLite_Core_Request::getInstance()->sender_name
            : $this->auth->getComplex('profile.billing_firstname') . ' ' . $this->auth->getComplex('profile.billing_lastname');
    }

    /**
     * Get sender email 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSenderEmail()
    {
        return isset(XLite_Core_Request::getInstance()->sender_email)
            ? XLite_Core_Request::getInstance()->sender_email
            : $this->auth->getComplex('profile.login');
    }

}

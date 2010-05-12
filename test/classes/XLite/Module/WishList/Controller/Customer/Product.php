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
class XLite_Module_WishList_Controller_Customer_Product extends XLite_Controller_Customer_Product implements XLite_Base_IDecorator
{
    function getSenderName() 
    {
        return isset($this->sender_name) ? $this->sender_name : $this->auth->getComplex('profile.billing_firstname')." ".$this->auth->getComplex('profile.billing_lastname');
    }

  	function getSenderEmail() 
    {
        return isset($this->sender_email) ? $this->sender_email : $this->auth->getComplex('profile.login');
    }


    function action_send_friend() 
    {
        $Mailer = new XLite_Model_Mailer();
        $Mailer->sender_name  = $this->sender_name;
        $Mailer->sender_email = $this->sender_email;
        $Mailer->recipient_email = $this->recipient_email;
        $product = new XLite_Model_Product($this->product_id);
 		$Mailer->product = $product;
        $Mailer->url = $this->getShopUrl("cart.php?target=product&product_id=".$this->product_id);
        $Mailer->compose($this->get('sender_email'),$this->get('recipient_email'),"modules/WishList/send_friend");
        $Mailer->send();

        $this->params[] = "mode";
        $this->set("mode","MessageSent");
      }
}

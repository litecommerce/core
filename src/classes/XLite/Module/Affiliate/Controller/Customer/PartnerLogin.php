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
class XLite_Module_Affiliate_Controller_Customer_PartnerLogin extends XLite_Controller_Customer_Login
{
    public $template = "modules/Affiliate/login.tpl";
    public $partnerZoneUrl = "cart.php?target=partner";
    
    function handleRequest()
    {
        if ($this->auth->is('logged') && $this->auth->isPartner($this->auth->get('profile')) && $this->get('action') != "logoff") {
            $this->returnUrl = $this->partnerZoneUrl;
            $this->redirect();
        } else {
            parent::handleRequest();
        }
    }

    function action_login()
    {
        parent::action_login();
        $this->returnUrl = $this->partnerZoneUrl;
    }

    function action_logoff()
    {
        $this->auth->logoff();
    }
}

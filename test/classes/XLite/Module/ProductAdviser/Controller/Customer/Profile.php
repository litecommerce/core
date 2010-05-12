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
class XLite_Module_ProductAdviser_Controller_Customer_Profile extends XLite_Controller_Customer_Profile implements XLite_Base_IDecorator
{
    public $from = null;

    function init()
    {
    	parent::init();

    	if (empty($this->action) && $this->from == "notify_me") {
    		$this->session->set("NotifyMePended", true);
    	}
    }

    function action_register()
    {
        parent::action_register();

        if ($this->auth->is("logged") && $this->session->isRegistered("NotifyMePended") && $this->session->isRegistered("NotifyMeInfo")) {
    		$this->session->set("NotifyMePended", null);
    		$this->session->set("NotifyMeReturn", true);
            $this->set("returnUrl", "cart.php?target=notify_me");
        }
    }
}

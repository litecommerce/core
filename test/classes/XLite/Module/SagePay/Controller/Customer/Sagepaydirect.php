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
class XLite_Module_SagePay_Controller_Customer_Sagepaydirect extends XLite_Controller_Customer_Checkout
{

    function init()
    {
        if (!is_object($this->registerForm) || is_null($this->registerForm)) {
            $this->registerForm = new XLite_Base();
        }
        parent::init();
        
        if ($this->action == "return") {
            if (!$this->auth->is('logged')) {
                // not logged - redirect to the cart
                $this->redirect('cart.php');
            } else {
                $this->action_return();
            }
        }

    }

    function action_return()
    {
        $oid = $this->session->get('SagePayDirectQueued');
    
        if ($oid && $this->get('PaRes') && $this->get('MD')) {
            $this->order = null;
            $_REQUEST['order_id'] = $oid;

            $this->session->set("SagePayDirectQueued", null);
            $this->session->set("last_order_id", $oid);
            $this->session->set("order_id", $oid);
            $this->session->writeClose();

            $order = $this->get('order');
            $payment = XLite_Model_PaymentMethod::factory('sagepaydirect_cc');

            require_once LC_MODULES_DIR . 'SagePay' . LC_DS . 'encoded.php';
            func_SagePayDirect_action_return($this, $order, $payment);
        }

        $this->order = null;

        parent::action_return();

        if ($oid) {
            $this->session->set("last_order_id", $oid);
            $this->session->set("order_id", $oid);
            $this->session->writeClose();
        }

    }

}

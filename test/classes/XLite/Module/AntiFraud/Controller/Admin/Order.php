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
class XLite_Module_AntiFraud_Controller_Admin_Order extends XLite_Controller_Admin_Order implements XLite_Base_IDecorator
{
    public $order = null;
    public $country = null;
    
    function init() 
    {
        $this->params[] = "mode";
        parent::init();
    }

    function action_fraud_notify()
    {
        $post = array();
        $post['mode'] = "add_ip";
        $post['ip']	= $this->getComplex('order.address');
        $post['shop_host'] = func_parse_host(XLite::getInstance()->getOptions(array('host_details', 'http_host')));
        $post['reason'] = strip_tags($this->get('fraud_comment'));
        $post['service_key'] = $this->config->getComplex('AntiFraud.antifraud_license');
        $request = new XLite_Model_HTTPS();
        $request->data = $post;
        $request->url = $this->config->getComplex('AntiFraud.antifraud_url')."/add_fraudulent_ip.php";
        $request->request();

        $request->response ? $this->set('mode',"sent") : $this->set('mode',"failed");
    }

    function getOrder()
    {
        if (is_null($this->order)) {
            $this->order = new XLite_Model_Order($this->get('order_id'));
        }
        return $this->order;
    }

    function compare($val1, $val2) 
    {
        return ($val1 >= $val2) ? 1 : 0;
    }
    
    function getCountry()
    {
        if (!is_null($this->country)) {
            return $this->country;
        }

        $order = $this->get('order');
        $this->country = new XLite_Model_Country($order->getComplex('profile.billing_country'));
        $this->country->set('order', $order);
        return $this->country;
    }

    function action_check_fraud() 
    {
        $order = $this->get('order');
        $order->checkFraud();
        $order->update();
    }
}

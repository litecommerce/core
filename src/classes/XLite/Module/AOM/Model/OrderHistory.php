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
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\AOM\Model;

func_define('ORDER_HISTORY_CRYPTED_MESSAGE', 'Encrypted');
func_define('ORDER_HISTORY_CHANGED_MESSAGE', 'Changed');

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class OrderHistory extends \XLite\Model\AModel
{
    public $fields = array("order_history_id" 	=> '',
                        "order_id"			=> '',
                        "login"				=> '',	
                        "date"				=> '',
                        "changes"			=> '',	
                        "secureChanges"		=> '');

    public $autoIncrement = "order_history_id";
    public $alias = "order_history";
    public $_changes = null;

    public $secure_prefix = array('cc_');

    function get($name) {
        if ($name == 'changes') 
            return $this->getChanges();
        return parent::get($name);
    }
    
    function getChanges() 
    {
        if (is_null($this->_changes)) {
            $this->_changes = unserialize(parent::get('changes'));

            $val = parent::get('secureChanges');
            if ( trim($val) != "" ) {
                $gpg = new \XLite\Module\AdvancedSecurity\Model\GPG();
                $secureChanges = unserialize($gpg->decrypt($val));
    
                if ( is_array($secureChanges) ) {
                    $this->_changes = ( is_array($this->_changes) ) ? $secureChanges + $this->_changes : $secureChanges;
                }
            }
        }

        return $this->_changes;
    }

    function set($name, $value)
    {
        if ( $name == "changes" ) {
            $this->setChanges($value);
            return;
        }
        parent::set($name, $value);
    }

    function setChanges($value)
    {
        if ( !is_array($value) )
            $value = array();

        $secureChanges = "";

        if (!$this->xlite->config->AOM->cc_info_history) {
            foreach ($value as $key=>$val) {
                if ( is_array($val) ) {
                    foreach ($val as $k=>$v) {
                        if ($this->isSecureKey($k)) {
                            $value[$key][$k] = ORDER_HISTORY_CHANGED_MESSAGE;
                        }
                    }
                }
            }
        }

        if (
            $this->config->AOM->cc_info_history
            && \XLite\Core\Database::getRepo('XLite\Model\Module')->isModuleActive('AdvancedSecurity')
            && \XLite::isAdminZone()
            && $this->config->AdvancedSecurity->gpg_crypt_db
        ) {
            foreach ($value as $key=>$val) {
                if ( is_array($val) ) {
                    foreach ($val as $k=>$v) {
                        if ( $this->isSecureKey($k) ) {
                            $secureChanges[$key][$k] = $v;
                            $value[$key][$k] = ORDER_HISTORY_CRYPTED_MESSAGE;
                        }
                    }
                }
            }

            $gpg = new \XLite\Module\AdvancedSecurity\Model\GPG();
            $secureChanges = $gpg->encrypt(serialize($secureChanges));
        }

        parent::set('changes', serialize($value));
        parent::set('secureChanges', $secureChanges);
    }

    function isSecureKey($key)
    {
        foreach ($this->secure_prefix as $prefix)
            if ( substr($key, 0, strlen($prefix)) == $prefix )
                return true;

        return false;
    }

    function log($order, $cloneOrder = null, $ordersItems = null, $action = null)  
    {
        $history = array();
        if ($action == "create_order")
        {
            $history['order']["created"] = $order->get('order_id');
        }
        
        if ($action == "split_order") 
        {
            if ($order->get('order_id') > $cloneOrder->get('order_id')) {
                $history['order']["split"]['parent'] = $cloneOrder->get('order_id');
                $history['order']["split"]['child']  = $order->get('order_id');
            } else {
                $history['order']["split"]['parent'] = $order->get('order_id');
                $history['order']["split"]['child']	 = $cloneOrder->get('order_id');
            }
        }
        
        if ($action == "clone_order")
        {
            $history['order']["cloned"] = $cloneOrder->get('order_id');
        }
        
        if (!is_null($ordersItems))	{
            foreach ($ordersItems as $items) {
                if (is_null($items['orderItem']) && !is_null($items['cloneItem'])) 
                    $history['items']['added'][] = $items['cloneItem']->get('product_name');
                if (is_null($items['cloneItem']) && !is_null($items['orderItem'])) 
                    $history['items']['deleted'][] = $items['orderItem']->get('product_name');
            }
            foreach ($ordersItems as $items) {
                if (!is_null($items['cloneItem']) && !is_null($items['orderItem'])) {
                    $cloneItem = $items['cloneItem']->get('properties');
                    $orderItem = $items['orderItem']->get('properties');
                    if ($cloneItem['price'] != $orderItem['price']) 
                    $history['items']['updated']['price'][] = array("name" => $orderItem['product_name'],"oldPrice" => $orderItem['price'],"newPrice" => $cloneItem['price']);
                    if ($cloneItem['amount'] != $orderItem['amount'])       
                    $history['items']['updated']['amount'][] = array("name" => $orderItem['product_name'],"oldAmount" => $orderItem['amount'],"newAmount" => $cloneItem['amount']);
                }
            }
            if (empty($history)) $history['items']["not_changed"] = true;
        }
        if (!is_null($order) && !is_null($cloneOrder) && $action == null) {
                $fields = array('subtotal',"shipping_cost","payment_method","discount", "global_discount", "payedByGC", "total", "payedByPoints");
                foreach ($fields as $field) 
                    if ($order->get($field) != $cloneOrder->get($field)) {
                        $history['totals'][$field] = $order->get($field);
                        $history['changedTotals'][$field] = $cloneOrder->get($field);
                    }

                // Log taxes changes
                $taxes = $order->get('displayTaxes');
                $cloneTaxes = $cloneOrder->get('displayTaxes');
                if ( is_array($taxes) ) {
                    foreach ($taxes as $tax=>$value) {
                        if ( $cloneTaxes[$tax] != $value ) {
                            $history['totals'][$tax] = $value;
                            $history['changedTotals'][$tax] = $cloneTaxes[$tax];
                        }
                    }
                }

                $profile = $order->get('profile');
                if ($profile) {
                    $cloneProfile = $cloneOrder->get('profile');
                    foreach ($profile->get('properties') as $key => $value)
                        if (($cloneProfile->get("$key") != $value) && !($key == 'order_id' || $key == 'profile_id'))
                        {
                            $history['profile'][$key] = $value;
                            $history['changedProfile'][$key] = $cloneProfile->get("$key");
                        }
                    }
        }
        if (!is_null($order) && isset($_POST['substatus'])) {
            if ($order->get('notes') != $_POST['notes']) {
                $history['notes'] = $order->get('notes');
                $history['changedNotes'] = $_POST['notes'];
            }
            if ($order->get('admin_notes') != $_POST['admin_notes']) {
                $history['admin_notes'] = $order->get('admin_notes');
                $history['changedAdmin_notes'] = $_POST['admin_notes'];
            }
            if ($_POST['details']) {
                if ( !is_null($this->session->get('masterPassword')) ) {
                    $temp_details = $order->getSecureDetails();
                } else {
                    $temp_details = $order->get('details');
                }
                foreach ($_POST['details'] as $ckey => $changedDetail) {
                    foreach ($temp_details as $key => $detail) {
                        if ($key == $ckey && $detail != $changedDetail)
                        {
                            $details[$key] = $detail;
                            $changedDetails[$ckey] = $changedDetail;
                        }
                    }
                }
            }
            if (!empty($details)) {
                $history['details'] = $details;
                $history['changedDetails'] = $changedDetails;
            }
            $changedStatus = new \XLite\Module\AOM\Model\OrderStatus();
            $changedStatus->find("status = '".$_POST['substatus']."'");
            if ($order->getComplex('orderStatus.name') != $changedStatus->get('name'))	{
            	$history['status'] = $order->getComplex('orderStatus.name');
                $history['changedStatus'] = $changedStatus->get('name');
            }
        }

        if ( count($history) == 1 && $history['items']["not_changed"] == "1" ) {
            $history = array();
        }

        if (!empty($history)) {
            $orderHistory = new \XLite\Module\AOM\Model\OrderHistory();
            $orderHistory->set('order_id',$order->get('order_id'));
            $orderHistory->set('login',$this->auth->getComplex('profile.login'));
            $orderHistory->set('changes', $history);
            $orderHistory->set('date',time());
            $orderHistory->create();
        }
    }
}

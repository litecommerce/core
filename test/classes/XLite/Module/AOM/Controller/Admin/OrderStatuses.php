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
class XLite_Module_AOM_Controller_Admin_OrderStatuses extends XLite_Controller_Admin_Abstract
{
    public $statuses 	= null;
    public $letters	= null;
    public $parent		= null;
    public $deleted	= false;

    function getParentStatuses() 
    {
        if (is_null($this->parent)) {
            $status = new XLite_Module_AOM_Model_OrderStatus();
            $this->parent = $status->findAll("parent = ''");
        }
        return $this->parent;
    }
    
    function getOrderStatuses()   
    {
        if (is_null($this->statuses)) {
            $status = new XLite_Module_AOM_Model_OrderStatus();
            $statuses = $status->findAll();
            foreach ($statuses as $status_) {
                $letter = $status_->get('status');
                $parent = $status_->get('parent');
                if ($parent == "") {
                    $this->statuses[$letter]['base'] = $status_;
                } else {
                    $order = new XLite_Model_Order();
                    $counter = $order->count("substatus = '$letter'");
                    $this->statuses[$parent]['children'][$letter]['disabled'] = ($counter ? true : false);
                    if (strlen($letter) != 1) {
                        $this->statuses[$parent]['children'][$letter]['disabled'] = false;
                    }
                    if ($counter) {
                        $this->deleted = true;
                    }
                    $this->statuses[$parent]['children'][$letter]['status'] = $status_;
                }
            }
        }
        return $this->statuses;
    }

    function getLetters()  
    {
        if (is_null($this->letters)) {
            foreach ($this->get('orderStatuses') as $key => $status) {
                $used[] = $key;
                if (isset($status['children']) && is_array($status['children'])) 
                    foreach ($status['children'] as $key_ => $child) {
                        $used[] = $key_;
                    }
            }
            $used[] = "T";
            for ($i = 65; $i <= 90; $i++)
                $free[] = chr($i);
            $this->letters = array_diff($free, $used);
        }
        return $this->letters;
    }

    function action_add_status()  
    {
        if (!isset($this->add_status)) {
            return;
        }
        if (!is_array($this->add_status)) {
            return;
        }
        if (!isset($this->add_status['status'])) {
            return;
        }
        $this->add_status['status'] = strval($this->add_status['status']);
        if (strlen($this->add_status['status']) != 1) {
            return;
        }
        $status = new XLite_Module_AOM_Model_OrderStatus();
        if (!intval($this->add_status['orderby'])) {
            $status = new XLite_Module_AOM_Model_OrderStatus();
            $statuses = $status->findAll("parent = '".$this->add_status['parent']."' OR status = '".$this->add_status['parent']."'");
            $status_ids = array();
            foreach ($statuses as $status_) {
                $status_ids[] = intval($status_->get('orderby'));
            }
            $this->add_status['orderby'] = (!empty($status_ids) ? max($status_ids) + 1 : 1);
        }
        $status->set('properties',$this->add_status);
        $status->create();
    }

    function action_update()  
    {
        if ($this->get('update_status')) {
            foreach ($this->get('update_status') as $status_id => $properties) {
                $orderStatus = new XLite_Module_AOM_Model_OrderStatus($status_id);
                $orderStatus->set('properties',$properties);
                $orderStatus->set('email',isset($properties['email']));
                $orderStatus->set('cust_email',isset($properties['cust_email']));
                
                $orderStatus->update();
            }
        }
    }
    
    function action_delete() 
    {
        if ($this->get('delete_status')) {
            foreach ($this->get('delete_status') as $status_id) {
                $orderStatus = new XLite_Module_AOM_Model_OrderStatus($status_id);
                $orderStatus->delete();
            }
        }
    }
    
}

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

namespace XLite\Controller\Admin;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Order extends AAdmin
{
    public $params = array('target', "order_id");

    /**
     * getRegularTemplate 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getRegularTemplate()
    {
        if ('invoice' == \XLite\Core\Request::getInstance()->mode) {
            $return = "common/print_invoice.tpl";

        } else {
            $return = parent::getRegularTemplate();
        }

        return $return;
    }

    function getOrder()
    {
        if (is_null($this->order)) {
            $this->order = new \XLite\Model\Order($this->get('order_id'));
        }
        return $this->order;
    }

    function action_update()
    {
        $status = $this->xlite->config->General->clear_cc_info;
        $postData = \XLite\Core\Request::getInstance()->getData();

        if ($status != "N" && ($postData['status'] == $status && $status != $this->order->get('status'))) {
            $postData['details']["cc_number"] = "--- Removed ---";
            $postData['details']["cc_date"] = "--- Removed ---";
            $postData['details']["cc_cvv2"] = "--- Removed ---";
        }
        $this->getOrder()->set('properties', $postData);
        $this->order->update();
    }
    
}

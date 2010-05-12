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
class XLite_Controller_Admin_Countries extends XLite_Controller_Admin_Abstract
{
    function obligatorySetStatus($status)
    {
        if (!in_array("status", $this->params)) {
            $this->params[] = "status";
        }
        $this->set("status", $status);
    }

    function action_update()
    {
        // parse POST'ed data, modify country properties
        $country = new XLite_Model_Country();
        if (!empty(XLite_Core_Request::getInstance()->countries)) {
            foreach ($country->readAll() as $country) {
                $code = $country->get('code');
                if (array_key_exists($code, XLite_Core_Request::getInstance()->countries)) {
                    $_tmp = XLite_Core_Request::getInstance()->countries;
                    $data = $_tmp[$code];
                    $data['eu_member'] = isset($data['eu_member']) ? 'Y' : 'N';
                    $data['enabled'] = isset($data['enabled']) ? 1 : 0;
                    $country->set("properties", $data);
                    $country->update();
                }
            }
        }

        $this->obligatorySetStatus('updated');
    }

    function action_add()
    {
        if ( empty(XLite_Core_Request::getInstance()->code) ) {
            $this->set("valid", false);
            $this->obligatorySetStatus('code');
            return;
        }

        $country = new XLite_Model_Country();
        if ( $country->find("code='" . XLite_Core_Request::getInstance()->code . "'") ) {
            $this->set("valid", false);
            $this->obligatorySetStatus('exists');
            return;
        }

        if ( empty(XLite_Core_Request::getInstance()->country) ) {
            $this->set("valid", false);
            $this->obligatorySetStatus('country');
            return;
        }

        if ( empty(XLite_Core_Request::getInstance()->charset) ) {
            $this->set("valid", false);
            $this->obligatorySetStatus('charset');
            return;
        }

        $country->set("properties", XLite_Core_Request::getInstance()->getData());
        $country->set("eu_member", isset(XLite_Core_Request::getInstance()->eu_member) ? 'Y' : 'N');
        $country->set("enabled", isset(XLite_Core_Request::getInstance()->enabled) ? 1 : 0);
        $country->create();

        $this->obligatorySetStatus('added');
    }

    function action_delete()
    {
        $countries = XLite_Core_Request::getInstance()->delete_countries;

        if ( is_array($countries) && count($countries) > 0 ) {
            foreach ($countries as $code) {
                $country = new XLite_Model_Country($code);
                $country->delete();
            }
        }

        $this->obligatorySetStatus('deleted');
    }
}

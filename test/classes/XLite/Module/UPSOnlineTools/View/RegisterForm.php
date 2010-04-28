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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Register form
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_UPSOnlineTools_View_RegisterForm extends XLite_View_RegisterForm implements XLite_Base_IDecorator
{
    /**
     * UPS address validation error 
     * 
     * @var    mixed
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $upsError = false;

    /**
     * Check address 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkAddress()
    {

        // TODO - temporary disabled
        return true;

        if (XLite::isAdminZone()) {
            return true;
        }

        $actionType = XLite_Core_Request::getInstance()->action_type;

        if ($actionType == 1) {

            // Use suggestion
            $suggest = $this->get('suggest');
            $value = $this->session->get('ups_av_result');
            $value = $value[$suggest];

            XLite_Core_Request::getInstance()->shipping_country = 'US';
            $obj = new XLite_Model_State();
            if ($obj->find('country_code = \'US\' and code = \'' . $value['state'] . '\'')) {
                XLite_Core_Request::getInstance()->shipping_state = $obj->get('state_id');
                XLite_Core_Request::getInstance()->shipping_custom_state = '';

            } else {
                XLite_Core_Request::getInstance()->shipping_state = -1;
                XLite_Core_Request::getInstance()->shipping_custom_state = $value['state'];
            }

            XLite_Core_Request::getInstance()->shipping_city = $value['city'];
            XLite_Core_Request::getInstance()->shipping_zipcode = $value['zipcode'];

            $this->session->set('ups_av_result', null);

            return true;
        }

        $this->session->set('ups_av_result', null);
        $this->session->set('ups_av_error', null);

        if ($actionType == 2) {

            // Keep current address
            return true;

        } elseif ($actionType == 3) {

            // Re-enter address
            return false;

        } else {

            $obj = new XLite_Module_UPSOnlineTools_Model_Shipping_Ups();
            $avResult = array();

            # copy billing to shipping
            $arr = array(
                'billing_country'      => 'shipping_country',
                'billing_state'        => 'shipping_state',
                'billing_city'         => 'shipping_city',
                'billing_zipcode'      => 'shipping_zipcode',
                'billing_custom_state' => 'shipping_custom_state',
            );
            foreach ($arr as $bil => $ship) {
                if (
                    empty(XLite_Core_Request::getInstance()->$ship)
                    || ($ship == 'shipping_state' && XLite_Core_Request::getInstance()->$ship == -1)
                ) {
                    $upsUsed[$ship] = XLite_Core_Request::getInstance()->$bil;

                } else {
                    $upsUsed[$ship] = XLite_Core_Request::getInstance()->$ship;
                    $this->session->set('ups_used', $upsUsed);
                }
            }

            $requestResult = array();
            $result = $obj->checkAddress(
                $upsUsed['shipping_country'],
                $upsUsed['shipping_state'],
                $upsUsed['shipping_custom_state'],
                $upsUsed['shipping_city'],
                $upsUsed['shipping_zipcode'],
                $avResult,
                $requestResult
            );
            $this->session->set('ups_av_result', $avResult);
            XLite_Core_Request::getInstance()->action_type = null;
            $this->session->set('ups_av_profile', XLite_Core_Request::getInstance()->getData());

            if (
                true !== $result
                && 0 >= count($avResult)
            ) {
                // AV return error
                $this->session->set('ups_av_error', 1);
                $this->session->set('ups_av_errorcode', $requestResult['errorcode']);
                $this->session->set('ups_av_errordescr', $requestResult['errordescr']);

            } else {
                $this->session->set('ups_av_error', 0);
                $this->session->set('ups_av_errorcode', '');
                $this->session->set('ups_av_errordescr', '');
            }

            return $result;
        }
    }

    function action_register() {
        if ($this->checkAddress()) {
            return parent::action_register();
        }

        $this->set('valid', false);
        $this->set('upsError', true);
    }

    function action_modify() {
        if ($this->checkAddress()) {
            return parent::action_modify();
        }

        $this->set('valid', false);
        $this->set('upsError', true);
    }

}

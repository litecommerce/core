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

namespace XLite\Module\CDev\UPSOnlineTools\Controller\Admin;

/**
 * Register UPS account controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class UpsOnlineTool extends \XLite\Controller\Admin\AAdmin
{

    function getCurrentStep()
    {
        return intval($this->session->get('ups_step'));
    }

    function getLicense(&$ret)
    {
        $obj = new \XLite\Module\CDev\UPSOnlineTools\Model\Shipping\Ups();

        return $obj->getAgreement($ret);
    }

    function getHaveAccount()
    {
        return $this->config->CDev->UPSOnlineTools->UPS_username
            && $this->config->CDev->UPSOnlineTools->UPS_password
            && $this->config->CDev->UPSOnlineTools->UPS_accesskey;
    }

    protected function processStep2()
    {
        $result = true;

        if ($this->get('confirmed') != 'Y') {
            $this->set('returnUrl', $this->buildUrl('ups_online_tool', '', array('error' => 'license')));
            $result = false;

        } elseif ($this->getLicense($license)) {
            $this->set('returnUrl', $this->buildUrl('ups_online_tool', '', array('error' => 'http')));
            $result = false;
        }

        return $result;
    }

    protected function processStep3()
    {
        $obj = new \XLite\Module\CDev\UPSOnlineTools\Model\Shipping\Ups();
        $ret = $this->getReg();

        $result = true;
        $error = false;

        if ($obj->setAccount($ret, $error)) {
            $this->session->set('ups_message', $error);
            $result = false;
        }

        return $result;
    }

    function action_next()
    {
        $cs = $this->getCurrentStep();
        $func = 'processStep' . $cs;
        if (!method_exists($this, $func) || $this->$func()) {
            $cs++;
    	    $this->session->set('ups_step', $cs);
        }
    }

    protected function doActionCancel()
    {
        $tmp = $this->session->set('ups_step', 0);
    }

    function action_showlicense()
    {
        $result = $this->getLicense($license);
        echo $license;

        if ($result == 0) {
            echo <<<EOT
<br />
<div align="justify"><font style="FONT-FAMILY: Courier; FONT-SIZE: 10px;">
DO YOU AGREE TO ACCESS THE UPS SYSTEMS IN ACCORDANCE WITH AND BE BOUND BY EACHOF THE TERMS AND CONDITIONS SET FORTH ABOVE?<br />

<input type="radio" id="confirmed_y" name="confirmed" value="Y" onclick="javascript: setConfirmed('Y');"><label for="confirmed_y">Yes, I Do Agree</label>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" id="confirmed_n" name="confirmed" value="N" onclick="javascript: setConfirmed('N');"><label for="confirmed_n">No, I Do Not Agree</label>
</div>

<script type="text/javascript">
<!--
    para = window.parent;
    if (para) {
        doc = para.document;

        buttons = doc.getElementById('manage_buttons');
        if (buttons) {
            buttons.style.display = '';
        }
    }

function setConfirmed(val)
{
    para = window.parent;
    if (para) {
        doc = para.document;

        obj = doc.getElementById('confirmedLicense');
        if (obj) {
            obj.value = val;
        }
    }
}
-->
</script>
EOT;

        }

        exit(0);
    }

    function getMessage()
    {
        if (!$this->message) {
            $this->message = $this->session->get('ups_message');
            $this->session->set('ups_message', '');
        }

        return $this->message;
    }

    function getReg()
    {
        $ret = $this->session->get('ups_profile');
        if (!is_array($ret)) {
            $ret = array();
        }

        if (\XLite\Core\Request::getInstance()->isPost()) {
            $ret = array_merge($ret, \XLite\Core\Request::getInstance()->getData());
            $this->session->set('ups_profile', $ret);
        }

        if (empty($ret['software_installer'])) {
            $ret['software_installer'] = 'yes';
        }

        return $ret;
    }

    function getProfileArray()
    {
        $ret = array();

        $profile = $this->auth->getProfile()->get('properties');
        $ret['contact_name'] = $profile['billing_firstname'].' '.$profile['billing_lastname'];
        $ret['title_name'] = $profile['billing_title'];
        $ret['company'] = $profile['billing_company'];
        $ret['address'] = $profile['billing_address'];
        $ret['city'] = $profile['billing_city'];
//        $ret['state'] = $profile['billing_state'];
        $ret['country'] = $profile['billing_country'];
        $ret['postal_code'] = $profile['billing_zipcode'];
        $ret['phone'] = $profile['billing_phone'];
        $ret['email'] = $profile['login'];

        $ret['state'] = $this->auth->getProfile()->getComplex('billingState.code');

        return $ret;
    }
 
    protected function doActionFillFromProfile()
    {
        $profile_arr = $this->getProfileArray();
        $profile_arr = array_merge($this->getReg(), $profile_arr);

        $this->session->set('ups_profile', $profile_arr);
    }

    function getUPSStates()
    {
        $obj = new \XLite\Module\CDev\UPSOnlineTools\Model\Shipping\Ups();
        return $obj->getUPSStates();
    }

    function getUPSCountries()
    {
        $obj = new \XLite\Module\CDev\UPSOnlineTools\Model\Shipping\Ups();
        return $obj->getUPSCountries();
    }
}


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

namespace XLite\Module\UPSOnlineTools\Controller;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class AController extends \XLite\Controller\AController implements \XLite\Base\IDecorator
{
    function isShowAV()
    {
        $target = $this->get('target');
        $mode = $this->get('mode');
        if ($target == 'profile'|| ($target == 'checkout' && $mode == 'register')) {
            $av_result = $this->session->get('ups_av_result');
            if (count($av_result) > 0 || $this->session->get('ups_av_error')) return true;
        }
        else {
            $this->session->set('ups_av_result', null);
            $this->session->set('ups_av_error', null);
        }

        return false;
    }

    function getUpsUsed() 
    {
        if (!isset($this->_ups_profile)) {
            $this->_ups_profile = new \XLite\Model\Profile();
            $this->_ups_profile->set('properties', $this->session->get('ups_used'));
        }

        return $this->_ups_profile;
    }

    function isSuggestionExists()
    {
        $av_result = $this->session->get('ups_av_result');
        return (count($av_result) > 0) ? true : false;
    }

    function isAVError()
    {
        return $this->session->get('ups_av_error');
    }

    function getAVErrorMessage()
    {
        $errcode = $this->session->get('ups_av_errorcode');
        if (empty($errcode)) {
            return "Unable to connect. UPS OnLine&reg; Tools Address Validation service is not available.";
        } else {
            return "UPS OnLine&reg; Tools Address Validation returned an error: (".$errcode.") ".$this->session->get('ups_av_errordescr');
        }
    }

}

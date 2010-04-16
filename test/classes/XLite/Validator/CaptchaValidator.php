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
 * @subpackage Validator
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
class XLite_Validator_CaptchaValidator extends XLite_Validator_Abstract
{	
    public $template = "common/captcha_validator.tpl";	
    public $validation_required = array( // array: target => array(action, option_name);
        'help'             => array('action' => 'contactus', 'option' => 'on_contactus'),
        'profile'          => array('action' => 'register',  'option' => 'on_register'),
        'partner_profile'  => array('action' => 'register',  'option' => 'on_partner_register'),
        'gift_certificate' => array('action' => 'add',       'option' => 'on_add_giftcert')
    );

    function isValid()
    {
        $id = $this->get("id");

        if(!$this->isActiveCaptchaPage($id))
            return true;

        if (!parent::isValid()) {
            return false;
        }

        if(!isset($_POST['action']))
            return true;

        $code = $this->session->get("captcha_".$this->get("id"));
        if(!isset($code) && $this->xlite->get("captchaValidated")) {
			return true;
        }
        $code_submitted = strtoupper(trim($_POST[$this->get("field")]));

        $result = (isset($_POST[$this->get("field")]) && !empty($_POST[$this->get("field")]) && $code == $code_submitted);
        if ($result) {
        	$this->session->set("captcha_".$this->get("id"), null);
        	$this->xlite->set("captchaValidated", true);
        }
        return $result;
    }

    function isValidationUnnecessary()
    {
        return !array_key_exists($_REQUEST['target'], $this->validation_required) || 
               !$this->validation_required[$_REQUEST['target']]['action'] == $_REQUEST['action'] ||
               !$this->isActiveCaptchaPage($requests[$_REQUEST['target']]['option']);
    }

}

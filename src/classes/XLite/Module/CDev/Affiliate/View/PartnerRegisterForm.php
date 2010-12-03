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

namespace XLite\Module\CDev\Affiliate\View;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class PartnerRegisterForm extends \XLite\View\RegisterForm implements \XLite\Base\IDecorator
{
    function fillForm()
    {
        parent::fillForm();
        $this->pending_plan = $this->config->Affiliate->default_plan;
        if (!$this->xlite->is('adminZone') && $this->auth->is('logged')) {
            $this->_savedParent = (isset($this->parent)) ? $this->parent : null;
            $this->set('properties', $this->auth->getComplex('profile.properties'));
            if (isset($this->_savedParent)) {
            	$this->set('parent', $this->_savedParent);
            }
            $this->setComplex('profile.parent', $this->parent);
            // don't show passwords
            $this->password = $this->confirm_password = "";
        }
    }

    function action_register()
    {
        parent::action_register();
        if (isset($_POST['pending_plan'])) { // partner's profile POST'ed..
            if ($this->is('userExists') && !$this->auth->is('logged')) {
                // new partner profile but existing user
                return;
            }
            // register partner
            $result = $this->auth->registerPartner($this->profile);
            if ($result == \XLite\Core\Auth::ACCESS_DENIED) {
                $this->set('invalidPassword', true);
            } else {
                $this->set('valid', true);
                $this->set('mode', $this->config->Affiliate->moderated ? "sent" : "success"); // go to success page
            }
        }
    }
    
    function getProfile()
    {
        if (!$this->xlite->is('adminZone') && $this->auth->is('logged')) {
            $this->profile = $this->auth->get('profile');
        }
        if (is_null($this->profile)) {
            $this->profile = new \XLite\Model\Profile(isset($_REQUEST['profile_id']) ? $_REQUEST['profile_id'] : null);
        }
        return $this->profile;
    }
    
    function isShowPartnerFields()
    {
        return !is_null($this->profile) && ($this->profile->is('declinedPartner') || $this->profile->is('pendingPartner') || $this->profile->is('partner'));
    }

    function getPartnerFields()
    {
        if (is_null($this->partnerFields)) {
            $pf = new \XLite\Module\CDev\Affiliate\Model\PartnerField();
            $this->partnerFields = $pf->findAll();
        }
        return $this->partnerFields;
    }
}

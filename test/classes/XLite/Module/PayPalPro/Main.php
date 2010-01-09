<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  'COPYRIGHT' |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URL: http://www.litecommerce.com/license.php                |
|                                                                              |
| THIS  AGREEMENT EXPRESSES THE TERMS AND CONDITIONS ON WHICH YOU MAY USE THIS |
| SOFTWARE PROGRAM AND ASSOCIATED DOCUMENTATION THAT CREATIVE DEVELOPMENT, LLC |
| REGISTERED IN ULYANOVSK, RUSSIAN FEDERATION (hereinafter referred to as 'THE |
| AUTHOR')  IS  FURNISHING  OR MAKING AVAILABLE TO  YOU  WITH  THIS  AGREEMENT |
| (COLLECTIVELY,  THE 'SOFTWARE'). PLEASE REVIEW THE TERMS AND  CONDITIONS  OF |
| THIS LICENSE AGREEMENT CAREFULLY BEFORE INSTALLING OR USING THE SOFTWARE. BY |
| INSTALLING,  COPYING OR OTHERWISE USING THE SOFTWARE, YOU AND  YOUR  COMPANY |
| (COLLECTIVELY,  'YOU')  ARE ACCEPTING AND AGREEING  TO  THE  TERMS  OF  THIS |
| LICENSE AGREEMENT. IF YOU ARE NOT WILLING TO BE BOUND BY THIS AGREEMENT,  DO |
| NOT  INSTALL  OR USE THE SOFTWARE. VARIOUS COPYRIGHTS AND OTHER INTELLECTUAL |
| PROPERTY  RIGHTS PROTECT THE SOFTWARE. THIS AGREEMENT IS A LICENSE AGREEMENT |
| THAT  GIVES YOU LIMITED RIGHTS TO USE THE SOFTWARE AND NOT AN AGREEMENT  FOR |
| SALE  OR  FOR TRANSFER OF TITLE. THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY |
|                                                                              |
| The Initial Developer of the Original Code is Ruslan R. Fazliev              |
| Portions created by Ruslan R. Fazliev are Copyright (C) 2003 Creative        |
| Development. All Rights Reserved.                                            |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* 
*
* @package 
* @access public
* @version $Id$
*/
class XLite_Module_PayPalPro_Main extends XLite_Module_Abstract
{
    /**
     * Module version
     *
     * @var    string
     * @access protected
     * @since  3.0
     */
    protected $version = '2.9';

    /**
     * Module description
     *
     * @var    string
     * @access protected
     * @since  3.0
     */
    protected $description = 'This module introduces support for several PayPal website payment solutions';

    /**
     * Determines if module is switched on/off
     *
     * @var    bool
     * @access protected
     * @since  3.0
     */
    protected $enabled = true;

	var $showSettingsForm = true;

	function getSettingsForm() // {{{
    {
        return 'admin.php?target=payment_method&payment_method=paypalpro';

    } // }}}

    function init() // {{{
    {
        parent::init();

		$pm = new XLite_Model_PaymentMethod();

		if ($pm->find('payment_method = \'paypalpro\'')) {

	        switch($pm->get('params.solution')) {

	            case 'standard':
    	            $this->registerPaymentMethod('paypalpro');
					XLite_Model_PaymentMethod::factory('paypalpro')->checkServiceURL();
            		break;

	            case 'pro':
    	            $this->registerPaymentMethod('paypalpro');
        	        $this->registerPaymentMethod('paypalpro_express');
            		break;

	            case 'express':
    	            $this->registerPaymentMethod('paypalpro_express');
        		    break;
        	}
		}

        if ($this->xlite->mm->get('activeModules.PayPal')) {
            $modules = $this->xlite->mm->get('modules');
            $ids = array();
            foreach ($modules as $module) {
                if ($module->get('name') != 'PayPal' && $module->get('enabled') ) {
                    $ids[] = $module->get('module_id');
                }
            }
            $this->xlite->mm->updateModules($ids);
            $this->session->set('PayPalOff', true);
        }

        $this->xlite->set('PayPalProEnabled', true);
        $this->xlite->set('PayPalProSolution',$pm->get('params.solution'));

        if ('standard' !== $pm->get('params.solution') && XLite_Model_PaymentMethod::isRegisteredMethod('paypalpro_express')) {
			XLite::getInstance()->set('PayPalProExpressEnabled', XLite_Model_PaymentMethod::factory('paypalpro_express')->get('enabled'));
        }
    }
}


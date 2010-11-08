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

namespace XLite\Controller\Customer;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Help extends \XLite\Controller\Customer\ACustomer
{
    /**
     * Add part to the location nodes list
     *                                    
     * @return void                                  
     * @access protected                             
     * @see    ____func_see____                      
     * @since  3.0.0                                 
     */                                              
    protected function addBaseLocation() 
    {
        parent::addBaseLocation();

        $this->addLocationNode('Help zone');                                                
    }

    /**
     * Common method to determine current location 
     * 
     * @return array
     * @access protected 
     * @since  3.0.0
     */
    protected function getLocation()
    {
        $modes = array(
            'terms_conditions'  => 'Terms & Conditions',
            'privacy_statement' => 'Privacy statement',
            'contactus'         => 'Contact us',
        );

        return isset($modes[$mode = \XLite\Core\Request::getInstance()->mode]) ? $modes[$mode] : parent::getLocation();
    }


    /**
     * Get page title
     *
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getTitle()
    {
        return 'Help section';
    }



    function fillForm()
    {
        if ($this->get('mode') == "contactus" ) {
            if ($this->auth->is('logged')) {
                // fill in contact us form with default values
                $this->set('email', $this->auth->getComplex('profile.login'));
                $this->set('firstname', $this->auth->getComplex('profile.billing_firstname'));
                $this->set('lastname', $this->auth->getComplex('profile.billing_lastname'));
                $this->set('address', $this->auth->getComplex('profile.billing_address'));
                $this->set('zipcode', $this->auth->getComplex('profile.billing_zipcode'));
                $this->set('city', $this->auth->getComplex('profile.billing_city'));
                $this->set('contactus_state', $this->auth->getComplex('profile.billing_state'));
                $this->set('contactus_custom_state', $this->auth->getComplex('profile.billing_custom_state'));
                $this->set('contactus_country', $this->auth->getComplex('profile.billing_country'));
                $this->set('phone', $this->auth->getComplex('profile.billing_phone'));
                $this->set('fax', $this->auth->getComplex('profile.billing_fax'));
            } else {
                $this->set('contactus_state', $this->config->General->default_state);
                $this->set('contactus_country', $this->config->General->default_country);
            }
        }
    }

    function getState()
    {
        $s = \XLite\Core\Database::getRepo('XLite\Model\State')->find($this->get('state_id'));
        return $s->get('state');
    }

    function getCountry()
    {
        $c = \XLite\Core\Database::getRepo('XLite\Model\Country')->find($this->get('country_id'));
        return $c->get('country');
    }
    
    function action_contactus()
    {
        $mailer = new \XLite\Model\Mailer();
        $mailer->mapRequest();
        $st = \XLite\Core\Database::getRepo('XLite\Model\State')->find($_REQUEST['contactus_state']);
        if ($st->get('state_id') == -1) {
            $st->set('state', $_REQUEST['contactus_custom_state']);
        }
        $mailer->set('state', $st->get('state')); // fetch state name
        $cn = \XLite\Core\Database::getRepo('XLite\Model\Country')->find($_REQUEST['contactus_country']);
        $mailer->set('country', $cn->get('country')); // fetch country name
        $mailer->set('charset', $cn->get('charset'));
        $mailer->compose($this->get('email'), $this->config->Company->support_department, "contactus");
        $mailer->send();
        $this->set('mode', "contactusMessage");
    }
}

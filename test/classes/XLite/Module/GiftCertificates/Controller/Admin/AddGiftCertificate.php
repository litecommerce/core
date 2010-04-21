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
 * Add gift certificate
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_GiftCertificates_Controller_Admin_AddGiftCertificate extends XLite_Controller_Admin_Abstract
{
    /**
     * Controller parameters
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $params = array('target', 'gcid');    

    /**
     * Gift Certificate object 
     * 
     * @var    XLite_Module_GiftCertificates_Model_GiftCertificate
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $gc = null;

    /**
     * Get GC object
     * 
     * @return XLite_Module_GiftCertificates_Model_GiftCertificate
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getGC()
    {
        if (is_null($this->gc)) {

            if (XLite_Core_Request::getInstance()->gcid) {
                $this->gc = new XLite_Module_GiftCertificates_Model_GiftCertificate(
                    XLite_Core_Request::getInstance()->gcid
                );

            } else {
                // set default form values
                $this->gc = new XLite_Module_GiftCertificates_Model_GiftCertificate();
                $this->gc->set('send_via', 'E');
                $this->gc->set('border', 'no_border');
                $auth = XLite_Model_Auth::getInstance();
                if ($auth->isLogged()) {
                    $profile = $auth->getProfile();
                    $this->gc->set(
                        'purchaser',
                        $profile->get('billing_title')
                        . ' '
                        . $profile->get('billing_firstname')
                        . ' '   
                        . $profile->get('billing_lastname')
                    );
                }
                $this->gc->set('recipient_country', $this->config->General->default_country);
            }
        }

        return $this->gc;
    }

    /**
     * Fill GC form 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function fillForm()
    {
        $this->set('properties', $this->getGC()->get('properties'));

        if (!$this->get('expiration_date')) {
            $month = 30 * 24 * 3600;
            $this->set('expiration_date', time() + $month * $this->getGC()->get('defaultExpirationPeriod'));
        }

        parent::fillForm();
    }

    /**
     * Add
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionAdd()
    {
        $this->sendGC();
        $this->set('returnUrl', $this->buildUrl('gift_certificates'));
    }

    /**
     * Select e-card
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionSelectEcard()
    {
        $this->saveGC();
        $this->set(
            'returnUrl',
            $this->buildUrl('gift_certificate_select_ecard', '', array('gcid' => $this->getGC()->get('gcid')))
        );
    }

    /**
     * Delete e-card 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionDeleteEcard()
    {
        $this->saveGC();
        $gc = $this->getGC();
        if (!is_null($gc)) {
            $gc->set('ecard_id', 0);
            $gc->update();

            $this->set('returnUrl', $this->buildUrl('gift_certificate', '', array('gcid' => $gc->get('gcid'))));
        }
    }

    /**
     * Preview e-card 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionPreviewEcard()
    {
        $this->saveGC();
        $this->set(
            'returnUrl',
            $this->buildUrl('preview_ecard', '', array('gcid' => $this->getGC()->get('gcid')))
        );
    }

    /**
     * Save gift certificate 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function saveGC()
    {
        $gc = $this->getGC();

        if (!is_null($gc)) {
            $gc->setProperties(XLite_Core_Request::getInstance()->getData());
            $gc->set('add_date', time());
            $expirationDate = mktime(
                0, 0, 0,
                XLite_Core_Request::getInstance()->expiration_dateMonth,
                XLite_Core_Request::getInstance()->expiration_dateDay,
                XLite_Core_Request::getInstance()->expiration_dateYear
            );
            $gc->set('expiration_date', $expirationDate);

            if (empty(XLite_Core_Request::getInstance()->debit)) {
                $gc->set('debit', $gc->get('amount'));
            }

            if (!$gc->get('gcid')) {
                $gc->set('gcid', $gc->generateGC());
                $gc->create();
            }

            $gc->update();
        }
    }

    /**
     * Send gift certificate
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function sendGC()
    {
        $this->saveGC();

        $gc = $this->getGC();
        if (!is_null($gc)) {
            // Activate and send GC (for send_via = E)
            $gc->set('status', 'A');
            $gc->update();
        }
    }
    
    /**
     * Get countries states 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCountriesStates()
    {
        $countriesArray = array();

        $country = new XLite_Model_Country();
        $countries = $country->findAll('enabled = \'1\'');
        foreach ($countries as $country) {
            $countriesArray[$country->get('code')]['number'] = 0;
            $countriesArray[$country->get('code')]['data'] = array();

            $state = new XLite_Model_State();
            $states = $state->findAll('country_code = \'' . $country->get('code') . '\'');
            if (is_array($states) && count($states) > 0) {
                $countriesArray[$country->get('code')]['number'] = count($states);
                foreach ($states as $state) {
                    $countriesArray[$country->get('code')]['data'][$state->get('state_id')] = $state->get('state');
                }
            }
        }

        return $countriesArray;
    }
    
    /**
     * Check XLite version
     * 
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isVersionUpper2_1()
    {    
        return true;
    }
}

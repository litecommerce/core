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
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * XLite_View_Model_Profile_Abstract 
 * 
 * @package    XLite
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
abstract class XLite_View_Model_Profile_Abstract extends XLite_View_Model_Abstract
{
    /**
     * Return title
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getHead()
    {
        return 'Profile details';
    }

    /**
     * getFormContentTemplate
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getFormContentTemplate()
    {
        return 'profile/form_content.tpl';
    }

    /**
     * getDefaultModelObjectClass
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultModelObjectClass()
    {
        return 'XLite_Model_Profile';
    }

    /**
     * getDefaultModelObjectKeys
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultModelObjectKeys()
    {
        return array(XLite_Model_Session::getInstance()->get('profile_id'));
    }

    /**
     * Return name of web form widget class
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getFormClass()
    {
        return 'XLite_View_Form_Profile_Register';
    }

    /**
     * Define form field classes and values
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function defineFormFields()
    {
        parent::defineFormFields();

        $this->formFields += $this->getBillingAddressFields();
        $this->formFields += $this->getShippingAddressFields();
    }

    /**
     * getBillingAddressFieldsSchema 
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getBillingAddressFieldsSchema()
    {
        return array(
            'billing_firstname' => array(
                self::SCHEMA_CLASS    => 'XLite_View_FormField_Input_Text',
                self::SCHEMA_LABEL    => 'Name',
                self::SCHEMA_REQUIRED => true,
            ),
            'billing_address' => array(
                self::SCHEMA_CLASS    => 'XLite_View_FormField_Input_Text',
                self::SCHEMA_LABEL    => 'Street',
                self::SCHEMA_REQUIRED => true,
            ),
        );
    }

    /**
     * getShippingAddressFieldsSchema 
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getShippingAddressFieldsSchema()
    {
        return array(
            'shipping_firstname' => array(
                self::SCHEMA_CLASS    => 'XLite_View_FormField_Input_Text',
                self::SCHEMA_LABEL    => 'Name',
                self::SCHEMA_REQUIRED => true,
            ),
            'shipping_address' => array(
                self::SCHEMA_CLASS    => 'XLite_View_FormField_Input_Text',
                self::SCHEMA_LABEL    => 'Street',
                self::SCHEMA_REQUIRED => true,
            ),
        );
    }


    /**
     * getBillingAddressFields 
     * 
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getBillingAddressFields()
    {
        return $this->getFieldsBySchema($this->getBillingAddressFieldsSchema());
    }

    /**
     * getShippingAddressFields 
     *  
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getShippingAddressFields()
    {
        return $this->getFieldsBySchema($this->getShippingAddressFieldsSchema());
    }

    /**
     * Get a list of CSS files required to display the widget properly 
     * 
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = $this->getDir() . '/profile/profile.css';

        return $list;
    }
}


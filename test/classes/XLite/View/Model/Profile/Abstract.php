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
     * addressSchema 
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $addressSchema = array(
        'title' => array(
            self::SCHEMA_CLASS    => 'XLite_View_FormField_Select_Title',
            self::SCHEMA_LABEL    => 'Title',
        ),
        'firstname' => array(
            self::SCHEMA_CLASS    => 'XLite_View_FormField_Input_Text',
            self::SCHEMA_LABEL    => 'Firstname',
            self::SCHEMA_REQUIRED => true,
        ),
        'lastname' => array(
            self::SCHEMA_CLASS    => 'XLite_View_FormField_Input_Text',
            self::SCHEMA_LABEL    => 'Lastname',
            self::SCHEMA_REQUIRED => true,
        ),
        'company' => array(
            self::SCHEMA_CLASS    => 'XLite_View_FormField_Input_Text',
            self::SCHEMA_LABEL    => 'Company',
        ),
        'phone' => array(
            self::SCHEMA_CLASS    => 'XLite_View_FormField_Input_Text',
            self::SCHEMA_LABEL    => 'Phone',
            self::SCHEMA_REQUIRED => true,
        ),
        'fax' => array(
            self::SCHEMA_CLASS    => 'XLite_View_FormField_Input_Text',
            self::SCHEMA_LABEL    => 'Fax',
        ),
        'address' => array(
            self::SCHEMA_CLASS    => 'XLite_View_FormField_Input_Text',
            self::SCHEMA_LABEL    => 'Address',
            self::SCHEMA_REQUIRED => true,
        ),
        'city' => array(
            self::SCHEMA_CLASS    => 'XLite_View_FormField_Input_Text',
            self::SCHEMA_LABEL    => 'City',
            self::SCHEMA_REQUIRED => true,
        ),
        'state' => array(
            self::SCHEMA_CLASS    => 'XLite_View_FormField_Select_State',
            self::SCHEMA_LABEL    => 'State',
            self::SCHEMA_REQUIRED => true,
        ),
        'country' => array(
            self::SCHEMA_CLASS    => 'XLite_View_FormField_Select_Country',
            self::SCHEMA_LABEL    => 'Country',
            self::SCHEMA_REQUIRED => true,
        ),
        'zipcode' => array(
            self::SCHEMA_CLASS    => 'XLite_View_FormField_Input_Text',
            self::SCHEMA_LABEL    => 'Zip code',
            self::SCHEMA_REQUIRED => true,
        ),
    );


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
     * Return file name for body template
     *
     * @return id
     * @access protected
     * @since  3.0.0
     */
    protected function getBodyTemplate()
    {
        return $this->isExported() ? 'profile/form_content.tpl' : parent::getBodyTemplate();
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
     * getAddressSchema 
     * 
     * @param string $type address type
     *  
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getAddressSchema($type)
    {
        $result = array();

        foreach ($this->addressSchema as $key =>$data) {
            $result[$type . '_' . $key] = $data;
        }

        return $result;
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
        return $this->getFieldsBySchema($this->getAddressSchema('billing'));
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
        return $this->getFieldsBySchema($this->getAddressSchema('shipping'));
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


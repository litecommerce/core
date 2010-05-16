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
     * Form sections 
     */
    
    const SECTION_MAIN     = 'main';
    const SECTION_ACCESS   = 'access';
    const SECTION_BILLING  = 'billing';
    const SECTION_SHIPPING = 'shipping';


    /**
     * Targets this widget is allowed for
     *
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $allowedTargets = array('profile');

    /**
     * Available form sections 
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $sections = array(
        self::SECTION_MAIN     => 'E-mail & Password',
        self::SECTION_ACCESS   => 'Access information',
        self::SECTION_BILLING  => 'Billing address',
        self::SECTION_SHIPPING => 'Shipping address',
    );


    /**
     * Schema of the "E-mail & Password" section
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $mainSchema = array(
        'login' => array(
            self::SCHEMA_CLASS    => 'XLite_View_FormField_Input_Text',
            self::SCHEMA_LABEL    => 'E-mail',
            self::SCHEMA_REQUIRED => true,
        ),
        'password' => array(
            self::SCHEMA_CLASS    => 'XLite_View_FormField_Input_Password',
            self::SCHEMA_LABEL    => 'Password',
            self::SCHEMA_REQUIRED => false,
            self::SCHEMA_VALUE    => '',
        ),
        'password_conf' => array(
            self::SCHEMA_CLASS    => 'XLite_View_FormField_Input_Password',
            self::SCHEMA_LABEL    => 'Confirm password',
            self::SCHEMA_REQUIRED => false,
            self::SCHEMA_VALUE    => '',
        ),
    );

    /**
     * Schema of the "User access" section
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $accessSchema = array(
        'access_level' => array(
            self::SCHEMA_CLASS    => 'XLite_View_FormField_Select_AccessLevel',
            self::SCHEMA_LABEL    => 'Access level',
            self::SCHEMA_REQUIRED => true,
        ),
        'status' => array(
            self::SCHEMA_CLASS    => 'XLite_View_FormField_Select_AccountStatus',
            self::SCHEMA_LABEL    => 'Account status',
            self::SCHEMA_REQUIRED => true,
        ),
        'membership' => array(
            self::SCHEMA_CLASS    => 'XLite_View_FormField_Select_Membership',
            self::SCHEMA_LABEL    => 'Membership',
            self::SCHEMA_REQUIRED => false,
        ),
    );

    /**
     * Schema of the "Billing/Shipping address" sections
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
     * Model class associated with the form
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
     * List of model primary keys
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultModelObjectKeys()
    {
        return array($this->getProfileId());
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

        foreach ($this->sections as $section => $label) {

            $this->formFields[$section] = new XLite_View_FormField_Separator_Regular(
                array(self::SCHEMA_LABEL => $label)
            );

            switch ($section) {

                case self::SECTION_MAIN:
                    $this->formFields += $this->getMainFields();
                    $this->formFields['password_conf']->setValue($this->formFields['password']->getValue());
                    break;

                case self::SECTION_ACCESS:
                    $this->formFields += $this->getAccessFields();
                    break;

                case self::SECTION_BILLING:
                    $this->formFields += $this->getBillingAddressFields();
                    break;

                case self::SECTION_SHIPPING:
                    $this->formFields += $this->getShippingAddressFields();
                    break;
            }
        }
    }

    /**
     * Modify address field schema for certain address type (billing or shipping) 
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
     * Populate model object properties by the passed data
     * 
     * @param array $data data to set
     *  
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function setModelProperties(array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = XLite_Model_Auth::encryptPassword($data['password']);
        }

        parent::setModelProperties($data);
    }

    /**
     * Check password and its confirmation
     * 
     * @param array $data passed data
     *  
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function checkPassword(array $data)
    {
        $result = true;

        if (isset($this->sections[self::SECTION_MAIN]) && (!empty($data['password']) || !empty($data['password_conf']))) {

            $result = $data['password'] == $data['password_conf'];

            if (!$result) {
                XLite_Core_TopMessage::getInstance()->addError('Password and its confirmation do not match');
            }
        } else {

            $this->getModelObject()->unsetProperty('password');
            $this->getModelObject()->unsetProperty('password_conf');
        }

        return $result;
    }

    /**
     * Create profile 
     * 
     * @param array $data model properties
     *  
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function performActionCreate(array $data = array())
    {
        return $this->checkPassword($data) ? parent::performActionCreate($data) : false;
    }

    /* Update profile 
     * 
     * @param array $data model properties
     *  
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function performActionUpdate(array $data = array())
    {
        return $this->checkPassword($data) ? parent::performActionUpdate($data) : false;
    }

    /**
     * Modify profile 
     * 
     * @param array $data model properties
     *  
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function performActionModify(array $data = array())
    {
        return $this->checkPassword($data) ? parent::performActionModify($data) : false;
    }


    /**
     * Return ID of current profile
     * 
     * @return int 
     * @access protected
     * @since  3.0.0
     */
    abstract public function getProfileId();


    /**
     * Return fields list by the corresponding schema
     * 
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getMainFields()
    {
        if (!$this->getModelObject()->isPersistent) {
            foreach (array('password', 'password_conf') as $field) {
                $this->mainSchema[$field][self::SCHEMA_REQUIRED] = true;
                unset($this->mainSchema[$field][self::SCHEMA_VALUE]);
            }
        }

        return $this->getFieldsBySchema($this->mainSchema);
    }

    /**
     * Return fields list by the corresponding schema
     * 
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getAccessFields()
    {
        return $this->getFieldsBySchema($this->accessSchema);
    }

    /**
     * Return fields list by the corresponding schema
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
     * Return fields list by the corresponding schema
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

    /**
     * Check - billing and shuipping addresses are equal or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isSameAddress()
    {
        return $this->getModelObject()->isSameAddress();
    }

    /**
     * Check data and return only ones for the current fieldset 
     * 
     * @param array $data data to check
     *  
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getFieldsetData(array $data)
    {
        $data = parent::getFieldsetData($data);

        if (isset($data['ship_as_bill'])) {
            if ('Y' == $data['ship_as_bill']) {
                foreach (array_keys($this->addressSchema) as $k) {
                    $data['shipping_' . $k] = $data['billing_' . $k];
                }
            }

            unset($data['ship_as_bill']);
        }

        return $data;
    }

    /**
     * Save form sections list
     *
     * @param array $params widget params
     *
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function __construct(array $params = array(), array $sections = array())
    {
        parent::__construct($params);

        if (!empty($sections)) {
            $this->sections = XLite_Core_Converter::filterArrayByKeys($this->sections, $sections);
        }
    }
}


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

namespace XLite\View\Model\Address;

/**
 * Profile model widget
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Address extends \XLite\View\Model\AModel
{
    /**
     * Schema of the address section
     * TODO: move to the module where this field is required:
     *   'address_type' => array(
     *       self::SCHEMA_CLASS    => '\XLite\View\FormField\Select\AddressType',
     *       self::SCHEMA_LABEL    => 'Address type',
     *       self::SCHEMA_REQUIRED => true,
     *   ),
     *
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $addressSchema = array(
        'title' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Select\Title',
            self::SCHEMA_LABEL    => 'Title',
            self::SCHEMA_REQUIRED => false,
        ),
        'firstname' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'Firstname',
            self::SCHEMA_REQUIRED => true,
        ),
        'lastname' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'Lastname',
            self::SCHEMA_REQUIRED => true,
        ),
        'street' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'Street',
            self::SCHEMA_REQUIRED => true,
        ),
        'city' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'City',
            self::SCHEMA_REQUIRED => true,
        ),
        'state_id' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Select\State',
            self::SCHEMA_LABEL    => 'State',
            self::SCHEMA_REQUIRED => false,
        ),
        'custom_state' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'Other state',
            self::SCHEMA_REQUIRED => false,
        ),
        'zipcode' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'Zip code',
            self::SCHEMA_REQUIRED => true,
        ),
        'country_code' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Select\Country',
            self::SCHEMA_LABEL    => 'Country',
            self::SCHEMA_REQUIRED => true,
        ),
        'phone' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'Phone',
            self::SCHEMA_REQUIRED => true,
        ),
    );

    /**
     * Address instance
     * 
     * @var    \XLite\Model\Address
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $address = null;

    /**
     * Returns widget head 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getHead()
    {
        return 'Address';
    }

    /**
     * This object will be used if another one is not pased
     *
     * @return \XLite\Model\AModel
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDefaultModelObject()
    {
        if (!isset($this->address)) {
            
            $addressId = $this->getAddressId();

            $address = null;

            if (isset($addressId)) {
                $this->address = \XLite\Core\Database::getRepo('XLite\Model\Address')->find($this->getAddressId());
            
            } elseif (isset(\XLite\Core\Request::getInstance()->profile_id)) {
                
                $profileId = \XLite\Core\Request::getInstance()->profile_id;

                if (isset($profileId)) {

                    $profileId = intval($profileId);
 
                    $this->address = new \XLite\Model\Address();
                    
                    $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->find($profileId);

                    if (isset($profile)) {
                        $this->address->setProfile($profile);
                        $this->address->setProfileId($profileId);
                    }
                }

            } else {
                $this->address = new \XLite\Model\Address();
            }
        }

        return $this->address;
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
        return '\XLite\View\Form\Address\Address';
    }

    /**
     * Pass the DOM IDs of the "State" selectbox to the "CountrySelector" widget
     * 
     * @param array &$fields Widgets list
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function setStateSelectorIds(array &$fields)
    {
        $addressId = $this->getAddressId();

        $fields[$addressId . '_country_code']->setStateSelectorIds(
            $fields[$addressId . '_state_id']->getFieldId(),
            $fields[$addressId . '_custom_state']->getFieldId()
        );
    }

    /**
     * Retrieve property from the model object
     *
     * @param string $name Field/property name
     *
     * @return mixed
     * @access protected
     * @since  3.0.0
     */
    protected function getModelObjectValue($name)
    {
        $name = preg_replace('/^([^_]*_)(.*)$/', '\2', $name);

        $methodName = 'get' . \XLite\Core\Converter::getInstance()->convertToCamelCase($name);

        if (method_exists($this->getModelObject(), $methodName)) {
            // Call the getter method
            $value = $this->getModelObject()->$methodName();
        }

        return $value;
    }

    /**
     * Define form field classes and values 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineFormFields()
    {
        parent::defineFormFields();
    }

    /**
     * Some JavaScript code to insert
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getTopInlineJSCode()
    {
        return $this->getWidget(array(), '\XLite\View\JS\StatesList')->getContent();
    }

    /**
     * Return text for the "Submit" button
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSubmitButtonLabel()
    {
        return 'Save';
    }

    /**
     * Return list of the "Button" widgets
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getFormButtons()
    {
        $result = parent::getFormButtons();
        $result['submit'] = new \XLite\View\Button\Submit(
            array(\XLite\View\Button\AButton::PARAM_LABEL => $this->getSubmitButtonLabel())
        );

        return $result;
    }

    /**
     * Return model object to use
     *
     * @return \XLite\Model\AModel
     * @access public
     * @since  3.0.0
     */
    public function getModelObject()
    {
        $address = parent::getModelObject();

        if (!isset($address)) {
            $address = $this->getDefaultModelObject();
        }

        return $address;
    }

    /**
     * getAddressSchema 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getAddressSchema()
    {
        $result = array();

        $addressId = $this->getAddressId();

        foreach ($this->addressSchema as $key => $data) {
            $result[$addressId . '_' . $key] = $data;
        }

        return $result;
    }

    /**
     * Return fields list by the corresponding schema
     *
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getFormFieldsForSectionDefault()
    {
        $result = $this->getFieldsBySchema($this->getAddressSchema());

        // For country <-> state syncronization
        $this->setStateSelectorIds($result);

        return $result;
    }

    /**
     * getRequestAddressId 
     * 
     * @return int|void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getRequestAddressId()
    {
        return \XLite\Core\Request::getInstance()->address_id;
    }

    /**
     * getRequestProfileId 
     * 
     * @return int|void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getRequestProfileId()
    {
        return \XLite\Core\Request::getInstance()->profile_id;
    }

    /**
     * Return current address ID
     * 
     * @return integer 
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getAddressId()
    {
        return $this->getRequestAddressId() ?: null;
    }

    /**
     * Update profile 
     * 
     * @return boolean 
     * @access protected
     * @since  3.0.0
     */
    protected function performActionUpdate()
    {
        parent::performActionUpdate();
    }

    /**
     * prepareDataForMapping 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareDataForMapping()
    {
        $data = parent::prepareDataForMapping();

        foreach ($data as $key => $value) {
            
            $newKey = preg_replace('/^([^_]*_)(.*)$/', '\2', $key);

            $data[$newKey] = $value;
            unset($data[$key]);
        }

        return $data;
    }

    /**
     * prepareObjectForMapping 
     * 
     * @return \XLite\Model\Address
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareObjectForMapping()
    {
        $address = parent::prepareObjectForMapping();

        $addressId = $address->getAddressId();

        if (!isset($addressId)) {
            
            if (isset(\XLite\Core\Request::getInstance()->profile_id)) {
                
                $profileId = \XLite\Core\Request::getInstance()->profile_id;

                if (isset($profileId)) {

                    $profileId = intval($profileId);
 
                    $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->find($profileId);

                    if (isset($profile)) {
                        $address->setProfile($profile);
                        $address->setProfileId($profileId);
                    }
                }
            }
        }

        return $address;
    }

}

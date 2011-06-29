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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\View\Model\Address;

/**
 * Profile model widget
 *
 * @see   ____class_see____
 * @since 1.0.0
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
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $addressSchema = array(
        'title' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Select\Title',
            self::SCHEMA_LABEL    => 'Title',
            self::SCHEMA_REQUIRED => false,
            \XLite\View\FormField\AFormField::PARAM_WRAPPER_CLASS => 'address-title',
        ),
        'firstname' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'Firstname',
            self::SCHEMA_REQUIRED => true,
            \XLite\View\FormField\AFormField::PARAM_WRAPPER_CLASS => 'address-firstname',
        ),
        'lastname' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'Lastname',
            self::SCHEMA_REQUIRED => true,
            \XLite\View\FormField\AFormField::PARAM_WRAPPER_CLASS => 'address-lastname',
        ),
        'street' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'Address',
            self::SCHEMA_REQUIRED => true,
            \XLite\View\FormField\AFormField::PARAM_WRAPPER_CLASS => 'address-street',
        ),
        'country_code' => array(
            self::SCHEMA_CLASS => '\XLite\View\FormField\Select\Country',
            self::SCHEMA_LABEL => 'Country',
            self::SCHEMA_REQUIRED => true,
            \XLite\View\FormField\AFormField::PARAM_WRAPPER_CLASS => 'address-country',
        ),
        'state_id' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Select\State',
            self::SCHEMA_LABEL    => 'State',
            self::SCHEMA_REQUIRED => true,
            \XLite\View\FormField\AFormField::PARAM_WRAPPER_CLASS => 'address-state',
        ),
        'custom_state' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'State',
            self::SCHEMA_REQUIRED => false,
            \XLite\View\FormField\AFormField::PARAM_WRAPPER_CLASS => 'address-customer-state',
        ),
        'city' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'City',
            self::SCHEMA_REQUIRED => true,
            \XLite\View\FormField\AFormField::PARAM_WRAPPER_CLASS => 'address-city',
        ),
        'zipcode' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'Zip code',
            self::SCHEMA_REQUIRED => true,
            \XLite\View\FormField\AFormField::PARAM_WRAPPER_CLASS => 'address-zipcode',
        ),
        'phone' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'Phone',
            self::SCHEMA_REQUIRED => true,
            \XLite\View\FormField\AFormField::PARAM_WRAPPER_CLASS => 'address-phone',
        ),
    );

    /**
     * Address instance
     *
     * @var   \XLite\Model\Address
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $address = null;


    /**
     * getAddressSchema
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * @return integer|void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getRequestAddressId()
    {
        return \XLite\Core\Request::getInstance()->address_id;
    }

    /**
     * Return current address ID
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getAddressId()
    {
        return $this->getRequestAddressId() ?: null;
    }

    /**
     * getRequestProfileId
     *
     * @return integer|void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getRequestProfileId()
    {
        return \XLite\Core\Request::getInstance()->profile_id;
    }

    /**
     * Return current profile ID
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getProfileId()
    {
        return ($this->getRequestProfileId() && \Xlite\Core\Auth::getInstance()->isAdmin())
            ? $this->getRequestProfileId()
            : \Xlite\Core\Auth::getInstance()->getProfile()->getProfileId();
    }


    /**
     * Returns widget head
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getHead()
    {
        return 'Address';
    }

    /**
     * This object will be used if another one is not pased
     *
     * @return \XLite\Model\Address
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultModelObject()
    {
        if (!isset($this->address)) {

            $addressId = $this->getAddressId();

            if (isset($addressId)) {
                $this->address = \XLite\Core\Database::getRepo('XLite\Model\Address')->find($this->getAddressId());

            } else {

                $this->address = new \XLite\Model\Address();

                $profileId = $this->getProfileId();

                if (0 < intval($profileId)) {

                    $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->find($profileId);

                    if (isset($profile)) {
                        $this->address->setProfile($profile);
                    }
                }
            }
        }

        return $this->address;
    }

    /**
     * Return name of web form widget class
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModelObjectValue($name)
    {
        $name = preg_replace('/^([^_]*_)(.*)$/', '\2', $name);

        return parent::getModelObjectValue($name);
    }

    /**
     * Some JavaScript code to insert
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTopInlineJSCode()
    {
        return $this->getWidget(array(), '\XLite\View\JS\StatesList')->getContent();
    }

    /**
     * Return text for the "Submit" button
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getSubmitButtonLabel()
    {
        return static::t('Save changes');
    }

    /**
     * Return list of the "Button" widgets
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getFormButtons()
    {
        $result = parent::getFormButtons();
        $result['submit'] = new \XLite\View\Button\Submit(
            array(
                \XLite\View\Button\AButton::PARAM_LABEL => $this->getSubmitButtonLabel(),
                \XLite\View\Button\AButton::PARAM_STYLE => 'action',
            )
        );

        return $result;
    }

    /**
     * prepareDataForMapping
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareDataForMapping()
    {
        $data = parent::prepareDataForMapping();

        foreach ($data as $key => $value) {

            $newKey = preg_replace('/^([^_]*_)(.*)$/', '\2', $key);

            $data[$newKey] = $value;
            
            unset($data[$key]);
        }

        if (isset($data['country_code'])) {

            $data['country'] = \XLite\Core\Database::getRepo('XLite\Model\Country')
                ->findOneByCode($data['country_code']);

            $data['state'] = null;

            if (isset($data['state_id'])) {

                $state = \XLite\Core\Database::getRepo('XLite\Model\State')->find($data['state_id']);

                if (isset($state) && $state->getCountry()->getCode() == $data['country_code']) {
                    $data['state'] = $state;
                    $data['custom_state'] = '';
                }

                unset($data['state_id']);
            }

            if (!isset($data['state'])) {
                $data['state'] = $data['custom_state'];
            }

            unset($data['country_code']);
        }

        return $data;
    }

    /**
     * Check if fields are valid
     *
     * @param array $data Current section data
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function validateFields(array $data)
    {
        $this->prepareDataToValidate($data);

        parent::validateFields($data);
    }

    /**
     * Prepare section data for validation
     *
     * @param array $data Current section data
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareDataToValidate($data)
    {
        $keys = array_keys($data[self::SECTION_PARAM_FIELDS]);
        $namePrefix = preg_replace('/^([^_]*_)(.*)$/', '\1', $keys[0]);

        if (
            isset($data[self::SECTION_PARAM_FIELDS][$namePrefix . 'state_id'])
            && isset($data[self::SECTION_PARAM_FIELDS][$namePrefix . 'country_code'])
        ) {

            $stateField = $data[self::SECTION_PARAM_FIELDS][$namePrefix . 'state_id'];

            if ('' == $stateField->getValue()) {

                $countryField = $data[self::SECTION_PARAM_FIELDS][$namePrefix . 'country_code'];

                $country = \XLite\Core\Database::getRepo('XLite\Model\Country')->find($countryField->getValue());

                // Disable state field required flag if selected country hasn't states
                if (!$country->hasStates()) {
                    $stateField->getWidgetParams(\XLite\View\FormField\AFormField::PARAM_REQUIRED)->setValue(false);
                }
            }
        }
    }
}

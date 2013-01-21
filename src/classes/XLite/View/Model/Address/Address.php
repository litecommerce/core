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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\View\Model\Address;

/**
 * Profile model widget
 *
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
     * @var array
     */
    protected $addressSchema = array();

    /**
     * Address instance
     *
     * @var \XLite\Model\Address
     */
    protected $address = null;

    /**
     * getAddressSchema
     *
     * @return array
     */
    public function getAddressSchema()
    {
        $result = array();

        $addressId = $this->getAddressId();

        foreach ($this->addressSchema as $key => $data) {
            $result[$addressId . '_' . $key] = $data;
        }

        foreach (\XLite\Core\Database::getRepo('XLite\Model\AddressField')->findAllEnabled() as $field) {

            $result[$addressId . '_' . $field->getServiceName()] = array(
                static::SCHEMA_CLASS    => $field->getSchemaClass(),
                static::SCHEMA_LABEL    => $field->getName(),
                static::SCHEMA_REQUIRED => $field->getRequired(),
                static::SCHEMA_MODEL_ATTRIBUTES => array(
                    \XLite\View\FormField\Input\Base\String::PARAM_MAX_LENGTH => 'length',
                ),
                \XLite\View\FormField\AFormField::PARAM_WRAPPER_CLASS => 'address-' . $field->getServiceName(),
            );
        }

        return $result;
    }

    /**
     * Return fields list by the corresponding schema
     *
     * @return array
     */
    public function getFormFieldsForSectionDefault()
    {
        $result = $this->getFieldsBySchema($this->getAddressSchema());

        // For country <-> state synchronization
        $this->setStateSelectorIds($result);

        return $result;
    }

    /**
     * getRequestAddressId
     *
     * @return integer|void
     */
    public function getRequestAddressId()
    {
        return \XLite\Core\Request::getInstance()->address_id;
    }

    /**
     * Return current address ID
     *
     * @return integer
     */
    public function getAddressId()
    {
        return $this->getRequestAddressId() ?: null;
    }

    /**
     * getRequestProfileId
     *
     * @return integer|void
     */
    public function getRequestProfileId()
    {
        return \XLite\Core\Request::getInstance()->profile_id;
    }

    /**
     * Return current profile ID
     *
     * @return integer
     */
    public function getProfileId()
    {
        return ($this->getRequestProfileId() && \XLite\Core\Auth::getInstance()->isAdmin())
            ? $this->getRequestProfileId()
            : \XLite\Core\Auth::getInstance()->getProfile()->getProfileId();
    }

    /**
     * Returns widget head
     *
     * @return string
     */
    protected function getHead()
    {
        return 'Address';
    }

    /**
     * This object will be used if another one is not pased
     *
     * @return \XLite\Model\Address
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
     * Return model field name for a provided form field name
     *
     * @param string $name Name of form field
     *
     * @return string
     */
    protected function getModelFieldName($name)
    {
        return preg_replace('/^([^_]*_)(.*)$/', '\2', parent::getModelFieldName($name));
    }

    /**
     * Return name of web form widget class
     *
     * @return string
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
     */
    protected function setStateSelectorIds(array &$fields)
    {
        $addressId = $this->getAddressId();

        if ($fields[$addressId . '_state_id'] && $fields[$addressId . '_custom_state']) {
            $fields[$addressId . '_country_code']->setStateSelectorIds(
                $fields[$addressId . '_state_id']->getFieldId(),
                $fields[$addressId . '_custom_state']->getFieldId()
            );
        }
    }

    /**
     * Retrieve property from the model object
     *
     * @param string $name Field/property name
     *
     * @return mixed
     */
    protected function getModelObjectValue($name)
    {
        $name = preg_replace('/^([^_]*_)(.*)$/', '\2', $name);

        return parent::getModelObjectValue($name);
    }

    /**
     * Return text for the "Submit" button
     *
     * @return string
     */
    protected function getSubmitButtonLabel()
    {
        return 'Save changes';
    }

    /**
     * Return list of the "Button" widgets
     *
     * @return array
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
     * @param array  $data    Current section data
     * @param string $section Current section name
     *
     * @return void
     */
    protected function validateFields(array $data, $section)
    {
        $this->prepareDataToValidate($data);

        parent::validateFields($data, $section);
    }

    /**
     * Prepare section data for validation
     *
     * @param array $data Current section data
     *
     * @return void
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

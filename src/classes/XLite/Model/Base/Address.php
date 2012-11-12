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

namespace XLite\Model\Base;

/**
 * Abstract address model
 *
 *
 * @MappedSuperclass
 */
abstract class Address extends \XLite\Model\AEntity
{
    /**
     * Unique id
     *
     * @var integer
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column (type="integer")
     */
    protected $address_id;

    /**
     * Address type: residential/commercial
     *
     * @var string
     *
     * @Column (type="fixedstring", length=1)
     */
    protected $address_type = 'R';

    /**
     * State
     *
     * @var \XLite\Model\State
     *
     * @ManyToOne  (targetEntity="XLite\Model\State", cascade={"merge","detach"})
     * @JoinColumn (name="state_id", referencedColumnName="state_id")
     */
    protected $state;

    /**
     * Country
     *
     * @var \XLite\Model\Country
     *
     * @ManyToOne  (targetEntity="XLite\Model\Country", cascade={"merge","detach"})
     * @JoinColumn (name="country_code", referencedColumnName="code")
     */
    protected $country;

    /**
     * Get address fields list
     *
     * @return array(string)
     */
    public function getAddressFields()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\AddressField')->findEnabledFields();
    }

    /**
     * Get state
     *
     * @return \XLite\Model\State
     */
    public function getState()
    {
        if ($this->state) {

            // Real state object
            $state = $this->state;

        } else {

            // Custom state
            $state = new \XLite\Model\State;
            $state->setState($this->getCustomState());
        }

        return $state;
    }

    /**
     * Set country
     *
     * @param integer $countryCode Country code
     */
    public function setCountryCode($countryCode)
    {
        $this->setterProperty('country_code', $countryCode);

        $this->setCountry(\XLite\Core\Database::getRepo('XLite\Model\Country')->findOneBy(array('code' => $countryCode)));
    }

    /**
     * Set state
     *
     * @param integer $state State id
     *
     * @return void
     */
    public function setStateId($stateId)
    {
        $this->setterProperty('state_id', $stateId);

        $this->setState(\XLite\Core\Database::getRepo('XLite\Model\State')->find($stateId));
    }

    /**
     * Set state
     *
     * @param mixed $state State object or state id or custom state name
     *
     * @return void
     * @todo Refactor?
     */
    public function setState($state)
    {
        if ($state instanceof \XLite\Model\State) {

            // Set by state object
            if ($state->getStateId()) {
                if (!$this->state || $this->state->getStateId() != $state->getStateId()) {
                    $this->state = $state;
                }
            } else {
                $this->state = null;
            }

        } elseif (is_string($state)) {
            // Set custom state
            $this->state = null;
        }
    }

    /**
     * Get state Id
     *
     * @return integer
     */
    public function getStateId()
    {
        return $this->getState()
            ? ($this->getState()->getStateId() ?: static::getDefaultFieldPlainValue('state_id'))
            : static::getDefaultFieldPlainValue('state_id');
    }

    /**
     * Get country code
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->getCountry()
            ? ($this->getCountry()->getCode() ?: static::getDefaultFieldPlainValue('country_code'))
            : static::getDefaultFieldPlainValue('country_code');
    }

    /**
     * Get country name
     *
     * @return string
     */
    public function getCountryName()
    {
        return $this->getCountry() ? $this->getCountry()->getCountry() : null;
    }

    /**
     * Get state name
     *
     * @return string
     */
    public function getStateName()
    {
        return $this->getState()->getState();
    }

    /**
     * Return default field value
     *
     * @param string $fieldName Field name
     *
     * @return string
     */
    public static function getDefaultFieldPlainValue($fieldName)
    {
        $field = \XLite\Core\Database::getRepo('\XLite\Model\Config')
            ->findOneBy(array(
                'category'  => \XLite\Model\Config::SHIPPING_CATEGORY,
                'name'      => static::getDefaultFieldName($fieldName)
            ));

        return $field ? $field->getValue() : '';
    }

    /**
     * Return name of the address field in the default shipping category of the settings
     *
     * @param string $fieldName
     *
     * @return string
     */
    protected static function getDefaultFieldName($fieldName)
    {
        $result = \XLite\Model\Config::SHIPPING_VALUES_PREFIX;

        switch ($fieldName) {
            case 'country_code':
                $result .= 'country';
                break;

            case 'state_id':
                $result .= 'state';
                break;

            case 'street':
                $result .= 'address';
                break;

            default:
                $result .= $fieldName;
                break;
        }

        return $result;
    }

    /**
     * Get required fields by address type
     *
     * @param string $atype Address type code
     *
     * @return array
     */
    public function getRequiredFieldsByType($atype)
    {
        return array();
    }

    /**
     * Get required and empty fields
     *
     * @param string $atype Address type code
     *
     * @return array
     */
    public function getRequiredEmptyFields($atype)
    {
        $result = array();

        foreach ($this->getRequiredFieldsByType($atype) as $name) {
            $method = 'get' . \XLite\Core\Converter::getInstance()->convertToCamelCase($name);
            // $method assebled from 'get' + \XLite\Core\Converter::getInstance()->convertToCamelCase() method
            if (!$this->$method()) {
                $result[] = $name;
            }
        }

        return $result;
    }

    /**
     * Check - address is completed or not
     *
     * @param string $atype Address type code
     *
     * @return boolean
     */
    public function isCompleted($atype)
    {
        return 0 == count($this->getRequiredEmptyFields($atype));
    }

    /**
     * Clone
     *
     * @return \XLite\Model\AEntity
     */
    public function cloneEntity()
    {
        $entity = parent::cloneEntity();

        if ($this->getCountry()) {
            $entity->setCountry($this->getCountry());
        }

        if ($this->getState()) {
            $entity->setState($this->getState());
        }

        return $entity;
    }

    /**
     * Update record in database
     *
     * @return boolean
     */
    public function update()
    {
        return $this->checkAddress() && parent::update();
    }

    /**
     * Create record in database
     *
     * @return boolean
     */
    public function create()
    {
        return $this->checkAddress() && parent::create();
    }


    /**
     * Check if address has duplicates
     *
     * @return boolean
     */
    protected function checkAddress()
    {
        return true;
    }
}

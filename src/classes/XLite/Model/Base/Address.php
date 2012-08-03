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
     * Phone
     *
     * @var string
     *
     * @Column (type="string", length=32)
     */
    protected $phone = '';

    /**
     * Street, number of building, apartment etc
     *
     * @var string
     *
     * @Column (type="string", length=255)
     */
    protected $street = '';

    /**
     * City
     *
     * @var string
     *
     * @Column (type="string", length=255)
     */
    protected $city = '';

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
     * Custom state
     *
     * @var string
     *
     * @Column (type="string", length=255)
     */
    protected $custom_state = '';

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
     * Zip/postal code
     *
     * @var string
     *
     * @Column (type="string", length=32)
     */
    protected $zipcode = '';

    /**
     * Get address fields list
     *
     * @return array(string)
     */
    public static function getAddressFields()
    {
        return array(
            'phone',
            'street',
            'city',
            'zipcode',
            'state_id',
            'custom_state',
            'country_code',
        );
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
     * Set state
     *
     * @param mixed $state State object or state id or custom state name
     *
     * @return void
     */
    public function setState($state)
    {
        if ($state instanceof \XLite\Model\State) {

            // Set by state object
            if ($state->getStateId()) {
                if (!$this->state || $this->state->getStateId() != $state->getStateId()) {
                    $this->state = $state;
                    $this->setCustomState('');
                }

            } else {

                $this->state = null;

                if ($state->getState()) {
                    $this->setCustomState($state->getState());
                }
            }


        } elseif (is_string($state)) {

            // Set custom state
            $this->state = null;
            $this->setCustomState($state);

        }
    }

    /**
     * Get state Id
     *
     * @return integer
     */
    public function getStateId()
    {
        return $this->getState() ? $this->getState()->getStateId() : null;
    }

    /**
     * Get country code
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->getCountry() ? $this->getCountry()->getCode() : null;
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

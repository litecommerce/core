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

namespace XLite\Model;

/**
 * Address model
 *
 *
 * @Entity (repositoryClass="\XLite\Model\Repo\Address")
 * @Table  (name="profile_addresses",
 *      indexes={
 *          @Index (name="is_billing", columns={"is_billing"}),
 *          @Index (name="is_shipping", columns={"is_shipping"})
 *      }
 * )
 */
class Address extends \XLite\Model\Base\PersonalAddress
{

    /**
     * Address type codes
     */
    const BILLING  = 'b';
    const SHIPPING = 's';


    /**
     * Address fields collection
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @OneToMany (targetEntity="XLite\Model\AddressFieldValue", mappedBy="address", cascade={"persist"})
     */
    protected $addressFields;

    /**
     * Flag: is it a billing address
     *
     * @var integer
     *
     * @Column (type="boolean")
     */
    protected $is_billing = false;

    /**
     * Flag: is it a shipping address
     *
     * @var integer
     *
     * @Column (type="boolean")
     */
    protected $is_shipping = false;

    /**
     * Profile: many-to-one relation with profile entity
     *
     * @var \XLite\Model\Profile
     *
     * @ManyToOne (targetEntity="XLite\Model\Profile", inversedBy="addresses")
     * @JoinColumn (name="profile_id", referencedColumnName="profile_id")
     */
    protected $profile;

    /**
     * Universal setter
     *
     * @param string $property
     * @param mixed  $value
     *
     * @return true|null Returns TRUE if the setting succeeds. NULL if the setting fails
     */
    public function setterProperty($property, $value)
    {
        $result = parent::setterProperty($property, $value);

        if (is_null($result)) {

            $addressField = \XLite\Core\Database::getRepo('XLite\Model\AddressField')
                ->findOneBy(array('serviceName' => $property));

            if ($addressField) {

                $repo = \XLite\Core\Database::getRepo('XLite\Model\AddressFieldValue');

                $data = array(
                    'address'       => $this,
                    'addressField'  => $addressField,
                );

                $addressFieldValue = $repo->findOneBy($data);

                if ($addressFieldValue) {
                    $addressFieldValue->setValue($value);

                    $repo->update($addressFieldValue);
                } else {

                    $data['value'] = $value;
                    $addressFieldValue = new \XLite\Model\AddressFieldValue($data);

                    $repo->insert($addressFieldValue);
                }

                $result = true;
            }
        }

        return $result;
    }

    /**
     * Universal getter
     *
     * @param string $property
     *
     * @return mixed|null Returns NULL if it is impossible to get the property
     */
    public function getterProperty($property)
    {
        $result = parent::getterProperty($property);

        if (is_null($result)) {

            $addressField = \XLite\Core\Database::getRepo('XLite\Model\AddressField')
                ->findOneBy(array('serviceName' => $property));

            if ($addressField) {

                $addressFieldValue = \XLite\Core\Database::getRepo('XLite\Model\AddressFieldValue')
                    ->findOneBy(array(
                        'address'       => $this->getAddressId(),
                        'addressField'  => $addressField->getId(),
                    ));

                $result = $addressFieldValue
                    ? $addressFieldValue->getValue()
                    : static::getDefaultFieldPlainValue($property);
            }
        }

        return $result;
    }

    /**
     * Get default value for the field
     *
     * @param string $fieldName Field service name
     *
     * @return mixed
     */
    public static function getDefaultFieldValue($fieldName)
    {
        $result = null;

        switch ($fieldName) {
            case 'country':
                $code = \XLite\Core\Config::getInstance()->Shipping->anonymous_country;
                $result = \XLite\Core\Database::getRepo('XLite\Model\Country')->findOneByCode($code);
                break;

            case 'state':
                $id = \XLite\Core\Config::getInstance()->Shipping->anonymous_state;
                $result = \XLite\Core\Database::getRepo('XLite\Model\State')->find($id);
                break;

            case 'zipcode':
                $result = \XLite\Core\Config::getInstance()->Shipping->anonymous_zipcode;
                break;

            case 'city':
                $result = \XLite\Core\Config::getInstance()->Shipping->anonymous_city;
                break;

            default:
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
        switch ($atype) {
            case static::BILLING:
                $list = \XLite\Core\Database::getRepo('XLite\Model\AddressField')->getBillingRequiredFields();
                break;

            case static::SHIPPING:
                $list = \XLite\Core\Database::getRepo('XLite\Model\AddressField')->getShippingRequiredFields();
                break;

            default:
                $list = null;
                // TODO - add throw exception
        }

        return $list;
    }

    /**
     * Clone
     *
     * @return \XLite\Model\AEntity
     */
    public function cloneEntity()
    {
        $entity = parent::cloneEntity();

        $cnd = array('address' => $this);

        foreach (\XLite\Core\Database::getRepo('XLite\Model\AddressField')->findAllEnabled() as $field) {

            $cnd['addressField'] = $field;

            $fieldValue = \XLite\Core\Database::getRepo('XLite\Model\AddressFieldValue')->findOneBy($cnd);

            if ($fieldValue) {

                $newFieldValue = $fieldValue->cloneEntity();
                $newFieldValue->setAddress($entity);
                $newFieldValue->setAddressField($field);
            }

            \XLite\Core\Database::getEM()->persist($newFieldValue);
        }

        if ($this->getProfile()) {
            $entity->setProfile($this->getProfile());
        }

        return $entity;
    }
}

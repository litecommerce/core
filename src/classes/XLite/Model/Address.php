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
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ManyToOne (targetEntity="XLite\Model\Profile", inversedBy="addresses")
     * @JoinColumn (name="profile_id", referencedColumnName="profile_id")
     */
    protected $profile;


    /**
     * Get billing address-specified required fields
     *
     * @return array
     */
    public function getBillingRequiredFields()
    {
        return array(
            'name',
            'street',
            'city',
            'zipcode',
            'state',
            'country',
        );
    }

    /**
     * Get shipping address-specified required fields
     *
     * @return array
     */
    public function getShippingRequiredFields()
    {
        return array(
            'name',
            'street',
            'city',
            'zipcode',
            'state',
            'country',
        );
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
            case self::BILLING:
                $list = $this->getBillingRequiredFields();
                break;

            case self::SHIPPING:
                $list = $this->getShippingRequiredFields();
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

        if ($this->getProfile()) {
            $entity->setProfile($this->getProfile());
        }

        return $entity;
    }


    /**
     * Check if address has duplicates
     *
     * @return boolean
     */
    protected function checkAddress()
    {
        $result = parent::checkAddress();

        $sameAddress = $this->getRepository()->findSameAddress($this);

        if ($sameAddress) {
            \XLite\Core\TopMessage::addWarning('Address was not saved as other address with specified fields is already exists.');
            $result = false;
        }

        return $result;
    }
}

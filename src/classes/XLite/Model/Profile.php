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
 * The "profile" model class
 *
 *
 * @Entity (repositoryClass="\XLite\Model\Repo\Profile")
 * @Table  (name="profiles",
 *      indexes={
 *          @Index (name="cms_profile", columns={"cms_name","cms_profile_id"}),
 *          @Index (name="login", columns={"login"}),
 *          @Index (name="order_id", columns={"order_id"}),
 *          @Index (name="password", columns={"password"}),
 *          @Index (name="access_level", columns={"access_level"}),
 *          @Index (name="first_login", columns={"first_login"}),
 *          @Index (name="last_login", columns={"last_login"}),
 *          @Index (name="status", columns={"status"})
 *      }
 * )
 */
class Profile extends \XLite\Model\AEntity
{
    /**
     * Profile unique ID
     *
     * @var integer
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="integer")
     */
    protected $profile_id;

    /**
     * Login (e-mail)
     *
     * @var string
     *
     * @Column (type="string", length=128)
     */
    protected $login;

    /**
     * Password
     *
     * @var string
     *
     * @Column (type="string")
     */
    protected $password = '';

    /**
     * Password hint
     *
     * @var string
     *
     * @Column (type="string", length=128)
     */
    protected $password_hint = '';

    /**
     * Password hint answer
     *
     * @var string
     *
     * @Column (type="string", length=128)
     */
    protected $password_hint_answer = '';

    /**
     * Access level
     *
     * @var integer
     *
     * @Column (type="integer")
     */
    protected $access_level = 0;

    /**
     * CMS profile Id
     *
     * @var integer
     *
     * @Column (type="integer")
     */
    protected $cms_profile_id = 0;

    /**
     * CMS name
     *
     * @var string
     *
     * @Column (type="string", length=32)
     */
    protected $cms_name = '';

    /**
     * Timestamp of profile creation date
     *
     * @var integer
     *
     * @Column (type="integer")
     */
    protected $added = 0;

    /**
     * Timestamp of first login event
     *
     * @var integer
     *
     * @Column (type="integer")
     */
    protected $first_login = 0;

    /**
     * Timestamp of last login event
     *
     * @var integer
     *
     * @Column (type="integer")
     */
    protected $last_login = 0;

    /**
     * Profile status
     *
     * @var string
     *
     * @Column (type="fixedstring", length=1)
     */
    protected $status = 'E';

    /**
     * Referer
     *
     * @var string
     *
     * @Column (type="string", length=255)
     */
    protected $referer = '';

    /**
     * Relation to a order
     *
     * @var \XLite\Model\Order
     *
     * @OneToOne   (targetEntity="XLite\Model\Order")
     * @JoinColumn (name="order_id", referencedColumnName="order_id")
     */
    protected $order;

    /**
     * Relation to an event
     *
     * @var \XLite\Model\OrderHistoryEvents
     *
     * @OneToMany   (targetEntity="XLite\Model\OrderHistoryEvents", mappedBy="author")
     * @JoinColumn (name="event_id", referencedColumnName="event_id")
     */
    protected $event;

    /**
     * Language code
     *
     * @var string
     *
     * @Column (type="string", length=2)
     */
    protected $language = '';

    /**
     * Last selected shipping id
     *
     * @var integer
     *
     * @Column (type="integer", nullable=true)
     */
    protected $last_shipping_id;

    /**
     * Last selected payment id
     *
     * @var integer
     *
     * @Column (type="integer", nullable=true)
     */
    protected $last_payment_id;

    /**
     * Membership: many-to-one relation with memberships table
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ManyToOne  (targetEntity="XLite\Model\Membership")
     * @JoinColumn (name="membership_id", referencedColumnName="membership_id", onDelete="SET NULL")
     */
    protected $membership;

    /**
     * Pending membership: many-to-one relation with memberships table
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ManyToOne  (targetEntity="XLite\Model\Membership")
     * @JoinColumn (name="pending_membership_id", referencedColumnName="membership_id", onDelete="SET NULL")
     */
    protected $pending_membership;

    /**
     * Address book: one-to-many relation with address book entity
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @OneToMany (targetEntity="XLite\Model\Address", mappedBy="profile", cascade={"all"})
     */
    protected $addresses;

    /**
     * Roles
     *
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ManyToMany (targetEntity="XLite\Model\Role", mappedBy="profiles", cascade={"merge","detach"})
     */
    protected $roles;

    /**
     * The count of orders placed by the user
     *
     * @var integer
     */
    protected $orders_count = null;


    /**
     * Set membership
     *
     * @param \XLite\Model\Membership $membership Membership OPTIONAL
     *
     * @return void
     */
    public function setMembership(\XLite\Model\Membership $membership = null)
    {
        $this->membership = $membership;
    }

    /**
     * Set pending membership
     *
     * @param \XLite\Model\Membership $pendingMembership Pending membership OPTIONAL
     *
     * @return void
     */
    public function setPendingMembership(\XLite\Model\Membership $pendingMembership = null)
    {
        $this->pending_membership = $pendingMembership;
    }

    /**
     * Get membership Id
     *
     * @return integer
     */
    public function getMembershipId()
    {
        return $this->getMembership() ? $this->getMembership()->getMembershipId() : null;
    }

    /**
     * Get pending membership Id
     *
     * @return integer
     */
    public function getPendingMembershipId()
    {
        return $this->getPendingMembership() ? $this->getPendingMembership()->getMembershipId() : null;
    }

    /**
     * Returns billing address
     *
     * @return \XLite\Model\Address
     */
    public function getBillingAddress()
    {
        return $this->getAddressByType(\XLite\Model\Address::BILLING);
    }

    /**
     * Returns shipping address
     *
     * @return \XLite\Model\Address
     */
    public function getShippingAddress()
    {
        return $this->getAddressByType(\XLite\Model\Address::SHIPPING);
    }

    /**
     * Returns first available address
     *
     * @return \XLite\Model\Address
     */
    public function getFirstAddress()
    {
        $result = null;

        foreach ($this->getAddresses() as $address) {
            $result = $address;
            break;
        }

        return $result;
    }

    /**
     * Returns the number of orders places by the user
     *
     * @return integer
     */
    public function getOrdersCount()
    {
        if (!isset($this->orders_count)) {

            $cnd = new \XLite\Core\CommonCell();
            $cnd->profile = $this;

            $this->orders_count = \XLite\Core\Database::getRepo('XLite\Model\Order')->search($cnd, true);
        }

        return $this->orders_count;
    }

    /**
     * Check if profile is enabled
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return 'E' == strtoupper($this->getStatus());
    }

    /**
     * Enable user profile
     *
     * @return void
     */
    public function enable()
    {
        $this->setStatus('E');
    }

    /**
     * Disable user profile
     *
     * @return void
     */
    public function disable()
    {
        $this->setStatus('D');
    }

    /**
     * Returns true if profile has an administrator access level
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->getAccessLevel() >= \XLite\Core\Auth::getInstance()->getAdminAccessLevel();
    }

    /**
     * Create an entity profile in the database
     *
     * @return boolean
     */
    public function create()
    {
        $this->prepareCreate();

        return parent::create();
    }

    /**
     * Update an entity in the database
     *
     * @return boolean
     */
    public function update($cloneMode = false)
    {
        // Check if user with specified e-mail address is already exists
        if (!$cloneMode) {
            $sameProfile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->findUserWithSameLogin($this);
        }

        if (isset($sameProfile)) {
            $this->addErrorEmailExists();
            $result = false;

        } else {

            // Do an entity update
            $result = parent::update();
        }

        return $result;
    }

    /**
     * Delete an entity profile from the database
     *
     * @return boolean
     */
    public function delete()
    {
        // Check if the deleted profile is a last admin profile
        if ($this->isAdmin() && 1 == \XLite\Core\Database::getRepo('XLite\Model\Profile')->findCountOfAdminAccounts()) {

            $result = false;

            \XLite\Core\TopMessage::addError('The only remaining active administrator profile cannot be deleted.');

        } else {
            $result = parent::delete();
        }

        return $result;
    }

    /**
     * Check if billing and shipping addresses are equal or not
     * TODO: review method after implementing at one-step-checkout
     *
     * @return boolean
     */
    public function isSameAddress()
    {
        $result = false;

        $billingAddress = $this->getBillingAddress();
        $shippingAddress = $this->getShippingAddress();

        if (isset($billingAddress) && isset($shippingAddress)) {

            $result = true;

            if ($billingAddress->getAddressId() != $shippingAddress->getAddressId()) {

                $addressFields = $billingAddress->getAddressFields();

                foreach ($addressFields as $name) {

                    $methodName = 'get' . \XLite\Core\Converter::getInstance()->convertToCamelCase($name);

                    if (method_exists($billingAddress, $methodName)) {

                        // Compare field values of billing and shipping addresses
                        if ($billingAddress->$methodName() != $shippingAddress->$methodName()) {
                            $result = false;
                            break;
                        }
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Check - billing and shipping addresses are equal or not
     *
     * @return boolean
     */
    public function isEqualAddress()
    {
        $billingAddress = $this->getBillingAddress();
        $shippingAddress = $this->getShippingAddress();

        return isset($billingAddress)
            && isset($shippingAddress)
            && $billingAddress->getAddressId() == $shippingAddress->getAddressId();
    }

    /**
     * Clone
     *
     * @return \XLite\Model\AEntity
     */
    public function cloneEntity()
    {
        $newProfile = parent::cloneEntity();

        if (!$newProfile->update(true) || !$newProfile->getProfileId()) {
            // TODO - add throw exception
            \XLite::getInstance()->doGlobalDie('Can not clone profile');
        }

        $newProfile->setMembership($this->getMembership());
        $newProfile->setPendingMembership($this->getPendingMembership());

        $billingAddress = $this->getBillingAddress();

        if (isset($billingAddress)) {

            $newBillingAddress = $billingAddress->cloneEntity();
            $newBillingAddress->setProfile($newProfile);
            $newProfile->addAddresses($newBillingAddress);
            $newBillingAddress->update();
        }

        if (!$this->isSameAddress() && $this->getShippingAddress()) {

            $newShippingAddress = $this->getShippingAddress()->cloneEntity();
            $newShippingAddress->setProfile($newProfile);
            $newProfile->addAddresses($newShippingAddress);
            $newShippingAddress->update();
        }

        $newProfile->update(true);

        return $newProfile;
    }

    /**
     * Constructor
     *
     * @param array $data Entity properties OPTIONAL
     *
     * @return void
     */
    public function __construct(array $data = array())
    {
        $this->addresses = new \Doctrine\Common\Collections\ArrayCollection();
        $this->roles     = new \Doctrine\Common\Collections\ArrayCollection();

        parent::__construct($data);
    }

    /**
     * Set order
     *
     * @param \XLite\Model\Order $order Order OPTIONAL
     *
     * @return void
     */
    public function setOrder(\XLite\Model\Order $order = null)
    {
        $this->order = $order;
    }

    /**
     * Get password hash algorhitm
     * 
     * @return string
     */
    public function getPasswordAlgo()
    {
        $parts = explode(':', $this->getPassword(), 2);

        return 1 == count($parts) ? 'MD5' : $parts[0];
    }

    /**
     * Prepare object for its creation in the database
     *
     * @return void
     */
    protected function prepareCreate()
    {
        // Assign a profile creation date/time
        if (!$this->getAdded()) {
            $this->setAdded(time());
        }

        // Assign current language
        $language = $this->getLanguage();

        if (empty($language)) {
            $this->setLanguage(\XLite\Core\Session::getInstance()->getLanguage()->getCode());
        }

        // Assign referer value
        if (empty($this->referer)) {
            if (\XLite\Core\Auth::getInstance()->isAdmin()) {
                $currentlyLoggedInProfile = \XLite\Core\Auth::getInstance()->getProfile();
                $this->setReferer(sprintf('Created by administrator (%s)', $currentlyLoggedInProfile->getLogin()));

            } elseif (isset($_COOKIE[\XLite\Core\Session::LC_REFERER_COOKIE_NAME])) {
                $this->setReferer($_COOKIE[\XLite\Core\Session::LC_REFERER_COOKIE_NAME]);
            }
        }

        // Assign status 'Enabled' if not defined
        if (empty($this->status)) {
            $this->enable();
        }

    }

    /**
     * Returns address by its type (shipping or billing)
     *
     * @param string $atype Address type: b - billing, s - shipping OPTIONAL
     *
     * @return \XLite\Model\Address
     */
    protected function getAddressByType($atype = \XLite\Model\Address::BILLING)
    {
        $result = null;

        foreach ($this->getAddresses() as $address) {
            if (
                (\XLite\Model\Address::BILLING == $atype && $address->getIsBilling())
                || (\XLite\Model\Address::SHIPPING == $atype && $address->getIsShipping())
            ) {
                // Select address if its type is same as a requested type...
                $result = $address;
                break;
            }
        }

        return $result;
    }

    /**
     * Add error top message 'Email already exists...'
     *
     * @return void
     */
    protected function addErrorEmailExists()
    {
        \XLite\Core\TopMessage::addError('Specified e-mail address is already used by other user.');
    }

    // {{{ Roles

    /**
     * Check - specified permission is allowed or not
     *
     * @param string $code Permission code
     *
     * @return boolean
     */
    public function isPermissionAllowed($code)
    {
        $allowed = false;

        if (0 < count($this->getRoles())) {
            foreach ($this->getRoles() as $role) {
                if ($role->isPermissionAllowed($code)) {
                    $allowed = true;
                    break;
                }
            }

        } elseif (0 == \XLite\Core\Database::getRepo('XLite\Model\Role')->count()) {
            $allowed = true;
        }

        return $allowed;
    }

    // }}}
}

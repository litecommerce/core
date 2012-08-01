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

namespace XLite\Controller\Customer;

/**
 * Addresses management controller
 *
 */
class AddressBook extends \XLite\Controller\Customer\ACustomer
{
    /**
     * address
     *
     * @var \XLite\Model\Address
     */
    protected $address = null;

    /**
     * Return the current page title (for the content area)
     *
     * @return string
     */
    public function getTitle()
    {
        return \XLite\Core\Request::getInstance()->widget ? 'Address details' : '';
    }

    /**
     * getAddress
     *
     * @return \XLite\Model\Address
     */
    public function getAddress()
    {
        return $this->address = $this->getModelForm()->getModelObject();
    }

    /**
     * Get return URL
     *
     * @return string
     */
    public function getReturnURL()
    {
        if (\XLite\Core\Request::getInstance()->action) {
            $profileId = \XLite\Core\Request::getInstance()->profile_id;

            if (!isset($profileId)) {
                $profileId = $this->correctProfileIdForURLParams($this->getAddress()->getProfile()->getProfileId());
            }

            $params = isset($profileId) ? array('profile_id' => $profileId) : array();
            $url = $this->buildURL('address_book', '', $params);

        } else {
            $url = parent::getReturnURL();
        }

        return $url;
    }

    /**
     * Remove profileId from URL params if it is profileId of already logged in user
     * 
     * @param integer $profileId Profile ID
     *  
     * @return integer
     */
    protected function correctProfileIdForURLParams($profileId)
    {
        if (\XLite\Core\Auth::getInstance()->getProfile()->getProfileId() === $profileId) {
            $profileId = null;
        }

        return $profileId;
    }

    /**
     * Alias
     *
     * @return \XLite\Model\Profile
     */
    protected function getProfile()
    {
        return $this->getModelForm()->getModelObject()->getProfile() ?: new \XLite\Model\Profile();
    }

    /**
     * Common method to determine current location
     *
     * @return string
     */
    protected function getLocation()
    {
        return 'Address book';
    }

    /**
     * Add part to the location nodes list
     *
     * @return void
     */
    protected function addBaseLocation()
    {
        parent::addBaseLocation();

        $this->addLocationNode('My account');
    }

    /**
     * getModelFormClass
     *
     * @return string
     */
    protected function getModelFormClass()
    {
        return '\XLite\View\Model\Address\Address';
    }

    /**
     * doActionSave
     *
     * @return void
     */
    protected function doActionSave()
    {
        return $this->getModelForm()->performAction('update');
    }

    /**
     * doActionDelete
     *
     * @return void
     */
    protected function doActionDelete()
    {
        $address = $this->getAddress();

        if (isset($address)) {
            $address->getProfile()->getAddresses()->removeElement($address);
            \XLite\Core\Database::getEM()->remove($address);
            \XLite\Core\Database::getEM()->flush();

            \XLite\Core\TopMessage::addInfo('Address has been deleted');
        }
    }

    /**
     * doActionCancelDelete
     *
     * @return void
     */
    protected function doActionCancelDelete()
    {
        // Do nothing, action is needed just for redirection back
    }
}

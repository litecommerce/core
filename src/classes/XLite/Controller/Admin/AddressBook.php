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
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Controller\Admin;

/**
 * Profile management controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class AddressBook extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Common method to determine current location
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getLocation()
    {
        return $this->getModelForm()->getModelObject()->getProfile()->getLogin();
    }

    /**
     * Add part to the location nodes list
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function addBaseLocation()
    {
        parent::addBaseLocation();

        $this->addLocationNode('Search profiles', $this->buildURL('users'));
    }

    /**
     * address 
     * 
     * @var    \XLite\Model\Address
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $address = null;

    /**
     * getAddress 
     * 
     * @return \XLite\Model\Address
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getAddress()
    {
        if (!isset($this->address)) {

            $addressId = \XLite\Core\Request::getInstance()->address_id;

            if (isset($addressId)) {
                $this->address = \XLite\Core\Database::getRepo('XLite\Model\Address')->find($addressId);
            }
        }

        return $this->address;
    }

    /**
     * getModelFormClass 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getModelFormClass()
    {
        return '\XLite\View\Model\Address\Address';
    }

    /**
     * doActionSave 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionSave()
    {
        return $this->getModelForm()->performAction('update');
    }

    /**
     * doActionDelete 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionDelete()
    {
        $address = $this->getAddress();
    
        if (isset($address)) {
            \XLite\Core\Database::getEM()->remove($address);
            \XLite\Core\Database::getEM()->flush();

            \XLite\Core\TopMessage::getInstance()->addInfo(
                $this->t('Address has been deleted')
            );
        }
    }

    /**
     * doActionCancelDelete 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionCancelDelete()
    {
        // Do nothing, action is needed just for redirection back
    }

    /**
     * Get return URL
     *
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getReturnUrl()
    {
        if (\XLite\Core\Request::getInstance()->action) {

            $profileId = \XLite\Core\Request::getInstance()->profile_id;

            if (!isset($profileId)) {
                $profileId = $this->getAddress()->getProfileId();
            }

            $params = isset($profileId) ? array('profile_id' => $profileId) : array();

            $url = $this->buildUrl('address_book', '', $params);

        } else {
            $url = parent::getReturnUrl();
        }

        return $url;
    }

}


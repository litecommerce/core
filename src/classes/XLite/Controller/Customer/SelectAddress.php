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

namespace XLite\Controller\Customer;

/**
 * Select address from address book
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class SelectAddress extends \XLite\Controller\Customer\Cart
{
    /**
     * Controller parameters
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $params = array('target', 'atype');

    /**
     * Common method to determine current location 
     *  
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getLocation()
    {
        return 'Pick address from address book';
    }

    /**
     * Check if current page is accessible
     *
     * @return boolean 
     * @access protected
     * @since  3.0.0
     */
    protected function checkAccess()
    {
        return parent::checkAccess()
            && $this->getCart()->getOrigProfile()
            && !$this->getCart()->getOrigProfile()->getOrderId();
    }
    /**
     * Get page title
     *
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getTitle()
    {
        return 'Pick address from address book';
    }

    /**
     * Select address
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionSelect()
    {
        $atype = \XLite\Core\Request::getInstance()->atype;
        $addressId = \XLite\Core\Request::getInstance()->addressId;

        if (\XLite\Model\Address::SHIPPING != $atype && \XLite\Model\Address::BILLING != $atype) {

            $this->valid = false;
            \XLite\Core\TopMessage::addError('Address type ahs wrong value');

        } elseif (!$addressId) {

            $this->valid = false;
            \XLite\Core\TopMessage::addError('Address is not selected');

        } else {
            $address = \XLite\Core\Database::getRepo('XLite\Model\Address')->find($addressId);

            if (!$address) {

                // Address not found
                $this->valid = false;
                \XLite\Core\TopMessage::addError('Address not found');

            } elseif (
                \XLite\Model\Address::SHIPPING == $atype
                && $this->getCart()->getProfile()->getShippingAddress()
                && $address->getAddressId() == $this->getCart()->getProfile()->getShippingAddress()->getAddressId()
            ) {

                // This shipping address is already selected
                $this->silenceClose = true;

            } elseif (
                \XLite\Model\Address::BILLING == $atype
                && $this->getCart()->getProfile()->getBillingAddress()
                && $address->getAddressId() == $this->getCart()->getProfile()->getBillingAddress()->getAddressId()
            ) {

                // This billing address is already selected
                $this->silenceClose = true;

            } else {

                if (\XLite\Model\Address::SHIPPING == $atype) {
                    $old = $this->getCart()->getProfile()->getShippingAddress();
                    if ($old) {
                        $old->setIsShipping(false);
                    }
                    $address->setIsShipping(true);
        
                    \XLite\Core\Event::updateCart(array('shippingAddress' => true));

                } else {
                    $old = $this->getCart()->getProfile()->getBillingAddress();
                    if ($old) {
                        $old->setIsBilling(false);
                    }
                    $address->setIsBilling(true);
                    \XLite\Core\Event::updateCart(
                        array(
                            'billingAddress' => array(
                                'same' => $address->getIsShipping(),
                            ),
                        )
                    );
                }

                \XLite\Core\Database::getEM()->flush();

                $this->updateCart();

                $this->silenceClose = true;
            }
        }
    }

    /**
     * Get current aAddress id 
     * 
     * @return integer|void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCurrentAddressId()
    {
        $address = null;

        if ($this->getCart()->getProfile()) {
            $address = \XLite\Model\Address::SHIPPING == \XLite\Core\Request::getInstance()->atype
                ? $this->getCart()->getProfile()->getShippingAddress()
                : $this->getCart()->getProfile()->getBillingAddress();
        }

        return $address ? $address->getAddressId() : null;
    }
}


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

namespace XLite\View\Checkout;

/**
 * Shipping address block
 *
 */
class ShippingAddress extends \XLite\View\Checkout\AAddressBlock
{
    /**
     * Get shipping address
     *
     * @return \XLite\Model\Address
     */
    public function getShippingAddress()
    {
        $address = null;

        $profile = $this->getCart()->getProfile();

        if ($profile) {

            $address = $profile->getShippingAddress();

            if (!$address) {
                $address = $profile->getFirstAddress();

                if ($address) {
                    $address->setIsShipping(true);
                    $address->update();
                }
            }
        }

        return $address;
    }

    /**
     * Get an array of address fields
     *
     * @return array
     */
    public function getAddressFields()
    {
        $result = array();

        foreach (\XLite\Core\Database::getRepo('XLite\Model\AddressField')->findAllEnabled() as $field) {
            $result[$field->getServiceName()] = array(
                \XLite\View\Model\Address\Address::SCHEMA_CLASS                 => $field->getSchemaClass(),
                \XLite\View\Model\Address\Address::SCHEMA_LABEL                 => $field->getName(),
                \XLite\View\Model\Address\Address::SCHEMA_REQUIRED              => $field->getRequired(),
                \XLite\View\Model\Address\Address::SCHEMA_MODEL_ATTRIBUTES      => array(
                    \XLite\View\FormField\Input\Base\String::PARAM_MAX_LENGTH => 'length',
                ),
                \XLite\View\FormField\AFormField::PARAM_WRAPPER_CLASS           => 'address-' . $field->getServiceName(),
            );
        }

        return $result;
    }

    /**
     * Get address info model
     *
     * @return \XLite\Model\Address
     */
    protected function getAddressInfo()
    {
        return $this->getShippingAddress();
    }

    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'checkout/steps/shipping/address.tpl';
    }
}

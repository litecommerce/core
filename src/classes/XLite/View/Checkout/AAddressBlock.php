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
 * Address block info
 *
 */
abstract class AAddressBlock extends \XLite\View\AView
{
    /**
     * Get address info model
     *
     * @return \XLite\Model\Address
     */
    abstract protected function getAddressInfo();

    /**
     * getFieldValue
     *
     * @param string  $fieldName    Field name
     * @param boolean $processValue Process value flag OPTIONAL
     *
     * @return string
     */
    public function getFieldValue($fieldName, $processValue = false)
    {
        $result = '';

        $address = $this->getAddressInfo();

        if (isset($address)) {

            $methodName = 'get' . \XLite\Core\Converter::getInstance()->convertToCamelCase($fieldName);

            // $methodName assembled from 'get' + camelized $fieldName
            $result = $address->$methodName();

            if ($result && false !== $processValue) {

                switch ($fieldName) {

                    case 'state_id':
                        $result = $address->getState()->getState();
                        break;

                    case 'country_code':
                        $result = $address->getCountry()->getCountry();
                        break;

                    default:

                }
            }
        } else {

            $result = \XLite\Model\Address::getDefaultFieldPlainValue($fieldName);
        }

        return $result;
    }

    /**
     * Add CSS classes to the list of attributes
     *
     * @param string $fieldName Field service name
     * @param array  $fieldData Array of field properties (see getAddressFields() for the details)
     *
     * @return array
     */
    public function getFieldAttributes($fieldName, $fieldData)
    {
        $classes = array('field-' . $fieldName);

        if ($fieldData[\XLite\View\Model\Address\Address::SCHEMA_REQUIRED]) {
            $classes[] = 'field-required';
        }

        return array(
            'class' => implode(' ', $classes),
        );
    }
}

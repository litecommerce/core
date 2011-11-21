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
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Controller\Admin;

/**
 * Zones page controller
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class ShippingZones extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Return the current page title (for the content area)
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTitle()
    {
        return 'Shipping zones';
    }
    /**
     * Add elements into the specified zone
     *
     * @param \XLite\Model\Zone $zone Zone object
     * @param array             $data Array of elements: array(<elementType> => array(value1, value2, value3...))
     *
     * @return \XLite\Model\Zone
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function addElements($zone, $data)
    {
        foreach ($data as $elementType => $elements) {

            if (is_array($elements) && !empty($elements)) {

                foreach ($elements as $elementValue) {

                    $newElement = new \XLite\Model\ZoneElement();

                    $newElement->setElementValue($elementValue);
                    $newElement->setElementType($elementType);
                    $newElement->setZone($zone);

                    $zone->addZoneElements($newElement);
                }
            }
        }

        return $zone;
    }


    /**
     * Common method to determine current location
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getLocation()
    {
        return 'Shipping zones';
    }

    /**
     * Do action 'Delete'
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionDelete()
    {
        $postedData = \XLite\Core\Request::getInstance()->getData();

        if (isset($postedData['to_delete']) && is_array($postedData['to_delete']) && !empty($postedData['to_delete'])) {
            $zoneIds = array();

            // Some validation
            foreach ($postedData['to_delete'] as $id => $value) {
                if (is_integer($id) && 0 < $id) {
                    $zoneIds[$id] = true;
                }
            }

            // Remove zones by ids
            \XLite\Core\Database::getRepo('XLite\Model\Zone')->deleteInBatchById($zoneIds);
            \XLite\Core\Database::getRepo('XLite\Model\Zone')->cleanCache();

            \XLite\Core\TopMessage::addInfo('The selected zones have been deleted successfully');
        }
    }

    /**
     * Do action 'Update'
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionUpdate()
    {
        $postedData = \XLite\Core\Request::getInstance()->getData();
        $zoneId = intval($postedData['zoneid']);

        if (isset($postedData['zoneid']) && 0 < $zoneId) {
            $zone = \XLite\Core\Database::getRepo('XLite\Model\Zone')->find($zoneId);
        }

        if (isset($zone)) {
            $data = $this->getElementsData($postedData);

            if (1 == $zoneId || !empty($data[\XLite\Model\ZoneElement::ZONE_ELEMENT_COUNTRY])) {

                // Remove all zone elements if exists
                if ($zone->hasZoneElements()) {

                    foreach ($zone->getZoneElements() as $element) {
                        \XLite\Core\Database::getEM()->remove($element);
                    }

                    $zone->getZoneElements()->clear();

                    \XLite\Core\Database::getEM()->persist($zone);
                    \XLite\Core\Database::getEM()->flush();
                }

                // Insert new elements from POST
                $zone = $this->addElements($zone, $data);

                // Prepare value for 'zone_name' field
                $zoneName = trim($postedData['zone_name']);

                if (!empty($zoneName) && $zoneName != $zone->getZoneName()) {
                    // Update zone name
                    $zone->setZoneName($zoneName);
                }

                \XLite\Core\Database::getEM()->persist($zone);
                \XLite\Core\Database::getEM()->flush();
                \XLite\Core\Database::getEM()->clear();

                \XLite\Core\Database::getRepo('XLite\Model\Zone')->cleanCache($zoneId);

                \XLite\Core\TopMessage::addInfo('Zone details have been updated successfully');

            } else {
                \XLite\Core\TopMessage::addError('The countries list for zone is empty. Please specify it.');
            }

            $this->redirect('admin.php?target=shipping_zones&zoneid=' . $zoneId);

        } else {
            \XLite\Core\TopMessage::addError(sprintf('Zone not found (%d)', $zoneId));
        }
    }

    /**
     * Do action 'Create'
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionCreate()
    {
        $postedData = \XLite\Core\Request::getInstance()->getData();

        $data = $this->getElementsData($postedData);

        if (!empty($data[\XLite\Model\ZoneElement::ZONE_ELEMENT_COUNTRY])) {

            $zoneName = trim($postedData['zone_name']);

            if (!empty($zoneName)) {
                // Create new zone with specified name
                $zone = new \XLite\Model\Zone();
                $zone->setZoneName($zoneName);
                \XLite\Core\Database::getEM()->persist($zone);
                \XLite\Core\Database::getEM()->flush();

                // Get zone_id of just created zone
                $zoneId = $zone->getZoneId();

                if (isset($zoneId)) {

                    // Insert new elements from POST
                    $zone = $this->addElements($zone, $data);

                    \XLite\Core\Database::getEM()->persist($zone);
                    \XLite\Core\Database::getEM()->flush();

                    \XLite\Core\Database::getRepo('XLite\Model\Zone')->cleanCache($zoneId);

                    \XLite\Core\TopMessage::addInfo('New zone has been created successfully');

                    $this->redirect('admin.php?target=shipping_zones&zoneid=' . $zoneId);

                } else {
                    \XLite\Core\TopMessage::addError('New zone was not created due to internal error');
                }

            } else {
                \XLite\Core\TopMessage::addError(
                    'Could not create zone with empty name. Please specify it.'
                );
            }

        } else {
            \XLite\Core\TopMessage::addError(
                'The countries list for zone is empty. Please specify it.'
            );
        }

        $this->redirect('admin.php?target=shipping_zones&mode=add');
    }

    /**
     * Get zone elements passed from post request
     *
     * @param array $postedData Array of data posted via post request
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getElementsData($postedData)
    {
        $data = array();

        $data[\XLite\Model\ZoneElement::ZONE_ELEMENT_COUNTRY] = !empty($postedData['zone_countries_store'])
            ? array_filter(explode(';', $postedData['zone_countries_store']))
            : array();

        $data[\XLite\Model\ZoneElement::ZONE_ELEMENT_STATE] = !empty($postedData['zone_states_store'])
            ? array_filter(explode(';', $postedData['zone_states_store']))
            : array();

        $data[\XLite\Model\ZoneElement::ZONE_ELEMENT_TOWN] = !empty($postedData['zone_cities'])
            ? array_filter(explode("\n", $postedData['zone_cities']))
            : array();

        $data[\XLite\Model\ZoneElement::ZONE_ELEMENT_ZIPCODE] = !empty($postedData['zone_zipcodes'])
            ? array_filter(explode("\n", $postedData['zone_zipcodes']))
            : array();

        $data[\XLite\Model\ZoneElement::ZONE_ELEMENT_ADDRESS] = !empty($postedData['zone_addresses'])
            ? array_filter(explode("\n", $postedData['zone_addresses']))
            : array();

        foreach ($data[\XLite\Model\ZoneElement::ZONE_ELEMENT_STATE] as $value) {

            $codes = explode('_', $value);

            if (!in_array($codes[0], $data[\XLite\Model\ZoneElement::ZONE_ELEMENT_COUNTRY])) {
                $data[\XLite\Model\ZoneElement::ZONE_ELEMENT_COUNTRY][] = $codes[0];
            }
        }

        return $data;
    }
}

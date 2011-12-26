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

namespace XLite\View\Tabs;

/**
 * Tabs related to shipping settings
 *
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @ListChild (list="admin.center", zone="admin")
 */
class ShippingSettings extends \XLite\View\Tabs\ATabs
{

    /**
     * Description of tabs related to shipping settings and their targets
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $tabs = array(
        'shipping_settings' => array(
            'title' => 'Shipping settings',
            'template' => 'shipping/settings.tpl',
        ),
        'shipping_methods' => array(
            'title' => 'Methods',
            'template' => 'shipping/methods.tpl',
        ),
        'shipping_zones' => array(
            'title' => 'Zones',
            'template' => 'shipping/zones/main.tpl',
            'jsFiles' => 'zone_edit.js'
        ),
        'shipping_rates' => array(
            'title' => 'Rates',
            'template' => 'shipping/charges.tpl',
        ),
    );

    /**
     * Zones
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $zones = null;

    /**
     * Markups
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $markups = null;

    /**
     * Returns a list of shipping processors
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getShippingProcessors()
    {
        return \XLite\Model\Shipping::getInstance()->getProcessors();
    }

    /**
     * Returns a list of shipping methods
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getShippingMethods()
    {
        return \XLite\Model\Shipping::getInstance()->getShippingMethods();
    }

    /**
     * Check if zone details page should be displayed
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isDisplayZoneDetails()
    {
        return 'add' == \XLite\Core\Request::getInstance()->mode
            || isset(\XLite\Core\Request::getInstance()->zoneid);
    }

    /**
     * getZone
     *
     * @return \XLite\Model\Zone
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getZone()
    {
        if (isset(\XLite\Core\Request::getInstance()->zoneid)) {
            $zone = \XLite\Core\Database::getRepo('XLite\Model\Zone')
                ->findZone(\XLite\Core\Request::getInstance()->zoneid);

            if (!isset($zone)) {
                \XLite\Core\TopMessage::addError(
                    'Requested zone does not exists'
                );
            }

        } else {
            $zone = new \XLite\Model\Zone();
        }

        return $zone;
    }

    /**
     * getShippingZones
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getShippingZones()
    {
        if (!isset($this->zones)) {
            $this->zones = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findAllZones();
        }

        return $this->zones;
    }

    /**
     * isZonesDefined
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isZonesDefined()
    {
        return (count($this->getShippingZones()) > 2);
    }

    /**
     * hasShippingMarkups
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function hasShippingMarkups()
    {
        return count($this->getShippingMarkups()) > 0;
    }

    /**
     * getShippingMarkups
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getShippingMarkups()
    {
        $postedData = \XLite\Core\Request::getInstance()->getData();

        // Initialize zoneId and methodId
        $zoneId = $methodId = null;

        // Get zoneId from the request data
        if (isset($postedData['zoneid']) && strlen($postedData['zoneid']) > 0) {
            $zoneId = intval($postedData['zoneid']);
        }

        // Get methodId from the request data
        if (isset($postedData['methodid']) && strlen($postedData['methodid']) > 0) {
            $methodId = intval($postedData['methodid']);
        }

        // Generate key for markups storage
        $key = md5(sprintf('%d-%d', isset($zoneId) ? $zoneId : -1, isset($methodId) ? $methodId : -1));

        // Check if markups for pair zone/method are already calculated
        if (!isset($this->markups[$key])) {

            // Get markups
            $markups = \XLite\Core\Database::getRepo('XLite\Model\Shipping\Markup')
                ->findMarkupsByZoneAndMethod($zoneId, $methodId);

            $this->markups[$key] = $markups;
        }

        return $this->markups[$key];
    }

    /**
     * getPreparedShippingMarkups
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getPreparedShippingMarkups()
    {
        return $this->prepareMarkups($this->getShippingMarkups());
    }

    /**
     * Service method for usage in the markups list template
     * Returns true if current markup number is lesser than count of markups of current method
     *
     * @param integer $id    Current index of markup
     * @param array   $array Array of markups
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isShowMarkupsSeparator($id, array $array)
    {
        return (count($array) - 1 > $id);
    }


    /**
     * Prepares markups array for displaying on admin page. Result array has the following format:
     *
     * array (
     *    0 => array (
     *       'zone'    => \XLite\Model\Zone,
     *       'methods' => array (
     *          0 => array (
     *             'method'  => \XLite\Model\Shipping\Method,
     *             'markups' => array (
     *                0 => \XLite\Model\Shipping\Markup,
     *                1 => ...
     *             )
     *          ),
     *          1 => ...
     *       ),
     *    1 => ...
     *    )
     * )
     *
     * @param mixed $markups ____param_comment____
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareMarkups($markups)
    {
        $result = array();

        $zones = $this->getShippingZones();

        $methods = array();

        foreach ($markups as $markup) {
            if (!isset($methods[$markup->getShippingMethod()->getMethodId()])) {
                $methods[$markup->getShippingMethod()->getMethodId()] = $markup->getShippingMethod();
            }
        }

        foreach ($zones as $zone) {

            $resultZone = array(
                'zone'    => $zone,
                'methods' => array()
            );

            foreach ($methods as $method) {

                $resultMethod = array(
                    'method'  => $method,
                    'markups' => array()
                );

                foreach ($markups as $markup) {

                    if (
                        $markup->getZone()->getZoneId() == $zone->getZoneId()
                        && $markup->getShippingMethod()->getMethodId() == $method->getMethodId()
                    ) {
                        $resultMethod['markups'][] = $markup;
                    }
                }

                if (!empty($resultMethod['markups'])) {
                    $resultZone['methods'][] = $resultMethod;
                }
            }

            if (!empty($resultZone['methods'])) {
                $result[] = $resultZone;
            }
        }

        return $result;
    }

    /**
     * Get processor methods 
     * 
     * @param \XLite\Model\Shipping\Processor\AProcessor $processor Processor
     *  
     * @return \XLite\Model\Shipping\Method
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getProcessorMethods(\XLite\Model\Shipping\Processor\AProcessor $processor)
    {
        $list = array();
        foreach ($processor->getShippingMethods() as $i => $method) {
            $method->setEditLanguageCode(\XLite::getController()->getCurrentLanguage());
            $list[$i] = $method;
        }

        return $list;
    }
}

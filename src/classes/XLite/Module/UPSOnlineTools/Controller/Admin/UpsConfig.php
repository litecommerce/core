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

namespace XLite\Module\UPSOnlineTools\Controller\Admin;

/**
 * Configuration controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class UpsConfig extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Options (cache)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $options = null;

    /**
     * UPS shipping method
     * 
     * @var    \XLite\Module\UPSOnLineTools\Model\Shipping\Ups
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $ups = null;

    /**
     * Update settings
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionUpdate() 
    {
        $settings = $this->get('settings');

        if (!isset($settings['upsoptions'])) {
            $settings['upsoptions'] = array();
        }

        // Normalize setting values
        $settings['cache_autoclean'] = intval(abs($settings['cache_autoclean']));

        $fields = array('width', 'height', 'length');
        foreach ($fields as $key) {
            if (isset($settings[$key])) {
                $settings[$key] = max(\XLite\Module\UPSOnlineTools\Model\PackItem::MIN_DIM_SIZE, $settings[$key]);
            }
        }

        if (is_array($settings)) {

            $cc = $this->config->Company->location_country;
            $settings['dim_units'] = in_array($cc, array('CA','DO','PR','US'))
                ? 'inches'
                : 'cm';

            foreach ($settings as $name => $value) {

                $optionType = null;

                if ($name == 'upsoptions' && is_array($value)) {

                    $res = null;

                    foreach ($value as $val) {
                        $res = array($val => 'Y');
                    }

                    $value = serialize($res);
                    $optionType = 'serialized';
                }

                \XLite\Core\Database::getRepo('\XLite\Model\Config')->createOption(
                    array(
                        'category' => 'UPSOnlineTools',
                        'name'     => $name,
                        'value'    => $value,
                        'type'     => $optionType
                    )
                );
            }
        }

        // Clear UPSOnlineTools cache
        $ups = new \XLite\Module\UPSOnLineTools\Model\Shipping\Ups();
        $ups->_cleanCache('ups_online_tools_cache');
    }

    /**
     * Do test request
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionTest() 
    {
        $this->ups = new \XLite\Module\UPSOnLineTools\Model\Shipping\Ups();

        $ptype = $this->config->UPSOnlineTools->packing_algorithm;
        $total_weight = $this->get('pounds');
        $ups_containers = array();
        $container = new \XLite\Module\UPSOnlineTools\Model\Container();

        $packaging_type = 2;

        switch ($ptype) {
            case \XLite\Model\Order::BINPACKING_SIMPLE_MAX_SIZE:    // Max size
                $container->setDimensions(10, 10, 10);
                $container->setWeightLimit(0);

                break;

            case \XLite\Model\Order::BINPACKING_NORMAL_ALGORITHM:    // pack all items in one package
            case \XLite\Model\Order::BINPACKING_OVERSIZE_ALGORITHM:    // pack items in similar containers
                $packaging_type = $this->config->UPSOnlineTools->packaging_type;
                $packData = $this->ups->getUPSContainerDims($packaging_type);
                $container->setDimensions($packData['width'], $packData['length'], $packData['height']);
                $container->setWeightLimit($packData['weight_limit']);

                break;

            default:

                // fixed-size container
                $container->setDimensions(
                    $this->config->UPSOnlineTools->width,
                    $this->config->UPSOnlineTools->length,
                    $this->config->UPSOnlineTools->height
                );
                $container->setWeightLimit(0);
                break;

        }

        $container->setContainerType($packaging_type); // Package type
        $container->setWeight($total_weight);
        $ups_containers[] = $container;

        // Get company state
        $state_id = $this->config->Company->location_state;
        if ($state_id != -1) {
            $state = \XLite\Core\Database::getEM()->find('\XLite\Model\State', $state_id);
            $originState = $state ? $state->code : '';
            unset($state);

        } else {
            $originState = $this->config->Company->location_custom_state;
        }

        // Get destination state
        $state_id = \XLite\Core\Request::getInstance()->destination_state;
        if ($state_id != -1) {
            $state = \XLite\Core\Database::getEM()->find('\XLite\Model\State', $state_id);
            $destinationState = $state ? $state->code : '';
            unset($state);

        } else {
            $destinationState = \XLite\Core\Request::getInstance()->destination_custom_state;
        }

        $this->rates = $this->ups->getRatesByQuery(
            \XLite\Core\Request::getInstance()->pounds,
            $this->config->Company->location_address,
            $originState,
            $this->config->Company->location_city,
            $this->config->Company->location_zipcode,
            $this->config->Company->location_country,
            \XLite\Core\Request::getInstance()->destinationAddress,
            $destinationState,
            \XLite\Core\Request::getInstance()->destinationCity,
            \XLite\Core\Request::getInstance()->destinationZipCode,
            \XLite\Core\Request::getInstance()->destination_country,
            $this->ups->getOptions(),
            $ups_containers
        );

        $this->testResult = true;
        $this->valid = false;
    }

    /**
     * Get options list
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getOptions()
    {
        if (!$this->options) {
            $this->options = $this->config->get('UPSOnlineTools');
        }

        return $this->options;
    }

    function getPackingTypeList()
    {
        $ups = new \XLite\Module\UPSOnLineTools\Model\Shipping\Ups();
        return $ups->getUPSContainersList();
    }

    function getWeightUnit()
    {
        return in_array($this->config->Company->location_country, array('DO', 'PR', 'US'))
            ? 'lbs'
            : 'kg';
    }
 
    function isGDlibEnabled()
    {
        return \XLite\Module\UPSOnlineTools\Main::isGDLibValid();
    }

    function isUseDGlibDisplay()
    {
        return $this->isGDlibEnabled() && $this->config->UPSOnlineTools->display_gdlib;
    }

}

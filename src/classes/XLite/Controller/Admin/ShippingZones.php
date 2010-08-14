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
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ShippingZones extends \XLite\Controller\Admin\AAdmin
{
    public function getShippingZones()
    {
        $zone = new \XLite\Model\Zone();
        return $zone->getShippingZones();
    }

    function getPageTemplate()
    {
        return "shipping/zones.tpl";
    }

    function action_update_states()
    {
        if (\XLite\Core\Request::getInstance()->target_state_zone == 'new') {
            // create new zone
            $zone = new \XLite\Model\ShippingZone();
            $zone->create();
        } else {
            // move to specified zone
            $zone = new \XLite\Model\ShippingZone(\XLite\Core\Request::getInstance()->target_state_zone);
        }
        // move selected states
        if (isset(\XLite\Core\Request::getInstance()->states)) {
            $zone->set('states', \XLite\Core\Request::getInstance()->states);
        }
    }

    function action_update_countries()
    {
        if (\XLite\Core\Request::getInstance()->target_country_zone == 'new') {
            // create new zone
            $zone = new \XLite\Model\ShippingZone();
            $zone->create();
        } else {
            // move to specified zone
            $zone = new \XLite\Model\ShippingZone(\XLite\Core\Request::getInstance()->target_country_zone);
        }
        // move selected countries
        if (isset(\XLite\Core\Request::getInstance()->countries)) {
            $zone->set('countries', \XLite\Core\Request::getInstance()->countries);
        }
    }
}

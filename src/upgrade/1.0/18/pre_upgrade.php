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
 * @since     1.0.17
 */

return function()
{
    $config = array(
        'insert' => array(
        ),
        'update' => array(
            'General' => array(
                'shop_closed' => array('Check this to temporary close the shop', 'Check this to close the shop temporarily'),
                'login_lifetime' => array('Days to store last login data', 'Number of days to store the last login data'),
                'recent_orders' => array('Amount of orders in the recent orders list', 'The number of orders in the recent order list'),
            ),
            'Company' => array(
                'location_custom_state' => array('Other state (specify)', 'Another state (specify)'),
            ),
            'Shipping' => array(
                'anonymous_custom_state' => array('Other state (specify)', 'Another state (specify)'),
            ),
        ),
        'delete' => array(
            'General' => array(
                'add_on_mode',
                'add_on_mode_page',
                'direct_product_url',
            ),
        ),
    );

    $objects = array();

    foreach ($config as $method => $tmp) {
        foreach ($tmp as $category => $data) {
            foreach ($data as $name => $value) {
                $object = \XLite\Core\Database::getRepo('\XLite\Model\Config')->findBy(array('name' => $name, 'category' => $category));

                if (isset($object)) {
                    switch ($method) {
                        case 'update':
                            list($oldLabel, $newLabel) = $value;

                            if ($object->getOptionName() === $oldLabel) {
                                $object->setOptionName($newValue);
                            }

                            break;
    
                        case 'delete':
                            // ...
                            break;

                        default:
                            // ...
                    }

                } elseif ('insert' === $method) {
                    $object = new \XLite\Model\Config();
                    $object->setCategory($category);
                    $object->setName($name);

                    // Add other actions (if needed)
                    // list($value, $label, $orderby) = $value;
                }

                if (isset($object)) {
                    $objects[$method] = $object;
                }
            }
        }
    }

    foreach ($objects as $method => $config) {
        \XLite\Core\Database::getRepo('\XLite\Model\Config')->{$method . 'InBatch'}($config);
    }
};

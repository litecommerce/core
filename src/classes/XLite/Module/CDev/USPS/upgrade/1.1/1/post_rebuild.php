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

return function()
{
    // Apply config changes
    $repo = \XLite\Core\Database::getRepo('XLite\Model\Config');

    $server_name = $repo->findOneBy(array('category' => 'CDev\\USPS', 'name' => 'server_name'));
    $server_path = $repo->findOneBy(array('category' => 'CDev\\USPS', 'name' => 'server_path'));

    if (!empty($server_name) && !empty($server_path)) {
        $server_url = 'http://' . $server_name . '/' . $server_path;

    } else {
        $server_url = '';
    }

    $repo->createOption(
        array(
            'category' => 'CDev\\USPS',
            'name'     => 'server_url',
            'value'    => $server_url,
        )
    );

    \XLite\Core\Database::getEM()->remove($server_name);
    \XLite\Core\Database::getEM()->remove($server_path);

    \XLite\Core\Database::getEM()->flush();
    \XLite\Core\Database::getCacheDriver()->deleteAll();
};

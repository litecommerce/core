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
    // Load data from yaml file
    $yamlFile = __DIR__ . LC_DS . 'post_rebuild.yaml';

    if (\Includes\Utils\FileManager::isFileReadable($yamlFile)) {
        \XLite\Core\Database::getInstance()->loadFixturesFromYaml($yamlFile);
    }

    // Update weight of order modifier
    $orderModifier = \XLite\Core\Database::getRepo('XLite\Model\Order\Modifier')
        ->findOneBy(array('class' => '\\XLite\\Module\\CDev\\VAT\\Logic\\Order\\Modifier\\Tax'));

    $orderModifierData = array(
        'class'  => '\\XLite\\Module\\CDev\\VAT\\Logic\\Order\\Modifier\\Tax',
        'weight' => 1000,
    );

    if (!$orderModifier) {
        $orderModifier = new XLite\Model\Order\Modifier();
    }

    $orderModifier->map($orderModifierData);

    \XLite\Core\Database::getEM()->persist($orderModifier);

    \XLite\Core\Database::getEM()->flush();
};

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
 */

return function()
{
    // Get disabled modules
    $classes = array();
    $cnd = new \XLite\Core\CommonCell;
    $cnd->inactive = true;
    foreach (\XLite\Core\Database::getRepo('XLite\Model\Module')->search($cnd) as $module) {
        $classes[] = $module->getActualName();
    }

    // Enable/disable  all payment methods by modules
    foreach (\XLite\Core\Database::getRepo('XLite\Model\Payment\Method')->findAll() as $method) {
        $parts = explode('\\', $method->getClass());
        $class = implode('\\', array_slice($parts, 1, 2));
        $method->setModuleEnabled(!in_array($class, $classes));
        if (!$method->getAdded() && $method->getType() == \XLite\Model\Payment\Method::TYPE_OFFLINE) {
            $method->setAdded(true);
        }
    }
    \XLite\Core\Database::getEM()->flush();

    // Loading data to the database from yaml file
    $yamlFile = __DIR__ . LC_DS . 'post_rebuild.yaml';

    if (\Includes\Utils\FileManager::isFileReadable($yamlFile)) {
        \XLite\Core\Database::getInstance()->loadFixturesFromYaml($yamlFile);
    }

};

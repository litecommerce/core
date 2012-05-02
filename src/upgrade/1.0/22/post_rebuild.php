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
 * @since     1.0.20
 */

return function()
{
    
    // Loading data to the database from yaml file
    $yamlFile = __DIR__ . LC_DS . 'post_rebuild.yaml';

    if (\Includes\Utils\FileManager::isFileReadable($yamlFile)) {
        \XLite\Core\Database::getInstance()->loadFixturesFromYaml($yamlFile);
    }

    // Loading currencies
    $yamlFile = __DIR__ . LC_DS . 'currencies.yaml';

    if (\Includes\Utils\FileManager::isFileReadable($yamlFile)) {
        \XLite\Core\Database::getInstance()->loadFixturesFromYaml($yamlFile);

        // Remove obsolete currencies
        foreach (\XLite\Core\Database::getRepo('XLite\Model\Currency')->findBy(array('prefix' => '', 'suffix' => '')) as $currency) {
            \XLite\Core\Database::getEM()->remove($currency);
        }
        \XLite\Core\Database::getEM()->flush();
    }

    // Loading countries
    $yamlFile = __DIR__ . LC_DS . 'countries.yaml';

    if (\Includes\Utils\FileManager::isFileReadable($yamlFile)) {
        \XLite\Core\Database::getInstance()->loadFixturesFromYaml($yamlFile);

        // Remove obsolete currencies
        $qb = \XLite\Core\Database::getRepo('XLite\Model\Country')->createQueryBuilder('c')->andWhere('c.id IS NULL');
        foreach ($qb->getResult() as $country) {
            \XLite\Core\Database::getEM()->remove($country);
        }
        \XLite\Core\Database::getEM()->flush();
    }

};

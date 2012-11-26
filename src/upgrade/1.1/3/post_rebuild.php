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
    // Loading data to the database from yaml file
    $yamlFile = __DIR__ . LC_DS . 'post_rebuild.yaml';

    if (\Includes\Utils\FileManager::isFileReadable($yamlFile)) {
        \XLite\Core\Database::getInstance()->loadFixturesFromYaml($yamlFile);
    }

    // Import profile addresses from the temporary YAML file storage
    $yamlProfileStorageFile = LC_DIR_VAR . 'temporary.storage.profiles.yaml';

    foreach (\Includes\Utils\Operator::loadServiceYAML($yamlProfileStorageFile) as $address) {

        $entity = \XLite\Core\Database::getRepo('XLite\Model\Address')
            ->findOneBy(array(
                'address_id' => $address['address_id'],
                'profile'    => $address['profile_id'],
                )
            );

        $entity->setProfile(\XLite\Core\Database::getRepo('XLite\Model\Profile')->find($address['profile_id']));
        $entity->setCountry(\XLite\Core\Database::getRepo('XLite\Model\Country')->findOneByCode($address['country_code']));
        $entity->setState(\XLite\Core\Database::getRepo('XLite\Model\State')->find($address['state_id']));

        unset($address['profile_id'], $address['state_id'], $address['country_code']);
        $entity->map($address);

        $entity->update();
        \XLite\Core\Database::getEM()->flush($entity);
    }

    \Includes\Utils\FileManager::deleteFile($yamlProfileStorageFile);

};

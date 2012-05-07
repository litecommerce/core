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
 * @since     1.0.22
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
        $data = \Symfony\Component\Yaml\Yaml::load($yamlFile);

        // Import new and update old currencies
        $repo = \XLite\Core\Database::getRepo('XLite\Model\Currency');
        foreach ($data['XLite\Model\Currency'] as $cell) {
            $currency = $repo->findOneBy(array('code' => $cell['code']));

            $prev = null;

            if (!$currency) {
                $prev = $repo->find($cell['currency_id']);
                $currency = new \XLite\Model\Currency;
                $currency->setCurrencyId($cell['currency_id']);
                \XLite\Core\Database::getEM()->persist($currency);

            } elseif ($cell['currency_id'] != $currency->getCurrencyId()) {
                $prev = $repo->find($cell['currency_id']);
            }

            if ($prev) {
                \XLite\Core\Database::getEM()->remove($prev);
            }

            $currency->map(
                array(
                    'code'        => $cell['code'],
                    'symbol'      => $cell['symbol'],
                    'prefix'      => isset($cell['prefix']) ? $cell['prefix'] : '',
                    'suffix'      => isset($cell['suffix']) ? $cell['suffix'] : '',
                )
            );

            foreach ($cell['translations'] as $t) {
                $currency->getTranslation($t['code'])->setName($t['name']);
            }
        }
        \XLite\Core\Database::getEM()->flush();

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

        // Remove obsolete countries
        $qb = \XLite\Core\Database::getRepo('XLite\Model\Country')->createQueryBuilder('c')->andWhere('c.id IS NULL');
        foreach ($qb->getResult() as $country) {
            \XLite\Core\Database::getEM()->remove($country);
        }
        \XLite\Core\Database::getEM()->flush();
    }

    // Update admin roles
    $permission = \XLite\Core\Database::getRepo('XLite\Model\Role\Permission')
        ->findOneBy(array('code' => \XLite\Model\Role\Permission::ROOT_ACCESS));
    $role = $permission->getRoles()->first();
    if ($role) {
        $admins = \XLite\Core\Database::getRepo('XLite\Model\Profile')
            ->findBy(array('access_level' => \XLite\Core\Auth::getInstance()->getAdminAccessLevel()));
        foreach ($admins as $admin) {
            if (!$admin->getRoles()->contains($role)) {
                $role->addProfiles($admin);
                $admin->addRoles($role);
            }
        }
        \XLite\Core\Database::getEM()->flush();
    }

    // Remove unused config options
    $yamlFile = __DIR__ . LC_DS . 'obsolete_config_options.yaml';

    if (\Includes\Utils\FileManager::isFileReadable($yamlFile)) {
        \XLite\Core\Database::getInstance()->unloadFixturesFromYaml($yamlFile);
    }
};

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

    // Loading currencies and countries
    $yamlFile = __DIR__ . LC_DS . 'countries.yaml';

    if (\Includes\Utils\FileManager::isFileReadable($yamlFile)) {
        $data = \Symfony\Component\Yaml\Yaml::parse($path);

        // Import new and update old currencies
        $repo = \XLite\Core\Database::getRepo('XLite\Model\Currency');
        foreach ($data['currencies'] as $cell) {
            $currency = $repo->find($cell['currency_id']);

            if (!$currency) {
                $currency = new \XLite\Model\Currency;
                $currency->setCurrencyId($cell['currency_id']);
                \XLite\Core\Database::getEM()->persist($currency);
            }

            $currency->map(
                array(
                    'code'   => $cell['code'],
                    'symbol' => $cell['symbol'],
                    'prefix' => isset($cell['prefix']) ? $cell['prefix'] : '',
                    'suffix' => isset($cell['suffix']) ? $cell['suffix'] : '',
                )
            );

            foreach ($cell['translations'] as $t) {
                $currency->getTranslation($t['code'])->setName($t['name']);
            }
        }
        \XLite\Core\Database::getEM()->flush();

        // Remove obsolete currencies
        foreach ($repo->findBy(array('prefix' => '', 'suffix' => '')) as $currency) {
            \XLite\Core\Database::getEM()->remove($currency);
        }
        \XLite\Core\Database::getEM()->flush();

        // Import new and update old countries
        $repo = \XLite\Core\Database::getRepo('XLite\Model\Country');
        foreach ($data['currencies'] as $cell) {
            $country = $repo->findOneBy(array('code' => $cell['code']));

            if (!$country) {
                $country = new \XLite\Model\Country;
                $country->setCode($cell['code']);
                \XLite\Core\Database::getEM()->persist($country);
            }

            $country->map(
                array(
                    'code3'   => isset($cell['code3']) ? $cell['code3'] : '',
                    'id'      => $cell['id'],
                    'country' => $cell['country'],
                )
            );

            if (isset($cell['currency']) && !empty($cell['currency']['currency_id'])) {
                $currency = \XLite\Core\Database::getRepo('XLite\Model\Currency')->find($cell['currency']['currency_id']);
                if ($currency) {
                    $country->setCurrency($currency);
                    $currency->addCountries($country);
                }
            }
        }
        \XLite\Core\Database::getEM()->flush();

        // Remove obsolete currencies
        $qb = $repo->createQueryBuilder('c')->andWhere('c.id IS NULL');
        foreach ($qb->getResult() as $country) {
            \XLite\Core\Database::getEM()->remove($country);
        }
        \XLite\Core\Database::getEM()->flush();
    }

};

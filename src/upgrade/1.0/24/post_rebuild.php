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

    // Load missing currency's translations
    $yamlFile = __DIR__ . LC_DS . 'currencies.yaml';

    if (\Includes\Utils\FileManager::isFileReadable($yamlFile)) {
        $data = \Symfony\Component\Yaml\Yaml::load($yamlFile);

        // Import new and update old currencies
        $repo = \XLite\Core\Database::getRepo('XLite\Model\Currency');
        foreach ($data['XLite\Model\Currency'] as $cell) {
            $currency = $repo->findOneBy(array('code' => $cell['code']));
            if ($currency) {
                foreach ($cell['translations'] as $t) {
                    $translation = $currency->getTranslation($t['code']);
                    $translation->setName($t['name']);
                    if (!$translation->getLabelId()) {
                        \XLite\Core\Database::getEM()->persist($translation);
                    }
                }
            }
        }
        \XLite\Core\Database::getEM()->flush();
    }

    // Change shop_currency from obsolete code to USD
    $currency = \XLite\Core\Database::getRepo('XLite\Model\Currency')->find(\XLite\Core\Config::getInstance()->General->shop_currency);
    if (!$currency) {
        \XLite\Core\Database::getRepo('XLite\Model\Config')->createOption(
            array(
                'category' => 'General',
                'name'     => 'shop_currency',
                'value'    => 840,
            )
        );
    }
};

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
    $prefix = \XLite\Core\Database::getInstance()->getTablePrefix();

    // Create tables
    $query = file_get_contents(__DIR__ . LC_DS . 'dump.sql');
    $query = preg_replace('/%%PREFIX%%/', $prefix, $query);

    $tmpFileName = sprintf('dump-%d.sql', time());

    file_put_contents($tmpFileName, $query);

    \Includes\Utils\Database::uploadSQLFromFile($tmpFileName, true);

    \Includes\Utils\FileManager::deleteFile($tmpFileName);

    // Get the default language code
    $code = \XLite::getInstance()->getDefaultLanguage() ?: 'en';

    // Copy multilingual data of pages
    $pages = \XLite\Core\Database::getRepo('XLite\Module\CDev\SimpleCMS\Model\Page')->findAll();

    $queries = array();

    $tableName = $prefix . 'page_translations';

    foreach ($pages as $page) {
        $id           = $page->getId();
        $name         = addslashes($page->getName());
        $teaser       = addslashes($page->getTeaser());
        $body         = addslashes($page->getBody());
        $metaKeywords = addslashes($page->getMetaKeywords());

        $queries[] =<<<OUT
INSERT INTO $tableName
(id, name, teaser, body, metaKeywords, code)
VALUES ($id, '$name', '$teaser', '$body', '$metaKeywords', '$code');
OUT;
    }

    \XLite\Core\Database::getInstance()->executeQueries($queries);


    // Copy multilingual data of menus
    $menus = \XLite\Core\Database::getRepo('XLite\Module\CDev\SimpleCMS\Model\Menu')->findAll();

    $queries = array();

    $tableName = $prefix . 'menu_translations';

    foreach ($menus as $menu) {
        $id   = $menu->getId();
        $name = addslashes($menu->getName());

        $queries[] =<<<OUT
INSERT INTO $tableName
(id, name, code)
VALUES ($id, '$name', '$code');
OUT;
    }

    \XLite\Core\Database::getInstance()->executeQueries($queries);
};

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
 * @since     1.0.11
 */

return function()
{
    // Update tables structure to avoid categories and products images loss
    $prefix = \XLite\Core\Database::getInstance()->getTablePrefix();

    $queries = array(
        "ALTER TABLE `{$prefix}category_images` DROP FOREIGN KEY `{$prefix}category_images_ibfk_1`;",
        "ALTER TABLE `{$prefix}category_images` CHANGE `id` `category_id` int(10) unsigned DEFAULT NULL;",
        "ALTER TABLE `{$prefix}category_images` CHANGE `image_id` `id` int(10) unsigned NOT NULL AUTO_INCREMENT;",
        "ALTER TABLE `{$prefix}product_images` DROP FOREIGN KEY `{$prefix}product_images_ibfk_1`;",
        "ALTER TABLE `{$prefix}product_images` CHANGE `id` `product_id` int(10) unsigned DEFAULT NULL;",
        "ALTER TABLE `{$prefix}product_images` CHANGE `image_id` `id` int(10) unsigned NOT NULL AUTO_INCREMENT;",
    );

    foreach ($queries as $query) {
        \Includes\Utils\Database::execute($query);
    }
};

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
    // OrderItem model has been changed
    $prefix = \XLite\Core\Database::getInstance()->getTablePrefix();

    $queries = array(
        "ALTER TABLE `{$prefix}order_items` ADD COLUMN `itemNetPrice` decimal(14,4) NOT NULL;",
        "ALTER TABLE `{$prefix}order_items` ADD COLUMN `discountedSubtotal` decimal(14,4) NOT NULL;",
        "UPDATE `{$prefix}order_items` SET `itemNetPrice`=`netPrice`, `discountedSubtotal`=`netPrice`*`amount`;",
        "ALTER TABLE `{$prefix}order_items` DROP COLUMN `netPrice`;",
    );

    foreach ($queries as $query) {
        \Includes\Utils\Database::execute($query);
    }

    // 'No fractional part' item has been removed from 'Currency decimal separator' option
    if ('' == \XLite\Core\Config::getInstance()->General->decimal_delim) {
        \XLite\Core\Database::getRepo('\XLite\Model\Config')->createOption(
            array(
                'category' => 'General',
                'name'     => 'decimal_delim',
                'value'    => '.'
            )
        );
    }
};

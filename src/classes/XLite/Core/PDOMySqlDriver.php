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
 * @since     1.0.0
 */

namespace XLite\Core;

/**
 * Doctrine-based PDO MySQL driver
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class PDOMySqlDriver extends \Doctrine\DBAL\Driver\PDOMySql\Driver
{
    /**
     * Get schema manager
     *
     * @param \Doctrine\DBAL\Connection $conn Connection
     *
     * @return \Doctrine\DBAL\Schema\MySqlSchemaManager
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getSchemaManager(\Doctrine\DBAL\Connection $conn)
    {
        return new \XLite\Core\MySqlSchemaManager($conn);
    }

    /**
     * Get database platform
     *
     * @return \Doctrine\DBAL\Platforms\AbstractPlatform
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getDatabasePlatform()
    {
        return new \XLite\Core\MySqlPlatform();
    }
}

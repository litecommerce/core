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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Module\CDev\UserPermissions;

/**
 * User permissions module main class
 *
 */
abstract class Main extends \XLite\Module\AModule
{
    /**
     * Author name
     *
     * @return string
     */
    public static function getAuthorName()
    {
        return 'X-Cart team';
    }

    /**
     * Get module major version
     *
     * @return string
     */
    public static function getMajorVersion()
    {
        return '1.1';
    }

    /**
     * Module version
     *
     * @return string
     */
    public static function getMinorVersion()
    {
        return '0';
    }

    /**
     * Module name
     *
     * @return string
     */
    public static function getModuleName()
    {
        return 'User permissions';
    }

    /**
     * Module description
     *
     * @return string
     */
    public static function getDescription()
    {
        return 'Allows you to restrict access to backend functions to only those employees who need them. You can define administrator roles and configure which groups of back-end functions are available to users having these roles.';
    }

    /**
     * Decorator run this method at the end of cache rebuild
     *
     * @return void
     */
    public static function runBuildCacheHandler()
    {
        parent::runBuildCacheHandler();

        $enabledRole = \XLite\Core\Database::getRepo('XLite\Model\Role')->findOneBy(array('enabled' => true));
        if (!$enabledRole) {
            $permanent = \XLite\Core\Database::getRepo('XLite\Model\Role')->getPermanentRole();
            if (!$permanent) {
                $permanent = \XLite\Core\Database::getRepo('XLite\Model\Role')->findFrame(0, 1);
                $permanent = 0 < count($permanent) ? array_shift($permanent) : null;
            }

            if ($permanent) {
                $permanent->setEnabled(true);
                \XLite\Core\Database::getEM()->flush();
            }
        }
    }

}

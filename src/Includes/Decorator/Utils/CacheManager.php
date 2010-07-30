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
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Includes_Decorator_Utils
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace Includes\Decorator\Utils;

/**
 * CacheManager 
 * 
 * @package    XLite
 * @see        ____class_see____
 * @since      3.0.0
 */
class CacheManager extends \Includes\Decorator\Utils\AUtils
{
    /**
     * List of cache directories 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $cacheDirs = array(
        LC_CLASSES_CACHE_DIR,
        LC_SKINS_CACHE_DIR,
        LC_LOCALE_DIR,
    );


    /**
     * Query to select the "developer_mode" param mode from config 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getDevmodeQuery()
    {
        return 'SELECT value FROM xlite_config WHERE category = \'General\' AND name = \'developer_mode\'';
    }

    /**
     * Check if so called "devloper mode" is enabled
     * 
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function isDeveloperMode()
    {
        return ('Y' === \Includes\Utils\Database::fetchColumn(self::getDevmodeQuery())) && empty($_REQUEST['action']);
    }

    /**
     * Check if classes cache directory exists
     *
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function isCacheDirExists()
    {
        return \Includes\Utils\FileManager::isFileReadable(LC_CLASSES_CACHE_DIR . \Includes\Decorator::LC_CACHE_BUILD_INDICATOR);
    }

    /**
     * Check if we need to rebuild classes cache
     * 
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function isRebuildNeeded()
    {
        return !defined('LC_DO_NOT_REBUILD_CACHE') && (!static::isCacheDirExists() || static::isDeveloperMode());
    }

    /**
     * Check id the Doctrine proxy classes are already generated 
     * 
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function isDoctrineProxiesExist()
    {
        return \Includes\Utils\FileManager::isDirReadable(LC_PROXY_CACHE_DIR);
    }

    /**
     * Build LC classes cache.
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function buildLCCache()
    {
        // Delete cache folders
        static::cleanupCache();
        // Create classes tree
        \Includes\Decorator::getInstance()->buildCache();
        // Perform redirect (needed for two-step cache generation)
        \Includes\Utils\Operator::redirect($_SERVER['REQUEST_URI']);
    }

    /**
     * Build the Doctrine proxy classes 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function buildDoctrineProxies()
    {
        // Create the proxies folder
        \Includes\Utils\FileManager::mkdirRecursive(LC_PROXY_CACHE_DIR);
        // Create model proxy classes (second step of cache generation)
        \Includes\Decorator\Utils\Doctrine\EntityManager::generateProxyClasses();
    }


    /**
     * Rebuild classes cache 
     * 
     * @param bool $force flag to force cache rebuild
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function rebuildCache($force = false)
    {
        if (static::isRebuildNeeded() || $force) {
            static::buildLCCache();
        }

        if (!static::isDoctrineProxiesExist()) {
            static::buildDoctrineProxies();
        }
    }

    /**
     * Clean up the cache 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function cleanupCache()
    {
        array_walk(static::$cacheDirs, array('\Includes\Utils\FileManager', 'unlinkRecursive'));
    }
}

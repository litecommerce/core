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
     * Text to display while working with cache 
     */

    const MESSAGE = 'Re-building cache, please wait...';

    /**
     * Time limit to build cache 
     */

    const TIME_LIMIT = 180;

    /**
     * Cache building steps
     */

    const STEP_FIRST  = 'first';
    const STEP_SECOND = 'second';


    /**
     * List of cache directories 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $cacheDirs = array(
        LC_COMPILE_DIR,
        LC_LOCALE_DIR,
        LC_DATACACHE_DIR,
        LC_TMP_DIR,
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
        return 'Y' === \Includes\Utils\Database::fetchColumn(self::getDevmodeQuery());
    }

    /**
     * Get plain text notice block
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getPlainMessage()
    {
        return self::MESSAGE . "\n";
    }

    /**
     * getHTMLMessageContent 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getHTMLMessageContent()
    {
        return '<table><tr><td><img src="'
            . \Includes\Utils\URLManager::getShopURL('skins/progress_indicator.gif')
            . '" alt="" /></td><td>' . self::MESSAGE . '</td></tr></table>';
    }

    /**
     * Get HTML notice block
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getHTMLMessage()
    {
        return '<script type="text/javascript">document.write(\''
            . static::getHTMLMessageContent() . '\');</script>' . "\n"
            . '<html>' . "\n" . '<body>' . "\n"
            . '<noscript>' . static::getHTMLMessageContent() . '</noscript>' . "\n";
    }

    /**
     * Text to display while working with cache
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function showMessage()
    {
        \Includes\Utils\Operator::flush(
            ('cli' == PHP_SAPI) ? static::getPlainMessage() : static::getHTMLMessage()
        );
    }

    /**
     * Return name of the file, which indicates the cache state
     *
     * @param string $step current step name
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getCacheStateIndicatorfileName($step)
    {
        return LC_COMPILE_DIR . '.cacheGenerated.' . $step . '.step';
    }

    /**
     * Check if cache rebuild is needed
     * 
     * @param string $step current step name
     *  
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function isRebuildNeeded($step)
    {
        return !\Includes\Utils\FileManager::isExists(static::getCacheStateIndicatorfileName($step));
    }

    /**
     * Set the cache validity indicator 
     *
     * @param string $step current step name
     *
     * @return null
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function complete($step)
    {
        \Includes\Utils\FileManager::write(static::getCacheStateIndicatorfileName($step), date('r'));
    }

    /**
     * Remove cache validity indicator
     *
     * @param string $step current step name
     *
     * @return null
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function clear($step)
    {
        if (\Includes\Utils\FileManager::isExists($file = static::getCacheStateIndicatorfileName($step))) {
            \Includes\Utils\FileManager::delete($file);
        }
    }

    /**
     * Perform some actions on current cache generation step
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function buildCacheStepFirst()
    {
        // Show the "Please wait" message
        static::showMessage();

        // Delete cache folders
        static::cleanupCache();

        // Main procedure: instantiate and run Decorator here
        \Includes\Utils\Operator::executeWithCustomMaxExecTime(
            self::TIME_LIMIT,
            array(new \Includes\Decorator(), 'buildCache')
        );
    }

    /**
     * Perform some actions on current cache generation step
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function buildCacheStepSecond()
    {
        // Run registered plugins
        \Includes\Decorator\Utils\PluginManager::invokeHook('postprocess');
    }


    /**
     * Rebuild classes cache 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function rebuildCache()
    {
        // Two steps of cache building
        foreach (array(self::STEP_FIRST, self::STEP_SECOND) as $step) {

            // Check if a step is passed
            if (static::isRebuildNeeded($step)) {

                // Perform step-specific actions
                call_user_func(array('static', 'buildCacheStep' . ucfirst($step)));

                // Perform some actions on complete
                static::complete($step);

                // Perform redirect (needed for two-step cache generation)
                \Includes\Utils\Operator::refresh();
            }
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
        // Remove cache validity indicators
        foreach (array(self::STEP_FIRST, self::STEP_SECOND) as $step) {
            static::clear($step);
        }

        // Remove all cache directories
        array_walk(static::$cacheDirs, array('\Includes\Utils\FileManager', 'unlinkRecursive'));
    }
}

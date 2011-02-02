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
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    GIT: $Id$
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
abstract class CacheManager extends \Includes\Decorator\Utils\AUtils
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
     * Available hooks
     */

    const HOOK_INIT        = 'init';
    const HOOK_PREPROCESS  = 'preprocess';
    const HOOK_RUN         = 'run';
    const HOOK_POSTPROCESS = 'postprocess';


    /**
     * List of cache building steps 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $steps = array(self::STEP_FIRST, self::STEP_SECOND);

    /**
     * List of cache directories 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $cacheDirs = array(LC_COMPILE_DIR, LC_LOCALE_DIR, LC_DATACACHE_DIR, LC_TMP_DIR);


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
     * @return null
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function showMessage()
    {
        \Includes\Utils\Operator::flush(LC_IS_CLI_MODE ? static::getPlainMessage() : static::getHTMLMessage());
    }

    /**
     * Remove cache validity indicator
     *
     * @param string $step current step name
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function clear($step)
    {
        !($file = static::getCacheStateIndicatorFileName($step)) ?: \Includes\Utils\FileManager::delete($file);
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
    protected static function getCacheStateIndicatorFileName($step)
    {
        return LC_COMPILE_DIR . '.cacheGenerated.' . $step . '.step';
    }

    /**
     * Return name of the file, which indicates if the build process started
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getRebuildIndicatorFileName()
    {
        return LC_VAR_DIR . '.rebuildStarted';
    }

    /**
     * Data to write into the "step completed" file indicator
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getCacheStateIndicatorFileContent()
    {
        return date('r');
    }

    /**
     * Data to write into the "step started" file indicator
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getRebuildIndicatorFileContent()
    {
        return getmypid();
    }

    /**
     * Check if cache rebuild process is already started
     *
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function checkIfRebuildStarted()
    {
        if (static::checkRebuildIndicatorState()) {
            \Includes\ErrorHandler::fireError('Cache rebuild is already started, please wait');
        }
    }

    /**
     * Step started
     *
     * @param string $step current step
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function startStep($step)
    {
        // Put the indicator file
        \Includes\Utils\FileManager::write(
            static::getRebuildIndicatorFileName(),
            static::getRebuildIndicatorFileContent()
        );

        static::showMessage();
    }

    /**
     * Step completed
     *
     * @param string $step current step
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function completeStep($step)
    {
        // "Step completed" indicator
        \Includes\Utils\FileManager::write(
            static::getCacheStateIndicatorFileName($step),
            static::getCacheStateIndicatorFileContent()
        );

        // Remove the "rebuilding cache" indicator file
        static::checkRebuildIndicatorState();

        // Perform redirect (needed for two-step cache generation)
        \Includes\Utils\Operator::refresh();
    }

    /**
     * Run a step callback
     *
     * @param string $step Step name
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getStepCallback($step)
    {
        return array(get_called_class(), 'executeStepHandler' . ucfirst($step));
    }

    /**
     * Run a step
     * 
     * @param string $step Step name
     *  
     * @return null
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function runStep($step)
    {
        // To prevent multiple processes execution
        static::checkIfRebuildStarted();

        // Write indicator files and show the message
        static::startStep($step);

        // Perform step-specific actions
        \Includes\Utils\Operator::executeWithCustomMaxExecTime(self::TIME_LIMIT, static::getStepCallback($step));

        // (Un)Set indicator files and redirect
        static::completeStep($step);
    }

    /**
     * Run a step
     *
     * @param string $step Step name
     *
     * @return null
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function runStepConditionally($step)
    {
        !static::isRebuildNeeded($step) ?: static::runStep($step);
    }

    /**
     * Return list of cache state indicator files
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getCacheStateFiles()
    {
        return array_map(array('static', 'getCacheStateIndicatorFileName'), static::$steps);
    }

    /**
     * Clean up the cache
     *
     * @return null
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function cleanupCache()
    {
        array_walk(static::$cacheDirs, array('\Includes\Utils\FileManager', 'unlinkRecursive'));
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
        return !\Includes\Utils\FileManager::isExists(static::getCacheStateIndicatorFileName($step));
    }

    /**
     * Run handler for the current step
     *
     * NOTE: method is public since it's called from
     * \Includes\Utils\Operator::executeWithCustomMaxExecTime()
     * 
     * @return null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function executeStepHandlerFirst()
    {
        // Invoke plugins
        \Includes\Decorator\Utils\PluginManager::invokeHook(self::HOOK_INIT);

        // Delete cache folders
        static::cleanupCache();

        // Invoke plugins
        \Includes\Decorator\Utils\PluginManager::invokeHook(self::HOOK_PREPROCESS);

        // Main procedure: instantiate and run Decorator here
        \Includes\Decorator\Utils\Operator::prepareClassesTree();

        // Write class files to FS
        \Includes\Decorator\Utils\Operator::writeClassFiles();

        // Invoke plugins
        \Includes\Decorator\Utils\PluginManager::invokeHook(self::HOOK_RUN);
    }

    /**
     * Run handler for the current step
     *
     * NOTE: method is public since it's called from
     * \Includes\Utils\Operator::executeWithCustomMaxExecTime()
     *
     * @return null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function executeStepHandlerSecond()
    {
        // Invoke plugins
        \Includes\Decorator\Utils\PluginManager::invokeHook(self::HOOK_POSTPROCESS);
    }

    /**
     * Clean up the cache rebuild indicator
     *
     * @return null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function cleanupRebuildIndicator()
    {
        \Includes\Utils\FileManager::delete(static::getRebuildIndicatorFileName());
    }

    /**
     * Clean up the cache validity indicators
     * 
     * @return null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function cleanupCacheIndicators()
    {
        // "Step is completed" indicators
        array_map(array('\Includes\Utils\FileManager', 'delete'), static::getCacheStateFiles());

        // "Step is running" indicator
        static::cleanupRebuildIndicator();
    }

    /**
     * Check and (if needed) remove the rebuild indicator file
     *
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function checkRebuildIndicatorState()
    {
        $name    = static::getRebuildIndicatorFileName();
        $content = \Includes\Utils\FileManager::read($name);

        // Only the process created the file can delete
        static::getRebuildIndicatorFileContent() != $content ?: \Includes\Utils\FileManager::delete($name);

        return (bool) $content;
    }

    /**
     * Main public method: rebuild classes cache
     *
     * @return null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function rebuildCache()
    {
        foreach (static::$steps as $step) {
            static::runStepConditionally($step);
        }
    }
}

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

namespace Includes\Decorator\Utils;

/**
 * CacheManager
 *
 */
abstract class CacheManager extends \Includes\Decorator\Utils\AUtils
{
    /**
     * Available hooks
     */
    const HOOK_BEFORE_CLEANUP  = 'before_cleanup';
    const HOOK_BEFORE_DECORATE = 'before_decorate';
    const HOOK_BEFORE_WRITE    = 'before_write';
    const HOOK_STEP_FIRST      = 'step_first';
    const HOOK_STEP_SECOND     = 'step_second';
    const HOOK_STEP_THIRD      = 'step_third';
    const HOOK_STEP_FOURTH     = 'step_fourth';
    const HOOK_STEP_FIFTH      = 'step_fifth';

    /**
     * List of cache building steps
     *
     * @var array
     */
    protected static $steps = array(
        self::STEP_FIRST,
        self::STEP_SECOND,
        self::STEP_THIRD,
        self::STEP_FOURTH,
        self::STEP_FIFTH,
    );

    /**
     * List of cache directories
     *
     * @var array
     */
    protected static $cacheDirs = array(
        LC_DIR_COMPILE,
        LC_DIR_LOCALE,
        LC_DIR_DATACACHE,
        LC_DIR_TMP,
        LC_DIR_CACHE_RESOURCES,
    );

    /**
     * Timestamp of the step start
     *
     * @var integer
     */
    protected static $stepStart;

    /**
     * Memory usage
     *
     * @var integer
     */
    protected static $stepMemory;


    // {{{ Dispaly message routines

    /**
     * showStepMessage
     *
     * @param string  $text       Message text
     * @param boolean $addNewline Flag OPTIONAL
     *
     * @return void
     */
    public static function showStepMessage($text, $addNewline = false)
    {
        static::$stepStart  = microtime(true);
        static::$stepMemory = memory_get_usage();

        \Includes\Utils\Operator::showMessage($text, $addNewline);
    }

    /**
     * showStepInfo
     *
     * @return void
     */
    public static function showStepInfo()
    {
        $text = number_format(microtime(true) - static::$stepStart, 2) . 'sec, ';

        $memory = memory_get_usage();
        $text .= \Includes\Utils\Converter::formatFileSize($memory, '');
        $text .= ' (' . \Includes\Utils\Converter::formatFileSize(memory_get_usage() - static::$stepMemory, '') . ')';

        \Includes\Utils\Operator::showMessage(' [' . $text . ']');
    }

    /**
     * Get decorator message
     *
     * @return string
     */
    protected static function getMessage()
    {
        return 'Re-building cache [step ' . static::$step . ' of ' . static::LAST_STEP . '], please wait...';
    }

    /**
     * Get plain text notice block
     *
     * @return string
     */
    protected static function getPlainMessage()
    {
        return "\n" . static::getMessage() . "\n";
    }

    /**
     * getHTMLMessage
     *
     * @return string
     */
    protected static function getHTMLMessage()
    {
        return '<table><tr><td><img src="'
            . \Includes\Utils\URLManager::getShopURL('skins/progress_indicator.gif')
            . '" alt="" /></td><td>' . static::getMessage() . '</td></tr></table>';
    }

    /**
     * displayCompleteMessage
     *
     * @return void
     */
    protected static function displayCompleteMessage()
    {
        echo '<div id="finish">Cache is built successfully</div>';
    }

    // }}}

    // {{{ Cache state indicator routines

    /**
     * Clean up the cache rebuild indicator
     *
     * @return void
     */
    public static function cleanupRebuildIndicator()
    {
        \Includes\Utils\FileManager::deleteFile(static::getRebuildIndicatorFileName());
    }

    /**
     * Clean up the cache validity indicators
     *
     * @return void
     */
    public static function cleanupCacheIndicators()
    {
        foreach (static::getCacheStateFiles() as $file) {
            if (\Includes\Utils\FileManager::isFile($file) && !\Includes\Utils\FileManager::deleteFile($file)) {
                \Includes\ErrorHandler::fireError(
                    'Unable to delete "' . $file . '" file. Please correct the permissions'
                );
            }
        }

        // "Step is running" indicator
        static::cleanupRebuildIndicator();
    }

    /**
     * Check and (if needed) remove the rebuild indicator file
     *
     * @return boolean
     */
    public static function checkRebuildIndicatorState()
    {
        $name    = static::getRebuildIndicatorFileName();
        $content = \Includes\Utils\FileManager::read($name);

        // Only the process created the file can delete
        // :NOTE: do not change the operator to the "==="
        if (static::getRebuildIndicatorFileContent() == $content) {
            \Includes\Utils\FileManager::deleteFile($name);
        }

        return (bool) $content;
    }

    /**
     * Remove cache validity indicator
     *
     * @param string $step Current step name
     *
     * @return void
     */
    protected static function clear($step)
    {
        $file = static::getCacheStateIndicatorFileName($step);

        if ($file) {
            \Includes\Utils\FileManager::deleteFile($file);
        }
    }

    /**
     * Return name of the file, which indicates the cache state
     *
     * @param string $step Current step name
     *
     * @return string
     */
    protected static function getCacheStateIndicatorFileName($step)
    {
        return LC_DIR_COMPILE . '.cacheGenerated.' . $step . '.step';
    }

    /**
     * Return name of the file, which indicates if the build process started
     *
     * @return string
     */
    protected static function getRebuildIndicatorFileName()
    {
        return LC_DIR_VAR . '.rebuildStarted';
    }

    /**
     * Data to write into the "step completed" file indicator
     *
     * @return string
     */
    protected static function getCacheStateIndicatorFileContent()
    {
        return date('r');
    }

    /**
     * Data to write into the "step started" file indicator
     *
     * @return string
     */
    protected static function getRebuildIndicatorFileContent()
    {
        return getmypid();
    }

    /**
     * Check if cache rebuild process is already started
     *
     * @return boolean
     */
    protected static function checkIfRebuildStarted()
    {
        if (static::checkRebuildIndicatorState()) {
            \Includes\ErrorHandler::fireError('Cache rebuild is already started, please wait');
        }
    }

    /**
     * Return list of cache state indicator files
     *
     * @return array
     */
    protected static function getCacheStateFiles()
    {
        return array_map(array('static', 'getCacheStateIndicatorFileName'), static::$steps);
    }

    // }}}

    // {{{ Common routines to run step handlers

    /**
     * Step started
     *
     * @param string $step Current step
     *
     * @return void
     */
    protected static function startStep($step)
    {
        static::$step = $step;

        // Put the indicator file
        \Includes\Utils\FileManager::write(
            static::getRebuildIndicatorFileName(),
            static::getRebuildIndicatorFileContent()
        );

        static::showStepMessage(LC_IS_CLI_MODE ? static::getPlainMessage() : static::getHTMLMessage(), true);
    }

    /**
     * Check if current step is last and redirect is prohibited after that step
     *
     * @param integer $step Current step
     *
     * @return boolean
     */
    protected static function isSkipRedirectAfterLastStep($step)
    {
        return static::LAST_STEP === $step && isset($_GET['doNotRedirectAfterCacheIsBuilt']);
    }

    /**
     * Check if only one step must be performed
     *
     * @return boolean
     */
    protected static function isDoOneStepOnly()
    {
        return defined('DO_ONE_STEP_ONLY');
    }

    /**
     * Step completed
     *
     * @param string $step Current step
     *
     * @return void
     */
    protected static function completeStep($step)
    {
        // "Step completed" indicator
        \Includes\Utils\FileManager::write(
            static::getCacheStateIndicatorFileName($step),
            static::getCacheStateIndicatorFileContent()
        );

        // Write classes cache
        if ($root = static::getClassesTree(false)) {
            \Includes\Utils\FileManager::write(
                static::getClassesHashPath(),
                serialize(array_merge($root->findAll(), array($root)))
            );
        }

        // Remove the "rebuilding cache" indicator file
        static::checkRebuildIndicatorState();

        if (static::isSkipRedirectAfterLastStep($step)) {
            // Do not redirect after last step
            // (this mode is used when cache builder was launched from LC standalone installation script)
            static::displayCompleteMessage();
            exit ();

        } elseif (!static::isDoOneStepOnly()) {
            // Perform redirect (needed for multi-step cache generation)
            \Includes\Utils\Operator::refresh();
        }
    }

    /**
     * Run a step callback
     *
     * @param string $step Step name
     *
     * @return array
     */
    protected static function getStepCallback($step)
    {
        return array(get_called_class(), 'executeStepHandler' . strval($step));
    }

    /**
     * Run a step
     *
     * @param string $step Step name
     *
     * @return void
     */
    protected static function runStep($step)
    {
        // Set internal flag
        if (!defined('XLITE_CACHE_BUILDING')) {
            define('XLITE_CACHE_BUILDING', true);
        }

        // To prevent multiple processes execution
        static::checkIfRebuildStarted();

        // Write indicator files and show the message
        static::startStep($step);

        // Enable output (if needed)
        static::setFastCGITimeoutEcho();

        // Perform step-specific actions
        \Includes\Utils\Operator::executeWithCustomMaxExecTime(
            \Includes\Utils\ConfigParser::getOptions(array('decorator', 'time_limit')),
            static::getStepCallback($step)
        );

        // (Un)Set indicator files and redirect
        static::completeStep($step);
    }

    /**
     * Run a step and return true if step is actually was performed or false if step has already been performed before
     *
     * @param string $step Step name
     *
     * @return void
     */
    protected static function runStepConditionally($step)
    {
        $result = false;

        if (static::isRebuildNeeded($step)) {
            static::runStep($step);
            $result = true;
        }

        return $result;
    }

    // }}}

    // {{{ Step handlers

    /**
     * Run handler for the current step
     *
     * :NOTE: method is public since it's called from
     * \Includes\Utils\Operator::executeWithCustomMaxExecTime()
     *
     * @return void
     */
    public static function executeStepHandler1()
    {
        // Invoke plugins
        \Includes\Decorator\Utils\PluginManager::invokeHook(static::HOOK_BEFORE_CLEANUP);

        // Delete cache folders
        static::showStepMessage('Cleaning up the cache...');
        static::cleanupCache();
        static::showStepInfo();

        // Load classes from "classes" (do not use cache)
        \Includes\Autoloader::switchLcAutoloadDir();

        // Main procedure: build decorator chains
        static::showStepMessage('Building classes tree...');
        static::getClassesTree();
        static::showStepInfo();

        // Invoke plugins
        \Includes\Decorator\Utils\PluginManager::invokeHook(static::HOOK_STEP_FIRST);
    }

    /**
     * Run handler for the current step
     *
     * NOTE: method is public since it's called from
     * \Includes\Utils\Operator::executeWithCustomMaxExecTime()
     *
     * @return void
     */
    public static function executeStepHandler2()
    {
        // Invoke plugins
        \Includes\Decorator\Utils\PluginManager::invokeHook(static::HOOK_BEFORE_DECORATE);

        // Main procedure: build decorator chains
        static::showStepMessage('Decorate classes...');
        static::getClassesTree()->walkThrough(array('\Includes\Decorator\Utils\Operator', 'decorateClass'));
        static::showStepInfo();

        // Invoke plugins
        \Includes\Decorator\Utils\PluginManager::invokeHook(static::HOOK_BEFORE_WRITE);

        // Write class files to FS
        static::showStepMessage('Writing class files to the cache...');
        static::getClassesTree()->walkThrough(array('\Includes\Decorator\Utils\Operator', 'writeClassFile'), true);
        static::showStepInfo();

        // Invoke plugins
        \Includes\Decorator\Utils\PluginManager::invokeHook(static::HOOK_STEP_SECOND);
    }

    /**
     * Run handler for the current step
     *
     * NOTE: method is public since it's called from
     * \Includes\Utils\Operator::executeWithCustomMaxExecTime()
     *
     * @return void
     */
    public static function executeStepHandler3()
    {
        // Invoke plugins
        \Includes\Decorator\Utils\PluginManager::invokeHook(static::HOOK_STEP_THIRD);
    }

    /**
     * Run handler for the current step
     *
     * NOTE: method is public since it's called from
     * \Includes\Utils\Operator::executeWithCustomMaxExecTime()
     *
     * @return void
     */
    public static function executeStepHandler4()
    {
        // Invoke plugins
        \Includes\Decorator\Utils\PluginManager::invokeHook(static::HOOK_STEP_FOURTH);
    }

    /**
     * Run handler for the current step
     *
     * NOTE: method is public since it's called from
     * \Includes\Utils\Operator::executeWithCustomMaxExecTime()
     *
     * @return void
     */
    public static function executeStepHandler5()
    {
        // Invoke plugins
        \Includes\Decorator\Utils\PluginManager::invokeHook(static::HOOK_STEP_FIFTH);
    }

    // }}}

    // {{{ Check permissions

    /**
     * Check directory permissions and try to correct them
     *
     * @param string $dir Path to check
     *
     * @return void
     */
    protected static function checkPermissions($dir)
    {
        \Includes\Utils\FileManager::mkdirRecursive($dir);

        if (!\Includes\Utils\FileManager::isDirWriteable($dir)) {
            @\Includes\Utils\FileManager::chmod($dir, static::getDirDefaultPermissions($dir));

            if (!\Includes\Utils\FileManager::isDirWriteable($dir)) {
                static::fireDirPermissionsError($dir);
            }
        }
    }

    /**
     * Fire the error them unable to set directory permissions
     *
     * @param string $dir Path to check
     *
     * @return void
     */
    protected static function fireDirPermissionsError($dir)
    {
        \Includes\ErrorHandler::fireError(static::getDirPermissionsErrorMessage($dir));
    }

    /**
     * Return permissions error message
     *
     * @param string $dir Path to check
     *
     * @return string
     */
    protected static function getDirPermissionsErrorMessage($dir)
    {
        return 'The "' . $dir . '" directory is not writeable. Please correct the permissions';
    }

    /**
     * Return default directory permissions
     *
     * @param string $dir Path to check
     *
     * @return integer
     */
    protected static function getDirDefaultPermissions($dir)
    {
        return 0755;
    }

    // }}}

    // {{{ Top-level methods

    /**
     * Main public method: rebuild classes cache
     *
     * @return void
     */
    public static function rebuildCache()
    {
        static::checkPermissions(LC_DIR_VAR);

        $stepStatus = false;

        foreach (static::$steps as $step) {

            $stepStatus = static::runStepConditionally($step);

            if ($stepStatus && static::isDoOneStepOnly()) {
                // Break after first performed step if isDoOneStepOnly() returned true
                break;
            }
        }

        if (!$stepStatus) {
            // Clear classes cache
            \Includes\Utils\FileManager::deleteFile(static::getClassesHashPath());
        }
    }

    /**
     * Return current step identifier
     *
     * @return string
     */
    public static function getCurrentStep()
    {
        return static::$step;
    }

    /**
     * Check if cache rebuild is needed
     *
     * @param string $step Current step name OPTIONAL
     *
     * @return boolean
     */
    public static function isRebuildNeeded($step = null)
    {
        if (!isset($step)) {
            $step = static::getCurrentStep();
        }

        return $step ? !\Includes\Utils\FileManager::isExists(static::getCacheStateIndicatorFileName($step)) : false;
    }

    /**
     * Clean up the cache
     *
     * @return void
     */
    public static function cleanupCache()
    {
        foreach (static::$cacheDirs as $dir) {
            \Includes\Utils\FileManager::unlinkRecursive($dir);
            static::checkPermissions($dir);
        }
    }

    // }}}

    // {{{ Fix for the FastCGI timeout (http://bt.litecommerce.com/view.php?id=41139)

    /**
     * Set output per tick(s)
     *
     * @return void
     */
    protected static function setFastCGITimeoutEcho()
    {
        if (\Includes\Utils\ConfigParser::getOptions(array('decorator', 'use_output'))) {
            declare(ticks = 10000);

            register_tick_function(array('\Includes\Utils\Operator', 'showMessage'), '.', false);
        }
    }

    // }}}
}

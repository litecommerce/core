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

namespace XLite\Upgrade;

/**
 * Logger 
 * 
 */
class Logger extends \XLite\Base\Singleton
{
    /**
     * Clear log file
     * 
     * @return boolean
     */
    public function clear()
    {
        return \Includes\Utils\FileManager::deleteFile($this->getLogFile());
    }

    /**
     * Return log file name
     * 
     * @return string
     */
    public function getLogFile()
    {
        return LC_DIR_LOG . 'upgrade.log';
    }

    /**
     * Return link to view the log file
     *
     * @return string
     */
    public function getLogURL()
    {
        return 'admin.php?target=upgrade&action=view_log_file';
    }

    /**
     * Add message to the log
     *
     * @param string  $message        Message text
     * @param array   $args           Arguments to substitute OPTIONAL
     * @param boolean $showTopMessage Flag OPTIONAL
     *
     * @return void
     */
    public function logInfo($message, array $args = array(), $showTopMessage = false)
    {
        $this->log($message, $args, $showTopMessage, \XLite\Core\TopMessage::INFO);
    }

    /**
     * Add message to the log
     *
     * @param string  $message        Message text
     * @param array   $args           Arguments to substitute OPTIONAL
     * @param boolean $showTopMessage Flag OPTIONAL
     *
     * @return void
     */
    public function logWarning($message, array $args = array(), $showTopMessage = false)
    {
        $this->log($message, $args, $showTopMessage, \XLite\Core\TopMessage::WARNING);
    }

    /**
     * Add message to the log
     *
     * @param string  $message        Message text
     * @param array   $args           Arguments to substitute OPTIONAL
     * @param boolean $showTopMessage Flag OPTIONAL
     *
     * @return void
     */
    public function logError($message, array $args = array(), $showTopMessage = false)
    {
        $this->log($message, $args, $showTopMessage, \XLite\Core\TopMessage::ERROR);
    }

    /**
     * Add message to the log
     * 
     * @param string  $message        Message text
     * @param array   $args           Arguments to substitute OPTIONAL
     * @param boolean $showTopMessage Flag OPTIONAL
     * @param string  $topMessageType \XLite\Core\TopMessage class constant OPTIONAL
     *  
     * @return void
     */
    protected function log($message, array $args = array(), $showTopMessage = false, $topMessageType = null)
    {
        // Write to file
        $this->write($this->getPrefix($topMessageType) . static::t($message, $args));

        // Show to admin
        if ($showTopMessage) {
            \XLite\Core\TopMessage::getInstance()->add($this->getTopMessage($message), $args, null, $topMessageType);
        }
    }

    /**
     * Write message to the file
     * 
     * @param string $message Message text
     *  
     * @return void
     */
    protected function write($message)
    {
        \Includes\Utils\FileManager::write($this->getLogFile(), $message . PHP_EOL, FILE_APPEND);
    }

    /**
     * Get message prefix
     * 
     * @param string $type Prefix type
     *  
     * @return string
     */
    protected function getPrefix($type)
    {
        return '[' . $type . ', ' . date('M d Y H:i:s') . '] ';
    }

    /**
     * Prepare message to display (not log)
     * 
     * @param string $message Message text
     *  
     * @return string
     */
    protected function getTopMessage($message)
    {
        return $message . '<p /><a target="_blank" href=' . $this->getLogURL() . '><u>See log file for details</u></a>';
    }
}

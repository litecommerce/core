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
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Controller\Console;

/**
 * Abstarct console-zone controller 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class AConsole extends \XLite\Controller\AController
{
    /**
     * Action time 
     * 
     * @var    float
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $actionTime;

    /**
     * Pure output flag
     * 
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $pureOutput = false;

    /**
     * Handles the request.
     * Parses the request variables if necessary. Attempts to call the specified action function 
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function handleRequest()
    {
        if ($this->checkAccess() && \XLite\Core\Request::getInstance()->help) {
            print $this->getHelp();

        } else {
            $this->actionTime = microtime(true);
            parent::handleRequest();

            if (!$this->pureOutput) {
                $duration = microtime(true) - $this->actionTime;
                $micro = $duration - floor($duration);

                $this->printContent(
                    PHP_EOL . 'Execution time: '
                    . date('H:i:s', floor($duration) - intval(date('Z')))
                    . '.' . sprintf('%04d', $micro * 10000) . ' sec.'
                    . PHP_EOL
                );
            }
        }
    }

    /**
     * isRedirectNeeded
     *
     * @return boolean 
     * @access public
     * @since  3.0.0
     */
    public function isRedirectNeeded()
    {
        return false;
    }

    /**
     * Check if current page is accessible
     *
     * @return boolean 
     * @access public
     * @since  3.0.0
     */
    public function checkAccess()
    {
        return $this->checkCLIKey();
    }

    /**
     * Check CLI key 
     * 
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkCLIKey()
    {
        $cliKey = \XLite\Core\Config::getInstance()->Security->cli_key;

        return !$cliKey || \XLite\Core\Request::getInstance()->key == $cliKey;
    }

    /**
     * Return Viewer object
     * 
     * @return \XLite\View\Controller
     * @access public
     * @since  3.0.0
     */
    public function getViewer()
    {
        return new \XLite\View\Console(array(), $this->getViewerTemplate());
    }

    /**
     * Get allowed actions 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getAllowedActions()
    {
        $r = new \ReflectionCLass(get_called_class());

        $actions = array();

        foreach ($r->getMethods() as $method) {
            if (preg_match('/^doAction(.+)$/Ss', $method->getName(), $m)) {
                $actions[] = lcfirst($m[1]);
            }
        }

        return $actions;
    }

    /**
     * Get help 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getHelp()
    {
        $help = null;

        $action = \XLite\Core\Request::getInstance()->action;
        if ($action) {
            $method = 'getHelp' . \XLite\Core\Converter::convertToCamelCase($action);
            $help = method_exists($this, $method)
                ? $this->$method()
                : 'Action \'' . $action . '\' has not help note';

        } else {
            $help = $this->getControllerHelp();
        }

        return $help;
    }

    /**
     * Get controller help 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getControllerHelp()
    {
        return 'Allowed actions: ' . PHP_EOL
            . implode(PHP_EOL, $this->getAllowedActions());
    }

    /**
     * Print content 
     * 
     * @param string $str Content
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function printContent($str)
    {
        print $str;
    }

    /**
     * Print error 
     * 
     * @param string $error Error message
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function printError($error)
    {
        $this->printContent('[ERROR] ' . $error . PHP_EOL);

        if (!defined('CLI_RESULT_CODE')) {
            define('CLI_RESULT_CODE', 1);
        }
    }

    /**
     * Perform redirect 
     * 
     * @param string $url Redirect URL OPTIONAL
     *  
     * @return void
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected function redirect($url = null)
    {
    }

   /**
     * Mark controller run thread as access denied
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function markAsAccessDenied()
    {
        $this->printError('Access denied');
    }

    /**
     * Check - script run with input stream or not
     * 
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isInputStream()
    {
        $result = false;

        $stdin = @fopen('php://stdin', 'r');
        if ($stdin) {
            $stat = fstat($stdin);
            $result = 0 < $stat['size'];
            fclose($stdin);
        }

        return $result;
    }

    /**
     * Open input stream 
     * 
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function openInputStream()
    {
        if (!isset($this->stdin)) {
            $this->stdin = @fopen($path, 'r');
            if (!$this->stdin) {
                $this->stdin = null;
            }
        }

        return isset($this->stdin);
    }

    /**
     * Read line form input stream 
     * 
     * @return string|boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function readInputStream()
    {
        $this->openInputStream(); 

        if ($this->openInputStream() && feof($this->stdin)) {
            fclose($this->stdin);
            $this->stdin = false;
        }

        return $this->stdin ? fgets($this->stdin) : false;
    }

    /**
     * Save input stream to temporary file
     * 
     * @return string|void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function saveInputStream()
    {
        $dir = function_exists('sys_get_temp_dir')
            ? sys_get_temp_dir() . LC_DS
            : LC_VAR_DIR;

        $path = tempnam($dir, 'input');
        file_put_contents($path, file_get_contents('php://stdin'));

        return $path;
    }
}

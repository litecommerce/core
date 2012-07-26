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

namespace XLite\Controller\Console;

/**
 * Abstarct console-zone controller
 *
 */
abstract class AConsole extends \XLite\Controller\AController
{
    /**
     * Action time
     *
     * @var float
     */
    protected $actionTime;

    /**
     * Pure output flag
     *
     * @var boolean
     */
    protected $pureOutput = false;

    /**
     * Handles the request.
     * Parses the request variables if necessary. Attempts to call the specified action function
     *
     * @return void
     */
    public function handleRequest()
    {
        if ($this->checkAccess() && \XLite\Core\Request::getInstance()->help) {
            $this->printContent($this->getHelp());

        } else {

            set_time_limit(0);

            $this->actionTime = microtime(true);
            \XLite\Core\Session::getInstance();
            parent::handleRequest();

            if (!$this->pureOutput) {
                $duration = microtime(true) - $this->actionTime;
                $micro = $duration - floor($duration);

                $this->printContent(
                    PHP_EOL . 'Execution time: '
                    . gmdate('H:i:s', floor($duration))
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
     */
    public function isRedirectNeeded()
    {
        return false;
    }

    /**
     * Check if current page is accessible
     *
     * @return boolean
     */
    public function checkAccess()
    {
        return $this->checkCLIKey();
    }

    /**
     * Return Viewer object
     *
     * @return \XLite\View\Controller
     */
    public function getViewer()
    {
        return new \XLite\View\Console(array(), $this->getViewerTemplate());
    }

    /**
     * Get allowed actions
     *
     * @return array
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
     * Check CLI key
     *
     * @return boolean
     */
    protected function checkCLIKey()
    {
        $cliKey = \XLite\Core\Config::getInstance()->Security->cli_key;

        return !$cliKey || \XLite\Core\Request::getInstance()->key == $cliKey;
    }

    /**
     * Get help
     *
     * @return string
     */
    protected function getHelp()
    {
        $help = null;

        $action = \XLite\Core\Request::getInstance()->action;
        if ($action) {
            $method = 'getHelp' . \XLite\Core\Converter::convertToCamelCase($action);
            $help = method_exists($this, $method)
                // Call an action-specific method
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
     */
    protected function printContent($str)
    {
        if (\XLite\Core\Request::getInstance()->isCLI()) {
            print ($str);
        }
    }

    /**
     * Print error
     *
     * @param string $error Error message
     *
     * @return void
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
     */
    protected function redirect($url = null)
    {
    }

    /**
     * Mark controller run thread as access denied
     *
     * @return void
     */
    protected function markAsAccessDenied()
    {
        $this->printError('Access denied');
    }

    /**
     * Check - script run with input stream or not
     *
     * @return boolean
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
     */
    protected function saveInputStream()
    {
        $path = tempnam(sys_get_temp_dir() . LC_DS, 'input');
        file_put_contents($path, file_get_contents('php://stdin'));

        return $path;
    }
}

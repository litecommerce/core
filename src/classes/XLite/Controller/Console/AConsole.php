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
            parent::handleRequest();
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
        return parent::checkAccess() && $this->checkCLIKey();
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

        return $cliKey && \XLite\Core\Request::getInstance()->key == $cliKey;
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
}

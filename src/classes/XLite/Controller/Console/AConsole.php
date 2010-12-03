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
     * Operation error 
     * 
     * @var    mixed
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $operationError;

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
     * Get operation error 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getOperationError()
    {
        return $this->operationError;
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
        if ($this->operationError) {
            \XLite\Core\Request::getInstance()->target = 'error';
            \XLite\Logger::getInstance()->log('Console error: ' . $this->operationError, LOG_ERR);

            if (!defined('CLI_RESULT_CODE')) {
                define('CLI_RESULT_CODE', 1);
            }
        }

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
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getHelp()
    {
        $method = null;

        $action = \XLite\Core\Request::getInstance()->action;
        if ($action) {
            $method = 'getHelp' . \XLite\Core\Converter::convertToCamelCase($action);
        }

        if (!$method || !method_exists($this, $method)) {
            $method = 'getControllerHelp';
        }

        return method_exists($this, $method) ? $this->$method() : null;
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
}

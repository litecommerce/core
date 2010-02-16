<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Application singleton
 *  
 * @category   Lite Commerce
 * @package    Lite Commerce
 * @subpackage XLite
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @version    SVN: $Id$
 * @link       http://www.qtmsoft.com/
 * @since      3.0.0 EE
 */


/**
 * Application singleton
 * 
 * @package    Lite Commerce
 * @subpackage XLite
 * @since      3.0.0 EE
 */
class XLite extends XLite_Base implements XLite_Base_ISingleton
{
    /**
     * Endpoints
     */

    const CART_SELF  = 'cart.php';
    const ADMIN_SELF = 'admin.php';

    /**
     * This target will be used if the "target" params is not passed in the request
     */

    const TARGET_DEFAULT = 'main';


    /**
     * Flag; determines if we need to cleanup (and, as a result, to rebuild) classes and templates cache
     *
     * @var    bool
     * @access protected
     * @since  3.0
     */
    protected $isNeedToCleanupCache = false;

    /**
     * Config options hash
     * 
     * @var    array
     * @access protected
     * @since  3.0.0 EE
     */
    protected $options = null;

    /**
     * Current area flag
     *
     * @var    bool
     * @access public
     * @since  3.0.0 EE
     */
    public $adminZone = false;

    /**
     * TODO - check if it's realy needed 
     * 
     * @var    mixed
     * @access public
     * @since  3.0.0 EE
     */
    public $_xlite_form_id = null;

    /**
     * Called controller 
     * 
     * @var    XLite_Controller_Abstract
     * @access public
     * @since  3.0.0 EE
     */
    public static $controller = null;


    /**
     * It's not possible to instantiate this class using the "new" operator 
     * 
     * @return void
     * @access protected
     * @since  3.0.0 EE
     */
    protected function __construct()
    {
    }

    /**
     * Parse config file and return options list
     * 
     * @return array
     * @access protected
     * @since  3.0.0 EE
     */
    protected function parseConfigFile()
    {
        $options = parse_ini_file(LC_ROOT_DIR . 'etc' . LC_DS . 'config.php', true);

        if (is_array($options)) {
            if (file_exists(LC_ROOT_DIR . 'etc' . LC_DS . 'config.local.php')) {
                $optionsLocal = parse_ini_file(LC_ROOT_DIR . 'etc' . LC_DS . 'config.local.php', true);
                if (is_array($optionsLocal)) {
                    $options = array_merge($options, $optionsLocal);
                }
            }
        } else {
            $this->_die('Unable to read/parse configuration file(s)');
        }

        return $options;
    }

    /**
     * Return current target 
     * 
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getTarget()
    {
        $target = XLite_Core_Request::getInstance()->target;

        if (empty($target)) {
            // FIXME - "_REQUEST" should be removed
            $_REQUEST['target'] = XLite_Core_Request::getInstance()->target = $target = self::TARGET_DEFAULT;
        }

        return $target;
    }

    /**
     * Return current action 
     * 
     * @return mixed
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getAction()
    {
        return XLite_Core_Request::getInstance()->action;
    }
    

    /**
     * Return specified (or the whole list) options 
     * 
     * @param mixed $names list (or single value) of option names
     *  
     * @return mixed
     * @access public
     * @since  3.0.0 EE
     */
    public function getOptions($names = null)
    {
        if (is_null($this->options)) {
            $this->options = $this->parseConfigFile();
            $this->options['host_details']['web_dir_wo_slash'] = rtrim($this->options['host_details']['web_dir'], '/');
        }

        $result = $this->options;

        if (!is_null($names)) {
            if (is_array($names)) {
                $names = array_reverse($names);
                while (!empty($names) && !is_null($result)) {
                    if (is_null($key = array_pop($names))) break;
                    $result = isset($result[$key]) ? $result[$key] : null;
                }
            } else {
                $result = isset($result[$names]) ? $result[$names] : null;
            }
        }

        return $result;
    }

    /**
     * Use this function to get a reference to this class object
     * 
     * @return XLite
     * @access public
     * @since  3.0.0 EE
     */
    public static function getInstance()
    {
        return self::_getInstance(__CLASS__);
    }

    /**
     * Clean up classes cache (if needed) 
     * 
     * @return void
     * @access public
     * @since  3.0.0 EE
     */
    public function __destruct()
    {
        if ($this->isNeedToCleanupCache) {
            XLite_Model_ModulesManager::getInstance()->cleanupCache();
        }
    }

    /**
     * Ability to provoke cache cleanup (or to prevent it)
     * 
     * @param bool $flag if it's needed to cleanup cache or not
     *  
     * @return void
     * @access public
     * @since  3.0.0 EE
     */
    public function setCleanUpCacheFlag($flag)
    {
        $this->isNeedToCleanupCache = (true === $flag);
    }

    /**
     * Return current endpoint script
     * 
     * @return string
     * @access public
     * @since  3.0.0 EE
     */
    public function getScript()
    {
        return $this->adminZone ? self::ADMIN_SELF : self::CART_SELF;
    }

    /**
     * Return instance of the abstract factory sigleton 
     * 
     * @return XLite_Model_Factory
     * @access public
     * @since  3.0.0 EE
     */
    public function getFactory()
    {
        return XLite_Model_Factory::getInstance();
    }

    public function initModules()
    {
        XLite_Model_ModulesManager::getInstance()->init();
    }

    public function initController()
    {
        $controllerClass = XLite_Core_Converter::getControllerClass($this->getTarget());

        self::$controller = new $controllerClass();
        self::$controller->init();
    }

    public function init()
    {
        $this->initModules();
        $this->initController();
    }

    public function runController()
    {
        self::$controller->handleRequest();

        return self::$controller->getViewer();
    }

    public function runViewer(XLite_View_Abstract $viewer = null, $template = null)
    {
        if (!isset($viewer)) {
            $viewer = new XLite_View($template);
        }

        $viewer->display();
    }

    public function run($adminZone = false)
    {
        // Set current area
        $this->adminZone = $adminZone;

        // Initialize modules amd create controller instance
        $this->init();

        // Handle action (if needed)
        $viewer = $this->runController();
    
        // Display page
        $this->runViewer($viewer);
    }


    /**
    * Runs the cart.
    *
    * @access public
    */
    /*function run()
    {
        XLite_Model_ModulesManager::getInstance()->init();

        if (isset($_REQUEST['target'])) {
            $target = $_REQUEST['target'];
        } else {
            $target = $_REQUEST['target'] = 'main';
        }

        $dialogClass = XLite_Core_Converter::getControllerClass($target);
        self::$controller = new $dialogClass();
        self::$controller->init();
        $this->profiler->log("dialog_init_time");
        self::$controller->handleRequest();
        $this->profiler->log("dialog_handleRequest_time");
        self::$controller = null;

        $this->profiler->log("run_time");
    }*/

    function shopUrl($url, $secure = false)
    {
        // construct requested cart URL 
        $proto   = $secure ? "https://" : "http://";
        $host    = $secure ? $this->getOptions(array('host_details', 'https_host')) : $this->getOptions(array('host_details', 'http_host'));
        $web_dir = $this->getOptions(array('host_details', 'web_dir'));
        $last    = strlen($web_dir) - 1;
        $web_dir .= ($web_dir{$last} == "/") ? "" : "/";
        $sid     = "";

        if ($secure) {
            $sid  = strpos($url, '?') ? "&" : "?";
            $sid .= $this->session->getName() . "=" . $this->session->getID();
        }

        return $proto . $host . $web_dir . $url . $sid;
    }

    /**
     * Get controller 
     * 
     * @return XLite_Controller_Abstract
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getController()
    {
        return self::$controller;
    }
}


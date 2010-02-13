<?php

/* $Id$ */

/**
 * Application singleton
 * 
 * @package    Lite Commerce
 * @subpackage XLite_
 * @since      3.0.0 EE
 */
class XLite extends XLite_Base implements XLite_Base_ISingleton
{
	/**
     * Flag; determines if we need to cleanup (and, as a result, to rebuild) classes and templates cache
     *
     * @var    bool
     * @access protected
     * @since  3.0
     */
    protected $isNeedToCleanupCache = false;

    /**
     * Current area flag
     * 
     * @var    bool
     * @access protected
     * @since  3.0.0 EE
     */
    protected $adminZone = false;

	/**
	 * Config options hash
	 * 
	 * @var    array
	 * @access protected
	 * @since  3.0.0 EE
	 */
	protected $options = null;

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



	public function setCleanUpCacheFlag($flag)
    {
        $this->isNeedToCleanupCache = (true === $flag);
    }

    /**
    * Runs the cart.
    *
    * @access public
    */
    function run()
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
    }

    function getScript()
    {
        return $this->get("adminZone") ? ADMIN_SELF : CART_SELF;
    }

    function getFactory()
    {
        return new XLite_Model_Factory();
    }
    
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

    function done()
    {
        // close session
        $this->session->writeClose();

        // stop profiler timer and show profiling result
        $this->profiler->stop();
    }

    function isClosedForMaintenance()
    {
    	if ($this->get("adminZone")) {
    		return false;
    	}

		if ($this->getComplex('config.General.shop_closed')) {
			if ($this->auth->is("logged") && $this->auth->isAdmin($this->auth->get("profile"))) {
    			return false;
			} else {
    			return true;
			}
		}
		
		return false;
    }

    function isPHPEarlier($version)
    {
        return version_compare(PHP_VERSION, $version, '<');
    }
}


<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URLs:                                                       |
|                                                                              |
| FOR LITECOMMERCE                                                             |
| http://www.litecommerce.com/software_license_agreement.html                  |
|                                                                              |
| FOR LITECOMMERCE ASP EDITION                                                 |
| http://www.litecommerce.com/software_license_agreement_asp.html              |
|                                                                              |
| THIS  AGREEMENT EXPRESSES THE TERMS AND CONDITIONS ON WHICH YOU MAY USE THIS |
| SOFTWARE PROGRAM AND ASSOCIATED DOCUMENTATION THAT CREATIVE DEVELOPMENT, LLC |
| REGISTERED IN ULYANOVSK, RUSSIAN FEDERATION (hereinafter referred to as "THE |
| AUTHOR")  IS  FURNISHING  OR MAKING AVAILABLE TO  YOU  WITH  THIS  AGREEMENT |
| (COLLECTIVELY,  THE "SOFTWARE"). PLEASE REVIEW THE TERMS AND  CONDITIONS  OF |
| THIS LICENSE AGREEMENT CAREFULLY BEFORE INSTALLING OR USING THE SOFTWARE. BY |
| INSTALLING,  COPYING OR OTHERWISE USING THE SOFTWARE, YOU AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE ACCEPTING AND AGREEING  TO  THE  TERMS  OF  THIS |
| LICENSE AGREEMENT. IF YOU ARE NOT WILLING TO BE BOUND BY THIS AGREEMENT,  DO |
| NOT  INSTALL  OR USE THE SOFTWARE. VARIOUS COPYRIGHTS AND OTHER INTELLECTUAL |
| PROPERTY  RIGHTS PROTECT THE SOFTWARE. THIS AGREEMENT IS A LICENSE AGREEMENT |
| THAT  GIVES YOU LIMITED RIGHTS TO USE THE SOFTWARE AND NOT AN AGREEMENT  FOR |
| SALE  OR  FOR TRANSFER OF TITLE. THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY |
| GRANTED  BY  THIS AGREEMENT.                                                 |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*
* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:
*/

/**
* The main class for LiteCommerce project. 
*
* @package XLite
* @version $Id$
* @access public
*/
class XLite extends Object
{
    var $adminZone = false;

    function initFromGlobals()
    {
        global $options;
        // transform into object tree
        $this->options = func_new("Object");
        foreach ($options as $key => $val) {
            $this->options->$key = func_new("Object");
            $this->options->$key->setProperties($val);
        }

        $this->profiler =& func_get_instance("Profiler");
        $this->profiler->start($this->options->profiler_details->enabled);

		$this->db =& func_new("Database");
        $this->db->connect();
        $this->profiler->log("db_time");

        // read configuration data from database
        $cfg =& func_new("Config");
        $this->config =& $cfg->readConfig();
        $this->profiler->log("cfg_time");
		
		// initialize logging
        require_once "kernel/Logger.php";
        $this->logger =& Logger::singleton();

        // start session
        $session =& func_get_instance("Session");
        $this->session =& $session->start();
        $this->profiler->log("ss_time");

        // attempt to initialize modules subsystem
        $this->mm =& func_new("ModulesManager");
        $this->mm->init();
        $this->profiler->log("mm_time");

        $this->layout =& func_get_instance("Layout");
        $this->layout->initFromGlobals();
        
        $this->auth =& func_get_instance("Auth");

        $this->license =& func_new("Object");
        if (function_exists('license_check')) {
            $this->license->set("properties", license_check());
        }

        $this->profiler->log("init_time");

        //check memory_limit_changeable
        $memory_limit = @ini_get("memory_limit");
        if (func_check_memory_limit($memory_limit, func_convert_to_byte($memory_limit) + 1024)) {
            func_check_memory_limit(0, $memory_limit);
            $this->memoryLimitChangeable = true;
        } else {
            $this->memoryLimitChangeable = false;
        }

        $this->suMode = get_php_execution_mode();

        $this->instanceUniqID = md5(uniqid(rand(), true));
    }

    /**
    * Runs the cart.
    *
    * @access public
    */
    function run()
    {
        if (isset($_REQUEST['target'])) {
            $target = $_REQUEST['target'];
        } else {
            $target = $_REQUEST['target'] = 'main';
        }
        $dialogClass = 'dialog_' . $target;
        if ($this->get('adminZone')) {
            $dialogClass = 'admin_' . $dialogClass;
        }
        $dialog =& func_new($dialogClass);
        $dialog->init();
        $this->profiler->log("dialog_init_time");
        $dialog->handleRequest();
        $this->profiler->log("dialog_handleRequest_time");
        $dialog = null;

        $this->profiler->log("run_time");
    }

    function getScript()
    {
        return $this->get("adminZone") ? ADMIN_SELF : CART_SELF;
    }

    function &getFactory()
    {
        return func_new("Factory");
    }
    
    function shopUrl($url, $secure = false)
    {
        // construct requested cart URL 
        $proto   = $secure ? "https://" : "http://";
        $host    = $secure ? $this->options->host_details->https_host :
                             $this->options->host_details->http_host;
        $web_dir = $this->options->host_details->web_dir;
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

		if ($this->get("config.General.shop_closed")) {
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

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

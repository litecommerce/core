<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2007 Creative Development <info@creativedevelopment.biz>  |
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
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* Session default options. Should be overridden through the [log_details]
* configuration file sections.
*/
define('SESSION_DEFAULT_TYPE', 'sql');
define('SESSION_DEFAULT_NAME', 'XSID');
define('SESSION_DEFAULT_PATH', '/');
define('SESSION_DEFAULT_TTL',  7200); // 2 hours (default), set 86400 for 1 day session TTL
list($usec, $sec) = explode(' ', microtime());
$seed = (float) $sec + ((float) $usec * 1000000);
if (isset($_SERVER["REMOTE_ADDR"])) $seed += (float) ip2long($_SERVER["REMOTE_ADDR"]);
if (isset($_SERVER["REMOTE_PORT"])) $seed += (float) $_SERVER["REMOTE_PORT"];
srand($seed);
define('SESSION_DEFAULT_ID', md5(uniqid(rand(), true)));

/**
* Class implements both an abstraction for the concrete Session classes and
* base session functionality .
*
* @package Kernel
* @access public
* @version $Id: Session.php,v 1.3 2007/05/21 11:53:28 osipov Exp $
*/
class Session extends Object
{
    /**
    * Session data containter.
    * @var array $_data
    * @access private
    */
    var $_data = array();

    /**
    * Session options.
    *
    * @var array $options
    * @access private
    */
    var $options = array(
            'type' => SESSION_DEFAULT_TYPE,
            'name' => SESSION_DEFAULT_NAME,
            'id'   => SESSION_DEFAULT_ID,
            'path' => SESSION_DEFAULT_PATH,
            'ttl'  => SESSION_DEFAULT_TTL
        );
    
    /**
    * Constructor.
    *
    */
    function constructor()
    {
        parent::constructor();
        global $options;
        $this->options = array_merge($this->options,
                $options["session_details"]);
        $this->options = array_merge($this->options,
                $options["host_details"]);
    }

    /**
    * Factory method. Attempts to create the concrete Session object instance.
    *
    * @access public
    * @return mixed The newly created concrete Session object instance
    */
    function factory()
    {
        $session = func_new("Session");
        $session_type = strtolower($session->getType());
        if (isset($session)) {
        	unset($session);
        }

        $class = "Session_$session_type";

        return func_new("$class");
    }

    /**
    * Singleton method. Attempts to return a reference to concrete Session
    * instance, only creating a new instance if no Session instance
    * currently exists.
    *
    * @return               The concrete Session reference
    *                       on error.
    */
    function &start()
    {
        static $session;
        if (!isset($session)) {
            $session = $this->factory();
        }    
        return $session;
    }

    /**
    * Destroys the concrete session object. Abstract method, should be 
    * overridden.
    */
    function destroy()
    {
    }
    
    /**
    * Sets the variable with specified name and value (add it to
    * the data container)
    *
    * @param string $name    The variable name.
    * @param mixed  $value   The variable value.
    * @access public
    * @return mixed          The concrete Session object SetVar method
    *                        result or singleton result on error.
    */
    function set($name, $value)
    {
    }
    
    /**
    * Returns the value for the specified session variable.
    * 
    * @param string $name     The variable name
    *
    * @access public
    * @return mixed           The variable value
    */
    function get($name)
    {
    }

    /**
    * Checks whether the variable has been registered to session
    */
    function isRegistered($name)
    {
    }

    /**
    * Abstract method for concrete Session object initialization.
    */
    function _initialize()
    {
    }

    /**
    * Abstract method for fetching the concrete Session object data
    * from the database.
    */
    function _fetchData()
    {
    }
    
    /**
    * Sets the session type.
    *
    * @param string $type The session type. Default is SESSION_DEFAULT_TYPE
    * @access public
    */
    function setType($type = SESSION_DEFAULT_TYPE)
    {
        $this->options["type"] = $type;
    }
    
    /**
    * Returns the session type.
    *
    * @access public
    * @return string Session type.
    */
    function getType()
    {
        return $this->options["type"];
    }

    /**
    * Sets the session name.
    *
    * @access public
    * @param string $name The session name, Default is SESSION_DEFAULT_NAME
    */
    function setName($name = SESSION_DEFAULT_NAME)
    {
        $this->options["name"] = $name;
    }
    
    function getName()
    {
        return $this->options["name"];
    }

    function setID($id = SESSION_DEFAULT_ID)
    {
        $this->options["id"] = $id;
    }
    
    function getID()
    {
        return $this->options["id"];
    }

    function setPath($path = SESSION_DEFAULT_PATH)
    {
        $this->options["path"] = $path;
    }
    
    function getPath()
    {
        return $this->options["path"];
    }

    function setTtl($ttl = SESSION_DEFAULT_TTL)
    {
        $this->options["ttl"] = $ttl;
    }
    
    function getTtl()
    {
        return $this->options["ttl"];
    }

    function getHttpHost()
    {
        return $this->options["http_host"];
    }
    
    function getHttpsHost()
    {
        return $this->options["https_host"];
    }

    function getShopURL($secure = false)
    {   
        $proto   = $secure ? "https://" : "http://";
        $host    = $secure ? $this->options['https_host'] :
                             $this->options['http_host'];
        $web_dir = $this->options['web_dir'];
        $last    = strlen($web_dir) - 1;
        $web_dir.= ($web_dir{$last} == "/") ? "" : "/";
    
        return $proto . $host . $web_dir;
    }
 
    function getData()
    {
        return addslashes(serialize($this->_data));
    }

    function setData($data = array())
    {
        $this->_data = unserialize(stripslashes($data));
    }

    /**
    * Saves the session data.
    *
    * @access public
    * @return void
    * @static
    */
    function writeClose()
    {
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>

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
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

define('SESSION_DEFAULT_ID', md5(uniqid(rand(), true)));

/**
* Class implements both an abstraction for the concrete Session classes and
* base session functionality .
*
* @package Kernel
* @access public
* @version $Id$
*/
class XLite_Model_Session extends XLite_Base implements XLite_Base_ISingleton
{
	const SESSION_DEFAULT_TYPE = 'Sql';
	const SESSION_DEFAULT_NAME = 'xid';
	const SESSION_DEFAULT_PATH = '/';
	const SESSION_DEFAULT_TTL  = 7200;

    /**
    * Session data containter.
    * @var array $_data
    * @access private
    */	
    public $_data = array();

    /**
    * Session options.
    *
    * @var array $options
    * @access private
    */
    protected $options = array(
		'type' => self::SESSION_DEFAULT_TYPE,
		'name' => self::SESSION_DEFAULT_NAME,
		'id'   => SESSION_DEFAULT_ID,
		'path' => self::SESSION_DEFAULT_PATH,
		'ttl'  => self::SESSION_DEFAULT_TTL
	);

	public static function getInstance()
    {
        return self::_getInstance(__CLASS__);
    }
 
    /**
    * Constructor.
    *
    */
    public function __construct()
    {
        parent::__construct();

		$xlite = XLite::getInstance();
		$this->options = array_merge($this->options, $xlite->getOptions('session_details'), $xlite->getOptions('host_details'));
    }

    /**
    * Singleton method. Attempts to return a reference to concrete Session
    * instance, only creating a new instance if no Session instance
    * currently exists.
    *
    * @return               The concrete Session reference
    *                       on error.
    */
    public function start()
    {
		return call_user_func(array('XLite_Model_Session_' . $this->getType(), 'getInstance'));
    }

    /**
    * Destroys the concrete session object. Abstract method, should be 
    * overridden. FIXME
    */
    function destroy()
    {
    }
    
    /**
    * Sets the variable with specified name and value (add it to
    * the data container). FIXME
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
    * Returns the value for the specified session variable. FIXME
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
    * Checks whether the variable has been registered to session. FIXME
    */
    function isRegistered($name)
    {
    }

    /**
    * Abstract method for concrete Session object initialization. FIXME
    */
    function _initialize()
    {
    }

    /**
    * Abstract method for fetching the concrete Session object data
    * from the database. FIXME
    */
    function _fetchData()
    {
    }
    
    /**
    * Sets the session type.
    *
    * @param string $type The session type. Default is self::SESSION_DEFAULT_TYPE
    * @access public
    */
    function setType($type = self::SESSION_DEFAULT_TYPE)
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
    * @param string $name The session name, Default is self::SESSION_DEFAULT_NAME
    */
    function setName($name = self::SESSION_DEFAULT_NAME)
    {
        $this->options["name"] = $name;
    }
    
    function getName()
    {
        return $this->options["name"];
    }

    function setID($id)
    {
		if (!preg_match('/^[0-9a-fA-F]{31,32}$/', $id)) {
			$this->_die('Session::setID(): Incorrect session ID has been detected: ' . $id);
		}      
        $this->options["id"] = $id;
    }
    
    function getID()
    {
        return $this->options["id"];
    }

    function setPath($path = self::SESSION_DEFAULT_PATH)
    {
        $this->options["path"] = $path;
    }
    
    function getPath()
    {
        return $this->options["path"];
    }

    function setTtl($ttl = self::SESSION_DEFAULT_TTL)
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
    * Saves the session data. FIXME
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

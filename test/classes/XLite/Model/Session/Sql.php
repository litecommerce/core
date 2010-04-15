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

/**
* Class Session_sql extends the Session base class and provides
* functionality to store session information to SQL database.
*
* @package Kernel
* @access public
* @version $Id$
*/
class XLite_Model_Session_Sql extends XLite_Model_Session implements XLite_Base_ISingleton
{
    /**
    * @var string $sql_table The database sql table to store session to
    */	
    public $sql_table;

	public static function getInstance()
    {
        return self::getInternalInstance(__CLASS__);
    }

    /**
    * Constructor.
    *
    * @param  array  $options  options parameters array
    * @access public
    * @return void
    */
    function __construct(array $options = array())
    {
        parent::__construct($options);

        $this->sql_table = $this->db->getTableByAlias("sessions");
        $this->gc();

        $sname = $this->getName();
        $sid = null;
        if (isset($_POST[$sname])) {
        	$sid = $_POST[$sname];

        } elseif (isset($_GET[$sname])) {
        	$sid = $_GET[$sname];

        } elseif (isset($_REQUEST[$sname])) {
        	$sid = $_REQUEST[$sname];
        }
        
        if (isset($sid)) {
            $this->setID($sid);
            $this->_fetchData();

        } else {
            $this->_initialize();
        }
    }

    /**
    * Restores the already initialized session.
    */
    function _restore()
    {
        $sid = null;

        $sname = $this->getName();
        if (isset($_POST[$sname])) {
        	$sid = $_POST[$sname];

        } elseif (isset($_GET[$sname])) {
        	$sid = $_GET[$sname];

        } elseif (isset($_REQUEST[$sname])) {
        	$sid = $_REQUEST[$sname];
        }

        if (isset($sid)) {
        	$this->setID($sid);
        	$this->_fetchData();
        }
    }

    /**
    * Initializes the session.
    */
    function _initialize()
    {
        $this->_sendHeaders(); 

        return $this->create();
    }

    /**
    * Fetches the session data from SQL database.
    */
    function _fetchData()
    {
        if ($this->isExists()) {
            $this->_sendHeaders();
            return $this->retrieve();

        } else {
        	$sname = $this->getName();
            if (!isset($_POST[$sname]) && !isset($_GET[$sname]) && isset($_REQUEST[$sname])) {
                $sql = "SELECT id FROM $this->sql_table WHERE data LIKE '%\"XSID=".$_REQUEST[$sname]."\"%'";
                $result = $this->db->getOne($sql);
        		if ($result) {
        			$this->setID($result);
                    $this->_sendHeaders();
                    return $this->retrieve();
        		}
        	}
            return $this->_initialize();
        }
    }
    
    /**
    * Sets the session variable.
    *
    * @param string $name The session variable name.
    * @param mixed $value The session variable to save.
    */
    public function set($name, $value)
    {
        if (is_null($value)) {
        	if (isset($this->_data[$name])) {
            	unset($this->_data[$name]);
            }

        } else {
            $this->_data[$name] = serialize($value);
        }    

        return true;
    }

    /**
    * Returns the session variable with specified name.
    *
    * @param string $name The session variable name to return value.
    * @return mixed The session variable or null.
    */
    public function get($name)
    {
		return $this->isRegistered($name) ? unserialize($this->_data[$name]) : null;
    }

    public function isRegistered($name)
    {
	    return is_array($this->_data) && isset($this->_data[$name]);
    }
    
    /**
    * Destroys and deletes the current session.
    */
    function destroy()
    {
        return $this->delete();
    }

    /**
    * Writes the current session to SQL database.
    */
    function writeClose()
    {
        return $this->update();
    }

    /**
    * Sends the session cookies to clients browser.
    */
    function _sendHeaders()
    {
        $error_reporting = error_reporting(0); // suppress warning messages
		
        $url = parse_url($this->getShopURL());
        $this->setPath($url['path']);

        if ($url['host'] == "localhost" || $url['host'] == "127.0.0.1" || !strpos($url['host'], ".")) {
            setcookie($this->getName(), $this->getID(), 0, $this->getPath());
        } else {
            setcookie($this->getName(), $this->getID(), 0, $this->getPath(), $url['host'], 0);
		}

        $url = parse_url($this->getShopURL(true));
        $this->setPath($url['path']);

        if ($url['host'] == "localhost" || $url['host'] == "127.0.0.1" || !strpos($url['host'], ".")) {
            setcookie($this->getName(), $this->getID(), 0, $this->getPath());
        } else {
            setcookie($this->getName(), $this->getID(), 0, $this->getPath(), $url['host'], 0);
		}

        error_reporting($error_reporting);
    }

/******************************* DB layer **********************************/

    /**
    * Creates the session database table record 
    *
    * @access public 
    * @return mixed   a DB_result object or DB_OK on success, a DB
    *                 error on failure
    */
    function create()
    {
        $sql = "REPLACE INTO $this->sql_table (id, expiry, data) VALUES ('".$this->getID()."', ".(time() + $this->getTtl()).", '".addslashes($this->getData())."')";
        return $this->db->query($sql);
    }

    /**
    * Retreives the session data
    *
    * @access public
    * @return array  $data The session data.
    */
    function retrieve()
    {
        $sql = "SELECT data FROM $this->sql_table WHERE id = '".$this->getID()."'";
        $result = $this->db->getOne($sql);
		if ($result) {
	        $this->setData($result);
		} else {
			$this->setData(array());
		}
        // touch session
        $sql = "UPDATE $this->sql_table SET expiry = ".(time() + $this->getTtl())." WHERE id = '".$this->getID()."'";
        return $this->db->query($sql);
    }

    /**
    * Updated the session database table record 
    *
    * @access public 
    * @return mixed   a DB_result object or DB_OK on success, a DB
    *                 error on failure
    */
    function update()
    {
        $sql = "UPDATE $this->sql_table SET expiry = ".(time() + $this->getTtl()).", data = '".addslashes($this->getData())."' WHERE id = '".$this->getID()."' AND expiry >= ".time();
        return $this->db->query($sql);
    }

    /**
    * Deletes the session database table record
    *
    * @access public 
    * @return mixed   a DB_result object or DB_OK on success, a DB
    *                 error on failure
    */
    function delete()
    {
        $sql = "DELETE FROM $this->sql_table WHERE id = '".$this->getID()."'";
        return $this->db->query($sql);
    }

    /**
    * Garbage collector. Removes the expired session database records.
    *
    * @access public 
    * @return mixed   a DB_result object or DB_OK on success, a DB
    *                 error on failure
    */
    function gc()
    {
        $sql = "DELETE FROM $this->sql_table WHERE expiry < ". time();
        return $this->db->query($sql);
    }

    /**
    * Checks whether the database record for the specified session id exists.
    *
    * @access public 
    * @return bool True if session record exists, false otherwise
    */
    function isExists()
    {
        $sql = "SELECT COUNT(*) FROM $this->sql_table WHERE id = '".$this->getID()."'";
        return (bool) $this->db->getOne($sql);
    }
}


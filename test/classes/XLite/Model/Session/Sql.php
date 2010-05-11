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
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Model_Session_Sql extends XLite_Model_Session implements XLite_Base_ISingleton
{
    /**
     * The database sql table to store session to 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDBTable()
    {
        return XLite_Model_Database::getInstance()->getTableByAlias('sessions');
    }

    /**
     * Restores the already initialized session
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function restore()
    {
        $sessionId = XLite_Core_Request::getInstance()->__get($this->getName());

        if ($result = isset($sessionId)) {
            $this->setID($sessionId);
            $this->_fetchData();
        }

        return $result;
    }

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

        $this->gc();

        if (!$this->restore()) {
            $this->_initialize();
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
            $result = $this->retrieve();

        } else {
            $sname = $this->getName();
            if (XLite_Core_Request::getInstance()->__get($sname)) {
                $sid = addslashes(XLite_Core_Request::getInstance()->__get($sname));

                $sql = 'SELECT id FROM ' . $this->getDBTable() . ' WHERE data LIKE \'%"' 
                    . self::SESSION_DEFAULT_NAME . '=' . $sid . '"%\'';
                $result = $this->db->getOne($sql);
                if ($result) {
                    $this->setID($result);
                    $this->_sendHeaders();
                    $result = $this->retrieve();
                }
            }

            if (!isset($result)) {
                $result = $this->_initialize();
            }
        }

        return $result;
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

        if ($url['host'] == 'localhost' || $url['host'] == '127.0.0.1' || !strpos($url['host'], '.')) {
            setcookie($this->getName(), $this->getID(), 0, $this->getPath());

        } else {
            setcookie($this->getName(), $this->getID(), 0, $this->getPath(), $url['host'], 0);
        }

        $url = parse_url($this->getShopURL(true));

        if ($url['host'] == 'localhost' || $url['host'] == '127.0.0.1' || !strpos($url['host'], '.')) {
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
        $sql = 'REPLACE INTO ' . $this->getDBTable() . ' (id, expiry, data) VALUES (\''
            . $this->getID() . '\', ' . (time() + $this->getTtl())
            . ', \'' . addslashes($this->getData()) . '\')';

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
        $sql = 'SELECT data FROM ' . $this->getDBTable() . ' WHERE id = \'' . $this->getID() . '\'';
        $result = $this->db->getOne($sql);
        if (!$result) {
            $result = array();
        }

        $this->setData($result);

        // touch session
        $sql = 'UPDATE ' . $this->getDBTable()
            . ' SET expiry = ' . (time() + $this->getTtl()) . ' WHERE id = \'' . $this->getID() . '\'';

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
        $sql = 'UPDATE ' . $this->getDBTable() . ' SET expiry = ' . (time() + $this->getTtl())
            . ', data = \'' . addslashes($this->getData())
            . '\' WHERE id = \'' . $this->getID() . '\' AND expiry >= ' . time();

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
        $sql = 'DELETE FROM ' . $this->getDBTable() . ' WHERE id = \'' . $this->getID() . '\'';

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
        $sql = 'DELETE FROM ' . $this->getDBTable() . ' WHERE expiry < ' . time();

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
        $sql = 'SELECT COUNT(*) FROM ' . $this->getDBTable() . ' WHERE id = \'' . $this->getID() . '\'';

        return (bool) $this->db->getOne($sql);
    }
}

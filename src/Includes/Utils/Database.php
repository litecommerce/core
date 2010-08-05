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
 * @subpackage Includes_Utils
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace Includes\Utils;

/**
 * Database 
 * 
 * @package    XLite
 * @see        ____class_see____
 * @since      3.0.0
 */
class Database extends \Includes\Utils\AUtils
{
    /**
     * DB handler 
     * 
     * @var    PDO
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $handler;

    /**
     * Database connection options 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $dbOptions;

    /**
     * Setter method for $dbOptions. Once tries to connect and return connection object
     * 
     * @return PDO
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function setDbOptions($options)
    {
        static::$dbOptions = $options;

        return static::getHandler();
    }

    /**
     * Getter method for $this->dbOptions
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getDbOptions($name = null)
    {
        $options = isset(static::$dbOptions) ? static::$dbOptions : \Includes\Utils\ConfigParser::getOptions(array('database_details'));

        return isset($name) ? (isset($options[$name]) ? $options[$name] : null) : $options;
    }

    /**
     * Reset method for $this->dbOptions
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function resetDbOptions()
    {
        static::$dbOptions = null;
    }

    /**
     * Return name of database user 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getUsername()
    {
        return static::getDbOptions('username');
    }

    /**
     * Return password of database user 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getPassword()
    {
        return static::getDbOptions('password');
    }

    /**
     * Return list of the PDO connection options 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getConnectionFlags()
    {
        return array(
            \PDO::ATTR_AUTOCOMMIT => true,
            \PDO::ATTR_ERRMODE    => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_PERSISTENT => false,
        );
    }

    /**
     * Connect to database
     *
     * @return PDO
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function connectToDb()
    {
        return new \PDO(
            static::getConnectionString(),
            static::getUsername(),
            static::getPassword(),
            static::getConnectionFlags()
        );
    }

    /**
     * Return PDO database handler
     *
     * @return PDO
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getHandler()
    {
        if (!isset(static::$handler)) {
            static::$handler = static::connectToDb();
        }

        return static::$handler;
    }

    /**
     * Return array of credentials to connect to DB 
     * 
     * @param bool $fullList add or not the additional fields
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getConnectionParams($fullList = false)
    {
        $options = static::getDbOptions();

        $dsnFields = array(
            'host'        => 'hostspec',
            'port'        => 'port',
            'unix_socket' => 'socket',
            'dbname'      => 'database',
        );

        foreach ($dsnFields as $pdoOption => $lcOption) {
            if (!empty($options[$lcOption])) {
                $dsnFields[$pdoOption] = $options[$lcOption];
            }   
        }

        if ($fullList) {
            $dsnFields['username'] = static::getUsername();
            $dsnFields['password'] = static::getPassword();
        }

        return $dsnFields;
    }

    /**
     * Prepare MySQL connection string
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getConnectionString()
    {
        return 'mysql:' . \Includes\Utils\Converter::buildQuery(static::getConnectionParams(), '=', ';');
    }

    /**
     * Perform SQL query (return araay of records)
     *
     * @param string  $sql   SQL query to execute
     * @param integer $flags PDO fetch option
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function fetchAll($sql, $flags = \PDO::FETCH_ASSOC)
    {
        return static::getHandler()->query($sql)->fetchAll($flags);
    }

    /**
     * Perform SQL query (single value)
     *
     * @param string $sql SQL query to execute
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function fetchColumn($sql)
    {
        return static::getHandler()->query($sql)->fetchColumn();
    }

    /**
     * Perform SQL query
     *
     * @param string $sql    SQL query to execute
     * @param array  $params query params
     *
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function execute($sql, array $params = array())
    {
        return static::getHandler()->prepare($sql)->execute($params);
    }

    /**
     * Get the database version
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getDbVersion()
    {
        return static::getHandler()->getAttribute(\PDO::ATTR_SERVER_VERSION);
    }
}

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
 * PHP version 5.3.0
 * 
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace Includes\Utils;

/**
 * Database 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class Database extends \Includes\Utils\AUtils
{
    /**
     * DB handler 
     * 
     * @var    \PDO
     * @access protected
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected static $handler;

    /**
     * Database connection options 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected static $dbOptions;

    /**
     * Setter method for $dbOptions. Once tries to connect and return connection object
     * 
     * @return \PDO
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function setDbOptions($options)
    {
        static::$dbOptions = $options;

        return static::getHandler();
    }

    /**
     * Reset method for $this->dbOptions
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function resetDbOptions()
    {
        static::$dbOptions = null;
    }

    /**
     * Return array of credentials to connect to DB
     *
     * @param bool $fullList add or not the additional fields
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
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
            } else {
                unset($dsnFields[$pdoOption]);
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
     * @since  1.0.0
     */
    public static function getConnectionString()
    {
        return 'mysql:' . \Includes\Utils\Converter::buildQuery(static::getConnectionParams(), '=', ';');
    }

    /**
     * Getter method for $this->dbOptions
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getDbOptions($name = null)
    {
        return \Includes\Utils\ArrayManager::getIndex(
            static::$dbOptions ?: \Includes\Utils\ConfigParser::getOptions(array('database_details')),
            $name
        );
    }

    /**
     * Return name of database user 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
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
     * @since  1.0.0
     */
    protected static function getPassword()
    {
        return static::getDbOptions('password');
    }

    /**
     * Return list of the \PDO connection options 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
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
     * @return \PDO
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
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
     * Return \PDO database handler
     *
     * @return \PDO
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getHandler()
    {
        if (!isset(static::$handler)) {
            static::$handler = static::connectToDb();
        }

        return static::$handler;
    }

    /**
     * Execute SQL query and return the PDO statement object
     *
     * @param string $sql    SQL query to execute
     * @param array  $params Query params
     *
     * @return \PDOStatement
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function executeStatement($sql, array $params = array())
    {
        $statement = static::getHandler()->prepare($sql);
        $statement->execute($params);

        return $statement;
    }

    /**
     * Perform SQL query (return araay of records)
     *
     * @param string  $sql    SQL query to execute
     * @param array   $params Query params
     * @param integer $flags  \PDO fetch option
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function fetchAll($sql, array $params = array(), $flags = \PDO::FETCH_ASSOC)
    {
        return static::executeStatement($sql, $params)->fetchAll($flags);
    }

    /**
     * Perform SQL query (single value)
     *
     * @param string $sql    SQL query to execute
     * @param array  $params Query params
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function fetchColumn($sql, array $params = array())
    {
        return static::executeStatement($sql, $params)->fetchColumn();
    }

    /**
     * Perform parameterized SQL query and return the flag (success or not)
     *
     * @param string $sql    SQL query to execute
     * @param array  $params Query params
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function execute($sql, array $params = array())
    {
        static::executeStatement($sql, $params);
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
     * @since  1.0.0
     */
    public static function exec($sql)
    {
        return static::getHandler()->exec($sql);
    }

    /**
     * Get the database version
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getDbVersion()
    {
        return static::getHandler()->getAttribute(\PDO::ATTR_SERVER_VERSION);
    }

    /**
     * Execute a set of SQL queries from file
     *
     * :FIXME: must be completely revised
     * 
     * @param string  $fileName Name of SQL-file
     * @param boolean $verbose  Display uploading progress flag OPTIONAL
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function uploadSQLFromFile($fileName, $verbose = false)
    {
        $result = false;

        if (false == \Includes\Utils\FileManager::isFileReadable($fileName)) {

            throw new \InvalidArgumentException(
                sprintf('SQL file \'%s\' not found or is not readable', $fileName)
            );

        } else {

            $fp = fopen($fileName, 'rb');

            $sql = '';

            $result = true;

            while ($result && !feof($fp)) {

                $c = '';

                // Read SQL statement from file
                do {
                    $c .= fgets($fp, 1024);
                    $endPos = strlen($c) - 1;

                } while (substr($c, $endPos) != PHP_EOL && !feof($fp));

                $c = rtrim($c);

                // Skip comments
                if (substr($c, 0, 1) == '#' || substr($c, 0, 2) == '--') {
                    continue;
                }

                // Parse SQL statement

                $sql .= $c;

                if (substr($sql, -1) == ';') {

                    $sql = substr($sql, 0, strlen($sql) - 1);

                    // Execute SQL query
                    try {

                        static::getHandler()->beginTransaction();

                        $result = (false !== static::exec($sql));

                        if ($result) {
                            static::getHandler()->commit();
                        
                        } else {
                            static::getHandler()->rollBack();
                        }

                        if ($verbose) {   
                            echo ('.');
                            flush();
                        }

                    } catch (\PDOException $e) {

                        static::getHandler()->rollBack();

                        $result = false;

                        echo ('<br />' . $e->getMessage());
                    }
                    $sql = '';
                }
            }

            fclose($fp);
        }

        return $result;
    }
}

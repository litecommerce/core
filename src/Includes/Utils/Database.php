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
     * Connect to database
     *
     * @return PDO
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function connectToDb()
    {
        $options = \Includes\Utils\ConfigParser::getOptions('database_details');

        $user     = isset($options['username']) ? $options['username'] : '';
        $password = isset($options['password']) ? $options['password'] : '';

        // PDO flags using for connection
        $params = array(
            \PDO::ATTR_AUTOCOMMIT => true,
            \PDO::ATTR_ERRMODE    => \PDO::ERRMODE_SILENT,
            \PDO::ATTR_PERSISTENT => false,
        );

        return new \PDO(static::getConnectionString($options), $user, $password, $params);
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
     * Prepare MySQL connection string
     * FIXME - must be protected
     *
     * @param array $options MySQL credentials
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getConnectionString(array $options)
    {
        $dsnFields = array(
            'host'        => 'hostspec',
            'port'        => 'port',
            'unix_socket' => 'socket',
            'dbname'      => 'database',
        );
        $dsnString = array();

        foreach ($dsnFields as $pdoOption => $lcOption) {
            if (!empty($options[$lcOption])) {
                $dsnString[] = $pdoOption . '=' . $options[$lcOption];
            }
        }

        return 'mysql:' . implode(';', $dsnString);
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
    public static function fetchAll($sql, $flags = PDO::FETCH_ASSOC)
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
}

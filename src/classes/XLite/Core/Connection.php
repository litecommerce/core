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

namespace XLite\Core;

/**
 * Doctrine-based connection 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class Connection extends \Doctrine\DBAL\Connection
{
    /**
     * Prepares an SQL statement 
     * 
     * @param string $statement The SQL statement to prepare
     *  
     * @return \Doctrine\DBAL\Driver\Statement
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function prepare($statement)
    {
        $this->connect();

        return new \XLite\Core\Statement($statement, $this);
    }

    /**
     * Executes an, optionally parameterized, SQL query.
     *
     * If the query is parameterized, a prepared statement is used.
     * If an SQLLogger is configured, the execution is logged.
     * 
     * @param string $query  The SQL query to execute
     * @param array  $params The parameters to bind to the query, if any OPTIONAL
     * @param array  $types  The parameters types to bind to the query, if any OPTIONAL
     *  
     * @return \Doctrine\DBAL\Driver\Statement
     * @throws \XLite\Core\PDOException
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function executeQuery($query, array $params = array(), $types = array())
    {
        try {
            $result = parent::executeQuery($query, $params, $types);

        } catch (\PDOException $e) {
            throw new \XLite\Core\PDOException($e, $query, $params);
        }

        return $result;
    }

    /**
     * Executes an SQL INSERT/UPDATE/DELETE query with the given parameters
     * and returns the number of affected rows.
     * 
     * This method supports PDO binding types as well as DBAL mapping types.
     * 
     * @param string $query  The SQL query
     * @param array  $params The query parameters OPTIONAL
     * @param array  $types  The parameter types OPTIONAL
     *
     * @return integer The number of affected rows
     * @throws \XLite\Core\PDOException
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function executeUpdate($query, array $params = array(), array $types = array())
    {
        try {
            $result = parent::executeUpdate($query, $params, $types);

        } catch (\PDOException $e) {
            throw new \XLite\Core\PDOException($e, $query, $params);
        }

        return $result;
    }

    /**
     * Replace query
     * 
     * @param string $tableName table name
     * @param array  $data      Data
     *  
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function replace($tableName, array $data)
    {
        $this->connect();

        // column names are specified as array keys
        $cols = array();
        $placeholders = array();
        
        foreach ($data as $columnName => $value) {
            $cols[] = $columnName;
            $placeholders[] = '?';
        }

        $query = 'REPLACE INTO ' . $tableName
               . ' (' . implode(', ', $cols) . ')'
               . ' VALUES (' . implode(', ', $placeholders) . ')';

        return $this->executeUpdate($query, array_values($data));
    }
}

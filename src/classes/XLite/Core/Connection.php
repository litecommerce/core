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
 * @subpackage Core
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Core;

/**
 * Doctrine-based connection 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Connection extends \Doctrine\DBAL\Connection
{
    /**
     * Executes an, optionally parameterized, SQL query.
     * If the query is parameterized, a prepared statement is used.
     * If an SQLLogger is configured, the execution is logged.
     * 
     * @param string $query  The SQL query to execute
     * @param array  $params The parameters to bind to the query, if any
     * @param array  $types  The parameters's types OPTIONAL
     *  
     * @return \Doctrine\DBAL\Driver\Statement
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function executeQuery($query, array $params = array(), $types = array())
    {
        $result = parent::executeQuery($query, $params, $types);

        if ($this->_config->getSQLLogger() !== null) {
            $this->_config->getSQLLogger()->setQueryTime($query, $params);
        }

        return $result;
    }

    /**
     * Executes an SQL INSERT/UPDATE/DELETE query with the given parameters
     * and returns the number of affected rows.
     * This method supports PDO binding types as well as DBAL mapping types.
     * 
     * @param string $query  The SQL query
     * @param array  $params The query parameters
     * @param array  $types  The parameter types
     *  
     * @return integer The number of affected rows
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function executeUpdate($query, array $params = array(), array $types = array())
    {
        $result = parent::executeUpdate($query, $params, $types);

        if ($this->_config->getSQLLogger() !== null) {
            $this->_config->getSQLLogger()->setQueryTime($query, $params);
        }

        return $result;
    }
}


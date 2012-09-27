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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Core;

/**
 * Statement
 *
 */
class Statement extends \Doctrine\DBAL\Statement
{
    /**
     * SQL query
     *
     * @var string
     */
    protected $_sql;

    /**
     * The bound parameters
     *
     * @var array
     */
    protected $_params = array();

    /**
     * The underlying driver statement
     *
     * @var \Doctrine\DBAL\Driver\Statement
     */
    protected $_stmt;

    /**
     * Executes the statement with the currently bound parameters
     *
     * @param array $params Parameters OPTIONAL
     *
     * @return boolean
     * @throws \XLite\Core\PDOException
     */
    public function execute($params = null)
    {
        try {
            $result = parent::execute($params);

        } catch (\PDOException $e) {
            $sql = $this->_sql;
            if (!$sql && is_object($this->_stmt) && $this->_stmt->queryString) {
                $sql = $this->_stmt->queryString;
            }

            throw new \XLite\Core\PDOException($e, $sql, $this->_params);
        }

        return $result;
    }
}

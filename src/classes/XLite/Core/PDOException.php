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
 * Extended PDO exception
 *
 */
class PDOException extends \PDOException
{
    /**
     * Constructor
     *
     * @param \PDOException $e      PDO exception
     * @param string        $query  SQL query OPTIONAL
     * @param array         $params SQL query parameters OPTIONAL
     *
     * @return void
     */
    public function __construct(\PDOException $e, $query = null, array $params = array())
    {
        $code = $e->getCode();
        $message = $e->getMessage();

        // Remove user credentials
        if (
            strstr($message, 'SQLSTATE[')
            && preg_match('/SQLSTATE\[(\w+)\] \[(\w+)\] (.*)/', $message, $matches)
        ) {
            $code = 'HT000' == $matches[1] ? $matches[2] : $matches[1];
            $message = $matches[3];
        }

        // Add additional information
        if ($query) {
            $message .= PHP_EOL . 'SQL query: ' . $query;
        }

        if ($params) {
            $message .= PHP_EOL . 'SQL query parameters: ' . var_export($params, true);
        }

        $this->code = intval($code);
        $this->message = $message;
    }
}

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
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

/**
 * Check DB indexes length
 */

define('MYSQL_USER', '');
define('MYSQL_PASSWORD', '');
define('MYSQL_DB', '');
define('INDEX_LENGTH_LIMIT', 1000);


mysql_connect('localhost', MYSQL_USER, MYSQL_PASSWORD);
mysql_select_db(MYSQL_DB);


$res = mysql_query('SHOW TABLES');

while($row = mysql_fetch_assoc($res)) {
    $table = array_shift($row);

    $indexes = array();
    $res2 = mysql_query('show index from ' . $table);
    while ($row = mysql_fetch_assoc($res2))  {
        $indexes[$row['Key_name']][] = $row['Column_name'];
    }

    $columns = array();
    $res2 = mysql_query('show columns from ' . $table);
    while ($row = mysql_fetch_assoc($res2))  {
        if (preg_match('/^(?:var)?char\((\d+)\)/Ss', $row['Type'], $match)) {
            $columns[$row['Field']] = $match[1];
        }
    }

    foreach ($indexes as $name => $rows) {
        $length = 0;
        foreach ($rows as $row) {
            if (isset($columns[$row])) {
                $length += $columns[$row];
            }
        }

        if ($length > INDEX_LENGTH_LIMIT) {
            var_dump($table, $name, $rows, $length);
        }
    }


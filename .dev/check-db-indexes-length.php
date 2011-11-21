#!/usr/bin/env php
<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Check DB indexes length
 */

$dir = dirname(__FILE__) . '/..';
$options = parse_ini_file($dir . '/src/etc/config.php', true);
$options = $options['database_details'];
if (file_exists($dir . '/src/etc/config.local.php')) {
    $tmp = parse_ini_file($dir . '/src/etc/config.local.php', true);
    if (isset($tmp['database_details'])) {
        $options = array_merge($options, $tmp['database_details']);
    }
}

define('MYSQL_USER', $options['username']);
define('MYSQL_PASSWORD', $options['password']);
define('MYSQL_DB', $options['database']);
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
    $res2 = mysql_query('show full columns from ' . $table);
    while ($row = mysql_fetch_assoc($res2))  {
        if (preg_match('/^(?:var)?char\((\d+)\)/Ss', $row['Type'], $match)) {
            $columns[$row['Field']] = $match[1];
            if (preg_match('/utf8/Ss', $row['Collation'])) {
                $columns[$row['Field']] = $columns[$row['Field']] * 3;
            }
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
            echo $table . ': ' . PHP_EOL
                . "\t" . $name . ': [' . implode(', ', $rows) . '] : ' .  $length . PHP_EOL;
        }
    }
}

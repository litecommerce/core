#!/usr/bin/env php
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
 * @since     1.0.19
 */

/**
 * ./amqp-publish.php queue_name json_encoded_array 
 */

define('PATH_SRC', __DIR__ . '/../src');
require_once PATH_SRC . '/top.inc.php';

if (PHP_SAPI != 'cli') {
    echo 'Only CLI!' . PHP_WOL;
    die(1);
}

if (!\XLite\Core\EventDriver\AMQP::isValid()) {
    echo 'Connection to AMPQ server failed' . PHP_EOL;
    die(3);
}

array_shift($_SERVER['argv']);
$queue = @array_shift($_SERVER['argv']);
$data = @array_shift($_SERVER['argv']);

if (!$queue) {
    echo 'Queue name is empty!' . PHP_EOL;
    die(2);
}

echo 'Publish \'' . $queue . '\' task ... ';
$driver = new \XLite\Core\EventDriver\AMQP;
$result = $driver->fire($queue, $data ? json_decode($data, true) : array());

echo ($result ? 'done' : 'failed') . PHP_EOL;
die(0);

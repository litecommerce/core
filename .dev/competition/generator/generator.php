<?php
// vim: set ts=4 sw=4 sts=4 et:
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
 * @package    Comparison
 * @subpackage Generator
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

if (php_sapi_name() != 'cli') {
    echo ('Server API must be CLI' . PHP_EOL);
    die(1);
}

if (2 > count($_SERVER['argv'])) {
    echo ('Arguments line is empty' . PHP_EOL);
    die(2);
}

define('DOT_PER_INTERATION', 10);
define('DOT_PER_ROW', 1000);

array_shift($_SERVER['argv']);
define('GENERATOR_ROOT', array_shift($_SERVER['argv']));

$path = __DIR__ . '/config.ini';

if (!file_exists($path)) {
    die('Configuration file is not found!' . PHP_EOL);
}

$config = parse_ini_file($path, true);
__g_config($config);

foreach ($config['paths'] as $name => $path) {
    chdir(GENERATOR_ROOT . '/' . $path);

    echo 'Start ' . $name . ' generator ...' . PHP_EOL;

    require_once __DIR__ . '/shops/' . $name . '.php';
}

die(0);

function __g_config($var = null)
{
    static $store = false;

    if ($var) {
        $store = $var;
    }

    return $store;
}

function __g_echo($string)
{
    echo $string;
    flush();
}

function __g_echo_title($string)
{
    __g_echo($string);
    __g_inc(true);
}

function __g_echo_done()
{
    __g_echo(' done.' . PHP_EOL);
}

function __g_dot($i)
{
    $i++;
    if ($i % DOT_PER_INTERATION == 0) {
        __g_echo('.');
        if ($i % DOT_PER_ROW == 0) {
            __g_echo(PHP_EOL);
        }
    }
}

function __g_inc($reset = false)
{
    static $i = null;

    if ($reset) {
        $i = 0;

    } else {
        $i = intval($i);
        $i++;
        __g_dot($i);
    }
}


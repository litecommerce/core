<?php
/*
 * This file is part of PHP Selenium Library.
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/vendor/Symfony/Component/ClassLoader/UniversalClassLoader.php';
require_once __DIR__.'/vendor/Symfony/Component/ClassLoader/DebugUniversalClassLoader.php';

$classLoader = new Symfony\Component\ClassLoader\DebugUniversalClassLoader();
$classLoader->register();

$classLoader->registerNamespace('Selenium', __DIR__.'/src');
$classLoader->registerNamespace('Symfony', __DIR__.'/vendor');
$classLoader->registerPrefix('PHPParser', __DIR__.'/vendor/php-parser/lib');

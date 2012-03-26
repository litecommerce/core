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
 * @since     1.0.18
 */

/**
 * Decorate class by file path
 */

require_once __DIR__ . '/core.php';

// get arguments
$path   = macro_get_plain_argument(0);
$author = macro_get_plain_argument(1);
$module = macro_get_plain_argument(2);

// Check arguments
macro_check_file_path($path);
macro_check_module($author, $module);

// Assemble decorate class name
$decoratedClass = macro_convert_path_to_class_name($path);

// Assemble target class name
$parts = explode('\\', $decoratedClass);
array_shift($parts);
$parts = array_values($parts);

if ('Module' == $parts[0]) {
    $parts = array_slice($parts, 3);
}

$targetClass = 'XLite\\Module\\' . $author . '\\' . $module . '\\' . implode('\\', $parts);

// Assemble target file path
$targetPath = macro_convert_class_name_to_path($targetClass);

// Get file content
$decoratedClassFull = '\\' . $decoratedClass;
$className = array_pop($parts);

$content = macro_get_class_repo_header($targetPath)
    . macro_get_class_header($path)
    . <<<CODE

abstract class $className extends $decoratedClassFull implements \XLite\Base\IDecorator
{
}

CODE;

// Write content
macro_file_put_contents($targetPath, $content);

echo $targetPath . ' create' . PHP_EOL;

die(0);

/**
 * Help
 */
function macro_help()
{
    return <<<HELP
Usage: decorate.php file_path module_author module_name

Example: .dev/macro/decorate.php src/classes/XLite.php Tester Test

As a result of the operation will create a file src/classes/XLite/Module/Tester/Test/XLite.php,
which will be decorated class \XLite.
HELP;
}


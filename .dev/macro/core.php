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

define('MACRO_START_DIR', getcwd());

require_once __DIR__ . '/../../src/top.inc.php';

if (PHP_SAPI != 'cli') {
    macro_error('Server API must be CLI!');
}

array_shift($_SERVER['argv']);

$options = getopt('h', array('help'));

if (isset($options['h']) || isset($options['help'])) {
    echo macro_help() . PHP_EOL;
    die(0);
}

/**
 * Functions 
 */

/**
 * Display error 
 * 
 * @param string $msg Error message
 *  
 * @return void
 * @see    ____func_see____
 * @since  1.0.18
 */
function macro_error($msg)
{
    echo 'Error: ' . $msg . PHP_EOL . PHP_EOL;
    echo macro_help() . PHP_EOL;
    die(1);
}

/**
 * Get sacript argument by index
 * 
 * @param integer $number Index
 *  
 * @return string
 * @see    ____func_see____
 * @since  1.0.18
 */
function macro_get_plain_argument($number)
{
    return isset($_SERVER['argv'][$number]) ? $_SERVER['argv'][$number] : null;
}

/**
 * Convert path to class name 
 * 
 * @param string $path Path
 *  
 * @return string
 * @see    ____func_see____
 * @since  1.0.18
 */
function macro_convert_path_to_class_name($path)
{
    return str_replace(DIRECTORY_SEPARATOR, '\\', substr($path, strlen(LC_DIR_CLASSES), -4));
}

/**
 * Convert class name to path 
 * 
 * @param string $class Class name
 *  
 * @return string
 * @see    ____func_see____
 * @since  1.0.18
 */
function macro_convert_class_name_to_path($class)
{
    return LC_DIR_CLASSES . str_replace('\\', DIRECTORY_SEPARATOR, ltrim($class, '\\')) . '.php';
}

/**
 * Safe write to file
 * 
 * @param string $path Path
 * @param string $data File content
 *  
 * @return void
 * @see    ____func_see____
 * @since  1.0.18
 */
function macro_file_put_contents($path, $data)
{
    if (!\Includes\Utils\FileManager::mkdirRecursive(dirname($path))) {
        macro_error('Directory \'' . $path . '\' write-protected!');
    }

    if (!@file_put_contents($path, $data)) {
        macro_error('File \'' . $path . '\' write-protected!');
    }
}

/**
 * Chec path - path is entity class or not
 * 
 * @param string $path path
 *  
 * @return boolean
 * @see    ____func_see____
 * @since  1.0.18
 */
function macro_is_entity($path)
{
    return preg_match('/XLite.Model.|XLite.Module.\w+.\w+.Model./Ss', $path);
}

// {{{ Arguments checkers

/**
 * Check file path 
 * 
 * @param string $path Path
 *  
 * @return void
 * @see    ____func_see____
 * @since  1.0.18
 */
function macro_check_file_path(&$path)
{
    if (!$path) {
        macro_error('\'file_path\' argument is empty!');

    } elseif (!file_exists($path)) {
        macro_error('Path \'' . $path . '\' not exists!');

    }

    $path = realpath($path);
}

/**
 * Check class repo file path 
 * 
 * @param string $path Path
 *  
 * @return void
 * @see    ____func_see____
 * @since  1.0.18
 */
function macro_check_class_file_path(&$path)
{
    macro_check_file_path($path);

    if (0 !== strcmp(LC_DIR_CLASSES, $path, strlen(LC_DIR_CLASSES))) {
        macro_error('Path \'' . $path . '\' is not LC class repository!!');
    }
}

/**
 * Check module name
 * 
 * @param string $author Author
 * @param string $module Name
 *  
 * @return void
 * @see    ____func_see____
 * @since  1.0.18
 */
function macro_check_module($author, $module)
{
    if (!$author) {
        macro_error('\'module_author\' argument is empty!');
    }

    if (!$module) {
        macro_error('\'module_name\' argument is empty!');
    }

}

// }}}

// {{{ Templates

/**
 * Get file header 
 * 
 * @param string $path Path
 *  
 * @return string
 * @see    ____func_see____
 * @since  1.0.18
 */
function macro_get_file_header($path)
{
    return <<<HEAD
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


HEAD;
}

/**
 * Get class file repo header 
 * 
 * @param string $path Path
 *  
 * @return string
 * @see    ____func_see____
 * @since  1.0.18
 */
function macro_get_class_repo_header($path)
{
    $ns = explode('\\', macro_convert_path_to_class_name($path));
    array_pop($ns);
    $ns = implode('\\', $ns);

    return macro_get_file_header($path)
        . <<<HEAD
namespace $ns;


HEAD;
}

/**
 * Get class header 
 * 
 * @param string $path Path
 *  
 * @return string
 * @see    ____func_see____
 * @since  1.0.18
 */
function macro_get_class_header($path)
{
    $class = macro_convert_path_to_class_name($path);
    $reflection = new \ReflectionClass($class);
    $header = $reflection->getDocComment();

    if (!$header) {
        $name = $reflection->getShortName();
        $header = <<<HEAD
/**
 * Abstract widget
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
HEAD;
    }

    if (macro_is_entity($path)) {
        $header = preg_replace('/@Index\s*\(.+\)/SsU', '', $header);
        $header = preg_replace('/@UniqueConstraint\s*\(.+\)/SsU', '', $header);
        $header = preg_replace('/@MappedSuperclass\s*\(.+\)/SsU', '', $header);
        $header = preg_replace('/@DiscriminatorMap\s*\(.+\)/SsU', '', $header);
        $header = preg_replace('/@DiscriminatorColumn\s*\(.+\)/SsU', '', $header);
        $header = preg_replace('/@InheritanceType\s*\(.+\)/SsU', '', $header);
        $header = preg_replace('/@HasLifecycleCallbacks/SsU', '', $header);
        $header = preg_replace('/@Table\s*\(.+\)/SsU', '', $header);
        $header = preg_replace('/@Entity\s*\(.+\)/SsU', '', $header);
        $header = preg_replace('/@Entity/SsU', '', $header);
    }

    $header = preg_replace('/@ListChild\s*\(.+\)/SsU', '', $header);

    $header = preg_replace('/( \*\s*.)+ \*\//SsU', ' */', $header);

    if (macro_is_entity($path)) {
        $header = preg_replace(
            '/( \*\/)/SsU',
            ' *' . PHP_EOL .' * @MappedSuperclass' . PHP_EOL . '$1',
            $header
        );
    }

    return $header;
}

// }}}


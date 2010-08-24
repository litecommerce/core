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
 * @subpackage Includes
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace Includes\Decorator\Utils\ClassData;

/**
 * Parser 
 * 
 * @package    XLite
 * @see        ____class_see____
 * @since      3.0.0
 */
abstract class Parser extends \Includes\Decorator\Utils\ClassData\AClassData
{
    /**
     * List of registered parsers 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $parsers = array('Main');


    /**
     * Compose the constant name and return its value
     * 
     * @param string $name short name
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getTokenName($name)
    {
        return constant('\Includes\Decorator\DataStructure\ClassData\Node::' . $name);
    }

    /**
     * Get pattern for the certain parser
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getPatternParserMain()
    {
        // Namespace (1)
        $pattern  = '(?:(?:namespace\s+)([\w\\\]+)\s*;.*)?';
        // Class accessability modifier (2)
        $pattern .= '((?:abstract|final)\s+)?';
        // Class or interface name (3,4)
        $pattern .= '(class|interface)\s+([\w\\\]+)';
        // Parent class name (5,6)
        $pattern .= '(\s+extends\s+([\w\\\]+))?';
        // Implemented interfaces (7,8)
        $pattern .= '(\s+implements\s+([\w\\\]+(?:\s*,\s*[\w\\\]+)*))?';
        // Whitespaces (10). TODO: must be improved
        $pattern .= '\s*(\/\*.*\*\/)?\s*{';

        return '/' . $pattern . '/USsi';
    }

    /**
     * Get schema for the data returned by certain parser
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getSchemaParserMain()
    {
        return array('NAME_SPACE' => 1, 'NAME' => 4, 'PARENT' => 6, 'INTERFACES' => 8);
    }

    /**
     * Postprocess parser results
     * 
     * @param array $data data returned by parser
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function postprocessParserMain(array $data)
    {
        $name = static::getTokenName('NAME');
        $namespace = static::getTokenName('NAME_SPACE');
        $interfaces = static::getTokenName('INTERFACES');

        // Add namespace
        if (isset($data[$namespace]) && '\\' !== substr($data[$name], 0, 1)) {
            $data[$name] = '\\' . $data[$namespace] . '\\' . $data[$name];
        }

        // Get implemented interfaces
        if (isset($data[$interfaces])) {
            $data[$interfaces] = explode(',', str_replace(' ', '', trim($data[$interfaces])));
        }

        return $data;
    }

    /**
     * Parse content
     * 
     * @param string $pattern  pattern to apply
     * @param string &$content content to parse
     *  
     * @return array|bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function applyPattern($pattern, &$content)
    {
        return preg_match($pattern, $content, $matches) ? $matches : false;
    }

    /**
     * Apply schema to the tokens 
     * 
     * @param array $tokens list of tokens returned by the "preg_match()"
     * @param array $schema tokens translation schema
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function applySchema(array $tokens, array $schema)
    {
        $data = array();

        foreach ($schema as $key => $index) {
            if (isset($tokens[$index])) {
                $data[static::getTokenName($key)] = $tokens[$index];
            }
        }

        return $data;
    }


    /**
     * Get file content and execute the parsers stack
     * 
     * @param \SplFileInfo $fileInfo file descriptor
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function parse(\SplFileInfo $fileInfo)
    {
        $data = array();
        $content = file_get_contents($path = $fileInfo->getPathname());

        foreach (static::$parsers as $method) {

            // Method names
            $method      = 'Parser' . $method;
            $pattern     = 'getPattern' . $method;
            $schema      = 'getSchema' . $method;
            $postprocess = 'postprocess' . $method;

            // Stop stack execution if parser returned "false"
            if (false === ($result = static::applyPattern(static::$pattern(), $content))) {
                break;
            }

            // Apply schema
            $result = static::applySchema($result, static::$schema());

            // Prepare and save data
            $data = array_replace_recursive($data, static::$postprocess($result));
        }

        if (!empty($data)) {
            $data += array(\Includes\Decorator\DataStructure\ClassData\Node::FILE_PATH => $path);
        }

        return $data;
    }
}

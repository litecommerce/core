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
     * Pattern to parse clas definition
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $pattern;

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
     * Safely get field from array
     * 
     * @param array  &$data data array
     * @param string $field field name
     *  
     * @return mixed
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getField(array &$data, $field)
    {
        return isset($data[$field]) ? $data[$field] : null;
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
        if (!isset(static::$pattern)) {

            // phpDocumenter comment
            static::$pattern  = '.*(?:\s*(\/\*+.*\*+\/))?\s*';
            // Namespace
            static::$pattern  = '(?:(?:namespace\s+)([\w\\\]+)\s*;' . static::$pattern . ')?';
            // Class accessability modifier
            static::$pattern .= '((?:abstract|final)\s+)?';
            // Class or interface name
            static::$pattern .= '(class|interface)\s+([\w\\\]+)';
            // Parent class name
            static::$pattern .= '(\s+extends\s+([\w\\\]+))?';
            // Implemented interfaces
            static::$pattern .= '(\s+implements\s+([\w\\\]+(?:\s*,\s*[\w\\\]+)*))?';
            // Whitespaces
            static::$pattern .= '\s*(\/\*.*\*\/)?\s*{';

            // Modifiers
            static::$pattern = '/' . static::$pattern . '/USsi';
        }

        return static::$pattern;
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
        return array(
            self::N_NAME_SPACE    => 1,
            self::N_CLASS_COMMENT => 2,
            self::N_CLASS         => 5,
            self::N_PARENT_CLASS  => 7,
            self::N_INTERFACES    => 9,
        );
    }

    /**
     * Prepare data for the "getTags()" method
     * 
     * @param array $matches data to prepare
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function prepareTags(array $matches)
    {
        // Invoke plugins
        \Includes\Decorator\Utils\PluginManager::invokeHook('prepareTags', array(&$matches));

        return array_combine(array_map('strtolower', $matches[1]), $matches[2]);
    }

    /**
     * Return the "@\w+" tokens (tags)
     * 
     * @param string $comment class comment
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getTags($comment)
    {
        return preg_match_all('/@(\w+)\s*(?:\()?(.*?)\s*(?:\))?(?=$|@\w+)/Smi', $comment, $matches) 
            ? static::prepareTags($matches) 
            : array();
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
        $namespace  = static::getField($data, self::N_NAME_SPACE);
        $class      = static::getField($data, self::N_CLASS);
        $interfaces = static::getField($data, self::N_INTERFACES);
        $filePath   = static::getField($data, self::N_FILE_PATH);

        // Add namespace
        if ($namespace && '\\' !== substr($class, 0, 1)) {
            $data[self::N_CLASS] = '\\' . $namespace . '\\' . $class;
        }

        // Get implemented interfaces
        $data[self::N_INTERFACES] = $interfaces ? explode(',', str_replace(' ', '', trim($interfaces))) : array();

        // Get phpDocumenter tags
        $data[self::N_TAGS] = static::getTags(static::getField($data, self::N_CLASS_COMMENT));

        // Save relative path
        $data[self::N_FILE_PATH] = \Includes\Utils\FileManager::getRelativePath($filePath, LC_CLASSES_DIR);

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
                $data[$key] = $tokens[$index];
            }
        }

        return $data;
    }

    /**
     * Check if the corresponded module is active
     * 
     * @param array $data data returned by parser
     *  
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function isModuleEnabled(array &$data)
    {
        return \Includes\Decorator\Utils\ModulesManager::checkClass(
            static::getField($data, self::N_CLASS),
            static::getField($data, self::N_PARENT_CLASS)
        );
    }


    /**
     * Parse value of a phpDocumenter tag
     * 
     * @param string $value value to parse
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function parseTagValue($value)
    {
        return \Includes\Utils\Converter::parseQuery($value, '=', ',', '"\'');
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

            // Stop stack execution if current parser returned "false"
            if (false === ($result = static::applyPattern(static::$pattern(), $content))) {
                break;
            }

            // Apply schema
            $result = static::applySchema($result, static::$schema());

            // Prepare and save data
            $data = array_replace_recursive($data, static::$postprocess($result + array(self::N_FILE_PATH => $path)));
        }

        return (empty($data) || !static::isModuleEnabled($data)) ? null : $data;
    }
}

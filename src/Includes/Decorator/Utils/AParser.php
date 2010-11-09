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
 * @subpackage Includes_Decorator_Utils
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace Includes\Decorator\Utils;

/**
 * AParser
 *
 * TODO: simplify code 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class AParser extends \Includes\Decorator\ADecorator
{
    /**
     * Indexes in the $parsers array
     */

    const PARSER_PATTERN        = 'pattern';
    const PARSER_SCHEMA         = 'schema';
    const PARSER_POSTPROCESSORS = 'postprocessors';
    const PARSER_BEHAVIOR       = 'behavior';


    /**
     * Parser behavior on fail: stop stack execution, continue etc.
     */

    const BEHAVIOR_STOP      = 1;
    const BEHAVIOR_CONTIONUE = 2;


    /**
     * List of parsers 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $parsers = array();


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
     * Parse content
     *
     * NOTE: we pass the large varible "$content" as reference
     * due to prevent it's coping (to increase the pecfomance)
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
     * Perform a ppostprocessing method (if exists) 
     * 
     * @param array $method postprocessor callback
     * @param array $data   data to process
     *  
     * @return array|null
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function postprocess(array $callback, array $data)
    {
        return call_user_func_array($callback, array($data));
    }

    /**
     * Parse value of a phpDocumenter tag
     *
     * @param string $value value to parse
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function parseTagValue($value)
    {
        return \Includes\Utils\Converter::parseQuery($value, '=', ',', '"\'');
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
        // There are so called "multiple" tags
        foreach (array_unique($matches[1]) as $tag) {

            // Check if tag is defined only once
            if (1 < count($keys = array_keys($matches[1], $tag))) {

                $list = array();
    
                // Convert such tag values into the single array
                foreach ($keys as $key) {

                    // Parse list of tag attributes and their values
                    $list[] = static::parseTagValue($matches[2][$key]);

                    // To prevent duplicates
                    unset($matches[1][$key], $matches[2][$key]);
                }
            
                // Add tag name and its values to the enf of tags list.
                // All existing entries for this tag was cleared by the "unset()"
                $matches[1][] = $tag;
                $matches[2][] = $list;

            // If the value was parsed (the corresponded tokens were found), change its type to the "array"
            // TODO: check if there is a more convenient approach to manage "multiple" tags
            } elseif ($matches[2][$key = array_shift($keys)] !== ($value = static::parseTagValue($matches[2][$key]))) {

                $matches[2][$key] = array($value);
            }
        }

        // Create an associative array of tag names and their values
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
        return preg_match_all('/@(\w+)\s*(?:\()?(.*?)\s*(?:\))?(?=$|^.*@\w+)/Smi', $comment, $matches)
            ? static::prepareTags($matches)
            : array();
    }

    /**
     * Postprocess parser results
     *
     * @param array  $data  data returned by parser
     * @param string $field field to parse
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function postprocessParserTags(array $data, $field = self::N_CLASS_COMMENT)
    {
        return array(self::N_TAGS => static::getTags(static::getField($data, $field))) + $data;
    }


    /**
     * Register or modify parser 
     * 
     * @param string $name          parser name (new or existing)
     * @param string $pattern       parser pattern
     * @param array  $schema        parser schema
     * @param array  $postprocessor postprocessor callback
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function registerParser(
        $name,
        $pattern,
        array $schema = null,
        array $postprocessor = null,
        $behavior = self::BEHAVIOR_STOP
    ) {

        if (!isset(static::$parsers[$name])) {

            static::$parsers[$name] = array(
                self::PARSER_PATTERN        => '',
                self::PARSER_SCHEMA         => array(),
                self::PARSER_POSTPROCESSORS => array(),
            );
        }

        if (isset($pattern)) {
            static::$parsers[$name][self::PARSER_PATTERN] = $pattern;
        }

        if (isset($schema)) {
            static::$parsers[$name][self::PARSER_SCHEMA] = $schema + static::$parsers[$name][self::PARSER_SCHEMA];
        }

        if (isset($postprocessor)) {
            static::$parsers[$name][self::PARSER_POSTPROCESSORS][] = $postprocessor;
        }

        static::$parsers[$name][self::PARSER_BEHAVIOR] = $behavior;
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

        // There is the stack of defined parsers. Execute them one by one
        foreach (static::$parsers as $name => $parser) {

            // Stop stack execution if current parser returned "false"
            if (false === ($result = static::applyPattern($parser[self::PARSER_PATTERN], $content))) {

                // Check the parser behavior flag and stop stack execution (if needed)
                if (self::BEHAVIOR_STOP == $parser[self::PARSER_BEHAVIOR]) {
                    unset($data);
                    break;
                }
            }

            // Apply schema
            $result = static::applySchema($result, $parser[self::PARSER_SCHEMA]) + array(self::N_FILE_PATH => $path);

            // Prepare and save data
            foreach ($parser[self::PARSER_POSTPROCESSORS] as $callback) {
                $data = array_replace_recursive($data, $result = static::postprocess($callback, $result) ?: $result);
            }
        }

        return empty($data) ? null : $data;
    }
}

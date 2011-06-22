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

namespace Includes\Decorator\Utils;

/**
 * Tokenizer
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class Tokenizer extends \Includes\Decorator\Utils\AUtils
{
    /**
     * Current (working) path
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected static $path;

    /**
     * List of tokens (hash)
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected static $tokens;

    /**
     * List of "public" methods
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected static $publicMethods = array(
        'getFullClassName',
        'getParentClassName',
        'getInterfaces',
        'getDockBlock',
        'getFlag',
        'hasMethod',
        'getClassName',
        'getInterfaceName',
        'getNamespace',
        'getSourceCode',
        'addCodeToClassBody',
    );


    // {{{ Common access method

    /**
     * "Magic" method to access some class method
     * 
     * @param string $method Method to call
     * @param array  $args   Call arguments OPTIONAL
     *  
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function __callStatic($method, array $args = array())
    {
        if (!in_array($method, static::$publicMethods)) {
            \Includes\ErrorHandler::fireError(__CLASS__ . ': unallowed method "' . $method . '"');
        }

        if (!($path = array_shift($args))) {
            \Includes\ErrorHandler::fireError(__METHOD__ . ': path is not passed');
        }

        // Prepare tokens
        static::reset($path);

        return call_user_func_array(array('static', $method), $args);
    }

    // }}}

    // {{{ Methods to get class-related tokens

    /**
     * Search for class declaration and return full class name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getFullClassName()
    {
        $result = static::getClassName() ?: static::getInterfaceName();

        if ($result && ($namespace = static::getNamespace())) {
            $result = $namespace . '\\' . $result;
        }

        return $result;
    }

    /**
     * Get parent class name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getParentClassName()
    {
        return static::getClassRelatedValue(T_EXTENDS);
    }

    /**
     * Return list of implemented interfaces
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getInterfaces()
    {
        $tokens = array();

        list(, $start, ) = static::getClassRelatedTokens(T_IMPLEMENTS);
        list(, $end, )   = static::findTokensByValueFromOffset(array('{'), T_IMPLEMENTS);

        if (isset($start)) {
            $tokens = array_filter(
                array_slice(static::$tokens, $start, $end - $start),
                function ($token) { return T_WHITESPACE !== $token[0]; }
            );
            $tokens = explode(',', static::composeTokens($tokens));
        }

        return $tokens;
    }

    /**
     * Check for a certain token
     *
     * @param integer $token Token index
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getFlag($token)
    {
        list(, $start, ) = static::findTokensByIndexFromOffset(array(T_CLASS), $token);

        return isset($start);
    }

    /**
     * Return class DocBlock
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getDockBlock()
    {
        return ($index = static::getDocBlockIndex()) ? static::$tokens[$index][1] : null;
    }

    /**
     * Check if method is declared in class
     * 
     * @param string $method Method to search
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function hasMethod($method)
    {
        $result = false;

        foreach (static::$tokens as $index => &$token) {
            if (
                T_STRING === $token[0] 
                && $method === $token[1] 
                && T_WHITESPACE === static::$tokens[$index - 1][0] 
                && T_FUNCTION === static::$tokens[$index - 2][0]
            ) {
                $result = true;
                break;
            }
        }

        return $result;
    }

    /**
     * Get class name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getClassName()
    {
        return static::getClassRelatedValue(T_CLASS);
    }

    /**
     * Get inteface name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getInterfaceName()
    {
        return static::getClassRelatedValue(T_INTERFACE);
    }

    /**
     * Get namespace
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getNamespace()
    {
        return static::getClassRelatedValue(T_NAMESPACE);
    }

    /**
     * Search for the DocBlock
     * 
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getDocBlockIndex()
    {
        $result = null;

        // Search for class or interface definition
        foreach (array(T_CLASS, T_INTERFACE) as $index) {
            list(, $start, ) = static::getClassRelatedTokens($index);
            if ($start) {
                break;
            }
        }

        // If class declaration found
        if (isset($start)) {

            // Search backward for class docblock
            for (; $start >= 0; $start--) {
                if (T_DOC_COMMENT === static::$tokens[$start][0]) {
                    $result = $start;
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * Common method to search class-related tokens
     *
     * @param integer $token Token index
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getClassRelatedValue($token)
    {
        list($tokens, , ) = static::getClassRelatedTokens($token);

        return static::prepareClassRelatedValue(static::composeTokens($tokens));
    }

    /**
     * Common method to prepare class-related tokens
     *
     * @param string $value Value to prepare
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function prepareClassRelatedValue($value)
    {
        return \Includes\Utils\Converter::trimLeadingChars($value, '\\');
    }

    // }}}

    // {{{ Methods to modify source code

    /**
     * Compose and return sourec code by tokens list
     *
     * @param string $namespace New namespace
     * @param string $class     New class name
     * @param string $parent    New parent class
     * @param string $dockblock New dockblock OPTIONAL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getSourceCode($namespace, $class, $parent, $dockblock = null)
    {
        // Class has been moved to a new location
        if (isset($namespace)) {
            static::replaceNamespace($namespace);
        }

        // Node class has been changed
        if (isset($class)) {
            static::replaceClassName($class);
        }

        // Parent class may be changed if class node has been "replanted" in classes tree
        if (isset($parent)) {
            static::replaceParentClassName($parent);
        }

        // Needed for some Doctrine plugins
        if (isset($dockblock)) {
            static::replaceDockblock($dockblock);
        }

        return static::composeTokens(static::$tokens);
    }

    /**
     * Set new namespace in the tokens list
     *
     * @param string $token Namespace to set
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function replaceNamespace($token)
    {
        static::replaceClassRelatedToken(T_NAMESPACE, $token);
    }

    /**
     * Set new class name in the tokens list
     *
     * @param string $token Class name to set
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function replaceClassName($token)
    {
        static::replaceClassRelatedToken(T_CLASS, $token);
    }

    /**
     * Set new parent class name in the tokens list
     *
     * @param string $token Class name to set
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function replaceParentClassName($token)
    {
        static::replaceClassRelatedToken(T_EXTENDS, $token);
    }

    /**
     * Set new dockblock in the tokens list
     *
     * @param string $token Dockblock to set
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function replaceDockblock($token)
    {
        if ($index = static::getDocBlockIndex()) {
            static::$tokens[$index][1] = $token;
        }
    }

    /**
     * Common method to replace declarations for classes, interfaces and namespaces
     *
     * @param integer $type  Token to search
     * @param string  $token Replacement
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function replaceClassRelatedToken($type, $token)
    {
        // Search for a class-related token
        list(, $start, $end) = static::getClassRelatedTokens($type);

        // Replace part of the tokens list
        if (isset($start, $end) && $end > $start) {

            // It's the hack to transform strings like '\XLite\Core\Request'
            // into arrays like array('\', 'XLite', '\', 'Core', '\', 'Request')
            static::replaceTokens(
                $start,
                $end,
                static::prepareTokens(explode('#', str_replace('\\', '#\#', $token)), T_STRING)
            );
        }
    }

    // }}}

    // {{{ Methods to modify code-related tokens

    /**
     * Add portion of code to the class source (to the end)
     *
     * @param string $code Code to add
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function addCodeToClassBody($code)
    {
        // Search position of the class end
        list(, , $start) = static::getBodyTokenData(T_CLASS);

        // Add new code
        if (0 < $start) {
            static::replaceTokens($start, $start, static::prepareTokens(array($code)));
        }

        return static::composeTokens(static::$tokens);
    }

    /**
     * Common method to get a strings from tokens list
     *
     * NOTE: for future use
     *
     * @param integer $type Token to search
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getBodyTokenData($type)
    {
        list (, , $start) = static::findTokensByValueFromOffset(array('{'), $type);

        $counter = 1;
        $count   = count(static::$tokens);
        $tokens  = array();

        for ($end = $start; $count > $end && 0 < $counter; $end++) {
            $value = static::$tokens[$end][1];
            $counter += '{' === $value ? 1 : ('}' === $value ? -1 : 0);
        }

        return array($tokens, $start, $end - 1);
    }

    // }}}

    // {{{ Auxiliary methods

    /**
     * Reset tokenizer state
     *
     * @param string $path New file path
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function reset($path)
    {
        if ($path !== static::$path) {
            static::$path = $path;
            static::$tokens = static::prepareTokens(
                token_get_all(\Includes\Utils\FileManager::read($path, LC_DEVELOPER_MODE))
            );
        }
    }

    /**
     * Unify tokens list
     *
     * @param array $tokens Tokens to prepare
     * @param array $index  Default token index
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function prepareTokens(array $tokens, $index = null)
    {
        foreach ($tokens as &$token) {
            if (is_string($token)) {
                $token = array($index, $token);
            }
        }

        return $tokens;
    }

    /**
     * Get string by tokens array
     *
     * @param array &$tokens List of tokens to use
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function composeTokens(array &$tokens)
    {
        $result = '';

        foreach ($tokens as &$token) {
            $result .= $token[1];
        }

        return $result;
    }

    /**
     * Replace part in the tokens list
     *
     * @param integer $start  Start position in th tokens list
     * @param integer $end    End position in th tokens list
     * @param array   $tokens Replacement
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function replaceTokens($start, $end, array $tokens)
    {
        array_splice(static::$tokens, $start, $end - $start, $tokens);
    }

    /**
     * Common method to search class-related tokens
     *
     * @param integer $token Token index
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getClassRelatedTokens($token)
    {
        return static::findTokensByIndexFromOffset(array(T_STRING, T_NS_SEPARATOR), $token);
    }

    /**
     * Search for certain tokens from position of some other one
     *
     * @param array   $index  List of token indexes to search
     * @param integer $offset Index of the "offset" token
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function findTokensByIndexFromOffset(array $index, $offset)
    {
        return static::findTokensFromOffset($index, $offset, 0);
    }

    /**
     * Search for certain tokens from position of some other one
     *
     * @param array   $values List of token values to search
     * @param integer $offset Index of the "offset" token
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function findTokensByValueFromOffset(array $values, $offset)
    {
        return static::findTokensFromOffset($values, $offset, 1);
    }

    /**
     * Search for certain tokens from position of some other one
     *
     * :WARNING: do not modify this method until you really know what you're doing
     *
     * @param array   $data   List of token indexes/values to search
     * @param integer $offset Index of the "offset" token
     * @param integer $index  Field index in the "token" array
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function findTokensFromOffset(array $data, $offset, $index)
    {
        $tokens = array();
        $start  = $end = null;

        foreach (static::$tokens as &$token) {

            if ($token[0] == $offset) {

                while (empty($found) && (list($key, $result) = each(static::$tokens))) {
                    $found = in_array($result[$index], $data);
                }

                if (!empty($found)) {

                    $start = $key;

                    do {
                        $tokens[] = $result;
                    } while ((list($end, $result) = each(static::$tokens)) && in_array($result[$index], $data));
                }

                break;
            }
        }

        return array($tokens, $start, $end);
    }

    // }}}
}

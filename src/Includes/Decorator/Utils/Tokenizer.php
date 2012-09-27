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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace Includes\Decorator\Utils;

/**
 * Tokenizer
 *
 */
abstract class Tokenizer extends \Includes\Decorator\Utils\AUtils
{
    /**
     * Current (working) path
     *
     * @var string
     */
    protected static $path;

    /**
     * List of tokens (hash)
     *
     * @var array
     */
    protected static $tokens;

    /**
     * Tokens count
     *
     * @var integer
     */
    protected static $count = 0;

    /**
     * decoratorFlag 
     * 
     * @var boolean
     */
    protected static $decoratorFlag;

    /**
     * List of "public" methods
     * 
     * @var array
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

    /**
     * Flag
     *
     * @var boolean
     */
    protected static $isPrepared = false;

    // {{{ Common access method

    /**
     * "Magic" method to access some class method
     * 
     * @param string $method Method to call
     * @param array  $args   Call arguments OPTIONAL
     *  
     * @return mixed
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
        static::reset($path, !LC_DEVELOPER_MODE || 'getFullClassName' !== $method);

        return call_user_func_array(array('static', $method), $args);
    }

    /**
     * getDecoratorFlag 
     * 
     * @return boolean
     */
    public static function getDecoratorFlag()
    {
        if (!isset(static::$decoratorFlag)) {
            static::$decoratorFlag = \Includes\Utils\ConfigParser::getOptions(array('decorator', 'use_tokenizer'));
        }

        return static::$decoratorFlag;
    }

    // }}}

    // {{{ Methods to get class-related tokens

    /**
     * Search for class declaration and return full class name
     *
     * @return string
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
     */
    protected static function getParentClassName()
    {
        return static::getClassRelatedValue(T_EXTENDS);
    }

    /**
     * Return list of implemented interfaces
     * 
     * @return array
     */
    protected static function getInterfaces()
    {
        $tokens = array();

        list(, $start, ) = static::getClassRelatedTokens(T_IMPLEMENTS);
        list(, $end, )   = static::findTokensByValueFromOffset(array('{'), T_IMPLEMENTS);

        if (isset($start)) {
            $tokens = array_filter(
                array_slice(static::$tokens, $start, $end - $start),
                function ($token) {
                    return T_WHITESPACE !== $token[0];
                }
            );
            $tokens = explode(',', static::composeTokens($tokens));
        }

        return $tokens;
    }

    /**
     * Check for certain class type
     *
     * @param integer $token Token index
     *
     * @return boolean
     */
    protected static function getFlag($token)
    {
        list(, $start, ) = static::findTokensByIndexFromOffset(array($token), T_CLASS, false);

        return isset($start);
    }

    /**
     * Return class DocBlock
     * 
     * @return string
     */
    protected static function getDockBlock()
    {
        list($tokens, ,) = static::findTokensByIndexFromOffset(array(T_DOC_COMMENT), T_CLASS, false);

        return empty($tokens) ? null : static::composeTokens($tokens);
    }

    /**
     * Check if method is declared in class
     * 
     * @param string $method Method to search
     *  
     * @return boolean
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
     */
    protected static function getClassName()
    {
        return static::getClassRelatedValue(T_CLASS);
    }

    /**
     * Get inteface name
     *
     * @return string
     */
    protected static function getInterfaceName()
    {
        return static::getClassRelatedValue(T_INTERFACE);
    }

    /**
     * Get namespace
     *
     * @return string
     */
    protected static function getNamespace()
    {
        return static::getClassRelatedValue(T_NAMESPACE);
    }

    /**
     * Common method to search class-related tokens
     *
     * @param integer $token Token index
     * @param boolean $isInc Flag OPTIONAL
     *
     * @return string
     */
    protected static function getClassRelatedValue($token, $isInc = true)
    {
        list($tokens, , ) = static::getClassRelatedTokens($token, $isInc);

        return static::prepareClassRelatedValue(static::composeTokens($tokens));
    }

    /**
     * Common method to prepare class-related tokens
     *
     * @param string $value Value to prepare
     *
     * @return string
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
     * @param string $prefix    New prefix {abstract|final} OPTIONAL
     *
     * @return string
     */
    protected static function getSourceCode($namespace, $class, $parent, $dockblock = null, $prefix = null)
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

        // To make abstract base classes in Decorator chains
        if (isset($prefix)) {
            static::replaceClassType($prefix);
        }

        return static::composeTokens(static::$tokens);
    }

    /**
     * Set new namespace in the tokens list
     *
     * @param string $token Namespace to set
     *
     * @return void
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
     */
    protected static function replaceDockblock($token)
    {
        list(, $start, $end) = static::findTokensByIndexFromOffset(array(T_DOC_COMMENT), T_CLASS, false);

        if (isset($start)) {
            static::replaceTokens($start, $end, static::prepareTokens(array($token)));
        }
    }

    /**
     * Replace class type
     *
     * @param string $token Class type to set
     *
     * @return void
     */
    protected static function replaceClassType($token)
    {
        list(, $start, ) = static::findTokensByIndexFromOffset(array(T_ABSTRACT, T_FINAL), T_CLASS, false);

        if (!isset($start)) {
            list(, , $start) = static::findTokensByIndexFromOffset(array(T_WHITESPACE), T_CLASS, false);

            if (isset($start)) {
                static::replaceTokens($start, $start, static::prepareTokens(array($token, ' ')));
            }
        }
    }

    /**
     * Common method to replace declarations for classes, interfaces and namespaces
     *
     * @param integer $type  Token to search
     * @param string  $token Replacement
     * @param boolean $isInc Flag OPTIONAL
     *
     * @return void
     */
    protected static function replaceClassRelatedToken($type, $token, $isInc = true)
    {
        // Search for a class-related token
        list(, $start, $end) = static::getClassRelatedTokens($type, $isInc);

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
     * @param string  $path    New file path
     * @param boolean $prepare Flag OPTIONAL
     *
     * @return void
     */
    protected static function reset($path, $prepare = true)
    {
        if ($path !== static::$path) {
            static::$tokens = token_get_all(\Includes\Utils\FileManager::read($path, LC_DEVELOPER_MODE));
            static::$count  = count(static::$tokens);

            static::$isPrepared = false;
        }

        if ($prepare && !static::$isPrepared) {
            static::$tokens = static::prepareTokens(static::$tokens);

            static::$isPrepared = true;
        }

        static::$path = $path;
    }

    /**
     * Unify tokens list
     *
     * @param array $tokens Tokens to prepare
     * @param array $index  Default token index
     *
     * @return array
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
     */
    protected static function replaceTokens($start, $end, array $tokens)
    {
        array_splice(static::$tokens, $start, $end - $start, $tokens);
    }

    /**
     * Common method to search class-related tokens
     *
     * @param integer $token Token index
     * @param boolean $isInc Flag OPTIONAL
     *
     * @return array
     */
    protected static function getClassRelatedTokens($token, $isInc = true)
    {
        return static::findTokensByIndexFromOffset(array(T_STRING, T_NS_SEPARATOR), $token, $isInc);
    }

    /**
     * Search for certain tokens from position of some other one
     *
     * @param array   $index  List of token indexes to search
     * @param integer $offset Index of the "offset" token
     * @param boolean $isInc  Flag OPTIONAL
     *
     * @return array
     */
    protected static function findTokensByIndexFromOffset(array $index, $offset, $isInc = true)
    {
        return static::findTokensFromOffset($index, $offset, 0, $isInc);
    }

    /**
     * Search for certain tokens from position of some other one
     *
     * @param array   $values List of token values to search
     * @param integer $offset Index of the "offset" token
     * @param boolean $isInc  Flag OPTIONAL
     *
     * @return array
     */
    protected static function findTokensByValueFromOffset(array $values, $offset, $isInc = true)
    {
        return static::findTokensFromOffset($values, $offset, 1, $isInc);
    }

    /**
     * Search for certain tokens from position of some other one
     *
     * DO NOT modify this method until you really know what you're doing
     *
     * @param array   $data   List of token indexes/values to search
     * @param integer $offset Index of the "offset" token
     * @param integer $index  Field index in the "token" array
     * @param boolean $isInc  Flag OPTIONAL
     *
     * @return array
     */
    protected static function findTokensFromOffset(array $data, $offset, $index, $isInc = true)
    {
        $tokens = array();
        $start  = $end = null;

        foreach (static::$tokens as $pos => &$token) {

            if ($token[0] == $offset) {

                for ( ; $pos < static::$count && 0 <= $pos; $isInc ? ++$pos : --$pos) {

                    if (in_array(static::$tokens[$pos][$index], $data)) {
                        $tokens[] = static::$tokens[$pos];
                        break;
                    }
                }

                if (!empty($tokens)) {
                    $start = $pos;

                    for ($end = ++$pos; $end < static::$count && in_array(static::$tokens[$end][$index], $data); $end++) {
                        $tokens[] = static::$tokens[$end];
                    }
                }

                break;
            }
        }

        return array($tokens, $start, $end);
    }

    // }}}
}

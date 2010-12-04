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

namespace Includes\Decorator\Utils;

/**
 * Parser 
 * 
 * @package    XLite
 * @see        ____class_see____
 * @since      3.0.0
 */
abstract class Parser extends \Includes\Decorator\Utils\Base\Parser
{
    /**
     * List of parsers
     *
     * NOTE: do not remove this (re)declaration:
     * it's needed for the correct work of the PHP late static binding
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $parsers = array();


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
        // phpDocumenter comment
        $pattern  = '.*(?:\s*(\/\*+.*\*+\/))?\s*';
        // Namespace
        $pattern  = '(?:(?:namespace\s+)([\w\\\]+)\s*;' . $pattern . ')?';
        // Class accessability modifier
        $pattern .= '((?:abstract|final)\s+)?';
        // Class or interface name
        $pattern .= '(class|interface)\s+([\w\\\]+)';
        // Parent class name
        $pattern .= '(\s+extends\s+([\w\\\]+))?';
        // Implemented interfaces
        $pattern .= '(\s+implements\s+([\w\\\]+(?:\s*,\s*[\w\\\]+)*))?';
        // Whitespaces
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
        return array(
            self::N_NAMESPACE     => 1,
            self::N_CLASS_COMMENT => 2,
            self::N_CLASS         => 5,
            self::N_PARENT_CLASS  => 7,
            self::N_INTERFACES    => 9,
        );
    }


    /**
     * Postprocess parser results
     * 
     * @param array $data data returned by parser
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function postprocessParserMain(array $data)
    {
        $namespace  = static::getField($data, self::N_NAMESPACE);
        $class      = static::getField($data, self::N_CLASS);
        $parent     = static::getField($data, self::N_PARENT_CLASS);
        $interfaces = static::getField($data, self::N_INTERFACES);
        $filePath   = static::getField($data, self::N_FILE_PATH);

        // Add namespace
        if ($namespace && '\\' !== substr($class, 0, 1)) {
            $data[self::N_CLASS] = '\\' . $namespace . '\\' . $class;
        }

        // Prepare for the "MultipleInheritance" plugin
        $data[self::N_PARENT_CLASS] = (array) $parent;

        // Get implemented interfaces
        $data[self::N_INTERFACES] = $interfaces ? explode(',', str_replace(' ', '', trim($interfaces))) : array();

        // Save relative path
        $data[self::N_FILE_PATH] = \Includes\Utils\FileManager::getRelativePath($filePath, LC_CLASSES_DIR);

        return $data;
    }

    /**
     * Static constructor
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function __constructStatic()
    {
        // Parse content
        static::registerParser(
            'Main',
            static::getPatternParserMain(),
            static::getSchemaParserMain(),
            array('static', 'postprocessParserMain')
        );

        // Prepare tags
        static::registerParser(
            'Main',
            null,
            null,
            array('static', 'postprocessParserTags')
        );
    }
}

\Includes\Decorator\Utils\Parser::__constructStatic();

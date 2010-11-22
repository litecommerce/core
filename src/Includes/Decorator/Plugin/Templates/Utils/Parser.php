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

namespace Includes\Decorator\Plugin\Templates\Utils;

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
     * Alias
     */
    const N_TEMPLATE_COMMENT = self::N_CLASS_COMMENT;


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
     * Define main pattern
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getPatternParserMain()
    {
        return '/\{\*(?:[^\*]|(?:\*+[^\*\}]))*@\s*'
            . (LC_DEVELOPER_MODE ? \Includes\Decorator\Plugin\Templates\ATemplates::TAG_LIST_CHILD : '\w+')
            . '\s*.*(?:[^\*]|(?:\*+[^\*\}]))*\*+\}/USsi';
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
            self::N_TEMPLATE_COMMENT => 0,
        );
    }

    
    /**
     * So called static constructor
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function __constructStatic()
    {
        static::registerParser(
            'Main',
            static::getPatternParserMain(),
            static::getSchemaParserMain(),
            array('static', 'postprocessParserTags')
        );
    }
}

// Call the static constructor
\Includes\Decorator\Plugin\Templates\Utils\Parser::__constructStatic();

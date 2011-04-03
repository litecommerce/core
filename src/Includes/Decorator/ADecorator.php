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
 * @subpackage Decorator
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    GIT: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace Includes\Decorator;

/**
 * ADecorator 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class ADecorator
{
    /**
     * Cache building steps
     */

    const STEP_FIRST  = 'first';
    const STEP_SECOND = 'second';
    const STEP_THIRD  = 'third';


    /**
     * Current step
     *
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $step;

    /**
     * Classes tree
     *
     * @var    \Includes\Decorator\DataStructure\Graph\Classes
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $classesTree;

    /**
     * Modules graph
     * 
     * @var    \Includes\Decorator\DataStructure\Graph\Modules
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $modulesGraph;


    /**
     * Return classes repository path 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getClassesDir()
    {
        return (self::STEP_FIRST === static::$step) ? LC_CLASSES_DIR : LC_CLASSES_CACHE_DIR;
    }

    /**
     * Return classes tree
     * 
     * @return \Includes\Decorator\DataStructure\Graph\Classes
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getClassesTree()
    {
        if (!isset(static::$classesTree)) {
            static::$classesTree = \Includes\Decorator\Utils\Operator::createClassesTree();
        }

        return static::$classesTree;
    }

    /**
     * Return modules graph
     * 
     * @return \Includes\Decorator\DataStructure\Graph\Modules
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getModulesGraph()
    {
        if (!isset(static::$modulesGraph)) {
            static::$modulesGraph = \Includes\Decorator\Utils\Operator::createModulesGraph();
        }

        return static::$modulesGraph;
    }
}

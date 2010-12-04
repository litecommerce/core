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
 * DataCollector 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class DataCollector extends \Includes\Decorator\Utils\AUtils
{
    /**
     * Return path to scan for PHP class files
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getClassesPath()
    {
        return LC_CLASSES_DIR;
    }

    /**
     * Return pattern to "preg_match()" files for class definition(s)
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getClassPattern()
    {
        return \Includes\Decorator\Utils\ModulesManager::getPathPatternForPHP();
    }

    /**
     * Get iterator for class files
     *
     * @return \Includes\Utils\FileFilter
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getFileIterator()
    {
        return new \Includes\Utils\FileFilter(static::getClassesPath(), static::getClassPattern());
    }

    /**
     * Generate classes tree (graph in common case)
     *
     * NOTE: do NOT call this function directly. Use wrapper in ADecorator instead
     * 
     * @return \Includes\DataStructure\Hierarchical\Graph
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function createClassesTree()
    {
        $tree = new \Includes\Decorator\DataStructure\Hierarchical\ClassesTree();
        $tree->createFromArray(static::getFileIterator()->getIterator());

        return $tree;
    }
}

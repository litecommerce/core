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

namespace Includes\Decorator;

/**
 * ADecorator
 *
 */
abstract class ADecorator
{
    /**
     * Cache building steps
     */
    const STEP_FIRST  = 1;
    const STEP_SECOND = 2;
    const STEP_THIRD  = 3;
    const STEP_FOURTH = 4;
    const STEP_FIFTH  = 5;

    const LAST_STEP   = self::STEP_FIFTH;

    /**
     * Current step
     *
     * @var string
     */
    protected static $step;

    /**
     * Classes tree
     *
     * @var \Includes\Decorator\DataStructure\Graph\Classes
     */
    protected static $classesTree;

    /**
     * Modules graph
     *
     * @var \Includes\Decorator\DataStructure\Graph\Modules
     */
    protected static $modulesGraph;

    /**
     * Return classes tree
     * 
     * @param boolean $create Flag OPTIONAL
     *  
     * @return \Includes\Decorator\DataStructure\Graph\Classes
     */
    public static function getClassesTree($create = true)
    {
        if (!isset(static::$classesTree) && $create) {
            if (\Includes\Utils\FileManager::isFileReadable(static::getClassesHashPath())) {
                $data = unserialize(\Includes\Utils\FileManager::read(static::getClassesHashPath()));
                static::$classesTree = array_pop($data);

            } else {
                static::$classesTree = \Includes\Decorator\Utils\Operator::createClassesTree();
            }
        }

        return static::$classesTree;
    }

    /**
     * Return modules graph
     *
     * @return \Includes\Decorator\DataStructure\Graph\Modules
     */
    public static function getModulesGraph()
    {
        if (!isset(static::$modulesGraph)) {
            static::$modulesGraph = \Includes\Decorator\Utils\Operator::createModulesGraph();
        }

        return static::$modulesGraph;
    }

    /**
     * Return classes repository path
     *
     * @return string
     */
    public static function getClassesDir()
    {
        return (self::STEP_FIRST == static::$step) ? LC_DIR_CLASSES : LC_DIR_CACHE_CLASSES;
    }

    /**
     * Return name of the file with the classes hash 
     * 
     * @return string
     */
    public static function getClassesHashPath()
    {
        return LC_DIR_COMPILE . 'Classes.php';
    }
}

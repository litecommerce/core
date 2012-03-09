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

namespace Includes\Decorator\DataStructure\Graph;

/**
 * Modules 
 *
 * @see   ____class_see____
 * @since 1.0.10
 */
class Modules extends \Includes\DataStructure\Graph
{
    /**
     * List of critical path legths for all child nodes
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $criticalPaths;

    // {{{ Getters and setters

    /**
     * Alias
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getActualName()
    {
        return $this->getKey();
    }

    /**
     * Return module dependencies list
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getDependencies()
    {
        return call_user_func(array($this->getModuleClass(), 'getDependencies'));
    }

    /**
     * Return name of the module main class
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModuleClass()
    {
        return \Includes\Utils\ModulesManager::getClassNameByModuleName($this->getActualName());
    }

    // }}}

    // {{{ Getters and setters

    /**
     * Method to get critical path length for a node
     *
     * @param string $module Module actual name
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCriticalPath($module)
    {
        if (!isset($this->criticalPaths)) {
            $this->criticalPaths = $this->calculateCriticalPathLengths();
        }

        return \Includes\Utils\ArrayManager::getIndex($this->criticalPaths, $module);
    }

    /**
     * Calculate critical path lengths
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function calculateCriticalPathLengths($length = 1)
    {
        $result = array();

        foreach ($this->getChildren() as $child) {

            // Critical path legth is equal to the current level
            $result[$child->getActualName()] = $length;

            // Recursive call for the next level nodes
            $result += $child->{__FUNCTION__}($length + 1);
        }

        return $result;
    }

    // }}}
}

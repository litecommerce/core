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

namespace Includes\Decorator\Plugin\ModuleContollers;

/**
 * Main 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Main extends \Includes\Decorator\Plugin\APlugin
{
    /**
     * Pattern to detect/modify module contoller class name
     */
    const PATTERN = '/\\\XLite\\\(Module\\\[\w]+\\\[\w]+\\\)Controller(\\\[\w\\\]*)/Ss';


    /**
     * Find all module controllers
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getModuleControllersList()
    {
        return static::getClassesTree()->findByCallback(array($this, 'isModuleController'));
    }

    /**
     * Remove the module-related part from module controller class
     *
     * @param \Includes\Decorator\DataStructure\Node\ClassInfo $node node to prepare
     *
     * @return string
     * @access protected
     * @since  3.0
     */
    protected function prepareModuleControllerClass(\Includes\Decorator\DataStructure\Node\ClassInfo $node)
    {
        return preg_replace(self::PATTERN, '\\\\XLite\\\\Controller$2', $node->getClass());
    }


    /**
     * Execute "preprocess" hook handler
     *
     * @return null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function executeHookHandlerPreprocess()
    {
        foreach ($this->getModuleControllersList() as $node) {
            static::getClassesTree()->changeNodeKey($node, $this->prepareModuleControllerClass($node));
        }
    }


    /**
     * Method to check class nodes in tree
     * 
     * @param \Includes\Decorator\DataStructure\Node\ClassInfo $node node to check
     *  
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isModuleController(\Includes\Decorator\DataStructure\Node\ClassInfo $node)
    {
        return !$node->isDecorator() && preg_match(self::PATTERN, $node->getClass());
    }
}

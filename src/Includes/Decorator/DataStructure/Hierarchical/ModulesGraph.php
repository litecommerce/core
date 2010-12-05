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

namespace Includes\Decorator\DataStructure\Hierarchical;

/**
 * ModulesGraph 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ModulesGraph extends \Includes\DataStructure\Hierarchical\Graph
{
    /**
     * Node key field 
     */
    const KEY_FIELD = \Includes\Decorator\DataStructure\Node\Module::MODULE_CLASS;


    /**
     * Name of the node class
     *
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $nodeClass = '\Includes\Decorator\DataStructure\Node\Module';


    /**
     * Get module class name
     * 
     * @param string $name   module name
     * @param string $author module author
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getModuleClassByName($name, $author)
    {
        return \Includes\Decorator\Utils\ModulesManager::getClassNameByModuleName($name, $author);
    }

    /**
     * Get module class name
     *
     * @param \Includes\DataStructure\Node\ANode $node node to get info
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getModuleClassByNode(\Includes\DataStructure\Node\ANode $node)
    {
        return $this->getModuleClassByName($node->name, $node->author);
    }

    /**
     * Include some required files
     *
     * @param \Includes\DataStructure\Node\ANode $node node to get info
     * 
     * @return null
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function includeModuleFiles(\Includes\DataStructure\Node\ANode $node)
    {
        require_once LC_MODULES_DIR . 'AModule.php';
        require_once LC_CLASSES_DIR . str_replace('\\', LC_DS, static::getModuleClassByNode($node)) . '.php';
    }

    /**
     * Get list of module dependencies
     *
     * @param \Includes\DataStructure\Node\ANode $node node to get info
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDependencies(\Includes\DataStructure\Node\ANode $node)
    {
        $this->includeModuleFiles($node);

        return call_user_func(array($this->getModuleClassByNode($node), 'getDependencies'));
    }

    /**
     * Prepare element from the "ActiveModules" array
     * 
     * @param array $dependency data to parse
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function parseDependency(array $dependency)
    {
        list($author, $name) = $dependency;

        return $this->getModuleClassByName($name, $author);
    }

    /**
     * Stub function to use in "addNode()"
     *
     * @param \Includes\DataStructure\Node\ANode $node node to get info
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getNodeParents(\Includes\DataStructure\Node\ANode $node)
    {
        return array_merge(parent::getNodeParents($node), $this->getDependencies($node));
    }

    /**
     * Ancillary method to use in "addNode()"
     *
     * @param mixed $data data to parse
     *
     * @return mixed
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function parseNodeData($data)
    {
        return $this->parseDependency($data);
    }
}

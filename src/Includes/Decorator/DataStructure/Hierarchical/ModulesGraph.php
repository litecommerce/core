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
     * @param \Includes\DataStructure\Node\ANode $node node to get info
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getModuleClass(\Includes\DataStructure\Node\ANode $node)
    {
        return \Includes\Decorator\Utils\ModulesManager::getClassNameByModuleName($node->getKey());
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
        if (!\Includes\Utils\Operator::checkIfClassExists('\XLite\Module\AModule')) {
            require_once LC_MODULES_DIR . 'AModule.php';
        }

        if (!\Includes\Utils\Operator::checkIfClassExists($class = $this->getModuleClass($node))) {
            require_once LC_CLASSES_DIR . str_replace('\\', LC_DS, $class) . '.php';
        }
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

        return call_user_func(array($this->getModuleClass($node), 'getDependencies'));
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
     * @param mixed $key some data to use as key
     *
     * @return mixed
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getNodeLogicalParentKey($key)
    {
        return \Includes\Decorator\Utils\ModulesManager::composeDependency($key);
    }


    /**
     * Check tree integrity
     *
     * @param \Includes\DataStructure\Node\ANode $root      root node for current step
     * @param array                              $checklist list of nodes which are not still checked
     *
     * @return null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function checkIntegrity(\Includes\DataStructure\Node\ANode $root = null, array &$checklist = null)
    {
        if (isset($root) && !\Includes\Decorator\Utils\ModulesManager::isActiveModule($root->getKey())) {

            // Unset inactive modules
            foreach ($this->removeNode($root) as $key) {
                \Includes\Decorator\Utils\ModulesManager::disableModule($key);
                unset($checklist[$key]);
            }
            
        } else {

            parent::checkIntegrity($root, $checklist);
        }
    }
}

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

namespace Includes\Decorator\Data\Classes;

/**
 * Tree 
 * 
 * @package    XLite
 * @see        ____class_see____
 * @since      3.0.0
 */
class Tree extends \Includes\DataStructure\Hierarchical\Tree
{
    /**
     * Name of the node class
     *
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $nodeClass = '\Includes\Decorator\Data\Classes\Node';


    /**
     * Get iterator for class files
     * 
     * @return \Includes\Utils\FileFilter
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getFileIterator()
    {
        return new \Includes\Utils\FileFilter(
            LC_CLASSES_DIR,
            \Includes\Decorator\Utils\ModulesManager::getPathPatternForPHP()
        );
    }

    /**
     * Search possible parent for a node
     *
     * @param \Includes\DataStructure\Node\Tree $node node to get info
     *
     * @return \Includes\DataStructure\Node\Tree
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getNodeLogicalParentKey(\Includes\DataStructure\Node\Tree $node)
    {
        return $node->__get(\Includes\Decorator\ADecorator::N_PARENT_CLASS);
    }

    /**
     * Check and prepare current element data
     * 
     * @param \SplFileInfo $fileInfo file descriptor
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareNodeData($fileinfo)
    {
        return \Includes\Decorator\Utils\Parser::parse($fileinfo);
    }

    /**
     * Check constrains
     * 
     * @param \Includes\DataStructure\Node\Tree $node node to check
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkAddedNode(\Includes\DataStructure\Node\Tree $node)
    {
        \Includes\Decorator\Utils\Verifier::checkNode($node);
    }


    /**
     * Change node data and parent
     *
     * @param \Includes\DataStructure\Node\Tree $parent node new parent
     * @param \Includes\DataStructure\Node\Tree $node   node to get data
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function replantNode(\Includes\DataStructure\Node\Tree $parent, \Includes\DataStructure\Node\Tree $node)
    {
        // Duplacate definition
        if (($child = $this->find($node->getKey())) && !$child->isStub()) {
            throw new \Exception('Duplicate class definition - "' . $child->getKey() . '"');
        }

        return parent::replantNode($parent, $node);
    }

    /**
     * Constructor
     *
     * @param string $nodeClass node class name
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct($nodeClass = null)
    {
        parent::__construct($nodeClass);

        // Walk through the PHP files tree and collect classes info
        $this->createFromArray($this->getFileIterator()->getIterator());
    }
}


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
class Tree extends \Includes\Decorator\Data\Classes\Base\ATree
{
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
     * Action to perform in "collectGarbage" method
     *
     * @param \Includes\DataStructure\Node\Tree $node current node
     *
     * @return null
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function performCleanupAction(\Includes\DataStructure\Node\Tree $node)
    {
        parent::performCleanupAction($node);

        // TODO: uncomment when new Decorator will be completed
        // $node->__unset(\Includes\Decorator\ADecorator::N_PARENT_CLASS);
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
     * Walk through the PHP files tree and collect classes info
     * 
     * @return null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function create()
    {
        $this->createFromArray($this->getFileIterator()->getIterator());
    }
}

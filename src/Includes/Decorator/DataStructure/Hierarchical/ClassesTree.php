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
 * ClassesTree 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ClassesTree extends \Includes\DataStructure\Hierarchical\Graph
{
    /**
     * Name of the node class
     *
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $nodeClass = '\Includes\Decorator\DataStructure\Node\ClassInfo';


    /**
     * Check and prepare current element data
     *
     * @param string|int   $key  node key in data array
     * @param \SplFileInfo $data data to prepare
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareNodeData($key, $data)
    {
        return \Includes\Decorator\Utils\Parser::parse($data);
    }

    /**
     * Check constrains
     *
     * @param \Includes\DataStructure\Node\ANode $node node to check
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkAddedNode(\Includes\DataStructure\Node\ANode $node)
    {
        \Includes\Decorator\Utils\Verifier::checkNode($node);
    }


    /**
     * Action to perform in "collectGarbage" method
     *
     * @param \Includes\DataStructure\Node\ANode $node current node
     *
     * @return null
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function performCleanupAction(\Includes\DataStructure\Node\ANode $node)
    {
        parent::performCleanupAction($node);

        // TODO: uncomment when new Decorator will be completed
        // $node->__unset(\Includes\Decorator\ADecorator::N_PARENT_CLASS);
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
        return array_merge(parent::getNodeParents($node), $node->__get(\Includes\Decorator\ADecorator::N_PARENT_CLASS));
    }
}

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

namespace Includes\DataStructure\Hierarchical;

/**
 * Graph 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Graph extends \Includes\DataStructure\Hierarchical\AHierarchical
{
    /**
     * Name of the node class
     *
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $nodeClass = '\Includes\DataStructure\Node\Graph';

    /**
     * List of critical path legths for all nodes
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $criticalPaths;


    /**
     * Method to get length of node critical path
     * 
     * @param \Includes\DataStructure\Node\Graph $node node to get info
     *  
     * @return int
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCriticalPath(\Includes\DataStructure\Node\Graph $node)
    {
        $length = 1;

        foreach ($node->getParents() as $parent) {
            $length += $parent->getKey() ? $this->{__FUNCTION__}($parent) : 0;
        }

        return $length;
    }

    /**
     * Method to get critical path legths for all nodes
     * 
     * @param string $key node key
     *  
     * @return array|int
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCriticalPaths($key = null)
    {
        if (!isset($this->criticalPaths)) {
            $this->criticalPaths = array_map(array($this, 'getCriticalPath'), $this->getIndex());
        }

        return isset($key) ? @$this->criticalPaths[$key] : $this->criticalPaths;
    }
}

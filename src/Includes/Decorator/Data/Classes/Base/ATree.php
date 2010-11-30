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

namespace Includes\Decorator\Data\Classes\Base;

/**
 * ATree 
 * 
 * @package    XLite
 * @see        ____class_see____
 * @since      3.0.0
 */
class ATree extends \Includes\DataStructure\Hierarchical\Tree
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
            static::fireError('Duplicate class definition - "' . $child->getKey() . '"');
        }

        return parent::replantNode($parent, $node);
    }
}

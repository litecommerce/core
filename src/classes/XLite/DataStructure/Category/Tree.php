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
 * @subpackage DataStructure
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\DataStructure\Category;

/**
 * Tree 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
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
    protected $nodeClass = '\XLite\DataStructure\Category\Node';


    /**
     * Check and prepare current element data
     * 
     * @param mixed $data Data to prepare
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareNodeData($data)
    {
        var_dump($data);die;

        return $data;
    }


    /**
     * Constructor
     *
     * @param string $nodeClass  Node class name
     * @param array  $categories Plain list of categories
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct($nodeClass = null, array $categories = array())
    {
        parent::__construct($nodeClass);

        // Create full categories tree
        $this->createFromArray($categories);
    }
}

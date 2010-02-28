<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * ____file_title____
 *  
 * @category   Lite Commerce
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @version    SVN: $Id$
 * @link       http://www.qtmsoft.com/
 * @since      3.0.0 EE
 */

/**
 * XLite_Model_LocationPath 
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0 EE
 */
class XLite_Model_LocationPath extends XLite_Base
{
    /**
     * List of location nodes 
     * 
     * @var    array
     * @access protected
     * @since  3.0.0 EE
     */
	protected $nodes = array();

    /**
     * Index of the last node
     * 
     * @var    mixed
     * @access protected
     * @since  3.0.0 EE
     */
    protected $last = null;


    /**
     * Return index of the last node
     * 
     * @return int
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getLastNodeIndex()
    {
        return isset($this->last) ? $this->last : ($this->last = count($this->nodes) - 1);
    }


    /**
     * Set the params 
     * 
     * @param array $nodes list of location nodes
     *  
     * @return void
     * @access public
     * @since  3.0.0 EE
     */
	public function __construct(array $nodes = array())
	{
        if (!empty($nodes)) {
    		$this->nodes = $nodes;
        }
	}

    /**
     * Add location node 
     * 
     * @param XLite_Model_Location $node location node
     *  
     * @return void
     * @access public
     * @since  3.0.0 EE
     */
	public function addNode(XLite_Model_Location $node)
	{
		$this->nodes[] = $node;
	}

    /**
     * Return list of location nodes
     * 
     * @return array
     * @access public
     * @since  3.0.0 EE
     */
	public function getNodes()
	{
		return $this->nodes;
	}

    /**
     * Check if node is a last one in the nodes list 
     * 
     * @param int $index index used for check
     *  
     * @return bool
     * @access public
     * @since  3.0.0 EE
     */
    public function isLast($index)
    {
        return $this->getLastNodeIndex() <= $index;
    }
}


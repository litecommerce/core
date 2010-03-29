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
 * @since      3.0.0
 */

/**
 * XLite_Model_Location 
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
class XLite_Model_Location extends XLite_Base
{
    /**
     * Breadcrumb name 
     * 
     * @var    string
     * @access protected
     * @since  3.0.0
     */
	protected $name = null;

    /**
     * Breadcrumb link 
     * 
     * @var    string
     * @access protected
     * @since  3.0.0
     */
	protected $link = null;


    /**
     * Set the params
     * 
     * @param string $name breadcrumb name
     * @param string $link breadcrumb link
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
	public function __construct($name, $link = null)
	{
		$this->name = $name;
		$this->link = $link;
	}

    /**
     * Return breadcrumb name
     * 
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Return breadcrumb link 
     * 
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getLink()
    {
        return $this->link;
    }
}


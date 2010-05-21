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
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
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

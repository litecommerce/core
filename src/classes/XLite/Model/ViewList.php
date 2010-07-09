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
 * View list
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 * @Entity (repositoryClass="XLite_Model_Repo_ViewList")
 * @Table (name="view_lists", indexes={@index(name="clzw", columns={"class", "list", "zone", "weight"})})
 */
class XLite_Model_ViewList extends XLite_Model_AModelEntity
{
    /**
     * Predefined weights 
     */
    const FIRST_POSITION = 0;
    const LAST_POSITION = 16777215;


    /**
     * Predefined interfaces
     */
    const CUSTOMER_INTERFACE = 'customer';
    const ADMIN_INTERFACE = 'admin';


    /**
     * List id 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column (type="integer", length="11", nullable=false)
     */
    protected $list_id;

    /**
     * Class name
     * 
     * @var    srting
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length="64", nullable=false)
     */
    protected $class = '';

    /**
     * Class list name
     * 
     * @var    srting
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length="64", nullable=false)
     */
    protected $list;

    /**
     * List interface
     * 
     * @var    srting
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length="16", nullable=false)
     */
    protected $zone = self::CUSTOMER_INTERFACE;

    /**
     * Child class name
     * 
     * @var    srting
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length="64", nullable=false)
     */
    protected $child = '';

    /**
     * Child weight
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="integer", length="11", nullable=false)
     */
    protected $weight = 0;

    /**
     * Template relative path
     * 
     * @var    srting
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length="255", nullable=false)
     */
    protected $tpl = '';

}

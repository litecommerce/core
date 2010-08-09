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

namespace XLite\Model;

/**
 * ZoneElement model
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 * @Entity (repositoryClass="XLite\Model\Repo\Zone")
 * @Table (name="zones")
 */
class ZoneElement extends AEntity
{
    /**
     * Zone unique id 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Id
     * @Column (type="integer", length="11", nullable=false)
     */
    protected $zone_id;

    /**
     * Field (element value), e.g. 'US', 'US_NY', 'New Y%' etc
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Id
     * @Column (type="string", length="255", nullable=false)
     */
    protected $field;

    /**
     * Field type:
     *   C - country code
     *   S - state code (with country code as a prefix)
     *   T - town/city mask
     *   Z - zip code mask
     *   A - address mask)
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length="1", nullable=false)
     */
    protected $field_type;

    /**
     * Zone (relation)
     * 
     * @var    \XLite\Model\Zone
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @ManyToOne (targetEntity="XLite\Model\Zone", inversedBy="zones")
     * @JoinColumn (name="zone_id", referencedColumnName="zone_id")
     */
    protected $zone;

}

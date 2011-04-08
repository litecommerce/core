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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Model;

/**
 * View list
 * 
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @Entity (repositoryClass="\XLite\Model\Repo\ViewList")
 * @Table  (name="view_lists",
 *          indexes={
 *              @Index (name="clzw", columns={"class", "list", "zone", "weight"})
 *          }
 * )
 */
class ViewList extends \XLite\Model\AEntity
{
    /**
     * Predefined weights 
     */

    const POSITION_FIRST = 0;
    const POSITION_LAST  = 16777215;


    /**
     * Predefined interfaces
     */

    const INTERFACE_CUSTOMER = 'customer';
    const INTERFACE_ADMIN    = 'admin';
    const INTERFACE_CONSOLE  = 'console';
    const INTERFACE_MAIL     = 'mail';


    /**
     * List id 
     * 
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="integer", length="11")
     */
    protected $list_id;

    /**
     * Class name
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length="512")
     */
    protected $class = '';

    /**
     * Class list name
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length="255")
     */
    protected $list;

    /**
     * List interface
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length="16")
     */
    protected $zone = self::INTERFACE_CUSTOMER;

    /**
     * Child class name
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length="512")
     */
    protected $child = '';

    /**
     * Child weight
     * 
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="integer", length="11")
     */
    protected $weight = 0;

    /**
     * Template relative path
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length="1024")
     */
    protected $tpl = '';
}

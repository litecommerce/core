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

namespace XLite\Module\CDev\DrupalConnector\Model;

/**
 * Class represents a relation between user profile and drupal roles
 * 
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @Entity
 * @Table  (name="drupal_roles",
 *      indexes={
 *          @Index (name="drupal_role_id", columns={"drupal_role_id"})
 *      }
 * )
 */
class DrupalRole extends \XLite\Model\AEntity
{
    /**
     * Role unique id
     * 
     * @var   mixed
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="uinteger", nullable=false)
     */
    protected $role_id;

    /**
     * Profile id
     * 
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="integer")
     */
    protected $profile_id;

    /**
     * Drupal role id 
     * 
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="integer")
     */
    protected $drupal_role_id = 0;

    /**
     * Related profile
     *
     * @var   \XLite\Model\Profile
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @ManyToOne  (targetEntity="XLite\Model\Profile", inversedBy="drupalRoles")
     * @JoinColumn (name="profile_id", referencedColumnName="profile_id")
     */
    protected $profile;
}

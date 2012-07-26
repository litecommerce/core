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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Model\Role;

/**
 * Permission
 * 
 *
 * @Entity
 * @Table  (name="permissions")
 */
class Permission extends \XLite\Model\Base\I18n
{
    const ROOT_ACCESS = 'root access';

    /**
     * ID 
     * 
     * @var integer
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="uinteger")
     */
    protected $id;

    /**
     * Code 
     * 
     * @var string
     *
     * @Column (type="fixedstring", length=32)
     */
    protected $code;

    /**
     * Section
     *
     * @var string
     *
     * @Column (type="string", length=128)
     */
    protected $section;

    /**
     * Roles
     *
     * @var \XLite\Model\Roles
     *
     * @ManyToMany (targetEntity="XLite\Model\Role", inversedBy="permissions")
     * @JoinTable (
     *      name="role_permissions",
     *      joinColumns={@JoinColumn(name="permission_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@JoinColumn(name="role_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     */
    protected $roles;

    /**
     * Constructor
     *
     * @param array $data Entity properties OPTIONAL
     *
     * @return void
     */
    public function __construct(array $data = array())
    {
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();

        parent::__construct($data);
    }

    /**
     * Get public name
     *
     * @return string
     */
    public function getPublicName()
    {
        return $this->getName() ?: $this->getCode();
    }
}

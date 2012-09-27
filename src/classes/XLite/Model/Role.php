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

namespace XLite\Model;

/**
 * Role 
 * 
 *
 * @Entity
 * @Table  (name="roles")
 */
class Role extends \XLite\Model\Base\I18n
{
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
     * Permissions
     *
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ManyToMany (targetEntity="XLite\Model\Role\Permission", mappedBy="roles", cascade={"merge","detach"})
     */
    protected $permissions;

    /**
     * Profiles
     *
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ManyToMany (targetEntity="XLite\Model\Profile", inversedBy="roles")
     * @JoinTable (
     *      name="profile_roles",
     *      joinColumns={@JoinColumn(name="role_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@JoinColumn(name="profile_id", referencedColumnName="profile_id", onDelete="CASCADE")}
     * )
     */
    protected $profiles;

    /**
     * Constructor
     *
     * @param array $data Entity properties OPTIONAL
     *
     * @return void
     */
    public function __construct(array $data = array())
    {
        $this->permissions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->profiles    = new \Doctrine\Common\Collections\ArrayCollection();

        parent::__construct($data);
    }

    /**
     * Get public name 
     * 
     * @return string
     */
    public function getPublicName()
    {
        return $this->getName();
    }

    /**
     * Check - specified permission is allowed or not
     *
     * @param string $code Permission code
     *
     * @return boolean
     */
    public function isPermissionAllowed($code)
    {
        $allowed = false;

        foreach ($this->getPermissions() as $permission) {
            if ($permission->getCode() == $code) {
                $allowed = true;
                break;
            }
        }

        return $allowed;
    }

    /**
     * Check - specified permission (only one from list) is allowed
     *
     * @param string|array $code Permission code(s)
     *
     * @return boolean
     */
    public function isPermissionAllowedOr($code)
    {
        $result = false;

        $list = array();
        foreach (func_get_args() as $code) {
            if (is_array($code)) {
                $list = array_merge($list, $code);

            } else {
                $list[] = $code;
            }
        }

        foreach ($list as $code) {
            if ($this->isPermissionAllowed($code)) {
                $result = true;
                break;
            }
        }

        return $result;
    }

}

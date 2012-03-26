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
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.17
 */

namespace XLite\Model\Role;

/**
 * Permission
 * 
 * @see   ____class_see____
 * @since 1.0.17
 *
 * @Entity
 * @Table  (name="permissions")
 */
class Permission extends \XLite\Model\Base\I18n
{
    const ROOT_ACCESS = 'root access';

    const CORE_OWNER = 'Core';


    /**
     * ID 
     * 
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.17
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="uinteger")
     */
    protected $id;

    /**
     * Code 
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.17
     *
     * @Column (type="fixedstring", length="32")
     */
    protected $code;

    /**
     * Owner
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.17
     *
     * @Column (type="string", length="128")
     */
    protected $owner = self::CORE_OWNER;

    /**
     * Roles
     *
     * @var   \XLite\Model\Roles
     * @see   ____var_see____
     * @since 1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.17
     */
    public function getPublicName()
    {
        return $this->getName() ?: $this->getCode();
    }

    /**
     * Get owner model
     * 
     * @return \XLite\Model\Module
     * @see    ____func_see____
     * @since  1.0.19
     */
    public function getOwnerModel()
    {
        $owner = null;

        $parts = explode('\\', $this->getOwner());
        if (2 === count($parts)) {
            $owner = \XLite\Core\Database::getRepo('XLite\Model\Module')
                ->findOneBy(array('author' => $parts[0], 'name' => $parts[1]));
        }

        return $owner;
    }

    /**
     * Get owner name 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.19
     */
    public function getOwnerName()
    {
        $model = $this->getOwnerModel();

        return $model ? $model->getModuleName() : 'Core';
    }
}

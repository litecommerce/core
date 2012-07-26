<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Pubic License (GPL 2.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-2.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 *
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU General Pubic License (GPL 2.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Module\CDev\DrupalConnector\Controller\Customer;

/**
 * \XLite\Module\CDev\DrupalConnector\Controller\Customer\Profile
 *
 */
class Profile extends \XLite\Controller\Customer\Profile implements \XLite\Base\IDecorator
{
    /**
     * Types of model form
     */

    const SECTIONS_MAIN      = 'main';
    const SECTIONS_ADDRESSES = 'addresses';
    const SECTIONS_ALL       = 'all';


    /**
     * Return params for the "Personal info" part of the register form
     *
     * @return array
     */
    protected function getModelFormPartMain()
    {
        return array(\XLite\View\Model\Profile\Main::SECTION_MAIN, \XLite\View\Model\Profile\Main::SECTION_ACCESS);
    }

    /**
     * Return params for the "Addresses" part of the register form
     *
     * @return array
     */
    protected function getModelFormPartAddresses()
    {
        return array(\XLite\View\Model\Profile\Main::SECTION_ADDRESSES);
    }

    /**
     * Return params for the whole register form
     *
     * @return array
     */
    protected function getModelFormPartAll()
    {
        return $this->getModelFormPartMain() + $this->getModelFormPartAddresses();
    }

    /**
     * Return part of the register form
     *
     * @param string $type Part(s) identifier
     *
     * @return \XLite\View\Model\Profile\AProfile
     */
    protected function getModelFormPart($type)
    {
        $method = __FUNCTION__ . ucfirst($type);

        // Get the corresponded sections list
        return $this->getModelForm(array(array(), $this->$method()));
    }

    /**
     * Register the account with the basic data
     *
     * @return boolean
     */
    protected function doActionRegisterBasic()
    {
        $result = $this->getModelFormPart(self::SECTIONS_MAIN)->performAction('create');

        if ($result && $this->getModelForm()->getModelObject()->getProfileId()) {
            $this->updateProfileRole($this->getModelForm()->getModelObject());
        }

        return $result;
    }

    /**
     * Update the account with the basic data
     *
     * @return boolean
     */
    protected function doActionUpdateBasic()
    {
        $result = $this->getModelFormPart(self::SECTIONS_MAIN)->performAction('update');

        if ($result) {
            $this->updateProfileRole($this->getModelForm()->getModelObject());
        }

        return $result;
    }

    /**
     * Cancel account (disable)
     *
     * @return boolean
     */
    protected function doActionCancel()
    {
        $profile = $this->getModelForm()->getModelObject();

        $profile->disable();

        \XLite\Core\Database::getEM()->persist($profile);

        \XLite\Core\Database::getEM()->flush();
    }

    /**
     * Delete an account
     *
     * @return boolean
     */
    protected function doActionDelete()
    {
        return $this->getModelFormPart(self::SECTIONS_MAIN)->performAction('delete');
    }

    /**
     * Update access level of users with drupal roles with permission 'lc admin'
     *
     * @return boolean
     */
    protected function doActionUpdateRoles()
    {
        $this->updateAdminAccessLevels();
    }

    /**
     * Delete roles and update access level of users with these roles
     *
     * @return boolean
     */
    protected function doActionDeleteRole()
    {
        $roles = \XLite\Core\Request::getInstance()->roles;

        if (is_array($roles)) {

            foreach ($roles as $role) {

                if (isset($role->rid)) {

                    $rolesToDelete 
                        = \XLite\Core\Database::getRepo('\XLite\Module\CDev\DrupalConnector\Model\DrupalRole')
                            ->findBy(array('drupal_role_id' => $role->rid));

                    if ($rolesToDelete) {
                        \XLite\Core\Database::getRepo('\XLite\Module\CDev\DrupalConnector\Model\DrupalRole')
                            ->deleteInBatch($rolesToDelete);
                    }

                    $this->updateAdminAccessLevels();
                }
            }
        }
    }

    /**
     * Find users with drupal roles with permission 'lc admin' and update access level
     *
     * @return void
     */
    protected function updateAdminAccessLevels()
    {
        // Drupal's function user_roles()
        $roles = user_roles();

        // Prepare list of roles with 'lc admin' permission
        $adminRoles = array();

        foreach ($roles as $roleId => $roleName) {
            if (\XLite\Module\CDev\DrupalConnector\Drupal\Profile::isRoleHasAdminPermission(array($roleId => $roleName))) {
                $adminRoles[] = $roleId;
            }
        }

        // Find admin profiles with non-admin roles and update their access level to customer
        $this->updateAccessLevel(
            \XLite\Core\Database::getRepo('\XLite\Model\Profile')->findAdminsWithoutRoles($adminRoles),
            \XLite\Core\Auth::getInstance()->getCustomerAccessLevel()
        );

        //Find non-admin profiles with admin roles and update their access level to administrator
        $this->updateAccessLevel(
            \XLite\Core\Database::getRepo('\XLite\Model\Profile')->findCustomersWithRoles($adminRoles),
            \XLite\Core\Auth::getInstance()->getAdminAccessLevel()
        );
    }

    /**
     * Update access_level property of the specified profiles
     *
     * @param array   $profiles    Profiles to update
     * @param integer $accessLevel Access level
     *
     * @return void
     */
    protected function updateAccessLevel(array $profiles, $accessLevel)
    {
        if ($profiles) {
            foreach ($profiles as $profile) {
                $profile->setAccessLevel($accessLevel);
                $this->updateProfileRole($profile);
                \XLite\Core\Database::getEM()->persist($profile);
            }
            \XLite\Core\Database::getEM()->flush();
        }
    }

    /**
     * Update profile role 
     * 
     * @param \XLite\Model\Profile $profile Profile
     *  
     * @return void
     */
    protected function updateProfileRole(\XLite\Model\Profile $profile)
    {
        $rootRole = \XLite\Core\Database::getRepo('XLite\Model\Role')->findOneRoot();
        if (\XLite\Core\Auth::getInstance()->getAdminAccessLevel() == $profile->getAccessLevel()) {

            // Add root role
            if ($rootRole && !$profile->getRoles()->contains($rootRole)) {
                $profile->addRoles($rootRole);
                $rootRole->addProfiles($profile);
            }

        } else {

            // Remove root role
            if ($rootRole && $profile->getRoles()->contains($rootRole)) {
                $profile->getRoles()->removeElement($rootRole);
                $rootRole->getProfiles()->removeElement($profile);
            }
        }

        \XLite\Core\Database::getEM()->flush();
    }
}

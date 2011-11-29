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
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU General Pubic License (GPL 2.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Module\CDev\DrupalConnector\Model\Repo;

/**
 * The Profile model repository
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Profile extends \XLite\Model\Repo\Profile implements \XLite\Base\IDecorator
{
    // {{{ linkProfiles

    /**
     * Link XLite and CMS profiles
     *
     * @param \XLite\Model\Profile $profile      XLite profile
     * @param integer              $cmsProfileId CMS profile id
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function linkProfiles(\XLite\Model\Profile $profile, $cmsProfileId)
    {
        $profile->setCMSProfileId($cmsProfileId);
        $profile->setCMSName(\XLite\Module\CDev\DrupalConnector\Handler::getInstance()->getCMSName());

        $this->defineUnlinkProfilesQuery($cmsProfileId)->execute();
        $this->defineLinkProfilesQuery($profile->getProfileId(), $cmsProfileId)->execute();
    }


    /**
     * Define query for linkProfiles() method
     *
     * @param integer $cmsProfileId CMS profile id
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineUnlinkProfilesQuery($cmsProfileId)
    {
        $qb = $this->getQueryBuilder();

        return $qb->update($this->_entityName, 'p')
            ->set('p.cms_name', $qb->expr()->literal(''))
            ->set('p.cms_profile_id', 0)
            ->andWhere('p.cms_name = :cmsName')
            ->andWhere('p.cms_profile_id = :cmsProfileId')
            ->setParameter('cmsName', \XLite\Module\CDev\DrupalConnector\Handler::getInstance()->getCMSName())
            ->setParameter('cmsProfileId', $cmsProfileId);
    }

    /**
     * Define query for linkProfiles() method
     *
     * @param integer $profileId    XLite profile id
     * @param integer $cmsProfileId CMS profile id
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineLinkProfilesQuery($profileId, $cmsProfileId)
    {
        $qb = $this->getQueryBuilder();

        $cmsName = $qb->expr()->literal(\XLite\Module\CDev\DrupalConnector\Handler::getInstance()->getCMSName());

        return $qb->update($this->_entityName, 'p')
            ->set('p.cms_name', $cmsName)
            ->set('p.cms_profile_id', $cmsProfileId)
            ->andWhere('p.profile_id = :profileId')
            ->setParameter('profileId', $profileId);
    }

    // }}}

    // {{{ findAdminsWithoutRoles

    /**
     * Find admin profiles without specified roles
     *
     * @param array $roles Roles list
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function findAdminsWithoutRoles($roles)
    {
        return $this->defineAdminsWithoutRolesQuery($roles)->getResult();
    }


    /**
     * Define query for findAdminsWithoutRoles() method
     *
     * @param array $roles Roles list
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineAdminsWithoutRolesQuery($roles)
    {
        $qb = $this->createQueryBuilder();

        $qb ->leftJoin('p.drupalRoles', 'dr')
            ->andWhere(
                $qb->expr()->notIn(
                    'dr.drupal_role_id',
                    $roles
                )
            )
            ->andWhere('p.access_level = :accessLevel')
            ->andWhere('p.cms_name = :cmsName')
            ->andWhere('p.cms_profile_id > 0')
            ->setParameter('accessLevel', \XLite\Core\Auth::getInstance()->getAdminAccessLevel())
            ->setParameter('cmsName', \XLite\Module\CDev\DrupalConnector\Handler::getInstance()->getCMSName());

        return $qb;
    }

    // }}}

    // {{{ findCustomersWithRoles

    /**
     * Find non-admin profiles with specified roles
     *
     * @param array $roles Roles list
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function findCustomersWithRoles($roles)
    {
        return $this->defineCustomersWithRolesQuery($roles)->getResult();
    }


    /**
     * Define query for findCustomersWithRoles() method
     *
     * @param array $roles Roles list
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineCustomersWithRolesQuery($roles)
    {
        $qb = $this->createQueryBuilder();

        $qb ->leftJoin('p.drupalRoles', 'dr')
            ->andWhere(
                $qb->expr()->In(
                    'dr.drupal_role_id',
                    $roles
                )
            )
            ->andWhere(
                $qb->expr()->Not('p.access_level = :accessLevel')
            )
            ->andWhere('p.cms_name = :cmsName')
            ->andWhere('p.cms_profile_id > 0')
            ->setParameter('accessLevel', \XLite\Core\Auth::getInstance()->getAdminAccessLevel())
            ->setParameter('cmsName', \XLite\Module\CDev\DrupalConnector\Handler::getInstance()->getCMSName());

        return $qb;
    }

    // }}}

    // {{{ findAllProfilesArray

    /**
     * Returns an array of all profiles with order_id = NULL
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function findAllProfilesArray()
    {
        return $this->defineAllProfilesArrayQuery()->getArrayResult();
    }


    /**
     * Define query for findAllProfilesArray() method
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineAllProfilesArrayQuery()
    {
        $cnd = new \Doctrine\ORM\Query\Expr\Orx();
        $cnd->add('p.cms_name = :cmsName');
        $cnd->add('p.cms_name = \'\'');

        return $this->createQueryBuilder()
            ->select('p.profile_id')
            ->addSelect('p.login')
            ->addSelect('p.cms_profile_id')
            ->addSelect('p.cms_name')
            ->andWhere('p.order is null')
            ->andWhere($cnd)
            ->setParameter('cmsName', \XLite\Module\CDev\DrupalConnector\Handler::getInstance()->getCMSName());
    }

    // }}}
}

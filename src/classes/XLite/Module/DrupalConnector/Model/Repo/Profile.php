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

namespace XLite\Module\DrupalConnector\Model\Repo;

/**
 * The Profile model repository
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Profile extends \XLite\Model\Repo\Profile implements \XLite\Base\IDecorator
{
    /**
     * Link XLite and CMS profiles 
     * 
     * @param integer $profileId    XLite profile id
     * @param integer $cmsProfileId CMS profile id
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function linkProfiles($profileId, $cmsProfileId)
    {
        $this->defineUnlinkProfilesQuery($cmsProfileId)->getQuery()->execute();
        $this->defineLinkProfilesQuery($profileId, $cmsProfileId)->getQuery()->execute();
    }

    /**
     * Define query for linkProfiles() method
     * 
     * @param integer $cmsProfileId CMS profile id
     *  
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineUnlinkProfilesQuery($cmsProfileId)
    {
        $qb = $this->_em->createQueryBuilder();

        return $qb
            ->update($this->_entityName, 'p')
            ->set('p.cms_name', $qb->expr()->literal(''))
            ->set('p.cms_profile_id', 0)
            ->andWhere('p.cms_name = :cmsName AND p.cms_profile_id = :cmsProfileId')
            ->setParameter('cmsName', \XLite\Module\DrupalConnector\Handler::getInstance()->getCMSName())
            ->setParameter('cmsProfileId', $cmsProfileId);
    }

    /**
     * Define query for linkProfiles() method
     * 
     * @param integer $profileId    XLite profile id
     * @param integer $cmsProfileId CMS profile id
     *  
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineLinkProfilesQuery($profileId, $cmsProfileId)
    {
        $qb = $this->_em->createQueryBuilder();

        return $qb
            ->update($this->_entityName, 'p')
            ->set('p.cms_name', $qb->expr()->literal(\XLite\Module\DrupalConnector\Handler::getInstance()->getCMSName()))
            ->set('p.cms_profile_id', $cmsProfileId)
            ->andWhere('p.profile_id = :profileId')
            ->setParameter('profileId', $profileId);
    }
}

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

namespace XLite\Model\Repo;

/**
 * Membership repository
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Membership extends \XLite\Model\Repo\Base\I18n
{
    /**
     * Default 'order by' field name
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $defaultOrderBy = 'orderby';

    /**
     * Define cache cells 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineCacheCells()
    {
        $list = parent::defineCacheCells();

        $list['all'] = array();

        $list['active'] = array(
            self::ATTRS_CACHE_CELL => array('active'),
        );

        return $list;
    }

    /**
     * Find all languages
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findAllMemberships()
    {
        $data = $this->getFromCache('all');
        if (!isset($data)) {
            $data = $this->defineAllMembershipsQuery()->getQuery()->getResult();
            $this->saveToCache($data, 'all');
        }

        return $data;
    }

    /**
     * Define query builder for findAllMemberships()
     * 
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineAllMembershipsQuery()
    {
        return $this->createQueryBuilder();
    }

    /**
     * Find all active languages
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findActiveMemberships()
    {
        $data = $this->getFromCache('active', array('active' => true));
        if (!isset($data)) {
            $data = $this->defineActiveMembershipsQuery()->getQuery()->getResult();
            $this->saveToCache($data, 'active', array('active' => true));
        }

        return $data;
    }

    /**
     * Define query builder for findActiveMemberships()
     * 
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineActiveMembershipsQuery()
    {
        return $this->createQueryBuilder()
            ->where('m.active = :true')
            ->setParameter('true', true);
    }

    /**
     * Find membership by name (any language)
     * 
     * @param string  $name       Name
     * @param boolean $onlyActive Search only in active mebmerships OPTIONAL
     *  
     * @return \XLite\Model\Membership|void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findOneByName($name, $onlyActive = true)
    {
        try {
            $m = $this->defineOneByNameQuery($name, $onlyActive)->getQuery()->getSingleResult();

        } catch (\Doctrine\ORM\NoResultException $exception) {
            $m = null;
        }

        return $m;
    }

    /**
     * Define query builder for findOneByName() method
     * 
     * @param string  $name       Name
     * @param boolean $onlyActive Search only in active mebmerships
     *  
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineOneByNameQuery($name, $onlyActive)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('translations.name = :name')
            ->setParameter('name', $name)
            ->setMaxResults(1);

        if ($onlyActive) {
            $qb->addWhere('m.active = :true');
            $qb->setParameter('true', true);
        }

        return $qb;
    }
}


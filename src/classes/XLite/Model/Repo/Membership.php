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

namespace XLite\Model\Repo;

/**
 * Membership repository
 *
 */
class Membership extends \XLite\Model\Repo\Base\I18n
{
    /**
     * Repository type
     *
     * @var string
     */
    protected $type = self::TYPE_SECONDARY;

    /**
     * Default 'order by' field name
     *
     * @var string
     */
    protected $defaultOrderBy = 'orderby';

    // {{{ defineCacheCells

    /**
     * Define cache cells
     *
     * @return array
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

    // }}}

    // {{{ findAllMemberships

    /**
     * Find all languages
     *
     * @return array
     */
    public function findAllMemberships()
    {
        return $this->defineAllMembershipsQuery()->getResult();
    }

    /**
     * Define query builder for findAllMemberships()
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function defineAllMembershipsQuery()
    {
        return $this->createQueryBuilder();
    }

    // }}}

    // {{{ findActiveMemberships

    /**
     * Find all active languages
     *
     * @return array
     */
    public function findActiveMemberships()
    {
        return $this->defineActiveMembershipsQuery()->getResult();
    }

    /**
     * Define query builder for findActiveMemberships()
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function defineActiveMembershipsQuery()
    {
        return $this->createQueryBuilder()
            ->where('m.active = :true')
            ->setParameter('true', true);
    }

    // }}}

    // {{{ findOneByName

    /**
     * Find membership by name (any language)
     *
     * @param string  $name       Name
     * @param boolean $onlyActive Search only in active mebmerships OPTIONAL
     *
     * @return \XLite\Model\Membership|void
     */
    public function findOneByName($name, $onlyActive = true)
    {
        return $this->defineOneByNameQuery($name, $onlyActive)->getSingleResult();
    }

    /**
     * Define query builder for findOneByName() method
     *
     * @param string  $name       Name
     * @param boolean $onlyActive Search only in active mebmerships
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function defineOneByNameQuery($name, $onlyActive)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('translations.name = :name')
            ->setParameter('name', $name)
            ->setMaxResults(1);

        if ($onlyActive) {
            $qb->andWhere('m.active = :true');
            $qb->setParameter('true', true);
        }

        return $qb;
    }

    // }}}
}

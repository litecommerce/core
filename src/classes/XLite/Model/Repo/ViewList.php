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
 * View list repository
 *
 */
class ViewList extends \XLite\Model\Repo\ARepo
{
    /**
     * Repository type
     *
     * @var string
     */
    protected $type = self::TYPE_INTERNAL;

    /**
     * Default 'order by' field name
     *
     * @var string
     */
    protected $defaultOrderBy = array(
        'weight' => true,
        'child'  => true,
        'tpl'    => true,
    );

    /**
     * Columns' character sets definitions
     *
     * @var array
     */
    protected $columnsCharSets = array(
        'class' => 'latin1',
        'list'  => 'latin1',
        'zone'  => 'latin1',
        'child' => 'latin1',
        'tpl'   => 'latin1',
    );


    /**
     * Find class list
     *
     * @param string $list List name
     * @param string $zone Current interface name OPTIONAL
     *
     * @return array
     */
    public function findClassList($list, $zone = \XLite\Model\ViewList::INTERFACE_CUSTOMER)
    {
        $data = $this->getFromCache('class_list', array('list' => $list, 'zone' => $zone));

        if (!isset($data)) {
            $data = $this->defineClassListQuery($list, $zone)->getResult();
            $this->saveToCache($data, 'class_list', array('list' => $list, 'zone' => $zone));
        }

        return $data;
    }

    /**
     * Find view list by tempalte pattern and list name
     *
     * @param string $tpl  Tempalte pattern
     * @param string $list List name
     *
     * @return \XLite\Model\ViewList|void
     */
    public function findOneByTplAndList($tpl, $list)
    {
        return $this->defineOneByTplAndListQuery($tpl, $list)->getSingleResult();
    }


    /**
     * Define cache cells
     *
     * @return array
     */
    protected function defineCacheCells()
    {
        $list = parent::defineCacheCells();

        $list['class_list'] = array(
            self::ATTRS_CACHE_CELL => array('list', 'zone'),
        );

        return $list;
    }

    /**
     * Define query for findOneByTplAndList() method
     *
     * @param string $tpl  Tempalte pattern
     * @param string $list List name
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function defineOneByTplAndListQuery($tpl, $list)
    {
        return $this->createQueryBuilder()
            ->andWhere('v.tpl LIKE :tpl AND v.list = :list')
            ->setParameter('tpl', $tpl)
            ->setParameter('list', $tpl);
    }

    /**
     * Define query builder for findClassList()
     *
     * @param string $list Class list name
     * @param string $zone Current interface name
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function defineClassListQuery($list, $zone)
    {
        return $this->createQueryBuilder()
            ->where('v.list = :list AND v.zone IN (:zone, :empty)')
            ->setParameter('empty', '')
            ->setParameter('list', $list)
            ->setParameter('zone', $zone);
    }
}

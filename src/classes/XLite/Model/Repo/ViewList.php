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
 * View list repository
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ViewList extends \XLite\Model\Repo\ARepo
{
    /**
     * Default 'order by' field name
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $defaultOrderBy = array(
        'weight' => true,
        'child'  => true,
        'tpl'    => true,
    );

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

        $list['class_list'] = array(
            self::ATTRS_CACHE_CELL => array('class', 'list', 'zone'),
        );

        return $list;
    }

    /**
     * Find class list 
     *
     * @param string $class List class-owner name
     * @param string $list  List name
     * @param string $zone  Current interface name OPTIONAL
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findClassList($class, $list, $zone = \XLite\Model\ViewList::INTERFACE_CUSTOMER)
    {
        $data = $this->getFromCache('class_list', array('class' => $class, 'list' => $list, 'zone' => $zone));
        if (!isset($data)) {
            $data = $this->defineClassListQuery($class, $list, $zone)->getQuery()->getResult();
            $this->saveToCache($data, 'class_list', array('class' => $class, 'list' => $list, 'zone' => $zone));
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
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findOneByTplAndList($tpl, $list)
    {
        try {
            $list = $this->defineOneByTplAndListQuery($tpl, $list)->getQuery()->getSingleResult();

        } catch (\Doctrine\ORM\NoResultException $e) {
            $list = null;
        }

        return $list;
    }

    /**
     * Define query for findOneByTplAndList() method
     * 
     * @param string $tpl  Tempalte pattern
     * @param string $list List name
     *  
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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
     * @param string $class Class name
     * @param string $list  Class list name
     * @param string $zone  Current interface name
     * 
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineClassListQuery($class, $list, $zone)
    {
        return $this->createQueryBuilder()
            ->where('v.class IN (:class, :empty) AND v.list = :list AND v.zone IN (:zone, :empty)')
            ->setParameter('class', $class)
            ->setParameter('empty', '')
            ->setParameter('list', $list)
            ->setParameter('zone', $zone);
    }
}

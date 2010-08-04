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
 * Country repository
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class State extends ARepo
{
    /**
     * Default 'order by' field name
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $defaultOrderBy = 'state';

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

        $list['szone'] = array(
            self::ATTRS_CACHE_CELL => array('shipping_zone'),
        );

        return $list;
    }

    /**
     * Get dump 'Other' state 
     *
     * @param string $customState Custom state name
     * 
     * @return \XLite\Model\State
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getOtherState($customState = '')
    {
        $state = new \XLite\Model\State();
        $state->state = $customState ? $customState : 'Other';
        $state->state_id = -1;

        return $state;
    }

    /**
     * Check - is state id of dump 'Other' state or not
     * 
     * @param integer $stateId State id
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isOtherStateId($stateId)
    {
        return -1 == $stateId;
    }

    /**
     * Get state code by state id 
     * 
     * @param integer $stateId State id
     *  
     * @return string or null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCodeById($stateId)
    {
        try {
            $code = $this->createQueryBuilder()
                ->where('s.state_id = :id')
                ->setMaxResults(1)
                ->setParameter('id', $stateId)
                ->getQuery()
                ->getSingleResult()
                ->code;

        } catch (\Doctrine\ORM\NoResultException $exception) {
            $code = null;
        }

        return $code;
    }

    /**
     * Find state by id (dump 'Other' state included) 
     * 
     * @param integer $stateId     State id
     * @param string  $customState Custom state name if state is dump 'Other' state
     *  
     * @return \XLite\Model\State
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findById($stateId, $customState = '')
    {
        return $this->isOtherStateId($stateId)
            ? $this->getOtherState($customState)
            : $this->findOneByStateId($stateId);
    }

    /**
     * Find state by id
     * 
     * @param integer $stateId State id
     *  
     * @return \XLite\Model\State
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findOneByStateId($stateId)
    {
        try {
            $state = $this->defineOneByStateIdQuery($stateId)->getQuery()->getSingleResult();
            $state->detach();

        } catch (\Doctrine\ORM\NoResultException $exception) {
            $state = null;
        }

        return $state;
    }

    /**
     * Define query builder for findOneByStateId()
     *
     * @param integer $stateId State id
     * 
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineOneByStateIdQuery($stateId)
    {
        return $this->createQueryBuilder()
            ->addSelect('c')
            ->leftJoin('s.country', 'c')
            ->andWhere('s.state_id = :id')
            ->setParameter('id', $stateId)
            ->setMaxResults(1);
    }

    /**
     * Find all states
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findAllStates()
    {
        $data = $this->getFromCache('all');
        if (!isset($data)) {
            $data = $this->defineAllStatesQuery()->getQuery()->getResult();
            $this->saveToCache($data, 'all');
        }

        return $data;
    }

    /**
     * Define query builder for findAllStates()
     * 
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineAllStatesQuery()
    {
        return $this->createQueryBuilder()
            ->addSelect('c')
            ->leftJoin('s.country', 'c');
    }

    /**
     * Find states by shipping zone
     *
     * @param integer $shippingZone Shipping zone
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findByShippingZone($shippingZone)
    {
        $data = $this->getFromCache('szone', array('shipping_zone' => $shippingZone));
        if (!isset($data)) {
            $data = $this->defineByShippingZoneQuery($shippingZone)->getQuery()->getResult();
            $this->saveToCache($data, 'szone', array('shipping_zone' => $shippingZone));
        }

        return $data;
    }

    /**
     * Define query builder for findByShippingZone()
     *
     * @param integer $shippingZone Shipping zone
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineByShippingZoneQuery($shippingZone)
    {
        return \XLite\Core\Database::getQB()
            ->addSelect('c')
            ->leftJoin('s.country', 'c')
            ->where('s.shipping_zone = :shipping_zone')
            ->setParameter('shipping_zone', $shippingZone);
    }

}


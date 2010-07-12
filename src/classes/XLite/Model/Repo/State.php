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

/**
 * Country repository
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Model_Repo_State extends XLite_Model_Repo_ARepo
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

        $list['all'] = array(
            self::TTL_CACHE_CELL => self::INFINITY_TTL,
        );
        $list['szone'] = array(
            self::TTL_CACHE_CELL   => self::INFINITY_TTL,
            self::ATTRS_CACHE_CELL => array('shipping_zone'),
        );

        return $list;
    }

    /**
     * Get dump 'Other' state 
     *
     * @param string $customState Custom state name
     * 
     * @return XLite_Model_State
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getOtherState($customState = '')
    {
        $state = new XLite_Model_State();
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
     * @return XLite_Model_State
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findById($stateId, $customState = '')
    {
        return $this->isOtherStateId($stateId) ? $this->getOtherState($customState) : $this->find($stateId);
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
        return $this->assignQueryCache($this->defineAllStatesQuery()->getQuery(), 'all')
            ->getResult();
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
        return $this->assignQueryCache(
            $this->defineByShippingZoneQuery($shippingZone)->getQuery(),
            'szone',
            array('shipping_zone' => $shippingZone)
        )
            ->getResult();
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
        return XLite_Core_Database::getQB()
            ->addSelect('c')
            ->leftJoin('s.country', 'c')
            ->where('s.shipping_zone = :shipping_zone')
            ->setParameter('shipping_zone', $shippingZone);
    }

}


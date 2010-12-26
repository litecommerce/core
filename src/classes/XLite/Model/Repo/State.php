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
class State extends \XLite\Model\Repo\ARepo
{
    /**
     * Repository type 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $type = self::TYPE_SERVICE;

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
            self::RELATION_CACHE_CELL => array('\XLite\Model\Country'),
        );

        return $list;
    }

    /**
     * Get dump 'Other' state 
     *
     * @param string $customState Custom state name OPTIONAL
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
     * @return string|void
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
                ->getCode();

        } catch (\Doctrine\ORM\NoResultException $exception) {
            $code = null;
        }

        return $code;
    }

    /**
     * Find state by id (dump 'Other' state included) 
     * 
     * @param integer $stateId     State id
     * @param string  $customState Custom state name if state is dump 'Other' state OPTIONAL
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
     * Find states by country code
     * 
     * @param string $countryCode Country code
     *  
     * @return \XLite\Model\State|void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findByCountryCode($countryCode)
    {
        $country = \XLite\Core\Database::getRepo('XLite\Model\Country')->find($countryCode);

        return $country
            ? $this->defineByCountryQuery($country)->getQuery()->getResult()
            : array();
    }

    /**
     * Define query for findByCountryCode() method
     * 
     * @param \XLite\Model\Country $country Country
     *  
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineByCountryQuery(\XLite\Model\Country $country)
    {
        return $this->createQueryBuilder()
            ->andWhere('s.country = :country')
            ->setParameter('country', $country);
    }

    /**
     * Find states by country code and state code
     * 
     * @param string $countryCode Country code
     * @param string $code        State code
     *  
     * @return \XLite\Model\State|void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findOneByCountryAndCode($countryCode, $code)
    {
        try {
            $state = $this->defineOneByCountryAndCodeQuery($countryCode, $code)
                ->getQuery()
                ->getSingleResult();

        } catch (\Doctrine\ORM\NoResultException $e) {
            $state = null;
        }

        return $state;
    }

    /**
     * Define query for findOneByCountryAndCode() method
     * 
     * @param string $countryCode Country code
     * @param string $code        State code
     *  
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineOneByCountryAndCodeQuery($countryCode, $code)
    {
        return $this->createQueryBuilder()
            ->innerJoin('s.country', 'country')
            ->andWhere('country.code = :country AND s.code = :code')
            ->setParameter('country', $countryCode)
            ->setParameter('code', $code);
    }

    /**
     * Find one by record
     *
     * @param array                $data   Record
     * @param \XLite\Model\AEntity $parent Parent model
     *
     * @return \XLite\Model\AEntity|void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findOneByRecord(array $data, \XLite\Model\AEntity $parent = null)
    {
        return isset($data['country_code']) && isset($data['code'])
            ? $this->findOneByCountryAndCode($data['country_code'], $data['code'])
            : parent::findOneByRecord($data, $parent);
    }

}

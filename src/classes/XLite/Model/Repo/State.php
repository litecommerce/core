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
 * Country repository
 *
 */
class State extends \XLite\Model\Repo\ARepo
{
    /**
     * Repository type
     *
     * @var string
     */
    protected $type = self::TYPE_SERVICE;

    /**
     * Default 'order by' field name
     *
     * @var string
     */
    protected $defaultOrderBy = 'state';

    /**
     * Get dump 'Other' state
     *
     * @param string $customState Custom state name OPTIONAL
     *
     * @return \XLite\Model\State
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
     */
    public function getCodeById($stateId)
    {
        $result = $this->getFromCache('codes', array('state_id' => $stateId));

        if (!isset($result)) {
            $entity = $this->defineGetCodeByIdQuery($stateId)->getSingleResult();
            $result = $entity ? $entity->getCode() : '';

            $this->saveToCache($result, 'codes', array('state_id' => $stateId));
        }

        return $result;
    }

    /**
     * Find state by id (dump 'Other' state included)
     *
     * @param integer $stateId     State id
     * @param string  $customState Custom state name if state is dump 'Other' state OPTIONAL
     *
     * @return \XLite\Model\State
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
     */
    public function findOneByStateId($stateId)
    {
        return $this->defineOneByStateIdQuery($stateId)->getSingleResult();
    }

    /**
     * Find all states
     *
     * @return array
     */
    public function findAllStates()
    {
        $data = $this->getFromCache('all');

        if (!isset($data)) {
            $data = $this->defineAllStatesQuery()->getResult();
            $this->saveToCache($data, 'all');
        }

        return $data;
    }

    /**
     * Find states by country code
     *
     * @param string $countryCode Country code
     *
     * @return \XLite\Model\State|void
     */
    public function findByCountryCode($countryCode)
    {
        $country = \XLite\Core\Database::getRepo('XLite\Model\Country')->find($countryCode);

        return $country ? $this->defineByCountryQuery($country)->getResult() : array();
    }

    /**
     * Find states by country code and state code
     *
     * @param string $countryCode Country code
     * @param string $code        State code
     *
     * @return \XLite\Model\State|void
     */
    public function findOneByCountryAndCode($countryCode, $code)
    {
        return $this->defineOneByCountryAndCodeQuery($countryCode, $code)->getSingleResult();
    }

    /**
     * Find one by record
     *
     * @param array                $data   Record
     * @param \XLite\Model\AEntity $parent Parent model OPTIONAL
     *
     * @return \XLite\Model\AEntity
     */
    public function findOneByRecord(array $data, \XLite\Model\AEntity $parent = null)
    {
        if (isset($data['country_code']) && isset($data['code'])) {
            $result = $this->findOneByCountryAndCode($data['country_code'], $data['code']);

        } elseif ($parent && $parent instanceOf \XLite\Model\Country) {
            $result = $this->findOneByCountryAndCode($parent->getCode(), $data['code']);

        } else {
            $result = parent::findOneByRecord($data, $parent);
        }

        return $result;
    }

    /**
     * Define query builder for getCodeById() method
     *
     * @param integer $stateId State id
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function defineGetCodeByIdQuery($stateId)
    {
        return $this->createQueryBuilder()
            ->where('s.state_id = :id')
            ->setMaxResults(1)
            ->setParameter('id', $stateId);
    }

    /**
     * Define query builder for findOneByStateId()
     *
     * @param integer $stateId State id
     *
     * @return \Doctrine\ORM\QueryBuilder
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
     * Define query builder for findAllStates()
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function defineAllStatesQuery()
    {
        return $this->createQueryBuilder()
            ->addSelect('c')
            ->leftJoin('s.country', 'c');
    }

    /**
     * Define query for findByCountryCode() method
     *
     * @param \XLite\Model\Country $country Country
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function defineByCountryQuery(\XLite\Model\Country $country)
    {
        return $this->createQueryBuilder()
            ->andWhere('s.country = :country')
            ->setParameter('country', $country);
    }

    /**
     * Define query for findOneByCountryAndCode() method
     *
     * @param string $countryCode Country code
     * @param string $code        State code
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function defineOneByCountryAndCodeQuery($countryCode, $code)
    {
        return $this->createQueryBuilder()
            ->innerJoin('s.country', 'country')
            ->andWhere('country.code = :country AND s.code = :code')
            ->setParameter('country', $countryCode)
            ->setParameter('code', $code);
    }

    // {{{ Cache

    /**
     * Define cache cells
     *
     * @return array
     */
    protected function defineCacheCells()
    {
        $list = parent::defineCacheCells();

        $list['all'] = array(
            self::RELATION_CACHE_CELL => array('\XLite\Model\Country'),
        );

        $list['codes'] = array(
            self::ATTRS_CACHE_CELL => array('state_id'),
        );

        return $list;
    }

    // }}}
}

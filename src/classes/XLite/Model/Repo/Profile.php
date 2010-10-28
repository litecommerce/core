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
 * The Profile model repository
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Profile extends \XLite\Model\Repo\ARepo
{
    /**
     * Allowable search params
     */

    const SEARCH_PROFILE_ID      = 'profile_id';
    const SEARCH_ORDER_ID        = 'order_id';
    const SEARCH_REFERER         = 'referer';
    const SEARCH_MEMBERSHIP      = 'membership';
    const SEARCH_LANGUAGE        = 'language';
    const SEARCH_PATTERN         = 'pattern';
    const SEARCH_PHONE           = 'phone';
    const SEARCH_COUNTRY         = 'country';
    const SEARCH_STATE           = 'state';
    const SEARCH_ADDRESS_PATTERN = 'address_pattern';
    const SEARCH_USER_TYPE       = 'user_type';
    const SEARCH_DATE_TYPE       = 'date_type';
    const SEARCH_DATE_PERIOD     = 'date_period';
    const SEARCH_START_DATE      = 'startDate';
    const SEARCH_END_DATE        = 'endDate';
    const SEARCH_ORDERBY         = 'order_by';
    const SEARCH_LIMIT           = 'limit';

    /**
     * currentSearchCnd 
     * 
     * @var    \XLite\Core\CommonCell
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $currentSearchCnd = null;
   
    /**
     * Return list of handling search params 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getHandlingSearchParams()
    {
        return array(
            self::SEARCH_PROFILE_ID,
            self::SEARCH_ORDER_ID,
            self::SEARCH_REFERER,
            self::SEARCH_MEMBERSHIP,
            self::SEARCH_LANGUAGE,
            self::SEARCH_PATTERN,
            self::SEARCH_PHONE,
            self::SEARCH_COUNTRY,
            self::SEARCH_STATE,
            self::SEARCH_ADDRESS_PATTERN,
            self::SEARCH_USER_TYPE,
            self::SEARCH_DATE_TYPE,
            self::SEARCH_ORDERBY,
            self::SEARCH_LIMIT,
        );
    }

    /**
     * Check if param can be used for search
     * 
     * @param string $param name of param to check
     *  
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isSearchParamHasHandler($param)
    {
        return in_array($param, $this->getHandlingSearchParams());
    }

    /**
     * Call corresponded method to handle a search condition
     * 
     * @param mixed                      $value        condition data
     * @param string                     $key          condition name
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder query builder to prepare
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function callSearchConditionHandler($value, $key, \Doctrine\ORM\QueryBuilder $queryBuilder)
    {
        if ($this->isSearchParamHasHandler($key)) {
            $methodName = 'prepareCnd' . \XLite\Core\Converter::getInstance()->convertToCamelCase($key);
            // Call method for preparing param condition
            $this->$methodName($queryBuilder, $value);
        
        } else {
            // TODO - add logging here
        }
    }

    /**
     * List of fields to use in search by substring
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getNameSubstringSearchFields()
    {
        return array(
            'CONCAT(CONCAT(addresses.firstname, \' \'), addresses.lastname)',
            'CONCAT(CONCAT(addresses.lastname, \' \'), addresses.firstname)',
            'p.login'
        );
    }

    /**
     * List of fields to use in search by substring
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getAddressSubstringSearchFields()
    {
        return array(
            'addresses.street',
            'addresses.custom_state',
            'addresses.city',
            'addresses.zipcode',
        );
    }

    /**
     * prepareCndCommon 
     * 
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder QueryBuilder instance
     * @param string                     $value        Searchable value
     * @param string                     $fieldName    Searchable parameter name
     * @param bool                       $exactCmp     Flag: use exact comparison (=) or 'LIKE'
     * @param string                     $alias        Profile entity alias
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareCndCommon(\Doctrine\ORM\QueryBuilder $queryBuilder, $value, $fieldName, $exactCmp = true, $alias = 'p') 
    {
        if (!empty($value)) {
            $queryBuilder->andWhere(
                sprintf(
                    '%s.%s %s', 
                    $alias, 
                    $fieldName, 
                    $exactCmp ? '= :' . $fieldName : 'LIKE :' . $fieldName
                )
            );

            $queryBuilder->setParameters(
                array_merge(
                    $queryBuilder->getParameters(), 
                    array($fieldName => $exactCmp ? $value : '%' . $value . '%')
                )
            );
        }
    }

    /**
     * prepareCndProfileId 
     * 
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder QueryBuilder instance
     * @param mixed                      $value        Searchable value
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareCndProfileId(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        $this->prepareCndCommon($queryBuilder, $value, 'profile_id');
    }

    /**
     * prepareCndOrderId 
     * 
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder QueryBuilder instance
     * @param mixed                      $value        Searchable value
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareCndOrderId(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        $queryBuilder->andWhere('p.order_id = :orderId')
            ->setParameter('orderId', $value);
    }

    /**
     * prepareCndReferer 
     * 
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder QueryBuilder instance
     * @param mixed                      $value        Searchable value
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareCndReferer(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        $this->prepareCndCommon($queryBuilder, $value, 'referer', false);
    }

    /**
     * prepareCndMembership 
     * 
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder QueryBuilder instance
     * @param mixed                      $value        Searchable value
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareCndMembership(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        $checkParam = self::SEARCH_USER_TYPE;
        
        if (!isset($this->currentSearchCnd->$checkParam) || ('A' != $this->currentSearchCnd->$checkParam)) {

            $value = trim($value);

            if ('pending_membership' == $value) {
                    $queryBuilder->andWhere('p.pending_membership_id > 0');
            
            } elseif ('' == $value) {
                    $queryBuilder->andWhere('p.membership_id = 0');
            
            } elseif (0 < intval($value)) {
                $queryBuilder->andWhere('p.membership_id = :membershipId')
                    ->setParameter('membershipId', intval($value));
            }
        }
    }

    /**
     * prepareCndLanguage 
     * 
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder QueryBuilder instance
     * @param mixed                      $value        Searchable value
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareCndLanguage(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        $this->prepareCndCommon($queryBuilder, $value, 'language');
    }

    /**
     * prepareCndPattern 
     * 
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder QueryBuilder instance
     * @param mixed                      $value        Searchable value
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareCndPattern(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        if (!empty($value)) {
            $cnd = new \Doctrine\ORM\Query\Expr\Orx();

            foreach ($this->getNameSubstringSearchFields() as $field) {
                $cnd->add($field . ' LIKE :pattern');
            }

            $queryBuilder->andWhere($cnd)->setParameter('pattern', '%' . $value . '%');
        }
    }

    /**
     * prepareCndPhone 
     * 
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder QueryBuilder instance
     * @param mixed                      $value        Searchable value
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareCndPhone(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        $this->prepareCndCommon($queryBuilder, $value, 'phone', false, 'addresses');
    }

    /**
     * prepareCndCountry 
     * 
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder QueryBuilder instance
     * @param mixed                      $value        Searchable value
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareCndCountry(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        $this->prepareCndCommon($queryBuilder, $value, 'country_code', true, 'addresses');
    }

    /**
     * prepareCndState 
     * 
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder QueryBuilder instance
     * @param mixed                      $value        Searchable value
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareCndState(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        $this->prepareCndCommon($queryBuilder, $value, 'state_id', true, 'addresses');
    }

    /**
     * prepareCndAddressPattern 
     * 
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder QueryBuilder instance
     * @param mixed                      $value        Searchable value
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareCndAddressPattern(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        if (!empty($value)) {
            $cnd = new \Doctrine\ORM\Query\Expr\Orx();

            foreach ($this->getAddressSubstringSearchFields() as $field) {
                $cnd->add($field . ' LIKE :addressPattern');
            }

            $queryBuilder->andWhere($cnd)->setParameter('addressPattern', '%' . $value . '%');
        }
    }

    /**
     * prepareCndUserType 
     * 
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder QueryBuilder instance
     * @param mixed                      $value        Searchable value
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareCndUserType(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        if (!empty($value)) {

            if ('A' == $value) {
                $accessLevel = \XLite\Base::getInstance()->auth->getAdminAccessLevel();
            
            } elseif ('C' == $value) {
                $accessLevel = \XLite\Base::getInstance()->auth->getCustomerAccessLevel();
            }

            $queryBuilder->andWhere('p.access_level = :accessLevel')
                ->setParameter('accessLevel', $accessLevel);
        }
    }

    /**
     * prepareCndDateType 
     * 
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder QueryBuilder instance
     * @param mixed                      $value        Searchable value
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareCndDateType(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        $dateRange = $this->getDateRange();

        if (isset($dateRange) && in_array($value, array('R', 'L'))) {

            $field = 'R' == $value ? 'added' : 'last_login';

            $queryBuilder->andWhere('p.' . $field . ' >= :startDate')
                ->andWhere('p.' . $field . ' <= :endDate')
                ->setParameter('startDate', $dateRange->startDate)
                ->setParameter('endDate', $dateRange->endDate);
        }
    }

    /**
     * getDateRange 
     * 
     * @return \XLite\Core\CommonCell
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDateRange()
    {
        $result = null;

        $paramDatePeriod = self::SEARCH_DATE_PERIOD;

        if (isset($this->currentSearchCnd->$paramDatePeriod)) {

            $endDate = time();

            if ('M' == $this->currentSearchCnd->$paramDatePeriod) {
                $startDate = mktime(0, 0, 0, date('n', $endDate), 1, date('Y', $endDate));
            
            } elseif ('W' == $this->currentSearchCnd->$paramDatePeriod) {
                $startDay = $endDate - (date('w', $endDate) * 86400);
                $startDate = mktime(0, 0, 0, date('n', $startDay), date('j', $startDay), date('Y', $startDay));
            
            } elseif ('D' == $this->currentSearchCnd->$paramDatePeriod) {
                $startDate = mktime(0, 0, 0, date('n', $endDate), date('j', $endDate), date('Y', $endDate));
            
            } elseif ('C' == $this->currentSearchCnd->$paramDatePeriod) {

                $paramStartDate = self::SEARCH_START_DATE;
                $paramEndDate = self::SEARCH_END_DATE;

                if (
                    isset($this->currentSearchCnd->$paramStartDate)
                    && !empty($this->currentSearchCnd->$paramStartDate)
                    && isset($this->currentSearchCnd->$paramEndDate)
                    && !empty($this->currentSearchCnd->$paramEndDate)
                ) {
                        
                    $tmpDate = strtotime($this->currentSearchCnd->$paramStartDate);

                    if (false !== $tmpDate) {
                        $startDate = mktime(0, 0, 0, date('n', $tmpDate), date('j', $tmpDate), date('Y', $tmpDate));
                    }
                         
                    $tmpDate = strtotime($this->currentSearchCnd->$paramEndDate);

                    if (false !== $tmpDate) {
                        $endDate = mktime(23, 59, 59, date('n', $tmpDate), date('j', $tmpDate), date('Y', $tmpDate));
                    }
                }
            }

            if (isset($startDate) && false !== $startDate && false !== $endDate) {
                $result = new \XLite\Core\CommonCell();
                $result->startDate = $startDate;
                $result->endDate = $endDate;
            }
        }

        return $result;
    }

    /**
     * prepareCndOrderBy 
     * 
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder QueryBuilder instance
     * @param mixed                      $value        Searchable value
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareCndOrderBy(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        list($sort, $order) = $value;

        $queryBuilder->addOrderBy($sort, $order);
    }

    /**
     * prepareCndLimit 
     * 
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder QueryBuilder instance
     * @param mixed                      $value        Searchable value
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareCndLimit(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
         call_user_func_array(array($this, 'assignFrame'), array_merge(array($queryBuilder), $value)); 
    }

    /**
     * Define query for findRecentAdmins() method 
     * 
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineFindRecentAdminsQuery()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.access_level >= :adminAccessLevel')
            ->andWhere('p.last_login > 0')
            ->addOrderBy('p.last_login')
            ->setMaxResults(8)
            ->setParameter('adminAccessLevel', \XLite\Base::getInstance()->auth->getAdminAccessLevel());
    }

    /**
     * defineFindUserWithSameLoginQuery 
     * 
     * @param \XLite\Model\Profile $profile Profile object
     *  
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineFindUserWithSameLoginQuery(\XLite\Model\Profile $profile) 
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.login = :login')
            ->andWhere('p.profile_id != :profileId')
            ->andWhere('p.order_id = 0')
            ->setParameters(
                array(
                    'login'     => $profile->getLogin(),
                    'profileId' => $profile->getProfileId() ?: 0
                )
            );
    }

    /**
     * Define query for findCountOfAdminAccounts() 
     * 
     * @return \Doctrine\ORM\PersistentCollection
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineFindCountOfAdminAccountsQuery()
    {
        return $this->createQueryBuilder()
            ->select('COUNT(p.profile_id)')
            ->andWhere('p.access_level >= :adminAccessLevel')
            ->andWhere('p.status = :status')
            ->andWhere('p.order_id = 0')
            ->setParameter('adminAccessLevel', \XLite\Base::getInstance()->auth->getAdminAccessLevel())
            ->setParameter('status', 'E');
    }

    /**
     * Common search
     * 
     * @param \XLite\Core\CommonCell $cnd       Search condition
     * @param bool                   $countOnly Flag: return items list or only items count
     *  
     * @return \Doctrine\ORM\PersistentCollection|int
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function search(\XLite\Core\CommonCell $cnd, $countOnly = false)
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->addSelect('addresses')
            ->leftJoin('p.addresses', 'addresses');

        $this->currentSearchCnd = $cnd;

        foreach ($this->currentSearchCnd as $key => $value) {
            $this->callSearchConditionHandler($value, $key, $queryBuilder);
        }

        $result = $queryBuilder->getQuery()->getResult();

        if ($countOnly) {
            $result = count($result);
        }

        return $result;
    }

    /**
     * Search profile by login 
     * 
     * @param string $login user's login
     *  
     * @return \XLite\Model\Profile
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findByLogin($login)
    {
        $result = $this->findOneBy(array('login' => $login, 'order_id' => 0));
    
        return $result;
    }

    /**
     * Search profile by login and password
     *
     * @param string $login    User's login
     * @param string $password User's password
     * @param int    $orderId  Order ID related to the profile
     *
     * @return \XLite\Model\Profile
     * @access public
     * @since  3.0.0
     */
    public function findByLoginPassword($login, $password, $orderId = 0)
    {
        $whereArray = array(
            'login'    => $login,
            'password' => $password,
            'status'   => 'E',
            'order_id' => $orderId,
        );

        return $this->findOneBy($whereArray);
    }

    /**
     * Find recently logged in administrators 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findRecentAdmins()
    {
        return $this->defineFindRecentAdminsQuery()->getQuery()->getResult();
    }

    /**
     * findUserWithSameLogin 
     * 
     * @param \XLite\Model\Profile $profile Profile object
     *  
     * @return \XLite\Model\Profile 
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findUserWithSameLogin(\XLite\Model\Profile $profile) 
    {
        $result = $this->defineFindUserWithSameLoginQuery($profile)->getQuery()->getResult();

        if (!empty($result)) {
            $result = array_shift($result);
        
        } else {
            $result = null;
        }

        return $result;
    }

    /**
     * Find the count of administrator accounts 
     * 
     * @return int
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findCountOfAdminAccounts()
    {
        $result = $this->defineFindCountOfAdminAccountsQuery()->getQuery()->getSingleScalarResult();
    
        return intval($result);
    }

}

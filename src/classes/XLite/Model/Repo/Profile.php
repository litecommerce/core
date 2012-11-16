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
 * The Profile model repository
 *
 */
class Profile extends \XLite\Model\Repo\ARepo
{
    /**
     * Allowable search params
     */
    const SEARCH_PROFILE_ID     = 'profile_id';
    const SEARCH_ORDER_ID       = 'order_id';
    const SEARCH_REFERER        = 'referer';
    const SEARCH_MEMBERSHIP     = 'membership';
    const SEARCH_ROLES          = 'roles';
    const SEARCH_PERMISSIONS    = 'permissions';
    const SEARCH_LANGUAGE       = 'language';
    const SEARCH_PATTERN        = 'pattern';
    const SEARCH_PHONE          = 'phone';
    const SEARCH_COUNTRY        = 'country';
    const SEARCH_STATE          = 'state';
    const SEARCH_CUSTOM_STATE   = 'custom_state';
    const SEARCH_ADDRESS        = 'address';
    const SEARCH_USER_TYPE      = 'user_type';
    const SEARCH_DATE_TYPE      = 'date_type';
    const SEARCH_DATE_PERIOD    = 'date_period';
    const SEARCH_START_DATE     = 'startDate';
    const SEARCH_END_DATE       = 'endDate';
    const SEARCH_ORDERBY        = 'order_by';
    const SEARCH_LIMIT          = 'limit';

    /**
     * Password length
     */
    const PASSWORD_LENGTH = 12;


    /**
     * Repository type
     *
     * @var string
     */
    protected $type = self::TYPE_SERVICE;

    /**
     * currentSearchCnd
     *
     * @var \XLite\Core\CommonCell
     */
    protected $currentSearchCnd = null;

    /**
     * Password characters list
     *
     * @var array
     */
    protected $chars = array(
        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
        'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j',
        'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't',
        'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D',
        'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N',
        'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X',
        'Y', 'Z',
    );

    /**
     * Common search
     *
     * @param \XLite\Core\CommonCell $cnd       Search condition
     * @param boolean                $countOnly Flag: return items list or only items count OPTIONAL
     *
     * @return \Doctrine\ORM\PersistentCollection|integer
     */
    public function search(\XLite\Core\CommonCell $cnd, $countOnly = false)
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->addGroupBy('p.profile_id')
            ->leftJoin('p.addresses', 'addresses')
            ->leftJoin('addresses.country', 'country')
            ->leftJoin('addresses.state', 'state');

        $this->currentSearchCnd = $this->preprocessCnd($cnd);

        foreach ($this->currentSearchCnd as $key => $value) {
            $this->callSearchConditionHandler($value, $key, $queryBuilder);
        }

        $result = $queryBuilder->getResult();

        return $countOnly ? count($result) : $result;
    }

    /**
     * Find profile by CMS identifiers
     *
     * @param array $fields CMS identifiers
     *
     * @return \XLite\Model\Profile|void
     */
    public function findOneByCMSId(array $fields)
    {
        return $this->defineFindOneByCMSIdQuery($fields)->getSingleResult();
    }

    /**
     * Search profile by login
     *
     * @param string $login User's login
     *
     * @return \XLite\Model\Profile
     */
    public function findByLogin($login)
    {
        return $this->findByLoginPassword($login);
    }

    /**
     * Search profile by login and password
     *
     * @param string  $login    User's login
     * @param string  $password User's password OPTIONAL
     * @param integer $orderId  Order ID related to the profile OPTIONAL
     *
     * @return \XLite\Model\Profile
     */
    public function findByLoginPassword($login, $password = null, $orderId = 0)
    {
        return $this->defineFindByLoginPasswordQuery($login, $password, $orderId)->getSingleResult();
    }

    /**
     * Find recently logged in administrators
     *
     * @return array
     */
    public function findRecentAdmins()
    {
        return $this->defineFindRecentAdminsQuery()->getResult();
    }

    /**
     * Find user with same login
     *
     * @param \XLite\Model\Profile $profile Profile object
     *
     * @return \XLite\Model\Profile|void
     */
    public function findUserWithSameLogin(\XLite\Model\Profile $profile)
    {
        return $this->defineFindUserWithSameLoginQuery($profile)->getSingleResult();
    }

    /**
     * Find the count of administrator accounts
     *
     * @return integer
     */
    public function findCountOfAdminAccounts()
    {
        return intval($this->defineFindCountOfAdminAccountsQuery()->getSingleScalarResult());
    }

    /**
     * Find one by record
     *
     * @param array                $data   Record
     * @param \XLite\Model\AEntity $parent Parent model OPTIONAL
     *
     * @return \XLite\Model\AEntity|void
     */
    public function findOneByRecord(array $data, \XLite\Model\AEntity $parent = null)
    {
        if (
            isset($data['login'])
            && (
                isset($data['order_id'])
                && 0 == $data['order_id']
                || 1 == count($data)
            )
        ) {
            $entity = $this->defineOneByRecord($data['login'])->getSingleResult();

        } else {
            $entity = parent::findOneByRecord($data, $parent);
        }

        return $entity;
    }

    /**
     * Generate password
     *
     * @return string
     */
    public function generatePassword()
    {
        $limit = count($this->chars) - 1;
        $x = explode('.', uniqid('', true));
        mt_srand(microtime(true) + intval(hexdec($x[0])) + $x[1]);

        $password = '';
        for ($i = 0; self::PASSWORD_LENGTH > $i; $i++) {
            $password .= $this->chars[mt_rand(0, $limit)];
        }

        return $password;
    }

    /**
     * Preprocess condition. Order id must be placed into condition in any case.
     *
     * @return \XLite\Core\CommonCell
     */
    protected function preprocessCnd(\XLite\Core\CommonCell $cnd)
    {
        if (!$cnd->{self::SEARCH_ORDER_ID}) {
            $cnd->{self::SEARCH_ORDER_ID} = 0;
        }

        return $cnd;
    }

    /**
     * Return list of handling search params
     *
     * @return array
     */
    protected function getHandlingSearchParams()
    {
        return array(
            self::SEARCH_PROFILE_ID,
            self::SEARCH_ORDER_ID,
            self::SEARCH_REFERER,
            self::SEARCH_MEMBERSHIP,
            self::SEARCH_PERMISSIONS,
            self::SEARCH_ROLES,
            self::SEARCH_LANGUAGE,
            self::SEARCH_PATTERN,
            self::SEARCH_PHONE,
            self::SEARCH_COUNTRY,
            self::SEARCH_STATE,
            self::SEARCH_CUSTOM_STATE,
            self::SEARCH_ADDRESS,
            self::SEARCH_USER_TYPE,
            self::SEARCH_DATE_TYPE,
            self::SEARCH_ORDERBY,
            self::SEARCH_LIMIT,
        );
    }

    /**
     * Check if param can be used for search
     *
     * @param string $param Name of param to check
     *
     * @return boolean
     */
    protected function isSearchParamHasHandler($param)
    {
        return in_array($param, $this->getHandlingSearchParams());
    }

    /**
     * Call corresponded method to handle a search condition
     *
     * @param mixed                      $value        Condition data
     * @param string                     $key          Condition name
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     *
     * @return void
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
     */
    protected function getNameSubstringSearchFields()
    {
        return array(
            'CONCAT(CONCAT(field_value_firstname.value, \' \'), field_value_lastname.value)',
            'CONCAT(CONCAT(field_value_lastname.value, \' \'), field_value_firstname.value)',
            'p.login'
        );
    }

    /**
     * prepareCndCommon
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder QueryBuilder instance
     * @param string                     $value        Searchable value
     * @param string                     $fieldName    Searchable parameter name
     * @param boolean                    $exactCmp     Flag: use exact comparison (=) or 'LIKE' OPTIONAL
     * @param string                     $alias        Profile entity alias OPTIONAL
     *
     * @return void
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
     * Prepare field search query
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder
     * @param type $fieldName
     */
    protected function prepareField(\Doctrine\ORM\QueryBuilder $queryBuilder, $fieldName)
    {
        $queryBuilder->leftJoin(
            'addresses.addressFields',
            'field_value_' . $fieldName
        )->leftJoin(
            'field_value_' . $fieldName . '.addressField',
            'field_' . $fieldName,
            \Doctrine\ORM\Query\Expr\Join::WITH,
            'field_' . $fieldName . '.serviceName = :' . $fieldName
        )->setParameter($fieldName, $fieldName);
    }

    /**
     * Prepare field search query
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder
     * @param type $value
     * @param type $fieldName
     */
    protected function prepareCommonField(\Doctrine\ORM\QueryBuilder $queryBuilder, $value, $fieldName, $exactCmp = true)
    {
        if ($value) {
            $this->prepareField($queryBuilder, $fieldName);

            $queryBuilder->andWhere(
                $exactCmp
                    ? 'field_value_' . $fieldName . '.value = :field_value_' . $fieldName
                    : 'field_value_' . $fieldName . '.value LIKE :field_value_' . $fieldName
            )->setParameter('field_value_' . $fieldName, $exactCmp ? $value : '%' . $value . '%');
        }
    }

    /**
     * prepareCndProfileId
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder QueryBuilder instance
     * @param mixed                      $value        Searchable value
     *
     * @return void
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
     */
    protected function prepareCndOrderId(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        if ($value) {
            $queryBuilder->innerJoin('p.order', 'porder')
                ->andWhere('porder.order_id = :orderId')
                ->setParameter('orderId', $value);
        } else {
            $queryBuilder->andWhere('p.order is null');
        }
    }

    /**
     * prepareCndReferer
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder QueryBuilder instance
     * @param mixed                      $value        Searchable value
     *
     * @return void
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
     */
    protected function prepareCndMembership(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        if ('A' !== $this->currentSearchCnd->{self::SEARCH_USER_TYPE}) {
            $value = trim($value);

            if ('pending_membership' == $value) {
                $queryBuilder->andWhere('p.pending_membership IS NOT NULL');

            } elseif ('' == $value) {
                $queryBuilder->andWhere('p.membership IS NULL');

            } elseif (0 < intval($value)) {
                $queryBuilder
                    ->innerJoin('p.membership', 'membership')
                    ->andWhere('membership.membership_id = :membershipId')
                    ->setParameter('membershipId', intval($value));
            }
        }
    }

    /**
     * Search condition by role(s)
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder QueryBuilder instance
     * @param mixed                      $value        Searchable value
     *
     * @return void
     */
    protected function prepareCndRoles(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        if (!empty($value)) {
            if (!is_array($value)) {
                $value = array($value);
            }

            $ids = array();
            foreach ($value as $id) {
                if ($id) {
                    $ids = is_object($id) ? $id->getId() : $id;
                }
            }

            if ($ids) {
                $keys = \XLite\Core\Database::buildInCondition($queryBuilder, $ids, 'rid');
                $queryBuilder->linkInner('p.roles')
                    ->andWhere('roles.id IN (' . implode(', ', $keys) . ')');
            }
        }
    }

    /**
     * Search condition by permission(s)
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder QueryBuilder instance
     * @param mixed                      $value        Searchable value
     *
     * @return void
     */
    protected function prepareCndPermissions(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        if (!empty($value)) {
            if (!is_array($value)) {
                $value = array($value);
            }

            $keys = \XLite\Core\Database::buildInCondition($queryBuilder, $value, 'perm');
            $queryBuilder->linkInner('p.roles')
                ->linkInner('roles.permissions')
                ->andWhere('permissions.code IN (' . implode(', ', $keys) . ')');
        }
    }

    /**
     * prepareCndLanguage
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder QueryBuilder instance
     * @param mixed                      $value        Searchable value
     *
     * @return void
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
     */
    protected function prepareCndPattern(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        if (!empty($value)) {
            $cnd = new \Doctrine\ORM\Query\Expr\Orx();

            $this->prepareField($queryBuilder, 'firstname');
            $this->prepareField($queryBuilder, 'lastname');

            foreach ($this->getNameSubstringSearchFields() as $field) {
                $cnd->add($field . ' LIKE :pattern');
            }

            $queryBuilder
                ->andWhere($cnd)
                ->setParameter('pattern', '%' . $value . '%');
        }
    }

    /**
     * prepareCndPhone
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder QueryBuilder instance
     * @param mixed                      $value        Searchable value
     *
     * @return void
     */
    protected function prepareCndPhone(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        $this->prepareCommonField($queryBuilder, $value, 'name', false);
    }

    /**
     * prepareCndCountry
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder QueryBuilder instance
     * @param mixed                      $value        Searchable value
     *
     * @return void
     */
    protected function prepareCndCountry(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        $this->prepareCndCommon($queryBuilder, $value, 'code', true, 'country');
    }

    /**
     * prepareCndState
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder QueryBuilder instance
     * @param mixed                      $value        Searchable value
     *
     * @return void
     */
    protected function prepareCndState(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        $this->prepareCndCommon($queryBuilder, $value, 'state_id', true, 'state');
    }

    /**
     * prepareCndCustomState
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder QueryBuilder instance
     * @param mixed                      $value        Searchable value
     *
     * @return void
     */
    protected function prepareCndCustomState(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        $this->prepareCommonField($queryBuilder, $value, 'custom_state');
    }

    /**
     * prepareCndAddress
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder QueryBuilder instance
     * @param mixed                      $value        Searchable value
     *
     * @return void
     */
    protected function prepareCndAddress(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        if (!empty($value)) {
            $queryBuilder->leftJoin(
                'addresses.addressFields',
                'field_value_address_pattern'
            )
            ->andWhere('field_value_address_pattern.value LIKE :addressPattern')
            ->setParameter('addressPattern', '%' . $value . '%');
        }
    }

    /**
     * prepareCndUserType
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder QueryBuilder instance
     * @param mixed                      $value        Searchable value
     *
     * @return void
     */
    protected function prepareCndUserType(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        if (!empty($value)) {

            if ('A' == $value) {
                $accessLevel = \XLite\Core\Auth::getInstance()->getAdminAccessLevel();

            } elseif ('C' == $value) {
                $accessLevel = \XLite\Core\Auth::getInstance()->getCustomerAccessLevel();
            }

            $queryBuilder
                ->andWhere('p.access_level = :accessLevel')
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
     */
    protected function prepareCndDateType(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        $dateRange = $this->getDateRange();

        if (isset($dateRange) && in_array($value, array('R', 'L'))) {
            $field = 'R' == $value ? 'added' : 'last_login';

            $queryBuilder
                ->andWhere('p.' . $field . ' >= :startDate')
                ->andWhere('p.' . $field . ' <= :endDate')
                ->setParameter('startDate', $dateRange->startDate)
                ->setParameter('endDate', $dateRange->endDate);
        }
    }

    /**
     * getDateRange
     *
     * :FIXME: simplify
     *
     * @return \XLite\Core\CommonCell
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

            if (
                isset($startDate)
                && false !== $startDate
                && false !== $endDate
            ) {
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
     */
    protected function prepareCndLimit(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
         call_user_func_array(array($this, 'assignFrame'), array_merge(array($queryBuilder), $value));
    }

    /**
     * Define query for findRecentAdmins() method
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function defineFindRecentAdminsQuery()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.access_level >= :adminAccessLevel')
            ->andWhere('p.last_login > 0')
            ->addOrderBy('p.last_login')
            ->setMaxResults(8)
            ->setParameter('adminAccessLevel', \XLite\Core\Auth::getInstance()->getAdminAccessLevel());
    }

    /**
     * Define query for findUserWithSameLogin() method
     *
     * @param \XLite\Model\Profile $profile Profile object
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function defineFindUserWithSameLoginQuery(\XLite\Model\Profile $profile)
    {
        $queryBuilder = $this->createQueryBuilder()
            ->andWhere('p.login = :login')
            ->andWhere('p.profile_id != :profileId')
            ->setParameter('login', $profile->getLogin())
            ->setParameter('profileId', $profile->getProfileId() ?: 0)
            ->setMaxResults(1);

        if ($profile->getOrder()) {
            $queryBuilder
                ->innerJoin('p.order', 'porder')
                ->andWhere('porder.order_id = :orderId')
                ->setParameter('orderId', $profile->getOrder()->getOrderId());

        } else {
            $queryBuilder->andWhere('p.order is null');
        }

        return $queryBuilder;
    }

    /**
     * Define query for findCountOfAdminAccounts()
     *
     * @return \Doctrine\ORM\PersistentCollection
     */
    protected function defineFindCountOfAdminAccountsQuery()
    {
        return $this->createQueryBuilder()
            ->select('COUNT(p.profile_id)')
            ->andWhere('p.access_level >= :adminAccessLevel')
            ->andWhere('p.status = :status')
            ->andWhere('p.order is null')
            ->setParameter('adminAccessLevel', \XLite\Core\Auth::getInstance()->getAdminAccessLevel())
            ->setParameter('status', 'E');
    }

    /**
     * Define query for findOneByCMSId()
     *
     * @return \Doctrine\ORM\PersistentCollection
     */
    protected function defineFindOneByCMSIdQuery(array $fields)
    {
        $queryBuilder = $this->createQueryBuilder()
            ->andWhere('p.order is null')
            ->setMaxResults(1);

        foreach ($fields as $name => $value) {
            $queryBuilder
                ->andWhere('p.' . $name . ' = :' . $name)
                ->setParameter($name, $value);
        }

        return $queryBuilder;
    }

    /**
     * Define query for findByLoginPassword() method
     *
     * @param string  $login    User's login
     * @param string  $password User's password
     * @param integer $orderId  Order ID related to the profile OPTIONAL
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function defineFindByLoginPasswordQuery($login, $password, $orderId)
    {
        $queryBuilder = $this->createQueryBuilder()
            ->andWhere('p.login = :login')
            ->andWhere('p.status = :status')
            ->setParameter('login', $login)
            ->setParameter('status', 'E')
            ->setMaxResults(1);

        if (isset($password)) {
            $queryBuilder
                ->andWhere('p.password = :password')
                ->setParameter('password', $password);
        }

        if ($orderId) {
            $queryBuilder
                ->innerJoin('p.order', 'porder')
                ->andWhere('porder.order_id = :orderId')
                ->setParameter('orderId', $orderId);

        } else {
            $queryBuilder->andWhere('p.order is null');
        }

        return $queryBuilder;
    }

    /**
     * Collect alternative identifiers by record
     *
     * @param array $data Record
     *
     * @return boolean|array(mixed)
     */
    protected function collectAlternativeIdentifiersByRecord(array $data)
    {
        $indetifiers = parent::collectAlternativeIdentifiersByRecord($data);
        if (
            !$indetifiers
            && isset($data['login'])
            && $data['login']
            && isset($data['order_id'])
            && !$data['order_id']
        ) {
            $indetifiers = array(
                'login' => $data['login'],
                'order' => null,
            );
        }

        return $indetifiers;
    }

    /**
     * Link loaded entity to parent object
     *
     * @param \XLite\Model\AEntity $entity      Loaded entity
     * @param \XLite\Model\AEntity $parent      Entity parent callback
     * @param array                $parentAssoc Entity mapped propery method
     *
     * @return void
     */
    protected function linkLoadedEntity(\XLite\Model\AEntity $entity, \XLite\Model\AEntity $parent, array $parentAssoc)
    {
        if (
            $parent instanceof \XLite\Model\Order
            && !$parentAssoc['mappedSetter']
            && 'setProfile' == $parentAssoc['setter']
        ) {
            // Add order to profile if this profile - copy of original profile
            $parentAssoc['mappedSetter'] = 'setOrder';
        }

        parent::linkLoadedEntity($entity, $parent, $parentAssoc);
    }

    /**
     * Get detailed foreign keys
     *
     * @return array
     */
    protected function getDetailedForeignKeys()
    {
        $list = parent::getDetailedForeignKeys();

        $list[] = array(
            'fields'        => array('order_id'),
            'referenceRepo' => 'XLite\Model\Order',
        );

        return $list;
    }

    /**
     * Define query for findOneByRecord () method
     *
     * @param string $login Login
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function defineOneByRecord($login)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.order IS NULL AND p.login = :login AND p.access_level = :zero')
            ->setParameter('login', $login)
            ->setParameter('zero', 0)
            ->setMaxResults(1);
    }
}

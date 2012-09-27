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

namespace XLite\Model\Repo\Payment;

/**
 * Payment method repository
 *
 */
class Method extends \XLite\Model\Repo\Base\I18n implements \XLite\Model\Repo\Base\IModuleLinked
{
    /**
     * Names of fields that are used in search
     */
    const P_ENABLED             = 'enabled';
    const P_MODULE_ENABLED      = 'moduleEnabled';
    const P_ADDED               = 'added';
    const P_ONLY_PURE_OFFLINE   = 'onlyPureOffline';
    const P_ONLY_MODULE_OFFLINE = 'onlyModuleOffline';
    const P_POSITION            = 'position';
    const P_TYPE                = 'type';
    const P_ORDERBY             = 'orderBy';

    /**
     * Name of the field which is used for default sorting (ordering)
     */
    const FIELD_DEFAULT_POSITION = 'orderby';

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

    /**
     * Alternative record identifiers
     *
     * @var array
     */
    protected $alternativeIdentifier = array(
        array('service_name'),
    );

    /**
     * currentSearchCnd
     *
     * @var \XLite\Core\CommonCell
     */
    protected $currentSearchCnd = null;

    // {{{ Module link

    /**
     * Switch module link
     *
     * @param boolean             $enabled Module enabled status
     * @param \XLite\Model\Module $module  Model module
     *
     * @return mixed
     */
    public function switchModuleLink($enabled, \XLite\Model\Module $module)
    {
        return $this->defineQuerySwitchModuleLink($enabled, $module)->execute();
    }

    /**
     * Define query for switchModuleLink() method
     *
     * @param boolean             $enabled Module enabled status
     * @param \XLite\Model\Module $module  Model module
     *
     * @return \XLite\Model\QueryBuilder\AQueryBuilder
     */
    protected function defineQuerySwitchModuleLink($enabled, \XLite\Model\Module $module)
    {
        return $this->getQueryBuilder()
            ->update($this->_entityName, 'e')
            ->set('e.moduleEnabled', ':enabled')
            ->where('LOCATE(:class, e.class) > 0')
            ->setParameter('enabled', $enabled)
            ->setParameter('class', $module->getActualName());
    }

    // }}}

    // {{{ Search

    /**
     * Common search
     *
     * @param \XLite\Core\CommonCell $cnd       Search condition
     * @param boolean                $countOnly Return items list or only its size OPTIONAL
     *
     * @return \Doctrine\ORM\PersistentCollection|integer
     */
    public function search(\XLite\Core\CommonCell $cnd, $countOnly = false)
    {
        $queryBuilder = $countOnly
            ? $this->createPureQueryBuilder()
            : $this->createQueryBuilder();

        $this->currentSearchCnd = $cnd;

        foreach ($this->currentSearchCnd as $key => $value) {
            $this->callSearchConditionHandler($value, $key, $queryBuilder, $countOnly);
        }

        return $countOnly
            ? $this->searchCount($queryBuilder)
            : $this->searchResult($queryBuilder);
    }

    /**
     * Search count only routine.
     *
     * @param \Doctrine\ORM\QueryBuilder $qb Query builder routine
     *
     * @return integer
     */
    public function searchCount(\Doctrine\ORM\QueryBuilder $qb)
    {
        $qb->select('COUNT(DISTINCT ' . $this->getMainAlias($qb) . '.' . $this->getPrimaryKeyField() . ')');

        return intval($qb->getSingleScalarResult());
    }

    /**
     * Search result routine.
     *
     * @param \Doctrine\ORM\QueryBuilder $qb Query builder routine
     *
     * @return \Doctrine\ORM\PersistentCollection
     */
    public function searchResult(\Doctrine\ORM\QueryBuilder $qb)
    {
        return $qb->getResult();
    }

    /**
     * Call corresponded method to handle a search condition
     *
     * @param mixed                      $value        Condition data
     * @param string                     $key          Condition name
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param boolean                    $countOnly    Count only flag
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function callSearchConditionHandler($value, $key, \Doctrine\ORM\QueryBuilder $queryBuilder, $countOnly)
    {
        if ($this->isSearchParamHasHandler($key)) {
            $this->{'prepareCnd' . ucfirst($key)}($queryBuilder, $value, $countOnly);
        }
    }

    /**
     * Check if parameter can be used for search
     *
     * @param string $parameter Name of parameter to check
     *
     * @return boolean
     */
    protected function isSearchParamHasHandler($parameter)
    {
        return in_array($parameter, $this->getHandlingSearchParams());
    }

    /**
     * Return list of handling search params
     *
     * @return array
     */
    protected function getHandlingSearchParams()
    {
        return array(
            static::P_ENABLED,
            static::P_MODULE_ENABLED,
            static::P_ADDED,
            static::P_ONLY_PURE_OFFLINE,
            static::P_ONLY_MODULE_OFFLINE,
            static::P_POSITION,
            static::P_TYPE,
            static::P_ORDERBY,
        );
    }

    /**
     * Prepare certain search condition for enabled flag
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param boolean                    $value        Condition data
     * @param boolean                    $countOnly    "Count only" flag
     *
     * @return void
     */
    protected function prepareCndEnabled(\Doctrine\ORM\QueryBuilder $queryBuilder, $value, $countOnly)
    {
        $queryBuilder
            ->andWhere($this->getMainAlias($queryBuilder) . '.enabled = :enabled_value')
            ->setParameter('enabled_value', $value);
    }

    /**
     * Prepare certain search condition for moduleEnabled flag
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param boolean                    $value        Condition data
     * @param boolean                    $countOnly    "Count only" flag
     *
     * @return void
     */
    protected function prepareCndModuleEnabled(\Doctrine\ORM\QueryBuilder $queryBuilder, $value, $countOnly)
    {
        $queryBuilder
            ->andWhere($this->getMainAlias($queryBuilder) . '.moduleEnabled = :module_enabled_value')
            ->setParameter('module_enabled_value', $value);
    }

    /**
     * Prepare certain search condition for added flag
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param boolean                    $value        Condition data
     * @param boolean                    $countOnly    "Count only" flag
     *
     * @return void
     */
    protected function prepareCndAdded(\Doctrine\ORM\QueryBuilder $queryBuilder, $value, $countOnly)
    {
        if (isset($value)) {
            $queryBuilder
                ->andWhere($this->getMainAlias($queryBuilder) . '.added = :added_value')
                ->setParameter('added_value', $value);
        }
    }

    /**
     * Prepare certain search condition for onlyModuleOffline flag
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param boolean                    $value        Condition data
     * @param boolean                    $countOnly    "Count only" flag
     *
     * @return void
     */
    protected function prepareCndOnlyPureOffline(\Doctrine\ORM\QueryBuilder $queryBuilder, $value, $countOnly)
    {
        if ($value) {
            $alias = $this->getMainAlias($queryBuilder);
            $queryBuilder
                ->andWhere($alias . '.class = :class AND ' . $alias . '.type = :offlineType')
                ->setParameter('class', 'Model\Payment\Processor\Offline')
                ->setParameter('offlineType', \XLite\Model\Payment\Method::TYPE_OFFLINE);
        }
    }

    /**
     * Prepare certain search condition for onlyModuleOffline flag
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param boolean                    $value        Condition data
     * @param boolean                    $countOnly    "Count only" flag
     *
     * @return void
     */
    protected function prepareCndOnlyModuleOffline(\Doctrine\ORM\QueryBuilder $queryBuilder, $value, $countOnly)
    {
        if ($value) {
            $alias = $this->getMainAlias($queryBuilder);
            $queryBuilder
                ->andWhere($alias . '.class != :class AND ' . $alias . '.type = :offlineType')
                ->setParameter('class', 'Model\Payment\Processor\Offline')
                ->setParameter('offlineType', \XLite\Model\Payment\Method::TYPE_OFFLINE);
        }
    }

    /**
     * Prepare certain search condition for position
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param array                      $value        Condition data
     * @param boolean                    $countOnly    "Count only" flag
     *
     * @return void
     */
    protected function prepareCndPosition(\Doctrine\ORM\QueryBuilder $queryBuilder, array $value, $countOnly)
    {
        if (!$countOnly) {
            list($sort, $order) = $value;

            $queryBuilder->addOrderBy($this->getMainAlias($queryBuilder) . '.' . $sort, $order);
        }
    }

    /**
     * Prepare certain search condition for position
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param string                     $value        Condition data
     * @param boolean                    $countOnly    "Count only" flag
     *
     * @return void
     */
    protected function prepareCndType(\Doctrine\ORM\QueryBuilder $queryBuilder, $value, $countOnly)
    {
        if ($value) {
            $alias = $this->getMainAlias($queryBuilder);
            if (is_array($value)) {
                $queryBuilder->andWhere($alias . '.type IN (' . $queryBuilder->getInCondition($value, 'type') . ')');

            } else {
                $queryBuilder->andWhere($alias . '.type = :type')
                    ->setParameter('type', $value);
            }
        }
    }

    /**
     * Prepare certain search condition for position
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param string                     $value        Condition data
     * @param boolean                    $countOnly    "Count only" flag
     *
     * @return void
     */
    protected function prepareCndOrderBy(\Doctrine\ORM\QueryBuilder $queryBuilder, $value, $countOnly)
    {
        if (!$countOnly && is_array($value)) {
            list($sort, $direction) = $value;

            $queryBuilder->orderBy($sort, $direction);
        }
    }

    // }}}

    // {{{ Finders

    /**
     * Find all methods
     *
     * @return \Doctrine\Common\Collection\Collection
     */
    public function findAllMethods()
    {
        return $this->defineAllMethodsQuery()->getResult();
    }

    /**
     * Find all active and ready for checkout payment methods.
     *
     * @return \Doctrine\Common\Collection\Collection
     */
    public function findAllActive()
    {
        return $this->defineAllActiveQuery()->getResult();
    }

    /**
     * Check - has active payment modules or not
     *
     * @return boolean
     */
    public function hasActivePaymentModules()
    {
        return 0 < $this->searchCount($this->defineHasActivePaymentModulesQuery());
    }

    /**
     * Find offline method (not from modules)
     *
     * @return array
     */
    public function findOffline()
    {
        $list = array();

        foreach ($this->defineFindOfflineQuery()->getResult() as $method) {
            if (!preg_match('/\\\Module\\\/Ss', $method->getClass())) {
                $list[] = $method;
            }
        }

        return $list;
    }

    /**
     * Find offline method (only from modules)
     *
     * @return array
     */
    public function findOfflineModules()
    {
        $list = array();

        foreach ($this->defineFindOfflineQuery()->getResult() as $method) {
            if (preg_match('/\\\Module\\\/Ss', $method->getClass())) {
                $list[] = $method;
            }
        }

        return $list;
    }

    /**
     * Find payment methods by specified type for dialog 'Add payment method' 
     * 
     * @param string $type Payment method type
     *  
     * @return \Doctrine\Common\Collection\Collection
     */
    public function findForAdditionByType($type)
    {
        return $this->defineAdditionByTypeQuery($type)->getResult();
    }

    /**
     * Define query for findAdditionByType() 
     * 
     * @param string $type Payment method type
     *  
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function defineAdditionByTypeQuery($type)
    {
        return $this->createPureQueryBuilder('m')
            ->andWhere('m.type = :type')
            ->andWhere('m.moduleEnabled = :moduleEnabled')
            ->addOrderBy('m.moduleName', 'asc')
            ->setParameter('type', $type)
            ->setParameter('moduleEnabled', true);
    }

    /**
     * Define query for findAllMethods() method
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function defineAllMethodsQuery()
    {
        return $this->createQueryBuilder();
    }

    /**
     * Define query for findAllActive() method
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function defineAllActiveQuery()
    {
        return $this->createQueryBuilder()
            ->andWhere('m.enabled = :true')
            ->andWhere('m.added = :true')
            ->setParameter('true', true);
    }

    /**
     * Define query for hasActivePaymentModules() method
     *
     * @return \XLite\Model\QueryBuilder\AQueryBuilder
     */
    protected function defineHasActivePaymentModulesQuery()
    {
        return $this->createPureQueryBuilder()
            ->select('COUNT(m.method_id) cns')
            ->andWhere('m.type != :offline')
            ->setParameter('offline', \XLite\Model\Payment\Method::TYPE_OFFLINE)
            ->setMaxResults(1);
    }

    /**
     * Define query for findOffline() method
     *
     * @return \XLite\Model\QueryBuilder\AQueryBuilder
     */
    protected function defineFindOfflineQuery()
    {
        return $this->createPureQueryBuilder()
            ->setParameter('offline', \XLite\Model\Payment\Method::TYPE_OFFLINE);
    }

    // }}}
}
